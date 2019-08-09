<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\College;
use App\Program;
use App\Curriculum;
use App\User;
use App\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\StudentResource;
use Gate;

class StudentsController extends Controller
{

    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {

        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        }

        if(request()->ajax() && request('json') == true) {
            if (request('q') != '') {
                $searched_students = Student::select('students.*')
                    ->join('users', 'users.id', '=', 'students.user_id')
                    ->where('first_name', 'LIKE', '%' . request('q') . '%')
                    ->orWhere('middle_name', 'LIKE', '%' . request('q') . '%')
                    ->orWhere('last_name', 'LIKE', '%' . request('q') . '%')
                    ->orWhere('email', 'LIKE', '%' . request('q') . '%')
                    ->orWhere(DB::raw("CONCAT(last_name, ' ', first_name, ', ', middle_name)"), 'LIKE', '%' . request('q') . '%')
                    ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', '%' . request('q') . '%')
                    ->get();
                return StudentResource::collection($searched_students); 
            } else {
                if(Gate::check('isSAdmin')) {
                    return StudentResource::collection(Student::paginate(10)); 
                } else {

                    $retrieved_students = Student::select('students.*')
                        ->join('programs', 'programs.id', '=', 'students.program_id')
                        ->join('colleges', 'colleges.id', '=', 'programs.college_id')
                        ->where('colleges.id', Session::get('college_id'))
                        ->latest()
                        ->paginate(10);

                    if(request('filter_by_college') != '') {
                        $retrieved_students = Student::select('students.*')
                        ->join('programs', 'programs.id', '=', 'students.program_id')
                        ->join('colleges', 'colleges.id', '=', 'programs.college_id')
                        ->where('colleges.id', request('filter_by_college'))
                        ->latest()
                        ->paginate(10);
                    }

                    if(request('filter_by_program') != '') {
                        $retrieved_students = Student::select('students.*')
                        ->join('programs', 'programs.id', '=', 'students.program_id')
                        ->join('colleges', 'colleges.id', '=', 'programs.college_id')
                        ->where('programs.id', request('filter_by_program'))
                        ->latest()
                        ->paginate(10);
                    }

                    return StudentResource::collection($retrieved_students);
                }
                
            }
            
        }

        $colleges = College::all();
        $programs = Program::all();
        $curriculums = Curriculum::latest()->get();
        
        return view('students.index', compact('colleges', 'programs', 'curriculums'));
    }

    public function create() {

        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        }


        $colleges = College::all();
        $programs = Program::all();
        $curriculums = Curriculum::all();

        return view('students.create', compact('colleges', 'programs', 'curriculums'));
    }

    public function edit(Student $student) {

        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        }

        if(!Gate::check('isSAdmin')) {
            if(Session::get('college_id') != $student->program->college_id) {
                return abort('401', 'Unauthorized');
            }
        }
        

        
        return view('students.edit', compact('student'));

    } 

    public function editAcademic(Student $student) {



        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        }

        if(!Gate::check('isSAdmin')) {
            if(Session::get('college_id') != $student->program->college_id) {
                return abort('401', 'Unauthorized');
            }
        }


        $colleges = College::all();
        $programs = Program::all();
        $curriculums = Curriculum::all();

        return view('students.edit_academic',compact('student', 'colleges', 'programs', 'curriculums'));
    }

    public function validateData($id='') {



        return request()->validate([
            'student_id' => 'required|digits:7|unique:students,student_id,' . $id,
            'last_name' => 'required|regex:/^[\pL\s]+$/u',
            'first_name' => 'required|regex:/^[\pL\s]+$/u',
            'middle_name' => 'required|regex:/^[\pL\s]+$/u',
            'sex' => 'required',
            'date_of_birth' => 'required|date',
            'college' => 'required',
            'program' => 'required',
            'curriculum' => 'required',
            'email' => 'required|email|max:255|unique:users,email,'. $id,
            'username' => 'required|min:6|max:25|unique:users,username,'. $id,
            'password' => 'required|min:8|max:20'
        ]);

    }



    public function update(Student $student) {

        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        }

        if(!Gate::check('isSAdmin')) {
            if(Session::get('college_id') != $student->program->college_id) {
                return abort('401', 'Unauthorized');
            }
        }
        
        $data = request()->validate([
            'student_id' => 'required|digits:7|unique:students,student_id,' . $student->id,
            'last_name' => 'required|regex:/^[\pL\s]+$/u',
            'first_name' => 'required|regex:/^[\pL\s]+$/u',
            'middle_name' => 'required|regex:/^[\pL\s]+$/u',
            'sex' => 'required',
            'date_of_birth' => 'required|date',
            'email' => 'required|email|max:255|unique:users,email,'. $student->user->id,
            'username' => 'required|min:6|max:25|unique:users,username,'. $student->user->id
        ]);

        try {
            DB::beginTransaction();
            

            $student->user->first_name = strtoupper($data['first_name']);
            $student->user->middle_name = strtoupper($data['middle_name']);
            $student->user->last_name = strtoupper($data['last_name']);
            $student->user->sex = $data['sex'];
            $student->user->date_of_birth = $data['date_of_birth'];
            $student->user->email = $data['email'];
            $student->user->username = $data['username'];

            $student->student_id = $data['student_id'];

            $student->push();

            DB::commit();

            //Session::flash('message', 'Student Information Successfully updated from database'); 
            //return redirect('/students/'. $student->id);

            return $student;

        } catch (\PDOException $e) {
            DB::rollBack();
            return abort(500, 'Internal Server Error');
        }
    }

    public function updateAcademic(Student $student) {

        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        }

        if(!Gate::check('isSAdmin')) {
            if(Session::get('college_id') != $student->program->college_id) {
                return abort('401', 'Unauthorized');
            }
        }


        $data = request()->validate([
            'college' => 'required',
            'program' => 'required',
            'curriculum' => 'required'
        ]);

        $student->program_id = $data['program'];
        $student->curriculum_id = $data['curriculum'];

        $student->update();

        Session::flash('message', 'Student Academic Information Successfully updated'); 

        return $student;

    }

    public function store() {

        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        }

        $data = $this->validateData();

        try {
            DB::beginTransaction();
            

            $user = User::create([
                'first_name' => strtoupper($data['first_name']),
                'middle_name' => strtoupper($data['middle_name']),
                'last_name' => strtoupper($data['last_name']),
                'sex' => $data['sex'],
                'date_of_birth' => $data['date_of_birth'],
                'email' => $data['email'],
                'username' => $data['username'],
                'password' => Hash::make($data['password']),
                'user_type_id' => 'stud'
            ]);

            $student = Student::create([
                'user_id' => $user->id,
                'student_id' => $data['student_id'],
                'program_id' => $data['program'],
                'college_id' => $data['college'],
                'curriculum_id' => $data['curriculum']
            ]);

            DB::commit();

            //Session::flash('message', 'Student Successfully added to database'); 
            //return redirect('/students/'. $student->id);

            return $student;

        } catch (\PDOException $e) {
            DB::rollBack();
            return abort(500, 'Internal Server Error');
        }

    }

    public function show(Student $student) {

        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        }

        $student->load('user');

        if(request()->ajax() && request('json') == true) {
            return $student;
        }

        return view('students.show', compact('student'));
    }


}
