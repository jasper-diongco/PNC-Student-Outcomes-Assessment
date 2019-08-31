<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ImageObject;

class TestQuestion extends Model
{
    public $fillable = ['title', 'body', 'student_outcome_id', 'course_id', 'difficulty_level_id', 'user_id', 'is_active', 'performance_criteria_id', 'ref_id', 'tq_code'];


    public function choices() {
        return $this->hasMany('App\Choice')->where('is_active', true)->orderBy('pos_order', 'ASC');
    }

    public function getCorrectAnswer() {
        $choices = $this->choices;
        $correct_answer;

        foreach ($choices as $choice) {
            if($choice->is_correct) {
                $correct_answer = $choice;
                break;
            }
        }

        return $correct_answer;
    }

    public function choicesRandom() {
        return Choice::where('is_active', true)
            ->where('test_question_id', $this->id)
            ->inRandomOrder()   
            ->get();
    }

    public function getRandomChoices() {
        return Choice::where('is_active', true)
            ->where('test_question_id', $this->id)
            ->inRandomOrder()   
            ->get();
    }

    public function difficultyLevel() {
        return $this->belongsTo('App\DifficultyLevel');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function course() {
        return $this->belongsTo('App\Course');
    }

    public function studentOutcome() {
        return $this->belongsTo('App\StudentOutcome');
    }

    public function getHtml() {
        $input = $this->body;
        $matches = [];
        $ids = [];


        //images
        preg_match_all('/(\[\[#img[0-9]+\]\])/im', $input, $matches);
        foreach ($matches[1] as $match) {
            $id = '';
            preg_match('/[0-9]+/', $match, $id);

            $image_object = ImageObject::find($id);

            if(count($image_object) <= 0) {
                $input = str_replace($match, '<span class="text-danger">[[undefined object]]</span>' , $input);
            } else {
                $input = str_replace($match, $image_object[0]->getHtml() , $input);
            }       
        }

        //codes
        preg_match_all('/(\[\[#code[0-9]+\]\])/im', $input, $matches);
        foreach ($matches[1] as $match) {
            $id = '';
            preg_match('/[0-9]+/', $match, $id);

            $code_object = CodeObject::find($id);

            if(count($code_object) <= 0) {
                $input = str_replace($match, '<span class="text-danger">[[undefined object]]</span>' , $input);
            } else {
                $input = str_replace($match, $code_object[0]->getHtml() , $input);
            }       
        }

        //math
        preg_match_all('/(\[\[#math[0-9]+\]\])/im', $input, $matches);
        foreach ($matches[1] as $match) {
            $id = '';
            preg_match('/[0-9]+/', $match, $id);

            $math_object = MathObject::find($id);

            if(count($math_object) <= 0) {
                $input = str_replace($match, '<span class="text-danger">[[undefined object]]</span>' , $input);
            } else {
                $input = str_replace($match, $math_object[0]->getHtml() , $input);
            }       
        }

        return nl2br($input);
    }

    public static function countEasy($student_outcome_id='', $course_id='') {
        return TestQuestion::where('student_outcome_id', $student_outcome_id)
                ->where('course_id', $course_id)
                ->where('is_active', true)
                ->where('difficulty_level_id', 1)
                ->count();
    }
    public static function countAverage($student_outcome_id='', $course_id='') {
        return TestQuestion::where('student_outcome_id', $student_outcome_id)
                ->where('course_id', $course_id)
                ->where('is_active', true)
                ->where('difficulty_level_id', 2)
                ->count();
    }
    public static function countDifficult($student_outcome_id='', $course_id='') {
        return TestQuestion::where('student_outcome_id', $student_outcome_id)
                ->where('course_id', $course_id)
                ->where('is_active', true)
                ->where('difficulty_level_id', 3)
                ->count();
    }


    public static function countTestQuestion($student_outcome_id='', $difficulty_level_id='') {
        return TestQuestion::where('student_outcome_id', $student_outcome_id)
                ->where('is_active', true)
                ->where('difficulty_level_id', $difficulty_level_id)
                ->count();
    }

    public static function countTestQuestionByCourse($student_outcome_id='', $course_id='') {
        return TestQuestion::where('student_outcome_id', $student_outcome_id)
                ->where('is_active', true)
                ->where('course_id', $course_id)
                ->count();
    }

    public static function getRandTestQuestions($student_outcome_id='',$course_id='', $difficulty_level_id='', $count=0) {
        return TestQuestion::where('student_outcome_id', $student_outcome_id)
                ->where('course_id', $course_id)
                ->where('is_active', true)
                ->where('difficulty_level_id', $difficulty_level_id)
                ->inRandomOrder()
                ->take($count)
                ->get();
    }

    public static function getRandTestQuestionsUnique($student_outcome_id='',$course_id='', $difficulty_level_id='', $count=0, $not_in=[]) {
        return TestQuestion::where('student_outcome_id', $student_outcome_id)
                ->where('course_id', $course_id)
                ->where('is_active', true)
                ->where('difficulty_level_id', $difficulty_level_id)
                ->whereNotIn('test_questions.id', $not_in)
                ->inRandomOrder()
                ->take($count)
                ->get();
    }
}
