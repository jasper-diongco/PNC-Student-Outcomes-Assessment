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
use App\Rules\TextOnly;
use App\StudentOutcomeArchiveVersion;
use App\AssessmentType;
use App\College;

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
        $so_archive_versions = StudentOutcomeArchiveVersion::where('program_id', $program->id)->orderBy('revision_no', 'DESC')->get();

        return view('student_outcomes.index')
            ->with('program', $program)
            ->with('programs', $programs)
            ->with('so_archive_versions', $so_archive_versions);
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

        $colleges = College::all();

        

        return view('student_outcomes.list_programs')->with('programs', $programs)->with('colleges', $colleges);
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
            'so_code' => 'required|max:10|alpha_num',
            'description' => ['required', new TextOnly],
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
            'so_code' => strtoupper(request('so_code')),
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

        //reorder
        foreach (request('student_outcomes') as $so) {
            if(isset($so["id"])) {
                $so_update = StudentOutcome::find($so["id"]);
                $so_update->so_code = $so["so_code"];
                $so_update->save();
            }
            
        }

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

        $college = $student_outcome->program->college;

        $assessment_types = AssessmentType::all();

        // $student_outcomes = StudentOutcome::where('');

        return view('student_outcomes.show')->with('student_outcome', $student_outcome)
            ->with('programs', $programs)
            ->with('college', $college)
            ->with('assessment_types', $assessment_types);

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
            'so_code' => 'required|max:10|alpha_num',
            'description' => ['required', new TextOnly],
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

        $prev_so_letter = $student_outcome->so_code;

        $student_outcome->so_code = strtoupper(request('so_code'));
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

        //reorder
        foreach (request('student_outcomes') as $so) {
            if($so["id"] != $student_outcome->id && $so["so_code"] == $student_outcome->so_code) {
                $so_update = StudentOutcome::find($so["id"]);
                $so_update->so_code = $prev_so_letter;
                $so_update->save();
            }
            
        }

        Session::flash('message', 'Student Outcome successfully updated from database');

        return $student_outcome;
    }

    public function delete(StudentOutcome $student_outcome) {
        $student_outcome->is_active = false;
        $student_outcome->save();

        return $student_outcome;
    }

    public function activate(StudentOutcome $student_outcome) {
        $student_outcome->is_active = true;
        $student_outcome->save();

        return $student_outcome;
    }

    public function change_assessment_type(StudentOutcome $student_outcome) {
        $data = request()->validate([
            'assessment_type_id' => 'required',
            'assessment_items' => 'required|numeric|min:50|max:200',
        ]);

        $student_outcome->assessment_type_id = request('assessment_type_id');
        $student_outcome->assessment_items = request('assessment_items');
        $student_outcome->randomize_items = request('randomize_items');

        $student_outcome->save();

        return $student_outcome;
    }
}
