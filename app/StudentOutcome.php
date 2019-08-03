<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class StudentOutcome extends Model
{
    public $fillable = ['program_id', 'so_code', 'description'];

    public function performanceCriterias() {
      return $this->hasMany('App\PerformanceCriteria');
    }

    public function curriculumMaps() {
        return $this->hasMany('App\CurriculumMap')->where('is_checked', true);
    }

    public function program() {
        return $this->belongsTo('App\Program');
    }

    public function checkIfAvailableForExam() {
        $is_available = true;
        $curriculum_maps = CurriculumMap::where('is_checked', true)
            ->where('student_outcome_id', $this->id)
            ->get();
        $courses = [];

        foreach ($curriculum_maps as $curriculum_map) {
            $courses[] = $curriculum_map->curriculumCourse->course;
        }

        $student = Student::find(auth()->user()->getStudent()->id);

        $curriculum = $student->curriculum;
        $curriculum_courses = $curriculum->curriculumCourses;
        $grade_values = GradeValue::all();
        $grades = $student->grades;
        $student_grades = [];

        foreach ($courses as $course) {
            $student_grade = new StudentGrade();
            $student_grade->student = $student;
            $student_grade->course_id = $course->id;
            $student_grade->getGrade();

            $student_grades[] = [
                'course_id' => $student_grade->course_id,
                'professor_name' => $student_grade->professor_name,
                'grade_text' => $student_grade->grade_text,
                'remarks' => $student_grade->remarks,
                'is_passed' => $student_grade->is_passed
            ];

            foreach ($student_grades as $student_grade) {
                if($course->id == $student_grade['course_id']) {
                    if(!$student_grade['is_passed']) {
                        $is_available = false;
                    }
                }
            }
        }

        // foreach ($courses as $course) {
            
        //     foreach ($student_grades as $student_grade) {
        //         if($course->id == $student_grade['course_id']) {
        //             if(!$student_grade['is_passed']) {
        //                 $is_available = false;
        //             }
        //         }
        //     }
        // }

        return $is_available;
    }

    public function checkIfTaken($student_id) {
        $assessment = Assessment::where('student_outcome_id', $this->id)
                            ->where('student_id', $student_id)
                            ->first();

        if($assessment) {
            return true;
        } else {
            return false;
        }
    }

    public function checkIfExamOngoing($student_id) {
        $answer_sheet = AnswerSheet::where('student_id', $student_id)
                                ->where('student_outcome_id', $this->id)
                                ->first();
        if($answer_sheet && !$answer_sheet->is_submitted) {
            
            $now = Carbon::now();
            $start_time = Carbon::parse($answer_sheet->created_at);

            $totalDuration = $now->diffInSeconds($start_time);

            if($totalDuration < $answer_sheet->time_limit * 60) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getCoursesGrade() {

        $curriculum_maps = CurriculumMap::where('is_checked', true)
            ->where('student_outcome_id', $this->id)
            ->get();
        $courses = [];

        foreach ($curriculum_maps as $curriculum_map) {
            $courses[] = $curriculum_map->curriculumCourse->course;
        }

        $student = Student::find(auth()->user()->getStudent()->id);

        $curriculum = $student->curriculum;
        $curriculum_courses = $curriculum->curriculumCourses;
        $grade_values = GradeValue::all();
        $grades = $student->grades;
        $student_grades = [];

        foreach ($courses as $course) {
            $student_grade = new StudentGrade();
            $student_grade->student = $student;
            $student_grade->course_id = $course->id;
            $student_grade->getGrade();

            $student_grades[] = [
                'course_code' => $course->course_code,
                'course_desc' => $course->description,
                'lec_unit' => $course->lec_unit,
                'lab_unit' => $course->lab_unit,
                'course_id' => $student_grade->course_id,
                'professor_name' => $student_grade->professor_name,
                'grade_text' => $student_grade->grade_text,
                'remarks' => $student_grade->remarks,
                'is_passed' => $student_grade->is_passed
            ];
        }

        return $student_grades;
    }

    public function getRandomExam($curriculum_id) {
        //need to consider the curriculum
        return Exam::where('student_outcome_id', $this->id)
            ->where('is_active', true)
            ->where('curriculum_id', $curriculum_id)
            ->inRandomOrder()
            ->first();
    }

    public function getExams($curriculum_id) {
        return Exam::where('student_outcome_id', $this->id)->where('is_active', true)->where('curriculum_id', $curriculum_id)->get();
    }
}
