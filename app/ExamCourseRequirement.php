<?php

namespace App;

class ExamCourseRequirement 
{
    public $total;
    public $easy;
    public $average;
    public $difficult;
    public $curriculum_map;


    public function computeTotalForeachDifficulties() {
        $easy_count = floor($this->total * .50);
        $average_count = floor($this->total * .30);
        $difficult_count = floor($this->total * .20);

        $easy_count += $this->total - ($easy_count + $average_count + $difficult_count);

        //set
        $this->easy = $easy_count;
        $this->average = $average_count;
        $this->difficult = $difficult_count;
    }
}