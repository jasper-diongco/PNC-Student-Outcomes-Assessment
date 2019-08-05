<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Student;
use App\GradeValue;
use App\Faculty;
use App\StudentGrade;
use App\Course;
use App\CurriculumCourse;

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

    public function print_obe(Student $student) {
        $curriculum = $student->curriculum;
        //$curriculum_courses = $curriculum->curriculumCourses;
        $grades = $student->grades;
        //$student_grades = [];

        $templates = $this->create_curriculum_template($curriculum);

        foreach ($templates as $template) {
            foreach ($template['curriculum_courses'] as $curriculum_course) {
                $student_grade = new StudentGrade();
                $student_grade->student = $student;
                $student_grade->course_id = $curriculum_course->course_id;
                $student_grade->getGrade();

                // $student_grades[] = [
                //     'course_id' => $student_grade->course_id,
                //     'course' => Course::find($student_grade->course_id) ,
                //     'professor_name' => $student_grade->professor_name,
                //     'grade_text' => $student_grade->grade_text,
                //     'remarks' => $student_grade->remarks,
                //     'is_passed' => $student_grade->is_passed
                // ];

                $curriculum_course->student_grade = [
                    'course_id' => $student_grade->course_id,
                    'professor_name' => $student_grade->professor_name,
                    'grade_text' => $student_grade->grade_text,
                    'remarks' => $student_grade->remarks,
                    'is_passed' => $student_grade->is_passed
                ];
            }
        }

        // foreach ($curriculum_courses as $curriculum_course) {
        //     $student_grade = new StudentGrade();
        //     $student_grade->student = $student;
        //     $student_grade->course_id = $curriculum_course->course_id;
        //     $student_grade->getGrade();

        //     $student_grades[] = [
        //         'course_id' => $student_grade->course_id,
        //         'course' => Course::find($student_grade->course_id) ,
        //         'professor_name' => $student_grade->professor_name,
        //         'grade_text' => $student_grade->grade_text,
        //         'remarks' => $student_grade->remarks,
        //         'is_passed' => $student_grade->is_passed
        //     ];
        // }

        //return $student_grades;

        return view('students.obe_print', compact('student','curriculum', 'grades', 'templates'));
    }

    private function create_curriculum_template($curriculum) {
        $templates = [];

        for($year = 1; $year <= $curriculum->year_level; $year++) {
            for($sem = 1; $sem <= 3; $sem++) {
                $curriculum_courses = CurriculumCourse::where('curriculum_id', $curriculum->id)
                                        ->where('year_level', $year)
                                        ->where('semester', $sem)
                                        ->where('is_active', true)
                                        ->get();
                $total_lec_units = 0;
                $total_lab_units = 0;

                foreach ($curriculum_courses as $curriculum_course) {
                    $course_requisites = $curriculum_course->courseRequisites;
                    $course_requisites_str = 'None';

                    if(count($course_requisites) > 0) {
                        $course_requisites_str = '';
                        foreach ($course_requisites as $course_requisite) {
                            $course_requisites_str .= $course_requisite->preReq()->course->course_code;
                            $course_requisites_str .= ';';
                        }
                    }

                    $total_lec_units += $curriculum_course->course->lec_unit;
                    $total_lab_units += $curriculum_course->course->lab_unit;
                    $curriculum_course->course_requisites_str = $course_requisites_str;
                }

                $templates[] = [
                    'year_sem' => $this->numIndex($year) . ' year /' . $this->numIndex($sem) . ' sem',
                    'curriculum_courses' => $curriculum_courses,
                    'total_lec_units' => $total_lec_units,
                    'total_lab_units' => $total_lab_units
                ];
            }
        }

        return $templates;
    }

    private function numIndex($num) {
        if($num == 1) {
            return "1st";
        } else if ($num == 2) {
            return "2nd";
        } else if ($num == 3) {
            return  "3rd";
        } else if ($num >= 4) {
            return $num . "th";
        } else {
            return $num;
        }
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
