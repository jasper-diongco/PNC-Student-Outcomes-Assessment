<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gate;
use App\College;
use App\Program;
use App\CurriculumMap;

class TestBankController extends Controller
{

    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        //authenticate
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        }

        $programs = Program::all();
        
        return view('test_bank.index', compact('programs'));
    }

    public function get_student_outcomes(Program $program) {
        $student_outcomes = $program->studentOutcomes;

        return $student_outcomes;
    }

    public function get_curriculum_courses_mapped($student_outcome_id) {
        $courses = [];

        $curriculum_id = request('curriculum_id');
        // return $curriculum_id;

        $curriculum_maps = CurriculumMap::select('curriculum_maps.*')
                                    ->join('curriculum_courses', 'curriculum_courses.id', '=', 'curriculum_maps.curriculum_course_id')
                                    ->where('is_checked', true)
                                    ->where('student_outcome_id', $student_outcome_id)
                                    ->where('curriculum_courses.curriculum_id', $curriculum_id)
                                    ->get();
        // return $curriculum_maps;
        foreach ($curriculum_maps as $key => $curriculum_map) {
            $found = false;
            foreach ($courses as $course) {
                if($course->id == $curriculum_map->curriculumCourse->course->id) {
                    $found = true;
                    break;
                }
            }

            if(!$found) {
                $courses[] = $curriculum_map->curriculumCourse->course;
                $courses[$key]->test_question_count = $curriculum_map->testQuestionCount();
            }
            
        }

        return $courses;
    }

    public function get_curricula(Program $program) {

        $student_outcome_id = request('student_outcome_id');

        $curricula = $program->curricula;

        foreach ($curricula as $curriculum) {
            $curriculum->load('program');
            $curriculum->exam_count = $curriculum->countExam($student_outcome_id);
        }

        return $curricula;
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
