<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TestQuestionProblem;

class TestQuestionProblemsController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $reported_test_questions = TestQuestionProblem::select('test_question_problems.*')
                    ->join('students', 'students.id', '=', 'test_question_problems.student_id')
                    ->with('testQuestion')
                    ->with('student')
                    ->where('students.program_id', request('program_id'))
                    ->latest()
                    ->get();

        return $reported_test_questions;
    }

    public function get_test_question_problems() {
        $reported_test_questions = TestQuestionProblem::select('test_question_problems.*')
                    ->with('testQuestion')
                    ->with('student')
                    ->where('test_question_problems.test_question_id', request('test_question_id'))
                    ->latest()
                    ->get();

        return $reported_test_questions;
    }

    public function store() {
        $data = request()->validate([
            'message' => 'required|max:255',
            'test_question_id' => 'required',
            'student_id' => 'required'
        ]);

        $test_question = TestQuestionProblem::create([
            'message' => $data['message'],
            'test_question_id' => $data['test_question_id'],
            'student_id' => $data['student_id']
        ]);


        return $test_question;

    }

    public function resolve(TestQuestionProblem $test_question_problem) {
        $test_question_problem->is_resolved = true;

        $test_question_problem->save();

        return $test_question_problem;
    }
}
