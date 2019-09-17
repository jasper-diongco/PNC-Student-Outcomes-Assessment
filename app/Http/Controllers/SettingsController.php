<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Setting;

class SettingsController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $settings = Setting::all();
        return view('settings.index', compact('settings'));
    }

    public function update() {
        $request_show_assessment_details_to_student = request('show_assessment_details_to_student');

        $show_assessment_details_to_student = Setting::find($request_show_assessment_details_to_student['id']);


        $show_assessment_details_to_student->value = $request_show_assessment_details_to_student == true ? 'true' : 'false';

        $show_assessment_details_to_student->save();

        return $show_assessment_details_to_student;
    }

    public function get_show_assessment_details_to_student() {
        $show_assessment_details_to_student = Setting::where('name', 'show_assessment_details_to_student')->first();

        return $show_assessment_details_to_student;
    }
}
