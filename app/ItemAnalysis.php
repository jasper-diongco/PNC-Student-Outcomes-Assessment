<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ItemAnalysisDetail;

class ItemAnalysis extends Model
{
    public $guarded = [];

    public function itemAnalysisDetails() {
        return $this->hasMany('ItemAnalysisDetail');
    }

    public function getRetainedItem() {
        return ItemAnalysisDetail::where('item_analysis_id', $this->id)
                ->where('action_resolved_id', 1)
                ->get();
    }

    public function getRevisedItem() {
        return ItemAnalysisDetail::where('item_analysis_id', $this->id)
                ->where('action_resolved_id', 2)
                ->get();
    }

    public function getRejectedItem() {
        return ItemAnalysisDetail::where('item_analysis_id', $this->id)
                ->where('action_resolved_id', 3)
                ->get();
    }

    public function requirementsAvailable($curriculum_course_requirements) {
        $available_items = [];

        // foreach ($curriculum_course_requirements as $curriculum_course_requirement) {
        //     exit($curriculum_course_requirement->curriculum_map->curriculum_course->course_id);
        // }
    }
}
