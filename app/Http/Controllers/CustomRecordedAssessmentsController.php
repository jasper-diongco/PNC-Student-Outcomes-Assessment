<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CustomRecordedAssessment;
use Carbon\Carbon;

class CustomRecordedAssessmentsController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $curriculum_id = request('curriculum_id');
        $student_outcome_id = request('student_outcome_id');

        $custom_recorded_assessments = CustomRecordedAssessment::where('curriculum_id', $curriculum_id)
                        ->where('student_outcome_id', $student_outcome_id)
                        ->where('is_active', true)
                        ->with('user')
                        ->latest()
                        ->get();

        return $custom_recorded_assessments;
    }

    public function get_records(CustomRecordedAssessment $custom_recorded_assessment) {
        return $custom_recorded_assessment->customRecordedAssessmentRecords;
    }

    public function show(CustomRecordedAssessment $custom_recorded_assessment) {

        return view('custom_recorded_assessments.show', compact('custom_recorded_assessment'));
    }

    public function store() {
        $data = request()->validate([
            'name' => 'required|max:255',
            'description' => 'required|max:2000',
            'overall_score' => 'required|max:1000',
            'passing_percentage' => 'required|max:100|min:1',
            'student_outcome_id' => 'required',
            'curriculum_id' => 'required'
        ]);

        $custom_recorded_assessment = CustomRecordedAssessment::create([
            'ca_code' => $this->generateAssessmentID(),
            'name' => $data['name'],
            'description' => $data['description'],
            'overall_score' => $data['overall_score'],
            'passing_percentage' => $data['passing_percentage'],
            'student_outcome_id' => $data['student_outcome_id'],
            'curriculum_id' => $data['curriculum_id'],
            'user_id' => auth()->user()->id
        ]);

        return $custom_recorded_assessment;
    }

    private function generateAssessmentID() {
        $now = Carbon::now();
        $new_id = $now->format('Ym') . '0001';

        $custom_recorded_assessment = CustomRecordedAssessment::where('ca_code', 'LIKE', $now->format('Ym') . '%')
            ->orderBy('ca_code', 'DESC')
            ->first();

        if($custom_recorded_assessment) {
            $current_count = intval(substr($custom_recorded_assessment->assessment_code, 6));
            $current_count += 1;

            return $now->format('Ym') . sprintf("%'.04d", $current_count);
        }

        return $new_id;
    }
}
