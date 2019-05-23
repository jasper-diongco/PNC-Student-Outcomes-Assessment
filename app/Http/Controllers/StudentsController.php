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

class StudentsController extends Controller
{
    public function index() {

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
                return StudentResource::collection(Student::paginate(10)); 
            }
            
        }
        
        return view('students.index');
    }

    public function create() {

        $colleges = College::all();
        $programs = Program::all();
        $curriculums = Curriculum::all();

        return view('students.create', compact('colleges', 'programs', 'curriculums'));
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
            'password' => 'required|min:8|max:20'
        ]);
    }

    public function store() {
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

            Session::flash('message', 'Student Successfully added to database'); 
            //return redirect('/students/'. $student->id);

            return $student;

        } catch (\PDOException $e) {
            DB::rollBack();
            return abort(500, 'Internal Server Error');
        }

    }

    public function show(Student $student) {

        return view('students.show', compact('student'));
    }
}
