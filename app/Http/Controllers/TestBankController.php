<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gate;
use App\College;
use App\Program;

class TestBankController extends Controller
{

    public function __construct() {
        $this->middleware('auth');
    }

    public function listProgram() {
        //authenticate
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        }


        $colleges = College::all();
        $programs = Program::paginate(20);

        

        return view('test_bank.list_programs', compact('colleges', 'programs'));
    }

    public function listStudentOutcome(Program $program) {

        //authenticate
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        }
        
        return view('test_bank.list_student_outcomes', compact('program'));
    }
}
