<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CurriculumCourse;

class CurriculumCoursesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'course_id' => 'required',
            'curriculum_id' => 'required',
            'year_level' => 'required',
            'semester' => 'required'
        ]);

        if(CurriculumCourse::where('curriculum_id', '=', request('curriculum_id'))->where('course_id', request('course_id'))->exists()) {

            return response()->json(["message" => "The given data was invalid.","errors" => ["course_id" => ["This course is already added in this curriculum."]]],422);
        }


        $curriculum_course = CurriculumCourse::create([
            'course_id' => request('course_id'),
            'curriculum_id' => request('curriculum_id'),
            'year_level' => request('year_level'),
            'semester' => request('semester')
        ]);

        return $curriculum_course;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $curriculumCourse = CurriculumCourse::findOrFail($id);
        $curriculumCourse->delete();

        return $curriculumCourse;
    }
}
