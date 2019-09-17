<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\CustomRecordedAssessmentRecord;
use App\CustomRecordedAssessment;
use App\StudentOutcome;
use App\Student;

class CustomRecordedAssessmentRecordsController extends Controller
{

    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {

    }

    

    public function store() {
        $data = request()->validate([
            'student_id' => 'required',
            'custom_recorded_assessment_id' => 'required',
            'score' => 'required|min:0'
        ]);

        $custom_recorded_assessment = CustomRecordedAssessment::find($data['custom_recorded_assessment_id']);

        if($data['score'] > $custom_recorded_assessment->overall_score) {
            return response()->json([
                'message' => 'Max score exceed!',
                'errors' => [
                    'score' => ['Overall score exceeded!']
                ]
            ], 422);
        }

        $custom_recorded_assessment_record = CustomRecordedAssessmentRecord::create([
            'code' => $this->generateAssessmentID(),
            'student_id' => $data['student_id'],
            'custom_recorded_assessment_id' => $data['custom_recorded_assessment_id'],
            'score' => $data['score'],
        ]);

        return $custom_recorded_assessment_record;
    }

    public function getCoursesGrade() {

        $student_outcome_id = request('student_outcome_id');
        $student_id = request('student_id');

        $student = Student::findOrFail($student_id);  

        $student_outcome = StudentOutcome::findOrFail($student_outcome_id);

        $courses_grade = $student_outcome->getCoursesGrade($student->curriculum_id, $student_outcome->id, $student_id);

        return $courses_grade;
    }

    private function generateAssessmentID() {
        $now = Carbon::now();
        $new_id = $now->format('Ym') . '0001';

        $custom_recorded_assessment_record = CustomRecordedAssessmentRecord::where('code', 'LIKE', $now->format('Ym') . '%')
            ->orderBy('code', 'DESC')
            ->first();

        if($custom_recorded_assessment_record) {
            $current_count = intval(substr($custom_recorded_assessment_record->assessment_code, 6));
            $current_count += 1;

            return $now->format('Ym') . sprintf("%'.04d", $current_count);
        }

        return $new_id;
    }


}
