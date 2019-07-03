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

        return view('exams.index', compact('program', 'curriculum', 'student_outcome', 'exams'));
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
            'time_limit' => 'required',
            'passing_grade' => 'required'
        ]);


        //create the exam
        $exam = Exam::create([
            'student_outcome_id' => $data['student_outcome_id'],
            'curriculum_id' => $data['curriculum_id'],
            'description' => $data['description'],
            'time_limit' => $data['time_limit'],
            'passing_grade' => $data['passing_grade'],
            'user_id' => Auth::user()->id,
            'is_active' => true
        ]);


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

        if($is_valid) {
           Session::flash('message', 'Exam successfully created'); 
           return response()->json(['exam' => $exam ,'test_questions' => $exam_test_questions], 201); 
        } else {
            return response()->json(['message' => 'Insufficient test questions!'] ,422);
        }
        
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

        $test_questions = $exam->getTestQuestions();
        $courses = $exam->getCourses();

        //Session::flash('message', 'Exam preview is showing');

        return view('exams.preview', compact('exam', 'test_questions', 'courses'));
    }


}
