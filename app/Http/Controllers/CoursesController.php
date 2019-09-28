<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\College;
use App\Course;
use App\Http\Resources\CourseResource;
use Illuminate\Support\Facades\Session;
use Gate;

class CoursesController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        }


        if($request->ajax()) {
            if(request('q') != '') {
                if(Gate::check('isSAdmin')) {
                    $courses_result = Course::where('course_code', 'LIKE', '%' . request('q') . '%')
                    ->orWhere('description', 'LIKE', '%' . request('q') . '%')
                    ->get();
                } else {
                    // $courses_result = Course::where('course_code', 'LIKE', '%' . request('q') . '%')
                    // ->orWhere('description', 'LIKE', '%' . request('q') . '%')
                    // ->orWhere('is_public', true)
                    // ->where('college_id', Session::get('college_id'))
                    // ->get();
                    $courses_result = Course::where(function($query) {
                        $query->where('course_code', 'LIKE', '%' . request('q') . '%')
                        ->orWhere('description', 'LIKE', '%' . request('q') . '%');
                        
                    })
                    ->where(function($query) {
                        $query->where('college_id', Session::get('college_id'))
                        ->orWhere('is_public', true);
                    })   
                    ->get();

                }


                return CourseResource::collection($courses_result);

            } else if(request('filter_by_college') != '' && request('filter_by_privacy') != '') {
                if(Gate::check('isSAdmin')) {
                    return CourseResource::collection(Course::where('college_id', request('filter_by_college'))
                        ->where('is_public', request('filter_by_privacy') == 'public' ? true : false )
                        ->paginate(20));
                } else {
                    return CourseResource::collection(Course::where('college_id', Session::get('college_id'))
                        ->where('is_public', request('filter_by_privacy') == 'public' ? true : false)
                        ->paginate(20));
                }
                
            } else if(request('filter_by_college') != '') {
                return CourseResource::collection(Course::where('college_id', request('filter_by_college'))
                    ->paginate(20));
            } else if(request('filter_by_privacy') != '') {
                if(Gate::check('isSAdmin')) {
                    return CourseResource::collection(Course::
                        where('is_public', request('filter_by_privacy') == 'public' ? true : false )
                        ->paginate(20));
                } else {
                    return CourseResource::collection(Course::where('college_id', Session::get('college_id'))
                        ->where('is_public', request('filter_by_privacy') == 'public' ? true : false)
                        ->paginate(20));
                }
            } else {
                if(Gate::check('isSAdmin')) {
                    return CourseResource::collection(Course::latest()->paginate(20));
                } else {
                    return CourseResource::collection(Course::where('college_id', Session::get('college_id'))
                        ->orWhere('is_public', true)
                        ->latest()
                        ->paginate(20));
                }
            }
            
        }

        $courses_count = 0;
        if(Gate::check('isSAdmin')) {
            $courses_count = Course::count();
        } else {
            $courses_count = Course::where('college_id', Session::get('college_id'))->count();
        }

        $colleges = College::all();
        $courses = Course::latest()->get();

        return view('courses.index')
            ->with('colleges', $colleges)
            ->with('courses_count', $courses_count);
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

        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin')) {
            return abort('401', 'Unauthorized');
        }

        $request->validate([
            'course_code' => 'required|max:10|unique:courses',
            'description' => 'required|max:255|string',
            'college_id' => 'required',
            'lec_unit' => 'required|integer|between:0,20',
            'lab_unit' => 'required|integer|between:0,20',
            'color' => 'required'
        ]);
        
        $course = Course::create([
            'course_code' => strtoupper(request('course_code')),
            'description' => strtoupper(request('description')),
            'college_id' => request('college_id'),
            'lec_unit' => request('lec_unit'),
            'lab_unit' => request('lab_unit'),
            'is_public' => true,
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
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        }

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

        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin')) {
            return abort('401', 'Unauthorized');
        }

        $request->validate([
            'course_code' => 'required|max:10|unique:courses,course_code,' . $id,
            'description' => 'required|max:255|string',
            'college_id' => 'required',
            'lec_unit' => 'required|integer|between:0,20',
            'lab_unit' => 'required|integer|between:0,20',
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
