<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gate;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Program;
use App\Curriculum;
use App\StudentOutcome;
use App\Course;
use App\TestQuestion;
use App\Choice;
use App\User;
use App\Rules\TextOnly;
use App\Http\Resources\TestQuestionResource;


class TestQuestionsController extends Controller
{

    public function index() {

        $student_outcome = StudentOutcome::findOrFail(request('student_outcome_id'));
        $course = Course::findOrFail(request('course_id'));

        if(request()->ajax() && request('json') == true) {

            //search
            if(request('q') != '') {
                $searched_test_questions = TestQuestion::where('student_outcome_id', $student_outcome->id)
                ->where('course_id', $course->id)
                ->where('title', 'LIKE' ,'%' . request('q') . '%')
                ->latest()
                ->get();

                return TestQuestionResource::collection($searched_test_questions);
            }

            if(request('user_id') != '') {
                $test_questions = TestQuestion::where('student_outcome_id', $student_outcome->id)
                ->where('course_id', $course->id)
                ->where('user_id', request('user_id'))
                ->latest()
                ->paginate(10);
            } else {
                $test_questions = TestQuestion::where('student_outcome_id', $student_outcome->id)
                ->where('course_id', $course->id)
                ->latest()
                ->paginate(10);
            }

            

            return TestQuestionResource::collection($test_questions);
        }

        

        return view('test_questions.index', compact('student_outcome', 'course'));
    }

    public function show(TestQuestion $test_question) {
        
        return view('test_questions.show', compact('test_question'));
    }

    public function edit(TestQuestion $test_question) {

        return view('test_questions.edit', compact('test_question'));
    }

    public function getCreators() {
        $student_outcome = StudentOutcome::findOrFail(request('student_outcome_id'));
        $course = Course::findOrFail(request('course_id'));

        return User::select('users.*')
            ->join('test_questions', 'test_questions.user_id', '=', 'users.id')
            ->where('student_outcome_id', $student_outcome->id)
            ->where('course_id', $course->id)
            ->distinct('users.id')
            ->get();
    }

    public function listProgram() {
        //authenticate
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        }

        if (request('college_id') == '') {
            return redirect('/test_questions/list_program?college_id='. Session::get('college_id'));
        } else if (request('college_id') == 'all') {
            if(Gate::check('isSAdmin')) {
                $programs = Program::orderBy('college_id')->paginate(10);
            } else {
                return redirect('/test_questions/list_program?college_id='. Session::get('college_id'));
            }
            
        } else {
            if(!Gate::check('isSAdmin') && Session::get('college_id') != request('college_id')) {
                return abort('401', 'Unauthorized');
            }

            $programs = Program::where('college_id', request('college_id'))->paginate(10);
        }

        

        return view('test_questions.list_programs')->with('programs', $programs);
    }

    public function listStudentOutcome(Program $program) {

        return view('test_questions.list_student_outcomes', compact('program'));
    }

    public function create() {
        return view('test_questions.create');
    }

    public function store() {

        $data = request()->validate([
            'title' => ['required', "regex: /^[A-Za-z\s\-0-9_.,()\'?!]+$/", 'max:255'],
            'question_body' => ['required', 'max:1000'],
            'level_of_difficulty' => 'required',
            'course_id' => 'required',
            'student_outcome_id' => 'required'
        ]);




        // DB::beginTransaction();

        // try {

            $so = StudentOutcome::findOrFail($data['student_outcome_id']);

            $test_question = TestQuestion::create([
                'title' => $data['title'],
                'body' => $data['question_body'],
                'student_outcome_id' => $data['student_outcome_id'],
                'course_id' => $data['course_id'],
                'difficulty_level_id' => $data['level_of_difficulty'],
                'user_id' => auth()->user()->id,
                'is_active' => true,
                'performance_criteria_id' => $so->performanceCriterias[0]->id
            ]);

            foreach (request('choices') as $choice) {
                Choice::create([
                    'test_question_id' => $test_question->id,
                    'body' => $choice['editorData'],
                    'is_correct' => $choice['is_correct'],
                    'is_active' => true,
                    'user_id' => auth()->user()->id
                ]);
            }

            Session::flash('message', 'New Test question successfully added to database');

            return $test_question;

        /*

            DB::commit();
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
        }

        */

    }
}
