<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Choice extends Model
{
    public $fillable = ['test_question_id', 'body', 'is_correct', 'is_active', 'user_id'];

    public function getHtml() {
        $input = $this->body;
        $matches = [];
        $ids = [];

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

        return $input;
    }
}
