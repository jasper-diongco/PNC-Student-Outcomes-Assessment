<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TestQuestion;
use App\Exam;

class ExamsController extends Controller
{
    public function index() {
        return view('exams.index');
    }

    public function create() {

        $count_easy = TestQuestion::countTestQuestion(request('student_outcome_id'), 1);
        $count_average = TestQuestion::countTestQuestion(request('student_outcome_id'), 2);
        $count_difficult = TestQuestion::countTestQuestion(request('student_outcome_id'), 3);

        $curriculum_course_requirements = Exam::getRequirements(request('student_outcome_id'), request('curriculum_id'));

        $total_test_questions = 0;

        foreach ($curriculum_course_requirements as $r) {
            $total_test_questions += $r->total;
        }

        return view('exams.create', compact('count_easy', 'count_average', 'count_difficult', 'curriculum_course_requirements', 'total_test_questions'));
    }

    public function store() {
        $curriculum_course_requirements = Exam::getRequirements(request('student_outcome_id'), request('curriculum_id'));
    }
}
