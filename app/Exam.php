<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ExamCourseRequirement;
use App\StudentOutcome;
use App\Curriculum;
use App\Exam;

class Exam extends Model
{
    public $guarded = [];


    public function user() {
        return $this->belongsTo('App\User');
    }

    public static function getRequirements($student_outcome_id='', $curriculum_id=''){
        $requirements = [];
        $student_outcome = StudentOutcome::find($student_outcome_id);
        $curriculum = Curriculum::find($curriculum_id);

        $curriculum_maps = $student_outcome->curriculumMaps;
        $curriculum_courses = $curriculum->curriculumCourses;

        $valid_curriculum_maps = [];

        foreach ($curriculum_maps as $curriculum_map) {

            foreach ($curriculum_courses as $curriculum_course) {

                if($curriculum_map->curriculumCourse->course->id == $curriculum_course->course_id) {
                    $valid_curriculum_maps[] = $curriculum_map;
                    break;
                }
            }
        }

        $each_courses = floor(100 / count($valid_curriculum_maps));
        $sum_distribute = 0;


        foreach ($valid_curriculum_maps as $valid_curriculum_map) {
            $requirement = new ExamCourseRequirement();
            $requirement->total = $each_courses;
            $sum_distribute += $requirement->total;
            $requirement->curriculum_map = $valid_curriculum_map;

            $requirements[] = $requirement;
        }

        $left_distribute = 100 - $sum_distribute;

        if($left_distribute > 0) {
            foreach ($requirements as $requirement) {
                $requirement->total += 1;
                $left_distribute -= 1;

                if($left_distribute <= 0) {
                    break;
                }
            }
        }

        foreach ($requirements as $requirement) {
            $requirement->computeTotalForeachDifficulties();
            // echo $requirement->curriculum_map->curriculumCourse->course->course_code . ' - ' .$requirement->total . '<br>';
            // echo '---<br>';
            // echo 'easy - ' . $requirement->easy . '<br>';
            // echo 'average - ' . $requirement->average . '<br>';
            // echo 'difficult - ' . $requirement->difficult . '<br>';
            // echo '---<br>';
        }

        //exit($sum_distribute);


        return $requirements;
    }

    public function getCourses() {
        $student_outcome = StudentOutcome::find(request('student_outcome_id'));
        $curriculum = Curriculum::find(request('curriculum_id'));

        $curriculum_maps = $student_outcome->curriculumMaps;
        $curriculum_courses = $curriculum->curriculumCourses;

        $courses = [];

        foreach ($curriculum_maps as $curriculum_map) {

            foreach ($curriculum_courses as $curriculum_course) {

                if($curriculum_map->curriculumCourse->course->id == $curriculum_course->course_id) {
                    $courses[] = $curriculum_map->curriculumCourse->course;
                    break;
                }
            }
        }

        return $courses;
    }


    public function examTestQuestions() {
        return $this->hasMany('App\ExamTestQuestion');
    }

    public function getTestQuestions() {
        return TestQuestion::select('test_questions.*')
            ->join('exam_test_questions', 'exam_test_questions.test_question_id', '=',  'test_questions.id')
            ->where('exam_test_questions.exam_id', $this->id)
            ->with('choices')
            ->with('user')
            ->get();
    }

    public function getTestQuestionsByCourse($course_id='') {
        return TestQuestion::select('test_questions.*')
            ->join('exam_test_questions', 'exam_test_questions.test_question_id', '=',  'test_questions.id')
            ->where('exam_test_questions.exam_id', $this->id)
            ->where('test_questions.course_id', $course_id)
            ->with('choices')
            ->with('user')
            ->get();
    }
    public function getRandomTestQuestionsByCourse($course_id='') {
        return TestQuestion::select('test_questions.*')
            ->join('exam_test_questions', 'exam_test_questions.test_question_id', '=',  'test_questions.id')
            ->where('exam_test_questions.exam_id', $this->id)
            ->where('test_questions.course_id', $course_id)
            ->with('choices')
            ->with('user')
            ->inRandomOrder()
            ->get();
    }



    public function countTestQuestionsByCourse($course_id) {
        return TestQuestion::select('test_questions.*')
            ->join('exam_test_questions', 'exam_test_questions.test_question_id', '=',  'test_questions.id')
            ->where('exam_test_questions.exam_id', $this->id)
            ->where('test_questions.course_id', $course_id)
            ->count();
    }
}
