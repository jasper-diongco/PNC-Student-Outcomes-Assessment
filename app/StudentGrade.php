<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentGrade extends Model
{
    public $student;
    public $course_id;
    public $course_code;
    public $remarks;
    public $professor_name;
    public $grade_text;
    public $is_passed;

    public function __construct() {

    }

    public function getGrade() {
        $curriculum = $this->student->curriculum;
        $curriculum_courses = $curriculum->curriculumCourses;
        $grades = $this->student->getGradesByCourse($this->course_id);

        if($grades->count() > 0) {
            $last_grade = $grades[0];

            $this->professor_name = $last_grade->faculty->user->getFullName();
            
            $this->remarks = $last_grade->gradeValue->value != '5.00' && $last_grade->gradeValue->value != 'INC' ? 'Passed' : "Failed" ;
            $this->is_passed = $last_grade->gradeValue->value != '5.00' && $last_grade->gradeValue->value != 'INC' ? true : false ;

            // foreach ($grades as $g) {
            //     $this->grade_text .= $g->gradeValue->value . ';';
            // }

            for($i = count($grades) - 1; $i >= 0; $i--) {
                $this->grade_text .= $grades[$i]->gradeValue->value . '; ';
            }
            

        } else {
            $this->professor_name = '---';
            $this->grade_text = '---';
            $this->remarks = '---';
        }
    }


}