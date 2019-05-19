<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Program;
use App\StudentOutcome;
use App\PerformanceCriteria;
use App\PerformanceCriteriaIndicator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Gate;

class StudentOutcomesController extends Controller
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


        if(request('program_id') == '') {
            return redirect('/student_outcomes/list_program');
        }
        $program = Program::find(request('program_id'));
        $programs = Program::all();

        return view('student_outcomes.index')
            ->with('program', $program)
            ->with('programs', $programs);
    }

    public function listProgram() {
        //authenticate
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        }

        if (request('college_id') == '') {
            return redirect('/student_outcomes/list_program?college_id='. Session::get('college_id'));
        } else if (request('college_id') == 'all') {
            if(Gate::check('isSAdmin')) {
                $programs = Program::orderBy('college_id')->paginate(10);
            } else {
                return redirect('/student_outcomes/list_program?college_id='. Session::get('college_id'));
            }
            
        } else {
            if(!Gate::check('isSAdmin') && Session::get('college_id') != request('college_id')) {
                return abort('401', 'Unauthorized');
            }

            $programs = Program::where('college_id', request('college_id'))->paginate(10);
        }

        

        return view('student_outcomes.list_programs')->with('programs', $programs);
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
            'so_code' => 'required|max:10|alpha_num|unique:student_outcomes',
            'description' => 'required|regex:/^[\pL\s\-0-9_]+$/u',
            'program' => 'required',
            'performance_criteria' => 'required|regex:/^[\pL\s\-0-9_.,()\']+$/u',
            'unsatisfactory_desc' => 'required|regex:/^[\pL\s\-0-9_.,()\']+$/u',
            'unsatisfactory_grade' => 'required|min:0|max:100|numeric',
            'developing_desc' => 'required|regex:/^[\pL\s\-0-9_.,()\']+$/u',
            'developing_grade' => 'required|min:0|max:100|numeric',
            'satisfactory_desc' => 'required|regex:/^[\pL\s\-0-9_.,()\']+$/u',
            'satisfactory_grade' => 'required|min:0|max:100|numeric',
            'exemplary_desc' => 'required|regex:/^[\pL\s\-0-9_.,()\']+$/u',
            'exemplary_grade' => 'required|min:0|max:100|numeric'
        ]);

        $student_outcome = StudentOutcome::create([
            'so_code' => request('so_code'),
            'description' => request('description'),
            'program_id' => request('program')
        ]);

        $performance_criteria = PerformanceCriteria::create([
            'description' => request('performance_criteria'),
            'student_outcome_id' => $student_outcome->id
        ]);

        $unsatisfactory = PerformanceCriteriaIndicator::create([
            'performance_criteria_id' => $performance_criteria->id,
            'performance_indicator_id' => 1,
            'description' => request('unsatisfactory_desc'),
            'score_percentage' => request('unsatisfactory_grade')
        ]);

        $developing = PerformanceCriteriaIndicator::create([
            'performance_criteria_id' => $performance_criteria->id,
            'performance_indicator_id' => 2,
            'description' => request('developing_desc'),
            'score_percentage' => request('developing_grade')
        ]);

        $satisfactory = PerformanceCriteriaIndicator::create([
            'performance_criteria_id' => $performance_criteria->id,
            'performance_indicator_id' => 3,
            'description' => request('satisfactory_desc'),
            'score_percentage' => request('satisfactory_grade')
        ]);

        $exemplary = PerformanceCriteriaIndicator::create([
            'performance_criteria_id' => $performance_criteria->id,
            'performance_indicator_id' => 4,
            'description' => request('exemplary_desc'),
            'score_percentage' => request('exemplary_grade')
        ]);

        Session::flash('message', 'Student Outcome successfully added to database');

        return $student_outcome;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //authenticate
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        }

        $student_outcome = StudentOutcome::findOrFail($id);
        $programs = Program::all();

        return view('student_outcomes.show')->with('student_outcome', $student_outcome)
            ->with('programs', $programs);

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
            'so_code' => 'required|max:10|alpha_num|unique:student_outcomes,so_code,'.$id,
            'description' => 'required|regex:/^[\pL\s\-0-9_]+$/u',
            'program' => 'required',
            'performance_criteria' => 'required|regex:/^[\pL\s\-0-9_.,()\']+$/u',
            'unsatisfactory_desc' => 'required|regex:/^[\pL\s\-0-9_.,()\']+$/u',
            'unsatisfactory_grade' => 'required|min:0|max:100|numeric',
            'developing_desc' => 'required|regex:/^[\pL\s\-0-9_.,()\']+$/u',
            'developing_grade' => 'required|min:0|max:100|numeric',
            'satisfactory_desc' => 'required|regex:/^[\pL\s\-0-9_.,()\']+$/u',
            'satisfactory_grade' => 'required|min:0|max:100|numeric',
            'exemplary_desc' => 'required|regex:/^[\pL\s\-0-9_.,()\']+$/u',
            'exemplary_grade' => 'required|min:0|max:100|numeric'
        ]);

        $student_outcome = StudentOutcome::findOrFail($id);
        $student_outcome->so_code = request('so_code');
        $student_outcome->description = request('description');
        $student_outcome->program_id = request('program');

        $student_outcome->update();

        $student_outcome->performanceCriterias[0]->description = request('performance_criteria');

        $student_outcome->performanceCriterias[0]->update();

        foreach ($student_outcome->performanceCriterias[0]->performanceCriteriaIndicators as $pci) {
            if ($pci->performance_indicator_id == 1) {
                $pci->description = request('unsatisfactory_desc');
                $pci->score_percentage = request('unsatisfactory_grade');
            } else if ($pci->performance_indicator_id == 2) {
                $pci->description = request('developing_desc');
                $pci->score_percentage = request('developing_grade');
            } else if ($pci->performance_indicator_id == 3) {
                $pci->description = request('satisfactory_desc');
                $pci->score_percentage = request('satisfactory_grade');
            } else if ($pci->performance_indicator_id == 4) {
                $pci->description = request('exemplary_desc');
                $pci->score_percentage = request('exemplary_grade');
            }

            $pci->update();
        }

        Session::flash('message', 'Student Outcome successfully updated from database');

        return $student_outcome;
    }
}
