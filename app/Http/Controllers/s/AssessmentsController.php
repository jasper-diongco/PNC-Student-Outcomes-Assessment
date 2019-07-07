<?php

namespace App\Http\Controllers\s;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\StudentOutcome;

class AssessmentsController extends Controller
{
    public function index() {

        $student_outcomes = auth()->user()->getStudent()->program->studentOutcomes;


        return view('s.assessments.index', compact('student_outcomes'));
    }

    public function show(StudentOutcome $student_outcome) {

        $exam = $student_outcome->getRandomExam();

        $courses = $exam->getCourses1($student_outcome->id, auth()->user()->getStudent()->curriculum_id);

        $test_questions = $exam->getRandomTestQuestions();

        foreach ($test_questions as $test_question) {
            $test_question->random_choices = $test_question->choicesRandom();
            $test_question->html = $test_question->getHtml();

            foreach ($test_question->random_choices as $choice) {
                $choice->html = $choice->getHtml();
            }
        }



        return view('s.assessments.show', compact('exam', 'courses', 'test_questions'));
    }
}
