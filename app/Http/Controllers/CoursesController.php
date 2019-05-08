<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\College;
use App\Course;
use App\Http\Resources\CourseResource;
use Illuminate\Support\Facades\Session;

class CoursesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
            return CourseResource::collection(Course::all());
        }

        $colleges = College::all();
        return view('courses.index')->with('colleges', $colleges);
    }

    public function get_courses() {
        return CourseResource::collection(Course::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_code' => 'required|max:10|unique:courses',
            'description' => 'required|max:255|string',
            'college_id' => 'required',
            'lec_unit' => 'required|integer|between:0,20',
            'lab_unit' => 'required|integer|between:0,20',
            'privacy' => 'required',
            'color' => 'required'
        ]);
        
        $course = Course::create([
            'course_code' => strtoupper(request('course_code')),
            'description' => strtoupper(request('description')),
            'college_id' => request('college_id'),
            'lec_unit' => request('lec_unit'),
            'lab_unit' => request('lab_unit'),
            'is_public' => request('privacy'),
            'color' => request('color')
        ]);

        Session::flash('message', 'Course successfully added to database'); 

        return $course;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $course = Course::findOrFail($id);
        $colleges = College::all();

        return view('courses.show')
            ->with('course', $course)
            ->with('colleges', $colleges);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'course_code' => 'required|max:10|unique:courses,course_code,' . $id,
            'description' => 'required|max:255|string',
            'college_id' => 'required',
            'lec_unit' => 'required|integer|between:0,20',
            'lab_unit' => 'required|integer|between:0,20',
            'privacy' => 'required',
            'color' => 'required'
        ]);

        $course = Course::findOrFail($id);

        $course->course_code = strtoupper(request('course_code'));
        $course->description = strtoupper(request('description'));
        $course->college_id = request('college_id');
        $course->lec_unit = request('lec_unit');
        $course->lab_unit = request('lab_unit');
        $course->is_public = request('privacy');

        $course->update();

        Session::flash('message', 'Course successfully updated from database');
        return $course;

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
