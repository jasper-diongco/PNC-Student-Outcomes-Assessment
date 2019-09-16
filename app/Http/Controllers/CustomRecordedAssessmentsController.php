<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CustomRecordedAssessment;

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
                        ->latest()
                        ->get();

        return $custom_recorded_assessments;
    }

    public function store() {
        $data = request()->validate([
            'description' => 'required|max:2000',
            'overall_score' => 'required|max:1000',
            'passing_percentage' => 'required|max:100|min:1',
            'student_outcome_id' => 'required',
            'curriculum_id' => 'required'
        ]);

        $custom_recorded_assessment = CustomRecordedAssessment::create([
            'description' => $data['description'],
            'overall_score' => $data['overall_score'],
            'passing_percentage' => $data['passing_percentage'],
            'student_outcome_id' => $data['student_outcome_id'],
            'curriculum_id' => $data['curriculum_id'],
            'user_id' => auth()->user()->id
        ]);

        return $custom_recorded_assessment;
    }
}
