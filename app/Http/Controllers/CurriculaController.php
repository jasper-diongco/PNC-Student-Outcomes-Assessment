<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Program;
use App\Curriculum;
use App\College;
use App\CurriculumCourse;
use App\CourseRequisite;
use App\Http\Resources\CurriculumResource;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CurriculaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $programs = Program::where('college_id', Session::get('college_id'))->get();
        // $curricula = Curriculum::join('programs', 'programs.id', '=', 'curricula.program_id')
        //     ->join('colleges', 'colleges.id', '=', 'programs.college_id')
        //     ->select('curricula.*')    
        //     ->where('college_id', Session::get('college_id'))
        //     ->get();

        $curricula = [];

        $list = DB::select('SELECT program_id, max(curricula.id) as id from curricula GROUP BY program_id');

        foreach ($list as $item) {
            $curricula[] = Curriculum::find($item->id);
        }

        return view('curricula.index')
            ->with('programs', $programs)
            ->with('curricula', $curricula);
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
            'program_id' => 'required',
            'name' => 'required|max:255|regex:/^[\pL\s\-0-9]+$/u',
            'description' => 'nullable|max:255|regex:/^[\pL\s\-0-9]+$/u',
            'year' => 'required|digits:4',
            'year_level' => 'required'
        ]);

        $curriculum = Curriculum::create([
            'program_id' => request('program_id'),
            'name' => strtoupper(request('name')),
            'description' => request('description'),
            'year' => request('year'),
            'user_id' => Auth::user()->id,
            'year_level' => request('year_level'),
            'revision_no' => 1
        ]);

        Session::flash('message', 'Curriculum successfully added to database');

        return $curriculum;

    }

    public function saveCurriculum(Request $request, $id) {
        $curriculum = Curriculum::findOrFail($id);
        $curriculum->is_saved = true;
        $curriculum->update();

        Session::flash('message', 'Curriculum successfully saved!');

        return redirect('/curricula/' . $curriculum->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $curriculum = Curriculum::findOrFail($id);
        $colleges = College::all();

        if($request->ajax() && request('json') == 'yes') {
            return new CurriculumResource($curriculum);
        }
        

        return view('curricula.show')
            ->with('curriculum', $curriculum)
            ->with('colleges', $colleges);
    }

    public function revise(Request $request, $id) {
        $request->validate([
            'program_id' => 'required',
            'name' => 'required|max:255|regex:/^[\pL\s\-0-9]+$/u',
            'description' => 'nullable|max:255|regex:/^[\pL\s\-0-9]+$/u',
            'year' => 'required|digits:4',
            'year_level' => 'required'
        ]);

        $curriculum = Curriculum::findOrFail($id);


        $curriculum->load('curriculumCourses');

        $newCurriculum = $curriculum->replicate();
        $newCurriculum->name = request('name');
        $newCurriculum->description = request('description');
        $newCurriculum->year = request('year');
        $newCurriculum->year_level = request('year_level');
        $newCurriculum->revision_no += 1;
        $newCurriculum->is_saved = false;
        $newCurriculum->push();

        foreach($curriculum->getRelations() as $relation => $items){
            foreach($items as $item){
                $cloneItem = clone $item;
                unset($item->id);
                $newItem = $newCurriculum->{$relation}()->create($item->toArray());
                //try lang
                foreach ($cloneItem->courseRequisites as $requisite) {
                    $course_id = CurriculumCourse::find($requisite->pre_req_id)->course->id;
                    //find curriculum course in the curriculum
                    $curriculum_course = CurriculumCourse::where('course_id', $course_id)
                    ->where('curriculum_id', $newCurriculum->id)
                    ->first();

                    //then add the requisite
                    // $course_requisite = new CourseRequisite();
                    // $course_requisite->curriculum_course_id = $newItem->id;
                    // $course_requisite->type = 2;
                    // $course_requisite->pre_req_id = $curriculum_course->id;
                    // $course_requisite->create();
                    CourseRequisite::create([
                        'curriculum_course_id' => $newItem->id,
                        'type' => 2,
                        'pre_req_id' => $curriculum_course->id
                    ]);
                }
            }
        }
        Session::flash('message', 'Curriculum successfully cloned!');

        return $newCurriculum;
    }
}
