<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TestQuestion;
use App\ItemAnalysisDetail;
use App\DifficultyLevel;
use Illuminate\Support\Facades\DB;

class ItemAnalysisController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function reject_test_question(TestQuestion $test_question) {

        DB::beginTransaction();

        try {

            $item_analysis_detail_id = request('item_analysis_detail_id');

            $test_question->is_active = false;

            $test_question->save();

            $item_analysis_detail = ItemAnalysisDetail::findOrFail($item_analysis_detail_id);

            $item_analysis_detail->is_resolved = true;
            $item_analysis_detail->action_resolved = "Item is rejected";
            $item_analysis_detail->save();

            

            DB::commit();
            // all good

            return $item_analysis_detail;
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
        }

        
    }

    public function retain_test_question(TestQuestion $test_question) {

        DB::beginTransaction();

        try {

            $item_analysis_detail_id = request('item_analysis_detail_id');

            $item_analysis_detail = ItemAnalysisDetail::findOrFail($item_analysis_detail_id);

            $item_analysis_detail->is_resolved = true;
            $item_analysis_detail->action_resolved = "Item is retained";
            $item_analysis_detail->save();

            

            DB::commit();
            // all good

            return $item_analysis_detail;
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
        }

        
    }

    public function change_level_of_difficulty(TestQuestion $test_question) {

        DB::beginTransaction();

        try {

            $item_analysis_detail_id = request('item_analysis_detail_id');
            $difficulty_level_id = request('difficulty_level_id');

            $current_diff = $test_question->difficultyLevel->description;
            $change_diff = DifficultyLevel::find($difficulty_level_id)->description;

            $test_question->difficulty_level_id = $difficulty_level_id;

            $test_question->save();

            $item_analysis_detail = ItemAnalysisDetail::findOrFail($item_analysis_detail_id);

            $item_analysis_detail->is_resolved = true;
            $item_analysis_detail->action_resolved = "Item's level of difficulty is changed from $current_diff to $change_diff";
            $item_analysis_detail->save();

            

            DB::commit();
            // all good

            return [
                'item_analysis_detail' => $item_analysis_detail,
                'test_question' => $test_question
            ];
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
        }

        
    }
}
