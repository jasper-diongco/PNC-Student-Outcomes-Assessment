<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TestQuestion;
use App\Exam;
use App\ExamTestQuestion;
use App\Program;
use App\Curriculum;
use App\StudentOutcome;
use App\AssessmentDetail;
use App\ItemAnalysis;
use App\ItemAnalysisDetail;
use App\ItemAnalysisDetailAction;
use App\CurriculumCourse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Gate;

class ExamsController extends Controller
{
    public function __construct() {
        $this->middleware('auth');

    }

    public function index() {

        //authenticate
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        }

        $program = Program::findOrFail(request('program_id'));
        $curriculum = Curriculum::findOrFail(request('curriculum_id'));
        $student_outcome = StudentOutcome::findOrFail(request('student_outcome_id'));

        $exams = $curriculum->getExams(request('student_outcome_id'));

        $deactivated_exams = $curriculum->getDeactivatedExams(request('student_outcome_id'));

        $curriculum_course_requirements = Exam::getRequirements(request('student_outcome_id'), request('curriculum_id'));

        $requirements_template = [];

        foreach ($curriculum_course_requirements as $curriculum_course_requirement) {
            $requirement = [
                'course' => $curriculum_course_requirement->curriculum_map->curriculumCourse->course,
                'test_question_count' => $curriculum_course_requirement->total,
                'easy' => $curriculum_course_requirement->easy,
                'average' => $curriculum_course_requirement->average,
                'difficult' => $curriculum_course_requirement->difficult,
                'isLoading' => true
            ];

            $requirements_template[] = $requirement;
        }

        return view('exams.index', compact('program', 'curriculum', 'student_outcome', 'exams', 'deactivated_exams', 'requirements_template'));
    }



    public function get_exams() {
        //authenticate
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        }

        $curriculum = Curriculum::findOrFail(request('curriculum_id'));

        $exams = $curriculum->getExams(request('student_outcome_id'));

        return $exams;
    }

    public function create() {

        //authenticate
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        }

        $program = Program::findOrFail(request('program_id'));
        $curriculum = Curriculum::findOrFail(request('curriculum_id'));
        $student_outcome = StudentOutcome::findOrFail(request('student_outcome_id'));

        $count_easy = TestQuestion::countTestQuestion(request('student_outcome_id'), 1);
        $count_average = TestQuestion::countTestQuestion(request('student_outcome_id'), 2);
        $count_difficult = TestQuestion::countTestQuestion(request('student_outcome_id'), 3);

        $curriculum_course_requirements = Exam::getRequirements(request('student_outcome_id'), request('curriculum_id'));

        $total_test_questions = 0;

        foreach ($curriculum_course_requirements as $r) {
            $total_test_questions += $r->total;
        }

        return view('exams.create', compact('count_easy', 'count_average', 'count_difficult', 'curriculum_course_requirements', 'total_test_questions', 'program', 'curriculum', 'student_outcome'));
    }

    public function store() {

        //authenticate
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        }

        $is_valid = true;

        $data = request()->validate([
            'student_outcome_id' => 'required',
            'curriculum_id' => 'required',
            'description' => 'required',
            'time_limit' => 'required|numeric|min:30|max:120',
            'passing_grade' => 'required|numeric|min:40|max:100'
        ]);


        DB::beginTransaction();

        try {
            

            $curriculum_course_requirements = Exam::getRequirements(request('student_outcome_id'), request('curriculum_id'));

            $exam_test_questions = [];
            $test_questions = [];

            foreach ($curriculum_course_requirements as $requirement) {

                $rand_test_questions_easy = TestQuestion::getRandTestQuestions($data['student_outcome_id'],$requirement->curriculum_map->curriculumCourse->course->id, 1, $requirement->easy);

                if($requirement->easy > $rand_test_questions_easy->count()) {
                    $is_valid = false;
                }

                $rand_test_questions_average = TestQuestion::getRandTestQuestions($data['student_outcome_id'],$requirement->curriculum_map->curriculumCourse->course->id, 2, $requirement->average);

                if($requirement->average > $rand_test_questions_average->count()) {
                    $is_valid = false;
                }

                $rand_test_questions_difficult = TestQuestion::getRandTestQuestions($data['student_outcome_id'],$requirement->curriculum_map->curriculumCourse->course->id, 3, $requirement->difficult);

                if($requirement->difficult > $rand_test_questions_difficult->count()) {
                    $is_valid = false;
                }

                $test_questions = array_merge($test_questions, $rand_test_questions_easy->toArray());

                $test_questions = array_merge($test_questions, $rand_test_questions_average->toArray());

                $test_questions = array_merge($test_questions, $rand_test_questions_difficult->toArray());
            }

            

            

            if($is_valid) {
                //create the exam
                $exam = Exam::create([
                    'exam_code' => $this->generate_exam_code(),
                    'student_outcome_id' => $data['student_outcome_id'],
                    'curriculum_id' => $data['curriculum_id'],
                    'description' => $data['description'],
                    'time_limit' => $data['time_limit'],
                    'passing_grade' => $data['passing_grade'],
                    'user_id' => Auth::user()->id,
                    'is_active' => true
                ]);

                $exam->parent_id = $exam->id;
                $exam->update();


                $counter = 1;

                foreach ($test_questions as $test_q) {
                    // $exam_test_question = new ExamTestQuestion();
                    // $exam_test_question->exam_id = $exam->id;
                    // $exam_test_question->test_question_id = $test_q['id'];
                    // $exam_test_question->create();
                    $exam_test_question = ExamTestQuestion::create([
                        'exam_id' => $exam->id,
                        'test_question_id' => $test_q['id'],
                        'pos_order' => $counter,
                        'difficulty_level_id' => $test_q['difficulty_level_id']
                    ]);

                    $counter++;

                    $exam_test_questions[] = $exam_test_question;
                }

                DB::commit();

               Session::flash('message', 'Exam successfully created'); 

               return response()->json(['exam' => $exam ,'test_questions' => $exam_test_questions], 201); 
            } else {
                return response()->json(['message' => 'Insufficient test questions!', 'my_code' => 'insufficient'] ,422);
            }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return response()->json(['message' => 'Internal server error!'] ,500);
        }
        
        
    }

    private function getOriginalRequirements($curriculum_course_requirements) {
        $requirements_template = [];

        foreach ($curriculum_course_requirements as $curriculum_course_requirement) {
            $requirement = [
                'course' => $curriculum_course_requirement->curriculum_map->curriculumCourse->course,
                'test_question_count' => $curriculum_course_requirement->total,
                'easy' => $curriculum_course_requirement->easy,
                'average' => $curriculum_course_requirement->average,
                'difficult' => $curriculum_course_requirement->difficult,
                'isLoading' => true
            ];

            $requirements_template[] = $requirement;
        }

        return $requirements_template;
    }


    public function revise_exam(Exam $exam) {

        //authenticate
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        }

        $is_valid = true;

        $data = request()->validate([
            'student_outcome_id' => 'required',
            'curriculum_id' => 'required',
            'description' => 'required',
            'time_limit' => 'required|numeric|min:30|max:120',
            'passing_grade' => 'required|numeric|min:40|max:100'
        ]);


        DB::beginTransaction();

        try {
            
            $item_analysis = ItemAnalysis::find(request('item_analysis_id'));

            $curriculum_course_requirements = Exam::getRequirements(request('student_outcome_id'), request('curriculum_id'));

            

            // return $curriculum_course_requirements;

            $exam_test_questions = [];
            $test_questions = [];

            //start
            $requirements_template = [];

            foreach ($curriculum_course_requirements as $curriculum_course_requirement) {

                $requirement = [
                    'course' => $curriculum_course_requirement->curriculum_map->curriculumCourse->course,
                    'test_question_count' => $curriculum_course_requirement->total,
                    'easy' => $curriculum_course_requirement->easy,
                    'average' => $curriculum_course_requirement->average,
                    'difficult' => $curriculum_course_requirement->difficult,
                    'isLoading' => true
                ];

                $requirements_template[] = $requirement;
            }

            $retained_items = $item_analysis->getRetainedItem();
            $revised_items = $item_analysis->getRevisedItem();

            // return $requirements_template;

            foreach ($retained_items as $retained_item) {

                for($i = 0; $i < count($requirements_template); $i++) {
                    if($requirements_template[$i]['course']->id == $retained_item->testQuestion->course_id) {

                        if($retained_item->testQuestion->difficulty_level_id == 1) {
                            $requirements_template[$i]['easy'] -= 1;
                        } else if($retained_item->testQuestion->difficulty_level_id == 2) {
                            $requirements_template[$i]['average'] -= 1;
                        } else if($retained_item->testQuestion->difficulty_level_id == 3) {
                            $requirements_template[$i]['difficult'] -= 1;
                        }

                        $requirements_template[$i]['test_question_count'] = $requirements_template[$i]['easy'] + $requirements_template[$i]['average'] + $requirements_template[$i]['difficult'];
                    }
                }
             }

             foreach ($revised_items as $retained_item) {

                for($i = 0; $i < count($requirements_template); $i++) {
                    if($requirements_template[$i]['course']->id == $retained_item->testQuestion->course_id) {

                        if($retained_item->testQuestion->difficulty_level_id == 1) {
                            $requirements_template[$i]['easy'] -= 1;
                        } else if($retained_item->testQuestion->difficulty_level_id == 2) {
                            $requirements_template[$i]['average'] -= 1;
                        } else if($retained_item->testQuestion->difficulty_level_id == 3) {
                            $requirements_template[$i]['difficult'] -= 1;
                        }

                        $requirements_template[$i]['test_question_count'] = $requirements_template[$i]['easy'] + $requirements_template[$i]['average'] + $requirements_template[$i]['difficult'];
                    }
                }
             }

             // return $requirements_template;
             //remove the negative
             $remained_test_questions = [];
             for($i = 0; $i < count($requirements_template); $i++) {
                // return $requirements_template[$i]['course'];
                $tq_easy = TestQuestion::select('test_questions.*')
                        ->join('item_analysis_details', 'test_questions.id', '=', 'item_analysis_details.test_question_id')
                        ->where('item_analysis_id', $item_analysis->id)
                        ->where('test_questions.difficulty_level_id', 1)
                        ->where('test_questions.course_id', $requirements_template[$i]['course']->id)
                        ->where(function($q) {
                            $q->where('item_analysis_details.action_resolved_id', 1)
                            ->orWhere('item_analysis_details.action_resolved_id', 2);
                        })
                        ->get();

                $tq_average = TestQuestion::select('test_questions.*')
                        ->join('item_analysis_details', 'test_questions.id', '=', 'item_analysis_details.test_question_id')
                        ->where('item_analysis_id', $item_analysis->id)
                        ->where('test_questions.difficulty_level_id', 2)
                        ->where('test_questions.course_id', $requirements_template[$i]['course']->id)
                        ->where(function($q) {
                            $q->where('item_analysis_details.action_resolved_id', 1)
                            ->orWhere('item_analysis_details.action_resolved_id', 2);
                        })
                        ->get();

                $tq_difficult = TestQuestion::select('test_questions.*')
                        ->join('item_analysis_details', 'test_questions.id', '=', 'item_analysis_details.test_question_id')
                        ->where('item_analysis_id', $item_analysis->id)
                        ->where('test_questions.difficulty_level_id', 3)
                        ->where('test_questions.course_id', $requirements_template[$i]['course']->id)
                        ->where(function($q) {
                            $q->where('item_analysis_details.action_resolved_id', 1)
                            ->orWhere('item_analysis_details.action_resolved_id', 2);
                        })
                        ->get();


                 if($requirements_template[$i]['easy'] < 0) {
                    
                    $less = $requirements_template[$i]['easy'] * -1;
                    $new_tq_easy = [];

                    for($x = 0; $x < count($tq_easy) - $less; $x++) {
                        $new_tq_easy[] = $tq_easy[$x];
                    }

                    $requirements_template[$i]['easy'] = 0;


                    $tq_easy = [];
                    $tq_easy = $new_tq_easy;
                 }
                 if($requirements_template[$i]['average'] < 0) {

                    $less = $requirements_template[$i]['average'] * -1;
                    $new_tq_average = [];

                    for($x = 0; $x < count($tq_average) - $less; $x++) {
                        $new_tq_average[] = $tq_average[$x];
                    }

                    $requirements_template[$i]['average'] = 0;

                    $tq_average = [];
                    $tq_average = $new_tq_average;
                 }
                 if($requirements_template[$i]['difficult'] < 0) {

                    $less = $requirements_template[$i]['difficult'] * -1;
                    $new_tq_difficult = [];

                    for($x = 0; $x < count($tq_difficult) - $less; $x++) {
                        $new_tq_difficult[] = $tq_difficult[$x];
                    }

                    $requirements_template[$i]['difficult'] = 0;

                    $tq_difficult = [];
                    $tq_difficult = $new_tq_difficult;
                 }

                 $requirements_template[$i]['test_question_count'] = $requirements_template[$i]['easy'] + $requirements_template[$i]['average'] + $requirements_template[$i]['difficult'];

                 $easy_ids = [];
                 $average_ids = [];
                 $difficult_ids = [];

                 foreach ($tq_easy as $tq) {
                     $easy_ids[] = $tq->id;
                 }
                 foreach ($tq_average as $tq) {
                     $average_ids[] = $tq->id;
                 }
                 foreach ($tq_difficult as $tq) {
                     $difficult_ids[] = $tq->id;
                 }


                 $remained_test_questions[] = [
                    'course' => $requirements_template[$i]['course'],
                    'easy_tq' => $tq_easy,
                    'average_tq' => $tq_average,
                    'difficult_tq' => $tq_difficult,
                    'easy_ids' => $easy_ids,
                    'average_ids' => $average_ids,
                    'difficult_ids' => $difficult_ids
                 ];
            }

            // return $remained_test_questions;

            // $remained_test_questions_list = [];

            // foreach ($tq_easy as $tq_easy1) {
            //     $remained_test_questions_list[] = $tq_easy1;
            // }

            // foreach ($tq_average as $tq_average1) {
            //     $remained_test_questions_list[] = $tq_average1;
            // }

            // foreach ($tq_difficult as $tq_difficult1) {
            //     $remained_test_questions_list[] = $tq_difficult1;
            // }

            // return $remained_test_questions_list;

            // return $remained_test_questions;
            // return $requirements_template;

            //end

             foreach ($requirements_template as $t) {
                 for($i = 0; $i < count($curriculum_course_requirements); $i++) {
                    if($t['course']->id == CurriculumCourse::find($curriculum_course_requirements[$i]->curriculum_map->curriculum_course_id)->course->id) {

                        // return $t;
                        $curriculum_course_requirements[$i]->total = $t['test_question_count'];
                        $curriculum_course_requirements[$i]->easy = $t['easy'];
                        $curriculum_course_requirements[$i]->average = $t['average'];
                        $curriculum_course_requirements[$i]->difficult = $t['difficult'];
                    }
                 }
             }

             // return $curriculum_course_requirements;

            foreach ($curriculum_course_requirements as $requirement) {
                $remained_tq;

                foreach ($remained_test_questions as $remained_test_question) {
                    if($remained_test_question['course']->id == $requirement->curriculum_map->curriculumCourse->course->id) {
                        $remained_tq = $remained_test_question;
                        break;
                    }
                }
                // return $requirement->curriculum_map->curriculumCourse->course;



                $rand_test_questions_easy = TestQuestion::getRandTestQuestionsUnique($data['student_outcome_id'],$requirement->curriculum_map->curriculumCourse->course->id, 1, $requirement->easy, $remained_tq['easy_ids']);

                if($requirement->easy > $rand_test_questions_easy->count()) {
                    $is_valid = false;
                }

                $rand_test_questions_average = TestQuestion::getRandTestQuestionsUnique($data['student_outcome_id'],$requirement->curriculum_map->curriculumCourse->course->id, 2, $requirement->average, $remained_tq['average_ids']);

                if($requirement->average > $rand_test_questions_average->count()) {
                    $is_valid = false;
                }

                $rand_test_questions_difficult = TestQuestion::getRandTestQuestionsUnique($data['student_outcome_id'],$requirement->curriculum_map->curriculumCourse->course->id, 3, $requirement->difficult, $remained_tq['difficult_ids']);

                if($requirement->difficult > $rand_test_questions_difficult->count()) {
                    $is_valid = false;
                }

                $test_questions = array_merge($test_questions, $rand_test_questions_easy->toArray());

                $test_questions = array_merge($test_questions, $rand_test_questions_average->toArray());

                $test_questions = array_merge($test_questions, $rand_test_questions_difficult->toArray());
            }

            

            

            if($is_valid) {
                //create the exam
                $old_exam = clone $exam;

                $exam = Exam::create([
                    'exam_code' => $this->generate_exam_code(),
                    'student_outcome_id' => $data['student_outcome_id'],
                    'curriculum_id' => $data['curriculum_id'],
                    'description' => $data['description'],
                    'time_limit' => $data['time_limit'],
                    'passing_grade' => $data['passing_grade'],
                    'user_id' => Auth::user()->id,
                    'is_active' => true
                ]);

                $exam->parent_id = $old_exam->id;
                $exam->update();


                $counter = 1;

                foreach ($test_questions as $test_q) {
                    // $exam_test_question = new ExamTestQuestion();
                    // $exam_test_question->exam_id = $exam->id;
                    // $exam_test_question->test_question_id = $test_q['id'];
                    // $exam_test_question->create();
                    $exam_test_question = ExamTestQuestion::create([
                        'exam_id' => $exam->id,
                        'test_question_id' => $test_q['id'],
                        'pos_order' => $counter,
                        'difficulty_level_id' => $test_q['difficulty_level_id']
                    ]);

                    $counter++;

                    $exam_test_questions[] = $exam_test_question;
                }

                //remaining test questions

                foreach ($remained_test_questions as $remained_test_question) {
                    //easy 
                    foreach ($remained_test_question['easy_tq'] as $remained_easy) {
                        $exam_test_question = ExamTestQuestion::create([
                            'exam_id' => $exam->id,
                            'test_question_id' => $remained_easy->id,
                            'pos_order' => $counter,
                            'difficulty_level_id' => $remained_easy->difficulty_level_id
                        ]);

                        $counter++;

                        $exam_test_questions[] = $exam_test_question;
                    }
                    //average

                    foreach ($remained_test_question['average_tq'] as $remained_average) {
                        $exam_test_question = ExamTestQuestion::create([
                            'exam_id' => $exam->id,
                            'test_question_id' => $remained_average->id,
                            'pos_order' => $counter,
                            'difficulty_level_id' => $remained_average->difficulty_level_id
                        ]);

                        $counter++;

                        $exam_test_questions[] = $exam_test_question;
                    }

                    //difficult

                    foreach ($remained_test_question['difficult_tq'] as $remained_difficult) {
                        $exam_test_question = ExamTestQuestion::create([
                            'exam_id' => $exam->id,
                            'test_question_id' => $remained_difficult->id,
                            'pos_order' => $counter,
                            'difficulty_level_id' => $remained_difficult->difficulty_level_id
                        ]);

                        $counter++;

                        $exam_test_questions[] = $exam_test_question;
                    }
                }

                // return $exam_test_questions;



                /****************** RE-ORDER TEST QUESTIONS ******************/
                $cntr = 1;

                foreach ($requirements_template as $requirement) {
                    foreach ($exam_test_questions as $exam_test_question) {
                        if($exam_test_question->testQuestion->course_id == $requirement['course']->id) {
                            $exam_test_question->pos_order = $cntr;
                            $exam_test_question->update(); 
                            $cntr++;
                        }
                    }

                }

                //deactivate the exam
                $old_exam->is_active = false;
                $old_exam->update();

                //save item_analysis
                $item_analysis->is_saved = true;
                $item_analysis->update();

                // return $remained_test_questions;

                DB::commit();

               Session::flash('message', 'Exam successfully revised!'); 

               return response()->json(['exam' => $exam ,'test_questions' => $exam_test_questions], 201); 
            } else {
                return response()->json(['message' => 'Insufficient test questions!', 'my_code' => 'insufficient'] ,422);
            }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return response()->json(['message' => 'Internal server error!'] ,500);
        }
        
        
    }

    public function generate_pos_order() {
        $exams = Exam::all();

        foreach ($exams as $exam) {
            $exam_test_questions = $exam->examTestQuestions;

            $counter = 1;

            foreach ($exam_test_questions as $exam_test_question) {
                $exam_test_question->pos_order = $counter;
                $exam_test_question->save();
                $counter++;
            }
        }

        return "OK";
    }

    private function generate_exam_code() {
        $code = 'EXAM000001';

        $last_exam = Exam::orderBy('exam_code', 'DESC')->first();

        if($last_exam) {
            $last_code = $last_exam->exam_code;

            $num = substr($last_code, 4);  
            $num = intval($num);
            $num += 1;

            $new_code = "EXAM" . sprintf("%'.06d\n", $num);

            $code = $new_code;
        }

        return $code;
    }


    public function show(Exam $exam) {

        //authenticate
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        }

        $test_questions = $exam->getTestQuestions();
        $courses = $exam->getCourses();

        $program = Program::findOrFail(request('program_id'));
        $curriculum = Curriculum::findOrFail(request('curriculum_id'));
        $student_outcome = StudentOutcome::findOrFail(request('student_outcome_id'));

        return view('exams.show', compact('exam', 'test_questions', 'courses', 'program', 'curriculum', 'student_outcome'));
    }

    public function item_analysis(Exam $exam) {

        //authenticate
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        }

        // $exam_test_questions = $exam->examTestQuestions;
        // $courses = $exam->getCourses();

        // // Session::flash('message', 'Exam preview is showing');

        return view('exams.item_analysis', compact('exam'));
    }

    private function get_items_remaining($item_analysis, $original_requirements_template) {
        $items_remaining_template = [];

        foreach ($original_requirements_template as $orig_requirement) {
            $count_easy = ItemAnalysisDetail::select('item_analysis_details.*')
            ->join('test_questions', 'test_questions.id', '=', 'item_analysis_details.test_question_id')
            ->where('item_analysis_id', $item_analysis->id)
            ->where('test_questions.difficulty_level_id', 1)
            ->where('test_questions.course_id', $orig_requirement['course']->id)
            ->where(function($q) {
                $q->where('item_analysis_details.action_resolved_id', 1)
                ->orWhere('item_analysis_details.action_resolved_id', 2);
            })
            ->count();

            $count_average = ItemAnalysisDetail::select('item_analysis_details.*')
            ->join('test_questions', 'test_questions.id', '=', 'item_analysis_details.test_question_id')
            ->where('item_analysis_id', $item_analysis->id)
            ->where('test_questions.difficulty_level_id', 2)
            ->where('test_questions.course_id', $orig_requirement['course']->id)
            ->where(function($q) {
                $q->where('item_analysis_details.action_resolved_id', 1)
                ->orWhere('item_analysis_details.action_resolved_id', 2);
            })
            ->count();

            $count_difficult = ItemAnalysisDetail::select('item_analysis_details.*')
            ->join('test_questions', 'test_questions.id', '=', 'item_analysis_details.test_question_id')
            ->where('item_analysis_id', $item_analysis->id)
            ->where('test_questions.difficulty_level_id', 3)
            ->where('test_questions.course_id', $orig_requirement['course']->id)
            ->where(function($q) {
                $q->where('item_analysis_details.action_resolved_id', 1)
                ->orWhere('item_analysis_details.action_resolved_id', 2);
            })
            ->count();


            $items_remaining_template[] = [
                'course' => $orig_requirement['course'],
                'easy' => $count_easy,
                'average' => $count_average,
                'difficult' => $count_difficult
            ];
        }

        return $items_remaining_template;
    }

    public function item_analysis_result(ItemAnalysis $item_analysis) {

         $curriculum_course_requirements = Exam::getRequirements(request('student_outcome_id'), request('curriculum_id'));

         // $item_analysis->requirementsAvailable($curriculum_course_requirements);

        $original_requirements_template = $this->getOriginalRequirements($curriculum_course_requirements);

        $items_remaining_template = $this->get_items_remaining($item_analysis, $original_requirements_template);

        $requirements_template = [];

        $courses = [];
        $item_analysis_result = [];


        foreach ($curriculum_course_requirements as $curriculum_course_requirement) {

            $courses[] = CurriculumCourse::find($curriculum_course_requirement->curriculum_map->curriculum_course_id)->course;
            $requirement = [
                'course' => $curriculum_course_requirement->curriculum_map->curriculumCourse->course,
                'test_question_count' => $curriculum_course_requirement->total,
                'easy' => $curriculum_course_requirement->easy,
                'average' => $curriculum_course_requirement->average,
                'difficult' => $curriculum_course_requirement->difficult,
                'isLoading' => true
            ];

            $requirements_template[] = $requirement;
        }

        $retained_items = $item_analysis->getRetainedItem();
        $revised_items = $item_analysis->getRevisedItem();

        // return $requirements_template;

        foreach ($retained_items as $retained_item) {

            for($i = 0; $i < count($requirements_template); $i++) {
                if($requirements_template[$i]['course']->id == $retained_item->testQuestion->course_id) {

                    if($retained_item->testQuestion->difficulty_level_id == 1) {
                        $requirements_template[$i]['easy'] -= 1;
                    } else if($retained_item->testQuestion->difficulty_level_id == 2) {
                        $requirements_template[$i]['average'] -= 1;
                    } else if($retained_item->testQuestion->difficulty_level_id == 3) {
                        $requirements_template[$i]['difficult'] -= 1;
                    }

                    $requirements_template[$i]['test_question_count'] = $requirements_template[$i]['easy'] + $requirements_template[$i]['average'] + $requirements_template[$i]['difficult'];
                }
            }
         }

         foreach ($revised_items as $retained_item) {

            for($i = 0; $i < count($requirements_template); $i++) {
                if($requirements_template[$i]['course']->id == $retained_item->testQuestion->course_id) {

                    if($retained_item->testQuestion->difficulty_level_id == 1) {
                        $requirements_template[$i]['easy'] -= 1;
                    } else if($retained_item->testQuestion->difficulty_level_id == 2) {
                        $requirements_template[$i]['average'] -= 1;
                    } else if($retained_item->testQuestion->difficulty_level_id == 3) {
                        $requirements_template[$i]['difficult'] -= 1;
                    }

                    $requirements_template[$i]['test_question_count'] = $requirements_template[$i]['easy'] + $requirements_template[$i]['average'] + $requirements_template[$i]['difficult'];
                }
            }
         }

        //  $total = 0;

        // foreach ($requirements_template as $t) {
        //     $total += $t['test_question_count'];
        // }

        // return $total;

        // foreach ($courses as $course) {
        //     # code...
        // }


        // foreach ($curriculum_course_requirements as $requirement) {

        //         $rand_test_questions_easy = TestQuestion::getRandTestQuestions($data['student_outcome_id'],$requirement->curriculum_map->curriculumCourse->course->id, 1, $requirement->easy);

        //         if($requirement->easy > $rand_test_questions_easy->count()) {
        //             $is_valid = false;
        //         }

        //         $rand_test_questions_average = TestQuestion::getRandTestQuestions($data['student_outcome_id'],$requirement->curriculum_map->curriculumCourse->course->id, 2, $requirement->average);

        //         if($requirement->average > $rand_test_questions_average->count()) {
        //             $is_valid = false;
        //         }

        //         $rand_test_questions_difficult = TestQuestion::getRandTestQuestions($data['student_outcome_id'],$requirement->curriculum_map->curriculumCourse->course->id, 3, $requirement->difficult);

        //         if($requirement->difficult > $rand_test_questions_difficult->count()) {
        //             $is_valid = false;
        //         }

        //         $test_questions = array_merge($test_questions, $rand_test_questions_easy->toArray());

        //         $test_questions = array_merge($test_questions, $rand_test_questions_average->toArray());

        //         $test_questions = array_merge($test_questions, $rand_test_questions_difficult->toArray());
        // }

        return view('exams.item_analysis_result', compact('item_analysis', 'requirements_template', 'original_requirements_template', 'items_remaining_template'));
    }

    public function start_item_analysis(Exam $exam) {
        //authenticate
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        }
        $templates = [];
        $exam_test_questions = $exam->exam_test_questions;

        if($exam->item_analysis) {
            $assessments = $exam->getAssessmentsWithItemAnalysis();
        } else {
            $assessments = $exam->getAvailableForItemAnalysis();

            //update
            // $exam->item_analysis = true;
            // $exam->save();


            foreach ($assessments as $assessment) {
                $assessment->item_analysis = true;
                $assessment->save();
            }

        }

        



        $assessments_count = count($assessments);
        $sorted_assessments = [];

        //sort the assessments
        $sorted_assessments = $exam->sortAssessments($assessments);


        //get upper group
        $upper_group = [];
        for($i = 0; $i < $assessments_count / 2; $i++) {
            $upper_group[] = $sorted_assessments[$i];
        }

        //get lower group
        $lower_group = [];
        for($i = $assessments_count / 2; $i < $assessments_count; $i++) {
            $lower_group[] = $sorted_assessments[$i];
        }

        // $templates['test_questions'] = [];
        // foreach ($exam->examTestQuestions as $exam_test_question) {
        //     $templates['test_questions'][] = getExamTestQuestionsSorted
        // }
        $templates['exam_test_questions'] = $exam->getExamTestQuestionsSorted();

        $templates['upper_group'] = [];
        $templates['lower_group'] = [];


        //upper
        foreach ($upper_group as $upper_assessment) {
            $upper_assessment_details = [];

            foreach ($templates['exam_test_questions'] as $exam_test_question) {
                $upper_assessment_details[] = AssessmentDetail::where('test_question_id', $exam_test_question->test_question_id)
                    ->where('assessment_id', $upper_assessment->id)
                    ->first();
            }

            $templates['upper_group'][] = [
                'assessment' => $upper_assessment,
                'assessment_details' => $upper_assessment_details,
                'total_score' => $upper_assessment->countCorrectAnswers()
            ];
        }

        //lower
        foreach ($lower_group as $lower_assessment) {
            $lower_assessment_details = [];

            foreach ($templates['exam_test_questions'] as $exam_test_question) {
                $lower_assessment_details[] = AssessmentDetail::where('test_question_id', $exam_test_question->test_question_id)
                    ->where('assessment_id', $lower_assessment->id)
                    ->first();
            }

            $templates['lower_group'][] = [
                'assessment' => $lower_assessment,
                'assessment_details' => $lower_assessment_details,
                'total_score' => $lower_assessment->countCorrectAnswers()
            ];
        }



        //get the upper_group_totals
        $templates['upper_group_totals'] = [];

        foreach ($templates['exam_test_questions'] as $exam_test_question) {
            $templates['upper_group_totals'][] = 0;
        }

        foreach ($templates['upper_group'] as $t) {
            $index = 0;
            foreach ($t['assessment_details'] as $t_assessment_detail) {
                if($t_assessment_detail->is_correct) {
                    $templates['upper_group_totals'][$index] += 1;
                }
                $index++;
            }
            
        }

        //get the lower_group_totals
        $templates['lower_group_totals'] = [];

        foreach ($templates['exam_test_questions'] as $exam_test_question) {
            $templates['lower_group_totals'][] = 0;
        }

        foreach ($templates['lower_group'] as $t) {
            $index = 0;
            foreach ($t['assessment_details'] as $t_assessment_detail) {
                if($t_assessment_detail->is_correct) {
                    $templates['lower_group_totals'][$index] += 1;
                }
                $index++;
            }
        }

        //maximum and minumum possible score
        $maximum_possible_score = count($upper_group) * 1;
        $minimum_possible_score = count($lower_group) * 0;
        $range_scores_possible = $maximum_possible_score - $minimum_possible_score;

        //get difficulty index
        $templates['difficulty_index'] = [];
        $templates['correct_answers'] = [];
        $templates['difficulty_index_num'] = [];
        $templates['difficulty_actions'] = [];
        $templates['discrimination_index'] = [];
        $templates['discrimination_actions'] = [];
        $templates['recommended_actions'] = [];

        for($i = 0; $i < count($templates['exam_test_questions']); $i++) {
            $templates['correct_answers'][$i] = 0;
            $templates['correct_answers'][$i] += $templates['upper_group_totals'][$i];
            $templates['correct_answers'][$i] += $templates['lower_group_totals'][$i];

            $templates['difficulty_index'][$i] = round(($templates['correct_answers'][$i] / count($sorted_assessments)) * 100);
            $templates['difficulty_index_num'][$i] = $this->get_difficulty_index_num($templates['difficulty_index'][$i]);
            $templates['difficulty_actions'][$i] = $this->get_recommended_action_for_diff($templates['difficulty_index_num'][$i], $templates['exam_test_questions'][$i]->testQuestion);


            $templates['discrimination_index'][$i] = ($templates['upper_group_totals'][$i] - $templates['lower_group_totals'][$i]) / $range_scores_possible;

            $templates['discrimination_actions'][$i] = $this->get_recommended_action_for_discrimination($templates['discrimination_index'][$i]);

            
            $templates['recommended_actions'][$i] = $this->get_recommendation($templates['difficulty_actions'][$i], $templates['discrimination_actions'][$i]);
            

        }




        if($exam->item_analysis) {
            $item_analysis = ItemAnalysis::where('exam_id', $exam->id)->first();
            $item_analysis_details = ItemAnalysisDetail::where('item_analysis_id', $item_analysis->id)->get();
        } else {

            $item_analysis = ItemAnalysis::create([
                'exam_id' => $exam->id,
                'is_saved' => false
            ]);

            $item_analysis_details = [];

            for($i = 0; $i < count($templates['exam_test_questions']); $i++) {
                $item_analysis_detail = ItemAnalysisDetail::create([
                    'item_analysis_id' => $item_analysis->id,
                    'test_question_id' => $templates['exam_test_questions'][$i]->test_question_id,
                    'is_resolved' => false,
                    'action_resolved' => ''
                ]);

                $item_analysis_details[] = $item_analysis_detail;

                foreach ($templates['recommended_actions'][$i] as $recommended_action) {
                    $item_analysis_detail_action = ItemAnalysisDetailAction::create([
                        'item_analysis_detail_id' => $item_analysis_detail->id,
                        'recommended_action' => $recommended_action,
                        'is_selected' => false
                    ]);
                }
            }


            //update exam and assessments
            $exam->item_analysis = true;
            $exam->save();


            
        }

        $templates['item_analysis'] = $item_analysis;
        $templates['item_analysis_details'] = $item_analysis_details;
        $templates['item_analysis'] = $item_analysis;

        return $templates;

    }

    private function get_difficulty_index_num($index) {
        if($index <= 20) {
            return 5;//Very Difficult
        } else if ($index >= 21 && $index <= 40) {
            return 4;//Difficult
        } else if ($index >= 41 && $index <= 60) {
            return 3;//Average
        } else if ($index >= 61 && $index <= 80) {
            return 2;//Easy
        } else if ($index >= 61 && $index <= 100) {
            return 1;//Very Easy
        } 
    }

    private function get_recommended_action_for_diff($index_num, $test_question) {
        /*
        **** ACTIONS FOR DIFFICULTY
            1 - To be retain
            2 - To be revised
            3 - To be discard
        */

        if($index_num == 5 || $index_num == 1) {
            return 3;
        }
        //for easy
        else if($test_question->difficulty_level_id == 1 && $index_num == 2) {
            return 1;
        } else if ($test_question->difficulty_level_id == 1 && $index_num != 2) {
            return 2;
        }

        //for average
        else if($test_question->difficulty_level_id == 2 && $index_num == 3) {
            return 1;
        } else if ($test_question->difficulty_level_id == 2 && $index_num != 3) {
            return 2;
        }

        //for difficult
        else if($test_question->difficulty_level_id == 3 && $index_num == 4) {
            return 1;
        } else if ($test_question->difficulty_level_id == 3 && $index_num != 4) {
            return 2;
        }
    }

    private function get_recommended_action_for_discrimination($discrimination_index) {
        /*
        **** ACTIONS FOR DISCRMINATION
            1 - VG retain
            2 - G retain/revise
            3 - F Improve
            4 - P revise/ reject
            5 - VP reject
        */

        if($discrimination_index < 0.09) {
            return 5;
        } else if ($discrimination_index >= 0.09 && $discrimination_index <= 0.19) {
            return 4;
        } else if ($discrimination_index >= 0.20 && $discrimination_index <= 0.29) {
            return 3;
        } else if ($discrimination_index >= 0.30 && $discrimination_index <= 0.39) {
            return 2;
        } else if ($discrimination_index >= 0.40) {
            return 1;
        }
    }

    private function get_recommendation($difficulty_action, $discrimination_action) {
        /*
        **** ACTIONS FOR DIFFICULTY
            1 - To be retain
            2 - To be revised
            3 - To be discard
        */

        /*
        **** ACTIONS FOR DISCRMINATION
            1 - VG retain
            2 - G retain/revise
            3 - F Improve
            4 - P revise/ reject
            5 - VP reject
        */

        /*
        **** ACTIONS
            1 - RETAIN
            2 - REVISE
            3 - REJECT
        */

        $actions = [];
        $actions_final = [];

        if($difficulty_action == 3 || $discrimination_action == 5) {
            $actions[] = 3;
        } else if ($difficulty_action == 1 && $discrimination_action == 1) {
            $actions[] = 1;
        } else {
            if($difficulty_action == 2) {
                $actions[] = 2;
            }

            if($discrimination_action == 2) {
                $actions[] = 1;
                $actions[] = 2;
            }

            if($discrimination_action == 3) {
                $actions[] = 2;
            }

            if($discrimination_action == 4) {
                $actions[] = 2;
                $actions[] = 3;
            }
        }

        //remove redundant
        $actions = array_unique($actions);

        foreach ($actions as $action) {
            $actions_final[] = $action;
        }

        return $actions_final;

    }

    private function getRecommendedAction() {

    }

    public function preview(Exam $exam) {

        //authenticate
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        }

        $exam_test_questions = $exam->examTestQuestions;
        $courses = $exam->getCourses();

        // Session::flash('message', 'Exam preview is showing');

        return view('exams.preview', compact('exam', 'exam_test_questions', 'courses'));
    }

    public function print_answer_key(Exam $exam) {
        //authenticate
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        } 


        return view('exams.print_answer_key', compact('exam'));
    }

    public function deactivate(Exam $exam) {
        //authenticate
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        }

        $exam->is_active = false;
        $exam->save();

        Session::flash('message', 'Exam successfully archived');

        return $exam;
    }

    public function activate(Exam $exam) {
        //authenticate
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        }

        $exam->is_active = true;
        $exam->save();

        Session::flash('message', 'Exam successfully activated');

        return $exam;
    }


}
