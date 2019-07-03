<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Student;
use App\GradeValue;
use App\Faculty;
use App\StudentGrade;

class OBEController extends Controller
{
    public function __contruct() {
        $this->middleware('auth');
    }

    public function show(Student $student) {

        $curriculum = $student->curriculum;
        $curriculum_courses = $curriculum->curriculumCourses;
        $grade_values = GradeValue::all();
        $grades = $student->grades;
        $student_grades = [];

        foreach ($curriculum_courses as $curriculum_course) {
            $student_grade = new StudentGrade();
            $student_grade->student = $student;
            $student_grade->course_id = $curriculum_course->course_id;
            $student_grade->getGrade();

            $student_grades[] = [
                'course_id' => $student_grade->course_id,
                'professor_name' => $student_grade->professor_name,
                'grade_text' => $student_grade->grade_text,
                'remarks' => $student_grade->remarks,
                'is_passed' => $student_grade->is_passed
            ];
        }

        //return $student_grades;

        return view('students.obe_show', compact('student','curriculum', 'curriculum_courses', 'grade_values', 'grades', 'student_grades'));
    }

    public function refreshStudentGrades(Student $student) {
        
        $curriculum = $student->curriculum;
        $curriculum_courses = $curriculum->curriculumCourses;
        $grade_values = GradeValue::all();
        $grades = $student->grades;
        $student_grades = [];

        foreach ($curriculum_courses as $curriculum_course) {
            $student_grade = new StudentGrade();
            $student_grade->student = $student;
            $student_grade->course_id = $curriculum_course->course_id;
            $student_grade->getGrade();

            $student_grades[] = [
                'course_id' => $student_grade->course_id,
                'professor_name' => $student_grade->professor_name,
                'grade_text' => $student_grade->grade_text,
                'remarks' => $student_grade->remarks,
                'is_passed' => $student_grade->is_passed
            ];
        }

        return $student_grades;
    }
}
