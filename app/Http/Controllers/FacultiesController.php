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
use Illuminate\Support\Facades\Auth;
use Gate;

class FacultiesController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //authenticate
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        }

        if($request->ajax()) {
            if(request('q') != '') {

                if(Gate::check('isSAdmin')) {
                    // $searched_faculties = Faculty::join('users', 'users.id', '=', 'faculties.user_id')
                    // ->where('users.is_active', true)
                    // ->where(function($q) {
                    //     $q->where('first_name', 'LIKE', '%' . request('q') . '%')
                    //     ->orWhere('middle_name', 'LIKE', '%' . request('q') . '%')
                    //     ->orWhere('last_name', 'LIKE', '%' . request('q') . '%')
                    //     ->orWhere('email', 'LIKE', '%' . request('q') . '%')
                    //     ->orWhere(DB::raw("CONCAT(last_name, ' ', first_name, ', ', middle_name)"), 'LIKE', '%' . request('q') . '%')
                    //     ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', '%' . request('q') . '%');
                    // })   
                    // ->select('faculties.*')
                    // ->get();

                    $searched_faculties = Faculty::join('users', 'users.id', '=', 'faculties.user_id')
                    ->where('users.is_active', true)
                    ->where(function($q) {
                        $q->where('first_name', 'LIKE', '%' . request('q') . '%')
                        ->orWhere('middle_name', 'LIKE', '%' . request('q') . '%')
                        ->orWhere('last_name', 'LIKE', '%' . request('q') . '%')
                        ->orWhere('email', 'LIKE', '%' . request('q') . '%')
                        ->orWhere(DB::raw("CONCAT(last_name, ' ', first_name, ', ', middle_name)"), 'LIKE', '%' . request('q') . '%')
                        ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', '%' . request('q') . '%');
                    })   
                    ->select('faculties.*')
                    ->get();



                } else {
                    $searched_faculties = Faculty::join('users', 'users.id', '=', 'faculties.user_id')
                    ->where('college_id', '=', Session::get('college_id'))
                    ->where('users.is_active', true)
                    ->where(function($q) {
                        $q->where('first_name', 'LIKE', '%' . request('q') . '%')
                        ->orWhere('middle_name', 'LIKE', '%' . request('q') . '%')
                        ->orWhere('last_name', 'LIKE', '%' . request('q') . '%')
                        ->orWhere('email', 'LIKE', '%' . request('q') . '%')
                        ->orWhere(DB::raw("CONCAT(last_name, ' ', first_name, ', ', middle_name)"), 'LIKE', '%' . request('q') . '%')
                        ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', '%' . request('q') . '%');
                    })
                    
                    ->select('faculties.*')
                    ->get();
                }

                //$searched_faculties = Faculty::all();
                
                return FacultyResource::collection($searched_faculties);

            } else if(request('json') == true && request('filter_by_college') != '') {
                return FacultyResource::collection(
                    Faculty::select('faculties.*')
                        ->join('users','users.id', '=', 'faculties.user_id')
                        ->where('users.is_active', true)
                        ->where('college_id', request('filter_by_college'))
                        ->latest()
                        ->paginate(10)
                );

            } else {
                if(Gate::check('isSAdmin')) {
                    return FacultyResource::collection(
                        Faculty::select('faculties.*')
                        ->join('users','users.id', '=', 'faculties.user_id')
                        ->where('users.is_active', true)
                        ->latest()
                        ->paginate(10)
                    );
                } else {
                    return FacultyResource::collection(
                        Faculty::select('faculties.*')
                        ->join('users','users.id', '=', 'faculties.user_id')
                        ->where('college_id', Session::get('college_id'))   
                        ->where('users.is_active', true)
                        ->latest()
                        ->paginate(10)
                    );
                }
                
            }
        }


        //deactivated users count
        if(Gate::check('isSAdmin')) {
            $deactivated_faculties_count = Faculty::join('users','users.id', '=', 'faculties.user_id')
            ->where('users.is_active', false)
            ->count(); 
        } else {
           $deactivated_faculties_count = Faculty::join('users','users.id', '=', 'faculties.user_id')
            ->where('college_id', Session::get('college_id'))   
            ->where('users.is_active', false)
            ->count(); 
        }
        
        $colleges = College::all();

        return view('faculties.index', compact('deactivated_faculties_count', 'colleges'));
    }

    public function searchFaculties() {
        $searched_faculties = Faculty::join('users', 'users.id', '=', 'faculties.user_id')
            ->where('users.is_active', true)
            ->where(function($q) {
                $q->where('first_name', 'LIKE', '%' . request('q') . '%')
                ->orWhere('middle_name', 'LIKE', '%' . request('q') . '%')
                ->orWhere('last_name', 'LIKE', '%' . request('q') . '%')
                ->orWhere('email', 'LIKE', '%' . request('q') . '%')
                ->orWhere(DB::raw("CONCAT(last_name, ' ', first_name, ', ', middle_name)"), 'LIKE', '%' . request('q') . '%')
                ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', '%' . request('q') . '%');
            })   
            ->select('faculties.*')
            ->with('user')
            ->with('college')
            ->get();

        return $searched_faculties;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //authenticate
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin')) {
            return abort('401', 'Unauthorized');
        }

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
        //authenticate
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin')) {
            return abort('401', 'Unauthorized');
        }

        $request->validate([
            'first_name' => 'required|max:45|regex:/^[\pL\s\-]+$/u',
            'last_name' => 'required|max:45|regex:/^[\pL\s\-]+$/u',
            'middle_name' => 'nullable|max:45|regex:/^[\pL\s\-]+$/u',
            'sex' => 'required|string',
            'date_of_birth' => 'required|date',
            'contact_no' => 'nullable|regex:/^[0-9\s-]*$/',
            'address' => 'nullable|string|max:255',
            'college' => 'required',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:8|max:20'
        ]);

        try {
            DB::beginTransaction();
            

            $user = User::create([
                'first_name' => strtoupper(request('first_name')),
                'middle_name' => strtoupper(request('middle_name')),
                'last_name' => strtoupper(request('last_name')),
                'sex' => request('sex'),
                'date_of_birth' => request('date_of_birth'),
                'email' => request('email'),
                'password' => Hash::make(request('password')),
                'user_type_id' => 'prof'
            ]);

            $faculty = Faculty::create([
                'user_id' => $user->id,
                'college_id' => request('college'),
                'is_active' => true
            ]);

            DB::commit();

            //Session::flash('message', 'Faculty Successfully added to database'); 
            //return redirect('faculties/'. $faculty->id);
            return $faculty;

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
        //authenticate
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        }

        $faculty = Faculty::findOrFail($id);

        if($request->ajax()) {
            return new FacultyResource($faculty);
        }

        $colleges = College::all();

        return view('faculties.show')
            ->with('colleges', $colleges)
            ->with('faculty', $faculty);
    }

    public function getFaculty(Faculty $faculty)
    {
        //authenticate
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        }

        $faculty->load('user');

        return $faculty;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //authenticate
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        }

        $name_readonly = false;

        if(Gate::check('isProf')) {
            if(Auth::user()->getFaculty()->id != $id) {
                return abort('401', 'Unauthorized');
            }
            $name_readonly = true;
        }

        $faculty = Faculty::findOrFail($id);

        $colleges = College::all();




        return view('faculties.edit')
            ->with('faculty', $faculty)
            ->with('colleges', $colleges)
            ->with('name_readonly', $name_readonly);
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
        //authenticate
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        }

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
            'college' => 'required'
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

            $user->first_name = strtoupper(request('first_name'));
            $user->middle_name = strtoupper(request('middle_name'));
            $user->last_name = strtoupper(request('last_name'));
            $user->sex = request('sex');
            $user->date_of_birth = request('date_of_birth');
            // $user->email = request('email');
            // $user->password = Hash::make(request('password'));

            $user->update();

            // $faculty = Faculty::create([
            //     'user_id' => $user->id,
            //     'college_id' => request('college_id'),
            //     'is_active' => true
            // ]);

            $faculty->college_id = request('college');
            $faculty->update();

            DB::commit();

            

            if(request('faculty_type') == 'prof') {
                Session::flash('message', 'Account Successfully updated from database');
                return redirect('profile/faculty/'. $faculty->id);
            }

            //Session::flash('message', 'Faculty Successfully updated from database');

            return $faculty;

        } catch (\PDOException $e) {
            DB::rollBack();
            return abort(500, 'Internal Server Error');
        }
    }


    public function updateAccount(Request $request, $id) {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);


        $faculty = Faculty::findOrFail($id);

        $user = $faculty->user;

        $user->email = request('email');
        $user->password = Hash::make(request('password'));

        $user->update();

        return $user;

    }

    public function deactivated() {

        if(Gate::check('isSAdmin')) {
            $faculties = Faculty::select('faculties.*')
                    ->join('users','users.id', '=', 'faculties.user_id')
                    ->where('users.is_active', false)
                    ->paginate(10);
        } else {
            $faculties = Faculty::select('faculties.*')
                    ->join('users','users.id', '=', 'faculties.user_id')
                    ->where('users.is_active', false)
                    ->where('college_id', Session::get('college_id'))
                    ->paginate(10);
        }
        

        return view('faculties.deactivated', compact('faculties'));
    }
}
