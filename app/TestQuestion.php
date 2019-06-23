<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ImageObject;

class TestQuestion extends Model
{
    public $fillable = ['title', 'body', 'student_outcome_id', 'course_id', 'difficulty_level_id', 'user_id', 'is_active', 'performance_criteria_id', 'ref_id'];


    public function choices() {
        return $this->hasMany('App\Choice')->where('is_active', true);
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
}
