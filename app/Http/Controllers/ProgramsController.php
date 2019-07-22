<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Program;
use App\College;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Gate;
use App\StudentOutcomeArchiveVersion;
use App\StudentOutcomeArchive;
use App\PerformanceCriteriaArchive;
use App\PerformanceCriteriaIndicatorArchive;

class ProgramsController extends Controller
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
        $programs = [];


        if(Gate::allows('isSAdmin')) {
            //return abort('401', 'Unauthorized');

            if(request('college_id') != '') {
                $programs = Program::where('college_id', request('college_id'))->paginate(10);
            } else {
                $programs = Program::paginate(10);
            }
            

        } else if(Gate::allows('isDean') || Gate::allows('isProf')) {
            $programs = Program::where('college_id', Auth::user()->getFaculty()->college_id)->paginate(10);
        }

        $colleges = College::all();
        
        $college_name = Gate::check('isSAdmin')  ? 'PNC' : Auth::user()->getFaculty()->college->college_code;

        return view('programs.index')
            ->with('programs', $programs)
            ->with('college_name', $college_name)
            ->with('colleges', $colleges);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //return view('programs.create');
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
            'program_code' => 'required|max:10|unique:programs',
            'description' => 'required|max:255',
            'college_id' => 'required',
            'color' => 'required'
        ]);


        $program = Program::create([
            'program_code' => request('program_code'),
            'description' => request('description'),
            'college_id' => request('college_id'),
            'color' => request('color')
        ]);
        Session::flash('message', 'Program successfully added to database'); 
        return redirect('/programs/' . $program->id);
    }

    public function check_code(Request $request) {
        $request->validate([
            'program_code' => 'unique:programs,program_code,'. request('id')
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $program = Program::findOrFail($id);
        $colleges = College::all();
        return view('programs.show')
            ->with('program', $program)
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

        $program = Program::findOrFail($id);

        $request->validate([
            'program_code' => 'required|max:10|unique:programs,program_code,'.$id,
            'description' => 'required|max:255',
            'college_id' => 'required',
            'color' => 'required'
        ]);

        $program->program_code = request('program_code');
        $program->description = request('description');
        $program->college_id = request('college_id');

        $program->update();

        Session::flash('message', 'Program successfully updated from database'); 

        return redirect('/programs/' . $program->id);
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

    public function save_student_outcomes(Program $program) {
        $program->so_is_saved = true;
        $program->save();

        return $program;
    }

    public function revise_student_outcomes(Program $program) {

        //create StudentOutcomeArchiveVersion
        $student_outcome_archive_version = new StudentOutcomeArchiveVersion();
        $student_outcome_archive_version->revision_no = $program->so_rev_no;
        $student_outcome_archive_version->program_id = $program->id;
        $student_outcome_archive_version->save();

        //create StudentOutcomeArchives
        $student_outcomes = $program->studentOutcomes;

        foreach ($student_outcomes as $student_outcome) {
            $so_archive = new StudentOutcomeArchive();
            $so_archive->program_id = $student_outcome->program_id;
            $so_archive->so_code = $student_outcome->so_code;
            $so_archive->description = $student_outcome->description;
            $so_archive->revision_no = $program->so_rev_no;
            $so_archive->save();

            foreach ($student_outcome->performanceCriterias as $pc) {
                $pc_archive = new PerformanceCriteriaArchive();
                $pc_archive->description = $pc->description;
                $pc_archive->student_outcome_id = $so_archive->id;
                $pc_archive->save();

                foreach ($pc->performanceCriteriaIndicators as $pc_indicator) {
                    $pc_indicator_archive = new PerformanceCriteriaIndicatorArchive();
                    $pc_indicator_archive->performance_criteria_id = $pc_archive->id;
                    $pc_indicator_archive->performance_indicator_id = $pc_indicator->performance_indicator_id;
                    $pc_indicator_archive->description = $pc_indicator->description;
                    $pc_indicator_archive->score_percentage = $pc_indicator->score_percentage;
                    $pc_indicator_archive->save();
                }
            }
            
        }

        //update program
        $program->so_is_saved = false;
        $program->so_rev_no += 1;
        $program->save();



        return $program;
    }
}
