<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Grade;
use App\GradeValue;

class GradesController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function store() {
        $data = request()->validate([
            'course_id' => 'required',
            'student_id' => 'required',
            'grade_value_id' => 'required',
            'faculty_id' => 'required'
        ]);

        $grade = Grade::create([
            'course_id' => $data['course_id'],
            'student_id' => $data['student_id'],
            'grade_value_id' => $data['grade_value_id'],
            'faculty_id' => $data['faculty_id'], 
            'is_passed' => GradeValue::find($data['grade_value_id'])->is_passed
        ]);

        $grade->load('gradeValue');

        return $grade;
    }
}
