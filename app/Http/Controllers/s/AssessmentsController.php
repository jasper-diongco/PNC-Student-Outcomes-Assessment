<?php

namespace App\Http\Controllers\s;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\StudentOutcome;
use Illuminate\Support\Facades\DB;
use App\AnswerSheet;
use App\AnswerSheetTestQuestion;
use App\AnswerSheetTestQuestionChoice;
use App\Assessment;
use App\AssessmentDetail;
use App\Student;
use App\CustomRecordedAssessmentRecord;
use Carbon\Carbon;
use Gate;

class AssessmentsController extends Controller
{
    public function __construct() {
        $this->middleware("auth");


    }

    public function index() {

        if(!Gate::check('isStud')) {
            return abort('404', 'Page not found');
        }

        $student_outcomes = auth()->user()->getStudent()->program->studentOutcomes;
        $student = auth()->user()->getStudent();

        return view('s.assessments.index', compact('student_outcomes', 'student'));
    }

    public function show(StudentOutcome $student_outcome) {

        if(!Gate::check('isStud')) {
            return abort('404', 'Page not found');
        }

        $student = auth()->user()->getStudent();
        

        $answer_sheet = AnswerSheet::where('student_id', $student->id)
                        ->where('student_outcome_id', $student_outcome->id)
                        ->latest()
                        ->first();

        if($answer_sheet) {



            if($answer_sheet->is_submitted) {
                return redirect('s/assessments/show_score?student_id=' . $student->id . '&student_outcome_id=' . $student_outcome->id);
            } 

            /*if (!$answer_sheet->checkIfHasAvailableTime()) {
                $this->store($answer_sheet);
                //return "asdsad";
                //return redirect('s/assessments/show_score?student_id=' . $student->id . '&student_outcome_id=' . $student_outcome->id);
            }*/





            //$answer_sheet->load('answerSheetTestQuestions');

            $answer_sheet->answer_sheet_test_questions = $answer_sheet->getAnswerSheetTestQuestionsRand();
            $courses = $answer_sheet->exam->getCourses1($student_outcome->id, $student->curriculum_id);
            return view('s.assessments.show', compact('courses', 'answer_sheet', 'student_outcome'));

        } else {

            $exam = $student_outcome->getRandomExam($student->curriculum_id);
            $courses = $exam->getCourses1($student_outcome->id, $student->curriculum_id);
            
            //$test_questions = $exam->getRandomTestQuestions();
            $exam_test_questions = $exam->getRandomExamTestQuestions();

            //create answer sheet
            DB::beginTransaction();
            try {

                $answer_sheet = new AnswerSheet();
                $answer_sheet->exam_id = $exam->id;
                $answer_sheet->student_id = $student->id;
                $answer_sheet->student_outcome_id = $student_outcome->id;
                $answer_sheet->curriculum_id = $student->curriculum_id;
                $answer_sheet->time_limit = $exam->time_limit;
                $answer_sheet->description = $exam->description;
                $answer_sheet->passing_grade = $exam->passing_grade;

                $answer_sheet->save();

                foreach ($exam_test_questions as $exam_test_question) {
                    $exam_test_question->testQuestion->random_choices = $exam_test_question->testQuestion->getRandomChoices();
                    $exam_test_question->testQuestion->html = $exam_test_question->testQuestion->getHtml();

                    $answer_sheet_test_question = new AnswerSheetTestQuestion();
                    $answer_sheet_test_question->answer_sheet_id = $answer_sheet->id;
                    $answer_sheet_test_question->test_question_id = $exam_test_question->testQuestion->id;
                    $answer_sheet_test_question->title = $exam_test_question->testQuestion->title;
                    $answer_sheet_test_question->body = $exam_test_question->testQuestion->body;
                    $answer_sheet_test_question->body_html = $exam_test_question->testQuestion->html;
                    $answer_sheet_test_question->student_outcome_id = $answer_sheet->student_outcome_id;
                    $answer_sheet_test_question->course_id = $exam_test_question->testQuestion->course_id;
                    $answer_sheet_test_question->difficulty_level_id = $exam_test_question->testQuestion->difficulty_level_id;
                    $answer_sheet_test_question->performance_criteria_id = $exam_test_question->testQuestion->performance_criteria_id;
                    $answer_sheet_test_question->pos_order = $exam_test_question->pos_order;

                    $answer_sheet_test_question->save();

                    foreach ($exam_test_question->testQuestion->random_choices as $choice) {
                        $choice->html = $choice->getHtml();

                        $answer_sheet_test_question_choice = new AnswerSheetTestQuestionChoice();
                        $answer_sheet_test_question_choice->choice_id = $choice->id;

                        $answer_sheet_test_question_choice->answer_sheet_test_question_id = $answer_sheet_test_question->id;
                        $answer_sheet_test_question_choice->test_question_id = $exam_test_question->testQuestion->id;
                        $answer_sheet_test_question_choice->body = $choice->body;
                        $answer_sheet_test_question_choice->body_html = $choice->html;
                        $answer_sheet_test_question_choice->is_correct = $choice->is_correct;
                        $answer_sheet_test_question_choice->pos_order = $choice->pos_order;

                        $answer_sheet_test_question_choice->save();
                    }
                }


                DB::commit();
                // all good
                //$answer_sheet->load('answerSheetTestQuestions');
                $answer_sheet->answer_sheet_test_questions = $answer_sheet->getAnswerSheetTestQuestionsRand();

                return view('s.assessments.show', compact('courses', 'answer_sheet', 'student_outcome'));
            } catch (\Exception $e) {
                DB::rollback();
                // something went wrong
                return abort('500', 'Server Error');
            }

        }


        
    }

    public function retake_assessment(StudentOutcome $student_outcome) {

        $student = auth()->user()->getStudent();

        $exam = $student_outcome->getRandomExam($student->curriculum_id);
        $courses = $exam->getCourses1($student_outcome->id, $student->curriculum_id);
        
        //$test_questions = $exam->getRandomTestQuestions();
        $exam_test_questions = $exam->getRandomExamTestQuestions();

        //create answer sheet
        DB::beginTransaction();

        try {

            $answer_sheet = new AnswerSheet();
            $answer_sheet->exam_id = $exam->id;
            $answer_sheet->student_id = $student->id;
            $answer_sheet->student_outcome_id = $student_outcome->id;
            $answer_sheet->curriculum_id = $student->curriculum_id;
            $answer_sheet->time_limit = $exam->time_limit;
            $answer_sheet->description = $exam->description;
            $answer_sheet->passing_grade = $exam->passing_grade;

            $answer_sheet->save();

            foreach ($exam_test_questions as $exam_test_question) {
                $exam_test_question->testQuestion->random_choices = $exam_test_question->testQuestion->getRandomChoices();
                $exam_test_question->testQuestion->html = $exam_test_question->testQuestion->getHtml();

                $answer_sheet_test_question = new AnswerSheetTestQuestion();
                $answer_sheet_test_question->answer_sheet_id = $answer_sheet->id;
                $answer_sheet_test_question->test_question_id = $exam_test_question->testQuestion->id;
                $answer_sheet_test_question->title = $exam_test_question->testQuestion->title;
                $answer_sheet_test_question->body = $exam_test_question->testQuestion->body;
                $answer_sheet_test_question->body_html = $exam_test_question->testQuestion->html;
                $answer_sheet_test_question->student_outcome_id = $answer_sheet->student_outcome_id;
                $answer_sheet_test_question->course_id = $exam_test_question->testQuestion->course_id;
                $answer_sheet_test_question->difficulty_level_id = $exam_test_question->testQuestion->difficulty_level_id;
                $answer_sheet_test_question->performance_criteria_id = $exam_test_question->testQuestion->performance_criteria_id;
                $answer_sheet_test_question->pos_order = $exam_test_question->pos_order;

                $answer_sheet_test_question->save();

                foreach ($exam_test_question->testQuestion->random_choices as $choice) {
                    $choice->html = $choice->getHtml();

                    $answer_sheet_test_question_choice = new AnswerSheetTestQuestionChoice();
                    $answer_sheet_test_question_choice->choice_id = $choice->id;

                    $answer_sheet_test_question_choice->answer_sheet_test_question_id = $answer_sheet_test_question->id;
                    $answer_sheet_test_question_choice->test_question_id = $exam_test_question->testQuestion->id;
                    $answer_sheet_test_question_choice->body = $choice->body;
                    $answer_sheet_test_question_choice->body_html = $choice->html;
                    $answer_sheet_test_question_choice->is_correct = $choice->is_correct;
                    $answer_sheet_test_question_choice->pos_order = $choice->pos_order;

                    $answer_sheet_test_question_choice->save();
                }
            }


            DB::commit();
            // all good
            //$answer_sheet->load('answerSheetTestQuestions');

            return redirect('s/assessments/' . $student_outcome->id);
            // $answer_sheet->answer_sheet_test_questions = $answer_sheet->getAnswerSheetTestQuestionsRand();

            // return view('s.assessments.show', compact('courses', 'answer_sheet', 'student_outcome'));
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return abort('500', 'Server Error');
        }
    }

    public function store(AnswerSheet $answer_sheet) {

        if(!Gate::check('isStud')) {
            return abort('404', 'Page not found');
        }

        DB::beginTransaction();

        try {

            $answer_sheet->is_submitted = true;
            $answer_sheet->save();

            $answer_sheet_test_questions = request('answer_sheet_test_questions');

            foreach ($answer_sheet_test_questions as $answer_sheet_test_question) {
                foreach ($answer_sheet_test_question['answer_sheet_test_question_choices'] as $choice) {
                    if($choice['is_selected']) {
                        $answer_sheet_test_question = AnswerSheetTestQuestion::find($choice['answer_sheet_test_question_id']);
                        //////////////
                        if($answer_sheet_test_question->testQuestion->type_id != 3) {
                            foreach ($answer_sheet_test_question->answerSheetTestQuestionChoices as $c) {
                                $c->is_selected = false;
                                $c->save();
                            }
                        }

                        $answer_sheet_test_question_choice = AnswerSheetTestQuestionChoice::find($choice['id']);
                        $answer_sheet_test_question_choice->is_selected = true;
                        $answer_sheet_test_question_choice->save();
                    }
                }
            }

            $this->recordAssessment($answer_sheet, $answer_sheet_test_questions);

            DB::commit();
            // all good
            return $answer_sheet;
            
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return response()->json(['message' => 'Server Error'], 500);
        }
    }

    private function recordAssessment($answer_sheet, $answer_sheet_test_questions) {

        if(!Gate::check('isStud')) {
            return abort('404', 'Page not found');
        }

        $now = Carbon::now();
        $start_time = $answer_sheet->created_at;

        $total_duration = $now->diffInSeconds($start_time);

        if($total_duration > $answer_sheet->time_limit * 60) {
            $total_duration = $answer_sheet->time_limit * 60;
        }

        // $assessment = Assessment::where('student_id', $answer_sheet->student_id)->where('student_outcome_id', $answer_sheet->student_outcome_id)
        //     ->latest()
        //     ->first();

        /*if($assessment) {
            $assessment->update([
                'assessment_code' => $this->generateAssessmentID(),
                'exam_id' => $answer_sheet->exam_id,
                'student_id' => $answer_sheet->student_id,
                'student_outcome_id' => $answer_sheet->student_outcome_id,
                'time_consumed' => $total_duration
            ]);

            foreach ($answer_sheet_test_questions as $answer_sheet_test_question) {
                $choice_id = $this->getChoiceId($answer_sheet_test_question['answer_sheet_test_question_choices']);
                $is_correct = $this->checkIfCorrect($answer_sheet_test_question['answer_sheet_test_question_choices'], $choice_id);

                $assessment_detail = AssessmentDetail::where('assessment_id', $assessment->id)
                                        ->where('test_question_id', $answer_sheet_test_question['test_question_id'])
                                        ->first();

                $assessment_detail->update([
                    'choice_id' => $choice_id,
                    'is_correct' => $is_correct
                ]);
            }



        } else {
            $assessment = Assessment::create([
                'assessment_code' => $this->generateAssessmentID(),
                'exam_id' => $answer_sheet->exam_id,
                'student_id' => $answer_sheet->student_id,
                'student_outcome_id' => $answer_sheet->student_outcome_id,
                'time_consumed' => $total_duration
            ]);

            foreach ($answer_sheet_test_questions as $answer_sheet_test_question) {
                $choice_id = $this->getChoiceId($answer_sheet_test_question['answer_sheet_test_question_choices']);
                $is_correct = $this->checkIfCorrect($answer_sheet_test_question['answer_sheet_test_question_choices'], $choice_id);
                AssessmentDetail::create([
                    'assessment_id' => $assessment->id,
                    'test_question_id' => $answer_sheet_test_question['test_question_id'],
                    'choice_id' => $choice_id,
                    'is_correct' => $is_correct
                ]);
            }
        }
        */
        $assessment = Assessment::create([
                'assessment_code' => $this->generateAssessmentID(),
                'exam_id' => $answer_sheet->exam_id,
                'student_id' => $answer_sheet->student_id,
                'student_outcome_id' => $answer_sheet->student_outcome_id,
                'time_consumed' => $total_duration
            ]);

            foreach ($answer_sheet_test_questions as $answer_sheet_test_question) {
                $choice_id = $this->getChoiceId($answer_sheet_test_question['answer_sheet_test_question_choices']);
                $is_correct = $this->checkIfCorrect($answer_sheet_test_question['answer_sheet_test_question_choices'], $choice_id, $answer_sheet_test_question['test_question']['type_id']);
                AssessmentDetail::create([
                    'assessment_id' => $assessment->id,
                    'test_question_id' => $answer_sheet_test_question['test_question_id'],
                    'choice_id' => $choice_id,
                    'is_correct' => $is_correct
                ]);
            }
        $answer_sheet->assessment_id = $assessment->id;
        $answer_sheet->update();
        
    }

    private function generateAssessmentID() {
        $now = Carbon::now();
        $new_id = $now->format('Ym') . '0001';

        $assessment = Assessment::where('assessment_code', 'LIKE', $now->format('Ym') . '%')
            ->orderBy('assessment_code', 'DESC')
            ->first();

        if($assessment) {
            $current_count = intval(substr($assessment->assessment_code, 6));
            $current_count += 1;

            return $now->format('Ym') . sprintf("%'.04d", $current_count);
        }

        return $new_id;
    }

    public function select_choice(AnswerSheetTestQuestion $answer_sheet_test_question) {

        if(!Gate::check('isStud')) {
            return abort('404', 'Page not found');
        }

        $r_answer_sheet_test_question_choices = request('answer_sheet_test_question_choices');

        DB::beginTransaction();

        try {

            foreach ($r_answer_sheet_test_question_choices as $c) {
                $answer_sheet_test_question_choice = AnswerSheetTestQuestionChoice::find($c['id']);
                $answer_sheet_test_question_choice->is_selected = $c['is_selected'];
                $answer_sheet_test_question_choice->save();
            }      
            DB::commit();
            // all good
            return $answer_sheet_test_question;
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
        }
    }

    public function show_score() {

        if(!Gate::check('isStud')) {
            return abort('404', 'Page not found');
        }

        if(auth()->user()->getStudent()->id != request('student_id')) {
            return abort('404', 'Page not found');
        }

        $student_id = request('student_id');
        $student_outcome_id = request('student_outcome_id');

        $student = Student::findOrFail($student_id);
        $student_outcome = StudentOutcome::findOrFail($student_outcome_id);

        $assessment = Assessment::where('student_id', $student_id)
                                ->where('student_outcome_id', $student_outcome_id)
                                ->latest()
                                ->first();
        $answer_sheet = AnswerSheet::where('student_id', $student_id)
                                ->where('student_outcome_id', $student_outcome_id)
                                ->where('exam_id', $assessment->exam_id)
                                ->where('assessment_id', $assessment->id)
                                ->latest()
                                ->first();


        $courses = $answer_sheet->exam->getCourses1($assessment->student_outcome_id, $assessment->student->curriculum_id);

        $answer_sheet_test_questions = AnswerSheetTestQuestion::where('answer_sheet_id', $answer_sheet->id)
                ->orderBy('pos_order', 'ASC')
                ->with('answerSheetTestQuestionChoices')
                ->with('testQuestion')
                ->get();


        // $answer_sheet = AnswerSheet::where('student_id', $assessment->student_id)
        //                     ->where('exam_id', $assessment->exam_id)
        //                     ->where('student_outcome_id', $assessment->student_outcome_id)
        //                     ->where('assessment_id', $assessment->id)
        //                     ->first();

        if($assessment) {
            return view('s.assessments.show_score', compact('student_outcome', 'student', 'assessment', 'answer_sheet', 'answer_sheet_test_questions', 'courses'));
        } else {
            abort(404, 'Page not found');
        }


        
    }

    public function show_custom_recorded_assessment_score() {
        if(!Gate::check('isStud')) {
            return abort('404', 'Page not found');
        }

        if(auth()->user()->getStudent()->id != request('student_id')) {
            return abort('404', 'Page not found');
        }

        $student_id = request('student_id');
        $student_outcome_id = request('student_outcome_id');

        $student = Student::findOrFail($student_id);
        $student_outcome = StudentOutcome::findOrFail($student_outcome_id);

        $custom_recorded_assessment = CustomRecordedAssessmentRecord::select('custom_recorded_assessment_records.*')
            ->join('custom_recorded_assessments', 'custom_recorded_assessment_records.custom_recorded_assessment_id', '=', 'custom_recorded_assessments.id')
            ->where('custom_recorded_assessment_records.student_id', $student_id)
            ->where('custom_recorded_assessments.student_outcome_id', $student_outcome_id)
            ->latest()
            ->first();


        if(!$custom_recorded_assessment) {
            return abort(404, 'Page not found');
        }


        return view('s.assessments.show_custom_assessment_score', compact('custom_recorded_assessment', 'student_outcome', 'student'));


    }

    private function getChoiceId($choices) {
        foreach ($choices as $choice) {
            if($choice['is_selected']) {
                return $choice['choice_id'];
            }
        }

        return null;
    }

    private function checkIfCorrect($choices, $choice_id, $type_id) {
        if($choice_id == null) {
            return false;
        }

        if($type_id == 1 || $type_id == 2) {
            foreach ($choices as $choice) {
                if($choice['is_correct'] && $choice['choice_id'] == $choice_id) {
                    return true;
                }
            }
        } else if($type_id == 3) {
            $is_correct = true;
            foreach ($choices as $choice) {
                if(!($choice['is_correct'] && $choice['is_selected'])) {
                    $is_correct = false;
                    break;
                }
            }

            return $is_correct;
        }
        

        return false;
    }
}
