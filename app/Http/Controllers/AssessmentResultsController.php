<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Program;
use App\Assessment;
use App\AnswerSheet;
use App\AnswerSheetTestQuestion;
use App\Exam;

class AssessmentResultsController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $college_id = session('college_id');

        if($college_id != "all") {
            if(auth()->user()->getFaculty()->college_id != request('college_id')) {
                return abort(404, 'Page not found.');
            }
        }


        if(request('college_id') == "all") {
            $programs = Program::all();
        } else {
            $programs = Program::where('college_id', request('college_id'))->get();
        }
        



        $assessments = Assessment::with('student')->with('studentOutcome')->latest()->get();

        //$assessments = $this->get_assessments();


        foreach ($assessments as $assessment) {
            $assessment->score = $assessment->computeScore();
            $assessment->is_passed = $assessment->checkIfPassed();
        }

        return view('assessment_results.index', compact('programs', 'assessments'));
    }

    public function get_assessments() {
        $q = request('q');
        $program_id = request('program_id');
        $student_outcome_id = request('student_outcome_id');
        $curriculum_id = request('curriculum_id');
        $filter_grade = request('filter_grade');

        
        if($q) {
            $assessments = Assessment::select('assessments.*')
                ->join('students', 'students.id', '=', 'assessments.student_id')
                ->join('users', 'users.id', '=', 'students.user_id')
                ->with('student')
                ->with('studentOutcome')
                ->orWhere('assessment_code', 'LIKE', '%' . $q .'%')
                ->orWhere('users.first_name', 'LIKE', '%' . $q .'%' )
                ->orWhere('users.last_name', 'LIKE', '%' . $q .'%' )
                ->orWhere('users.middle_name', 'LIKE', '%' . $q .'%' )
                ->orWhere('students.student_id', 'LIKE', '%' . $q .'%' )
                ->get();
        }
        else if($student_outcome_id && $curriculum_id) {
            $assessments = Assessment::select('assessments.*')
                    ->join('students', 'students.id', '=', 'assessments.student_id')
                    ->with('student')
                    ->with('studentOutcome')
                    ->where('students.curriculum_id', $curriculum_id)
                    ->where('assessments.student_outcome_id', $student_outcome_id)
                    ->latest()
                    ->get();


        } else if($student_outcome_id) {
            $assessments = Assessment::select('assessments.*')
                    ->join('students', 'students.id', '=', 'assessments.student_id')
                    ->with('student')
                    ->with('studentOutcome')
                    ->where('assessments.student_outcome_id', $student_outcome_id)
                    ->latest()
                    ->get();
        } else if ($program_id) {
            $assessments = Assessment::select('assessments.*')
                    ->join('students', 'students.id', '=', 'assessments.student_id')
                    ->with('student')
                    ->with('studentOutcome')
                    ->where('students.program_id', $program_id)
                    ->latest()
                    ->get();
        } else {
            $assessments = Assessment::with('student')->with('studentOutcome')->latest()->get();
        }

        


        foreach ($assessments as $assessment) {
            $assessment->score = $assessment->computeScore();
            $assessment->is_passed = $assessment->checkIfPassed();
        }

        if($filter_grade) {
            $temp_assessments = [];

            foreach ($assessments as $assessment) {
               if($filter_grade == 1) {
                    if($assessment->is_passed) {
                        $temp_assessments[] = $assessment;
                    }
               } else if ($filter_grade == 2) {
                    if(!$assessment->is_passed) {
                        $temp_assessments[] = $assessment;
                    }
               }
            }

            $assessments = $temp_assessments;
        }

        return $assessments;
    }

    private function sortTestQuestions($exam_test_questions, $answer_sheet_test_questions) {
        $sorted_test_questions = [];

        foreach ($exam_test_questions as $exam_test_question) {
            foreach ($answer_sheet_test_questions as $answer_sheet_test_question) {
                if($answer_sheet_test_question->test_question_id == $exam_test_question->id) {
                    $sorted_test_questions[] = $answer_sheet_test_question;
                    break;
                }
            }
        }

        return $sorted_test_questions;
    }

    public function show(Assessment $assessment) {

        $answer_sheet = AnswerSheet::where('student_id', $assessment->student_id)
                            ->where('exam_id', $assessment->exam_id)
                            ->where('student_outcome_id', $assessment->student_outcome_id)
                            ->where('assessment_id', $assessment->id)
                            ->first();
        //$answer_sheet->load('answerSheetTestQuestions');

        //$answer_sheet_test_questions = [];

        //$exam = Exam::findOrFail($answer_sheet->exam_id);

        // foreach ($exam->examTestQuestions as $exam_test_question) {
        //     $answer_sheet_test_question = AnswerSheetTestQuestion::where('test_question_id', $exam_test_question->test_question_id)
        //         ->with('answerSheetTestQuestionChoices')
        //         ->first();
        //     $answer_sheet_test_questions[] = $answer_sheet_test_question;
        // }

        //$answer_sheet->answer_sheet_test_questions = $answer_sheet_test_questions;
        //$answer_sheet->load('answerSheetTestQuestions');
        

        //$exam_test_questions = $exam->load('examTestQuestions');

        //$sorted_test_questions = $this->sortTestQuestions($exam->examTestQuestions, $answer_sheet->answerSheetTestQuestions);

        //$answer_sheet->answerSheetTestQuestions = $sorted_test_questions;

        $courses = $answer_sheet->exam->getCourses1($assessment->student_outcome_id, $assessment->student->curriculum_id);

        $answer_sheet_test_questions = AnswerSheetTestQuestion::where('answer_sheet_id', $answer_sheet->id)
                ->orderBy('pos_order', 'ASC')
                ->with('answerSheetTestQuestionChoices')
                ->with('testQuestion')
                ->get();

        return view('assessment_results.show', compact('assessment', 'answer_sheet', 'courses', 'answer_sheet_test_questions'));
    }

    public function print_assessment_result(Assessment $assessment) {
        $answer_sheet = AnswerSheet::where('student_id', $assessment->student_id)
                            ->where('exam_id', $assessment->exam_id)
                            ->where('student_outcome_id', $assessment->student_outcome_id)
                            ->where('assessment_id', $assessment->id)
                            ->first();

        return view('assessment_results.print_assessment_result', compact('assessment', 'answer_sheet', 'answer_sheet'));
    }


    public function print_answer_key(Assessment $assessment) {
        $answer_sheet = AnswerSheet::where('student_id', $assessment->student_id)
                            ->where('exam_id', $assessment->exam_id)
                            ->where('student_outcome_id', $assessment->student_outcome_id)
                            ->where('assessment_id', $assessment->id)
                            ->first();

        return view('assessment_results.print_answer_key', compact('assessment', 'answer_sheet'));
    }

    public function retake_assessment(Assessment $assessment) {
        $assessment->take_again = true;

        $assessment->save();

        return $assessment;
    }
}
