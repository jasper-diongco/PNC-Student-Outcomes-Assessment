<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TestQuestion;
use App\Exam;
use App\ExamTestQuestion;
use App\Program;
use App\Curriculum;
use App\StudentOutcome;
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


                foreach ($test_questions as $test_q) {
                    // $exam_test_question = new ExamTestQuestion();
                    // $exam_test_question->exam_id = $exam->id;
                    // $exam_test_question->test_question_id = $test_q['id'];
                    // $exam_test_question->create();
                    $exam_test_question = ExamTestQuestion::create([
                        'exam_id' => $exam->id,
                        'test_question_id' => $test_q['id']
                    ]);

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

    public function preview(Exam $exam) {

        //authenticate
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        }

        $test_questions = $exam->examTestQuestions;
        $courses = $exam->getCourses();

        //Session::flash('message', 'Exam preview is showing');

        return view('exams.preview', compact('exam', 'test_questions', 'courses'));
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
