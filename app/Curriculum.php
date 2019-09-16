<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\CurriculumCourse;
use App\CurriculumMap;
use App\Exam;
use App\Grades;

class Curriculum extends Model
{
    public $fillable = ['program_id', 'name', 'description', 'year', 'user_id', 'year_level', 'revision_no', 'ref_id'];

    public function program() {
        return $this->belongsTo('App\Program');
    }

    public function curriculumCourses() {
        return $this->hasMany('App\CurriculumCourse')->where('is_active', 1)->with('course');
    }

    public function getDeactivatedCourses() {
        return CurriculumCourse::where('curriculum_id', $this->id)->where('is_active', 0)->get();
    }

    public function totalUnits() {
        return CurriculumCourse::where('curriculum_id', $this->id)
        ->where('is_active', 1)
        ->join('courses', 'courses.id', '=', 'curriculum_courses.course_id')
        ->sum('lec_unit') + CurriculumCourse::where('curriculum_id', $this->id)
        ->where('is_active', 1)
        ->join('courses', 'courses.id', '=', 'curriculum_courses.course_id')
        ->sum('lab_unit') ;
    }

    public function getDoneCurriculumCourses($student_id) {
        $grades = Grade::where('student_id', $student_id)
                    ->where('is_passed', true)
                    ->get();

        $curriculum_courses = $this->curriculumCourses;

        $done_curriculum_courses = [];

        foreach ($curriculum_courses as $curriculum_course) {
            foreach ($grades as $grade) {
                if($curriculum_course->course_id == $grade->course_id) {
                    $done_curriculum_courses[] = $curriculum_course;
                    break;
                }
            }
        }

        return $done_curriculum_courses;
    }

    public function getDoneUnits($student_id) {
        $done_curriculum_courses = $this->getDoneCurriculumCourses($student_id);
        $total_units = 0;

        foreach ($done_curriculum_courses as $done_curriculum_course) {
            $total_units += $done_curriculum_course->course->lec_unit + $done_curriculum_course->course->lab_unit;
        }

        return $total_units;
    }

    public function getSemCourses($year, $sem) {
        return CurriculumCourse::where('curriculum_id', $this->id)
            ->where('is_active', 1)
            ->where('year_level', $year)
            ->where('semester', $sem)
            ->get();
    }

    public function checkMap($curriculum_course_id, $student_outcome_id) {
        return CurriculumMap::where('curriculum_course_id', $curriculum_course_id)
            ->where('student_outcome_id', $student_outcome_id)
            ->where('is_checked', true)
            ->first();
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function getPastVersion() {
        return Curriculum::where('ref_id', $this->ref_id)
            ->where('id','<>', $this->id)
            ->latest()
            ->get();
    }

    public function checkIfLatestVersion() {
        return !Curriculum::where('ref_id', $this->ref_id)
            ->where('revision_no', '>', $this->revision_no)
            ->exists();
    }

    public function getLatestVersion() {
        return Curriculum::where('ref_id', $this->ref_id)
            ->latest()
            ->first();
    }


    public function countExam($student_outcome_id='') {
        return Exam::where('curriculum_id', $this->id)
            ->where('student_outcome_id', $student_outcome_id)
            ->where('is_active', true)
            ->count();
    }

    public function getExams($student_outcome_id='') {
        return Exam::where('curriculum_id', $this->id)
            ->where('student_outcome_id', $student_outcome_id)
            ->where('is_active', true)
            ->latest()
            ->with('user')
            ->with('examTestQuestions')
            ->get();
    }

    public function getDeactivatedExams($student_outcome_id='') {
        return Exam::where('curriculum_id', $this->id)
            ->where('student_outcome_id', $student_outcome_id)
            ->where('is_active', false)
            ->latest()
            ->with('user')
            ->with('examTestQuestions')
            ->get();
    }
}
