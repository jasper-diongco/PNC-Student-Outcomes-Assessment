<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CurriculumCourse;
use Illuminate\Support\Facades\DB;
use App\CourseRequisite;
use App\Http\Resources\CurriculumCourseResource;

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

        //validate pre requisite
        if (count(request('pre_requisites')) > 0) {
            foreach (request('pre_requisites') as $pre_req) {
               if(($pre_req['year_level'] >= request('year_level') && $pre_req['semester'] >= request('semester')) || ($pre_req['year_level'] == request('year_level') && $pre_req['semester'] > request('semester'))) {
                return response()->json(["message" => "The given data was invalid.","errors" => ["pre_requisites" => ["Invalid pre requisite, make sure you are not creating pre requisite loop. Also, check if the pre requisite is on earlier year level or semester"]]], 422); 
               }
            }
        }


        DB::beginTransaction();

        try {

            $curriculum_course = CurriculumCourse::create([
                'course_id' => request('course_id'),
                'curriculum_id' => request('curriculum_id'),
                'year_level' => request('year_level'),
                'semester' => request('semester')
            ]);

            //save pre requisite
            if (count(request('pre_requisites')) > 0) {
                foreach (request('pre_requisites') as $pre_req) {
                    CourseRequisite::create([
                        'curriculum_course_id' => $curriculum_course->id,
                        'type' => 1,
                        'pre_req_id' => $pre_req["id"]
                    ]);
                }
            }
            DB::commit();
            // all good
            return $curriculum_course;

        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return abort(500, 'Internal Server Error');
        }
        
        
        //return $request;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $curriculum_resource = CurriculumCourse::findOrFail($id);

        return new CurriculumCourseResource($curriculum_resource);
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
            'course_id' => 'required',
            'curriculum_id' => 'required',
            'year_level' => 'required',
            'semester' => 'required'
        ]);

        //validate pre requisite
        if (count(request('pre_requisites')) > 0) {
            foreach (request('pre_requisites') as $pre_req) {
               if(($pre_req['year_level'] >= request('year_level') && $pre_req['semester'] >= request('semester')) || ($pre_req['year_level'] == request('year_level') && $pre_req['semester'] > request('semester'))) {
                return response()->json(["message" => "The given data was invalid.","errors" => ["pre_requisites" => ["Invalid pre requisite, make sure you are not creating pre requisite loop. Also, check if the pre requisite is on earlier year level or semester"]]], 422); 
               }
            }
        }



        $curriculum_course = CurriculumCourse::findOrFail($id);



        //check if pre requisite of other courses
        if(CourseRequisite::where('pre_req_id', '=', $curriculum_course->id)->count() > 0) {

            //check if position change
            if($curriculum_course->year_level != request('year_level') || $curriculum_course->semester != request('semester')) {
                return response()->json(["message" => "The given data was invalid.","errors" => ["course_id" => ["Cannot update. This course is pre requisite of other courses"]]], 422);
            }
            
        }




        $curriculum_course->course_id = request('course_id');
        $curriculum_course->curriculum_id = request('curriculum_id');
        $curriculum_course->year_level = request('year_level');
        $curriculum_course->semester = request('semester');


        $curriculum_course->update();

        //delete previous pre requisite(s)
        foreach ($curriculum_course->courseRequisites as $pre_req) {
           // $course_req = CourseRequisite::findOrFail($pre_req['pre_req_id']);
           // $course_req->delete();
            $pre_req->delete();
        }
        


        //save pre requisite
        if (count(request('pre_requisites')) > 0) {
            foreach (request('pre_requisites') as $pre_req) {
                CourseRequisite::create([
                    'curriculum_course_id' => $curriculum_course->id,
                    'type' => 1,
                    'pre_req_id' => $pre_req["id"]
                ]);
            }
        }


        return $curriculum_course;
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

        DB::beginTransaction();

        try {
            //validate first before delete
            if(CourseRequisite::where('pre_req_id', '=', $curriculumCourse->id)->count() > 0) {
                return abort(422, 'Unprocess Entity');
            }



            //delete previous pre requisite(s)
            foreach ($curriculumCourse->courseRequisites as $pre_req) {
                $pre_req->delete();
            }

            $curriculumCourse->delete();

            

            DB::commit();
            // all good

            return $curriculumCourse;
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong

            return abort(500, 'Internal Server Error');
        }


        
    }
}
