<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\College;
use App\Faculty;
use App\Program;
use App\Course;
use App\Curriculum;
use App\Student;
use App\Assessment;
use App\Exam;
use App\TestQuestion;
use App\CustomRecordedAssessmentRecord;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Gate;

class CollegesController extends Controller
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
        if(!Gate::allows('isSAdmin')) {
            return abort('401', 'Unauthorized');
        }

        $colleges = College::with('faculty')->get();

        foreach ($colleges as $college) {
            $college->faculty->load('user');
        }

        if(request()->ajax() && request('json') == true) {
            return $colleges;
        }
        
        return view('colleges.index')->with('colleges', $colleges);
    }

    public function dashboard($id) {
        if(Auth::user()->getFaculty()->college_id != $id) {
            return abort('401', 'Unauthorized');
        }

        //check if password is already changed
        if(Hash::check('DefaultPass123', Auth::user()->password)) {
            $password_changed = false;
        } else {
            $password_changed = true;
        }

        $college = College::findOrFail($id);

        $program_count = Program::where('college_id', $college->id)->count();

        $courses_count = Course::where('college_id', $college->id)->count();

        $curriculum_count = Curriculum::join('programs', 'programs.id', '=', 'curricula.program_id')
            ->join('colleges', 'colleges.id', '=', 'programs.college_id')
            ->where('college_id', $college->id)
            ->count();

        $student_count = Student::select('students.*')
            ->join('programs', 'programs.id', '=', 'students.program_id')
            ->join('colleges', 'colleges.id', '=', 'programs.college_id')
            ->where('colleges.id', Session::get('college_id'))
            ->count();

        $exams_count = Exam::select('exams.*')
                    ->join('student_outcomes', 'student_outcomes.id', '=', 'exams.student_outcome_id')
                    ->join('programs', 'programs.id', '=', 'student_outcomes.program_id')
                    ->where('programs.college_id', $college->id)
                    ->count();
        $test_question_count = TestQuestion::select('test_questions.*')
                    ->join('student_outcomes', 'student_outcomes.id', '=', 'test_questions.student_outcome_id')
                    ->join('programs', 'programs.id', '=', 'student_outcomes.program_id')
                    ->where('programs.college_id', $college->id)
                    ->count();

        $faculties_count = Faculty::where('college_id', $college->id)->count();

        $assessment_count = Assessment::select('assessments.*')
                            ->join('students', 'students.id', '=', 'assessments.student_id')
                            ->join('programs', 'programs.id', '=', 'students.program_id')
                            ->where('programs.college_id', $college->id)
                            ->count();

        // return view('colleges.dashboard')
        //     ->with('college', $college)
        //     ->with('program_count', $program_count)
        //     ->with('courses_count', $courses_count)
        //     ->with('curriculum_count', $curriculum_count)
        //     ->with('password_changed', $password_changed)
        //     ->with('student_count', $student_count);
        $custom_recorded_assessments = CustomRecordedAssessmentRecord::select('custom_recorded_assessment_records.*')
                ->join('custom_recorded_assessments', 'custom_recorded_assessments.id', '=', 'custom_recorded_assessment_records.custom_recorded_assessment_id')
                ->join('student_outcomes', 'student_outcomes.id', '=', 'custom_recorded_assessments.student_outcome_id')
                ->join('programs', 'programs.id', '=', 'student_outcomes.program_id')
                ->where('programs.college_id', Session::get('college_id'))
                ->get();

        $assessments = Assessment::select('assessments.*')
                    ->join('student_outcomes', 'student_outcomes.id', '=', 'assessments.student_outcome_id')
                    ->join('programs', 'programs.id', '=', 'student_outcomes.program_id')
                    ->where('programs.college_id', Session::get('college_id'))
                    ->get();
        $passing_count = 0;
        $failing_count = 0;

        $total_assessment_count = $assessments->count() + $custom_recorded_assessments->count();

        foreach ($assessments as $assessment) {
            if($assessment->checkIfPassed()) {
                $passing_count++;
            }
        }

        foreach ($custom_recorded_assessments as $custom_recorded_assessment) {
            if($custom_recorded_assessment->checkIfPassed()) {
                $passing_count++;
            }
        }

        $failing_count = $total_assessment_count - $passing_count;

        $programs = Program::where('college_id', Session::get('college_id'))->get();

        $programs = $this->getProgramsPassingPercentage($programs);

        return view('colleges.dashboard', compact('college', 'program_count', 'courses_count', 'curriculum_count', 'password_changed', 'student_count', 'faculties_count', 'assessment_count', 'exams_count', 'test_question_count', 'assessments', 'passing_count', 'total_assessment_count', 'failing_count', 'programs'));
    }

    private function getProgramsPassingPercentage($programs) {
        for($i = 0; $i < count($programs); $i++) {
            $custom_recorded_assessments = CustomRecordedAssessmentRecord::select('custom_recorded_assessment_records.*')
                ->join('custom_recorded_assessments', 'custom_recorded_assessments.id', '=', 'custom_recorded_assessment_records.custom_recorded_assessment_id')
                ->join('student_outcomes', 'student_outcomes.id', '=', 'custom_recorded_assessments.student_outcome_id')
                ->join('programs', 'programs.id', '=', 'student_outcomes.program_id')
                // ->where('programs.college_id', Session::get('college_id'))
                ->where('programs.id', '=', $programs[$i]->id)
                ->get();

            $assessments = Assessment::select('assessments.*')
                        ->join('student_outcomes', 'student_outcomes.id', '=', 'assessments.student_outcome_id')
                        ->join('programs', 'programs.id', '=', 'student_outcomes.program_id')
                        // ->where('programs.college_id', Session::get('college_id'))
                        ->where('programs.id', '=', $programs[$i]->id)
                        ->get();
            $passing_count = 0;
            $failing_count = 0;

            $total_assessment_count = $assessments->count() + $custom_recorded_assessments->count();

            foreach ($assessments as $assessment) {
                if($assessment->checkIfPassed()) {
                    $passing_count++;
                }
            }

            foreach ($custom_recorded_assessments as $custom_recorded_assessment) {
                if($custom_recorded_assessment->checkIfPassed()) {
                    $passing_count++;
                }
            }

            $failing_count = $total_assessment_count - $passing_count;

            $programs[$i]->passing_count = $passing_count;
            $programs[$i]->failing_count = $failing_count;
            $programs[$i]->total_assessment_count = $total_assessment_count;
        }

        return $programs;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!Gate::allows('isSAdmin')) {
            return abort('401', 'Unauthorized');
        }

        return view('colleges.edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!Gate::allows('isSAdmin')) {
            return abort('401', 'Unauthorized');
        }

        $request->validate([
            'college_code' => 'required|max:10|unique:colleges',
            'name'=> 'required|max:255',
            'faculty_id' => 'required|unique:colleges,faculty_id'
        ]);

        
        try {
            DB::beginTransaction();
            
            $college = College::create([
                'college_code' => strtoupper(request('college_code')),
                'name' => strtoupper(request('name')),
                'faculty_id' => request('faculty_id')
            ]);

            //update the user_type_id of the new dean to dean
            $faculty = Faculty::findOrFail(request('faculty_id'));
            $user = $faculty->user;

            $faculty->college_id = $college->id;
            $user->user_type_id = 'dean';

            $user->update(); 
            $faculty->update();   

            DB::commit();

            //Session::flash('message', 'College Successfully added to database'); 


            //return redirect('colleges/'. $college->id);
            return $college;

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
    public function show($id)
    {
        if(!Gate::allows('isSAdmin')) {
            return abort('401', 'Unauthorized');
        }

        $college = College::findOrFail($id);

        $college->faculty->load('user');

        if(request()->ajax() && request('json') == true) {
            return $college;
        }

        return view('colleges.show')->with('college', $college);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!Gate::allows('isSAdmin')) {
            return abort('401', 'Unauthorized');
        }

        $college = College::findOrFail($id);

        return view('colleges.edit')->with('college', $college);
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
        if(!Gate::allows('isSAdmin')) {
            return abort('401', 'Unauthorized');
        }
        
        $request->validate([
            'college_code' => 'required|max:10|unique:colleges,college_code,'. $id,
            'name'=> 'required|max:255',
            'faculty_id' => 'required|unique:colleges,faculty_id,' . $id
        ]);

        $college = College::findOrFail($id);

        //update the last user type of dean to prof
        $last_dean_id = $college->faculty_id;
        $last_dean = Faculty::find($last_dean_id);
        $last_dean_user = $last_dean->user;
        $last_dean_user->user_type_id = 'prof';
        $last_dean_user->update();

        //update college
        $college->college_code = strtoupper($request->input('college_code'));
        $college->name = strtoupper($request->input('name'));
        $college->faculty_id = $request->input('faculty_id');

        $college->update();

        //update the user_type_id of the new dean to dean
        $faculty = Faculty::findOrFail(request('faculty_id'));
        $user = $faculty->user;

        $faculty->college_id = $college->id;
        $user->user_type_id = 'dean';

        $user->update(); 
        $faculty->update();  



        //Session::flash('message', 'College Successfullyupdated from database'); 
        //return redirect('colleges/'. $college->id);
        $college->faculty->load('user');
        return $college;
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
