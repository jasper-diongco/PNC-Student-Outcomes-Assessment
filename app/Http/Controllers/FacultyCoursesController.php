<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FacultyCourse;
use App\User;

class FacultyCoursesController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {

        $faculty_id = request('faculty_id');


        if(request('user_id') != '') {
            $user = User::find(request('user_id'));
            $faculty_id = $user->getFaculty()->id;
        }

        

        $faculty_courses = FacultyCourse::where('faculty_id', $faculty_id)
                            ->with('course')
                            ->latest()
                            ->get();

        return $faculty_courses;
    }

    public function getFacultyCourseTestQuestions(FacultyCourse $faculty_course) {

        return response()->json([
            'course' => $faculty_course->course,
            'test_questions' => $faculty_course->getFacultyCourseTestQuestions()
        ], 200);
    }

    public function store() {
        $faculty_course = FacultyCourse::create([
            'faculty_id' => request('faculty_id'),
            'course_id' => request('course_id'),
            'is_active' => true
        ]);

        return $faculty_course;
    }

    public function deactivate(FacultyCourse $faculty_course) {

        $faculty_course->is_active = false;
        $faculty_course->save();

        return $faculty_course;
    }

    public function activate(FacultyCourse $faculty_course) {
        
        $faculty_course->is_active = true;
        $faculty_course->save();

        return $faculty_course;
    }
}
