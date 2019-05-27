<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gate;
use Illuminate\Support\Facades\Session;
use App\Program;
use App\Curriculum;
use App\StudentOutcome;

class TestQuestionsController extends Controller
{

    public function listProgram() {
        //authenticate
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        }

        if (request('college_id') == '') {
            return redirect('/test_questions/list_program?college_id='. Session::get('college_id'));
        } else if (request('college_id') == 'all') {
            if(Gate::check('isSAdmin')) {
                $programs = Program::orderBy('college_id')->paginate(10);
            } else {
                return redirect('/test_questions/list_program?college_id='. Session::get('college_id'));
            }
            
        } else {
            if(!Gate::check('isSAdmin') && Session::get('college_id') != request('college_id')) {
                return abort('401', 'Unauthorized');
            }

            $programs = Program::where('college_id', request('college_id'))->paginate(10);
        }

        

        return view('test_questions.list_programs')->with('programs', $programs);
    }

    public function listStudentOutcome(Program $program) {

        return view('test_questions.list_student_outcomes', compact('program'));
    }

    public function create() {
        return view('test_questions.create');
    }
}
