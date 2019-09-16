<?php

namespace App\Http\Controllers\s;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Assessment;

class HomeController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }


    public function index() {
        $assessments = Assessment::where('student_id', auth()->user()->getStudent()->id)->get();
        $student = auth()->user()->getStudent();
        return view('s.home', compact('assessments', 'student'));
    }
}
