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
use App\CurriculumMappingStatus;
use Gate;

class CurriculaController extends Controller
{

    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //authenticate
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        }


        if(Auth::user()->user_type_id == 's_admin') {
            $colleges = College::all();
            if(request('college_id') == '') {
                return view('curricula.list')->with('colleges', $colleges);
            }
            
        } else {
            //validate
            if(request('college_id') == '') {
                // abort('404', 'Page not found');
                return redirect('/curricula?college_id='. Session::get('college_id'));
            } else if (request('college_id') != Session::get('college_id')) {
                return abort('401', 'Unauthorized');
            }
        }


        $programs = Program::where('college_id', Session::get('college_id'))->get();

        $curricula = [];

        $list = DB::select('SELECT ref_id, max(curricula.id) as id from ((curricula INNER JOIN programs ON programs.id = curricula.program_id) INNER JOIN colleges ON colleges.id = programs.college_id) WHERE programs.college_id = :college_id GROUP BY ref_id', ['college_id' => request('college_id')]);

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
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin')) {
            return abort('401', 'Unauthorized');
        }

        $request->validate([
            'program_id' => 'required',
            'name' => 'required|max:255|regex:/^[\pL\s\-0-9_]+$/u',
            'description' => 'nullable|max:255|regex:/^[\pL\s\-0-9_]+$/u',
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



        $curriculum->ref_id = $curriculum->id;

        $curriculum->save();

        CurriculumMappingStatus::create([
            'curriculum_id' => $curriculum->id,
            'status' => false
        ]);

        Session::flash('message', 'Curriculum successfully added to database');

        return $curriculum;

    }

    public function saveCurriculum(Request $request, $id) {
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin')) {
            return abort('401', 'Unauthorized');
        }

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


    public function edit($id) {
        $curriculum = Curriculum::findOrFail($id);
        $curriculum->is_saved = false;
        $curriculum->update();

        Session::flash('message', 'You can edit this curriculum now.');

        return redirect('/curricula/' . $curriculum->id);
    }

    public function revise(Request $request, $id) {
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin')) {
            return abort('401', 'Unauthorized');
        }

        $request->validate([
            'program_id' => 'required',
            'name' => 'required|max:255|regex:/^[\pL\s\-0-9_]+$/u',
            'description' => 'nullable|max:255|regex:/^[\pL\s\-0-9_]+$/u',
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

    public function deactivatedCourses($id) {
        $curriculum_courses =  CurriculumCourse::where('curriculum_id', $id)
            ->where('is_active', 0)
            ->paginate(10);

        return view('curricula.deactivated_courses')->with('curriculum_courses', $curriculum_courses);
    }
}
