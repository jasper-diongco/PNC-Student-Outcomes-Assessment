<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Program;
use App\StudentOutcome;
use App\PerformanceCriteria;
use App\PerformanceCriteriaIndicator;
use Illuminate\Support\Facades\Session;

class StudentOutcomesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
        if(request('college_id') == '') {
            return redirect('/student_outcomes/list_program?college_id='. Session::get('college_id'));
        }
        $programs = Program::where('college_id', request('college_id'))->get();

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


        return $request;
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
}
