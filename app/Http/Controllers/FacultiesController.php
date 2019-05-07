<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\College;
use App\User;
use App\Faculty;
use App\Http\Resources\Faculty as FacultyResource;
use Illuminate\Support\Facades\Session;

class FacultiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
            if(request('q') != '') {
                return Faculty::join('users', 'faculties.user_id', '=', 'users.id')
                    ->join('colleges', 'faculties.college_id', '=', 'colleges.id')
                    ->join('user_types', 'users.user_type_id', '=', 'user_types.id')
                    // ->selectRaw('faculties.id as id,users.id as user_id, first_name, middle_name, last_name , colleges.college_code as college_code, email, user_types.description as user_type')
                    ->select('faculties.id as id','users.id as user_id', 'first_name', 'middle_name', 'last_name' , 'colleges.college_code as college_code', 'email', 'user_types.description as user_type')
                    ->where('first_name', 'LIKE', '%' . request('q') . '%')
                    ->orWhere('middle_name', 'LIKE', '%' . request('q') . '%')
                    ->orWhere('last_name', 'LIKE', '%' . request('q') . '%')
                    ->orWhere('email', 'LIKE', '%' . request('q') . '%')
                    ->orWhere(DB::raw("CONCAT(last_name, ' ', first_name, ', ', middle_name)"), 'LIKE', '%' . request('q') . '%')
                    ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', '%' . request('q') . '%')
                    ->get();

                    // ->select('faculties.id as id','users.id as user_id', 'first_name', 'middle_name', 'last_name' , 'colleges.college_code as college_code', 'email', 'user_types.description as user_type', DB::raw('CONCAT(first_name, " ", last_name) AS full_name'))
            } else {
                return FacultyResource::collection(Faculty::paginate(10));
            }
        }

        return view('faculties.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $colleges = College::all();
        return view('faculties.create')->with('colleges', $colleges);
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
            'first_name' => 'required|max:45|regex:/^[\pL\s\-]+$/u',
            'last_name' => 'required|max:45|regex:/^[\pL\s\-]+$/u',
            'middle_name' => 'nullable|max:45|regex:/^[\pL\s\-]+$/u',
            'sex' => 'required|string',
            'date_of_birth' => 'required|date',
            'contact_no' => 'nullable|regex:/^[0-9\s-]*$/',
            'address' => 'nullable|string|max:255',
            'college_id' => 'required',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|max:20'
        ]);

        try {
            DB::beginTransaction();
            

            $user = User::create([
                'first_name' => strtoupper(request('first_name')),
                'middle_name' => strtoupper(request('middle_name')),
                'last_name' => request('last_name'),
                'sex' => request('sex'),
                'date_of_birth' => request('date_of_birth'),
                'contact_no' => request('contact_no'),
                'address' => request('address'),
                'email' => request('email'),
                'password' => Hash::make(request('password')),
                'user_type_id' => 'prof'
            ]);

            $faculty = Faculty::create([
                'user_id' => $user->id,
                'college_id' => request('college_id'),
                'is_active' => true
            ]);

            DB::commit();

            Session::flash('message', 'Faculty Successfully added to database'); 
            return redirect('faculties/'. $faculty->id);

        } catch (\PDOException $e) {
            DB::rollBack();
            return abort(500, 'Internal Server Error');
        }

        


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $faculty = Faculty::findOrFail($id);
        if($request->ajax()) {
            return new FacultyResource($faculty);
        }
        return view('faculties.show')->with('faculty', $faculty);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $faculty = Faculty::findOrFail($id);
        $colleges = College::all();


        return view('faculties.edit')->with('faculty', $faculty)->with('colleges', $colleges);
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
        $faculty = Faculty::findOrFail($id);
        $user = $faculty->user;

        $request->validate([
            'first_name' => 'required|max:45|regex:/^[\pL\s\-]+$/u',
            'last_name' => 'required|max:45|regex:/^[\pL\s\-]+$/u',
            'middle_name' => 'nullable|max:45|regex:/^[\pL\s\-]+$/u',
            'sex' => 'required|string',
            'date_of_birth' => 'required|date',
            'contact_no' => 'nullable|regex:/^[0-9\s-]*$/',
            'address' => 'nullable|string|max:255',
            'college_id' => 'required',
            'email' => 'required|email|max:255|unique:users,email,' . $faculty->user_id,
            'password' => 'required|min:6|max:20'
        ]);

        try {
            DB::beginTransaction();
            

            // $user = User::create([
            //     'first_name' => strtoupper(request('first_name')),
            //     'middle_name' => strtoupper(request('middle_name')),
            //     'last_name' => request('last_name'),
            //     'sex' => request('sex'),
            //     'date_of_birth' => request('date_of_birth'),
            //     'contact_no' => request('contact_no'),
            //     'address' => request('address'),
            //     'email' => request('email'),
            //     'password' => Hash::make(request('password')),
            //     'user_type_id' => 'prof'
            // ]);

            $user->first_name = request('first_name');
            $user->middle_name = request('middle_name');
            $user->last_name = request('last_name');
            $user->sex = request('sex');
            $user->date_of_birth = request('date_of_birth');
            $user->contact_no = request('contact_no');
            $user->address = request('address');
            $user->email = request('email');
            $user->password = Hash::make(request('password'));

            $user->update();

            // $faculty = Faculty::create([
            //     'user_id' => $user->id,
            //     'college_id' => request('college_id'),
            //     'is_active' => true
            // ]);

            $faculty->college_id = request('college_id');
            $faculty->update();

            DB::commit();

            Session::flash('message', 'Faculty Successfully updated from database'); 
            return redirect('faculties/'. $faculty->id);

        } catch (\PDOException $e) {
            DB::rollBack();
            return abort(500, 'Internal Server Error');
        }
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
