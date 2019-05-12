<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Program;
use App\Curriculum;
use App\College;
use App\Http\Resources\CurriculumResource;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

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
        $curricula = Curriculum::join('programs', 'programs.id', '=', 'curricula.program_id')
            ->join('colleges', 'colleges.id', '=', 'programs.college_id')
            ->select('curricula.*')
            ->where('college_id', Session::get('college_id'))
            ->get();
        return view('curricula.index')
            ->with('programs', $programs)
            ->with('curricula', $curricula);
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
        $request->validate([
            'program_id' => 'required',
            'name' => 'required|max:255|regex:/^[\pL\s\-0-9]+$/u',
            'description' => 'required|max:255|regex:/^[\pL\s\-0-9]+$/u',
            'year' => 'required|digits:4',
            'year_level' => 'required'
        ]);

        $curriculum = Curriculum::create([
            'program_id' => request('program_id'),
            'name' => strtoupper(request('name')),
            'description' => request('description'),
            'year' => request('year'),
            'user_id' => Auth::user()->id,
            'year_level' => request('year_level')
        ]);

        Session::flash('message', 'Curriculum successfully added to database');

        return $curriculum;

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
        //
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
