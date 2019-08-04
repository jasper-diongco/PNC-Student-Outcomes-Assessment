<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Program;
use App\Assessment;
use App\AnswerSheet;
use App\Exam;

class AssessmentResultsController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {


        $programs = Program::where('college_id', request('college_id'))->get();
        $assessments = Assessment::with('student')->with('studentOutcome')->get();


        foreach ($assessments as $assessment) {
            $assessment->score = $assessment->computeScore();
            $assessment->is_passed = $assessment->checkIfPassed();
        }

        return view('assessment_results.index', compact('programs', 'assessments'));
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
                            ->first();
        $answer_sheet->load('answerSheetTestQuestions');

        $exam = Exam::findOrFail($answer_sheet->exam_id);

        //$exam_test_questions = $exam->load('examTestQuestions');

        //$sorted_test_questions = $this->sortTestQuestions($exam->examTestQuestions, $answer_sheet->answerSheetTestQuestions);

        //$answer_sheet->answerSheetTestQuestions = $sorted_test_questions;

        $courses = $answer_sheet->exam->getCourses1($assessment->student_outcome_id, $assessment->student->curriculum_id);

        return view('assessment_results.show', compact('assessment', 'answer_sheet', 'courses'));
    }
}
