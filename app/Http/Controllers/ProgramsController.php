<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Program;
use App\College;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Gate;

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
        


        return view('programs.index')
            ->with('programs', $programs)
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
}
