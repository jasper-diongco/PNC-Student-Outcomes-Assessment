<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Choice extends Model
{
    public $fillable = ['test_question_id', 'body', 'is_correct', 'is_active', 'user_id'];
}
