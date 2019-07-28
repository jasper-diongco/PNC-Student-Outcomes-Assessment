<?php

namespace App\Http\Controllers\s;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\StudentOutcome;
use Illuminate\Support\Facades\DB;
use App\AnswerSheet;
use App\AnswerSheetTestQuestion;
use App\AnswerSheetTestQuestionChoice;

class AssessmentsController extends Controller
{
    public function __construct() {
        $this->middleware("auth");
    }

    public function index() {

        $student_outcomes = auth()->user()->getStudent()->program->studentOutcomes;


        return view('s.assessments.index', compact('student_outcomes'));
    }

    public function show(StudentOutcome $student_outcome) {
        $student = auth()->user()->getStudent();
        

        $answer_sheet = AnswerSheet::where('student_id', $student->id)
                        ->where('student_outcome_id', $student_outcome->id)
                        ->first();

        if($answer_sheet) {
            $answer_sheet->load('answerSheetTestQuestions');
            $courses = $answer_sheet->exam->getCourses1($student_outcome->id, $student->curriculum_id);
            return view('s.assessments.show', compact('courses', 'answer_sheet'));

        } else {

            $exam = $student_outcome->getRandomExam($student->curriculum_id);
            $courses = $exam->getCourses1($student_outcome->id, $student->curriculum_id);
            
            $test_questions = $exam->getRandomTestQuestions();

            //create answer sheet
            DB::beginTransaction();
            try {

                $answer_sheet = new AnswerSheet();
                $answer_sheet->exam_id = $exam->id;
                $answer_sheet->student_id = $student->id;
                $answer_sheet->student_outcome_id = $student_outcome->id;
                $answer_sheet->curriculum_id = $student->curriculum_id;
                $answer_sheet->time_limit = $exam->time_limit;
                $answer_sheet->description = $exam->description;
                $answer_sheet->passing_grade = $exam->passing_grade;

                $answer_sheet->save();

                foreach ($test_questions as $test_question) {
                    $test_question->random_choices = $test_question->choicesRandom();
                    $test_question->html = $test_question->getHtml();

                    $answer_sheet_test_question = new AnswerSheetTestQuestion();
                    $answer_sheet_test_question->answer_sheet_id = $answer_sheet->id;
                    $answer_sheet_test_question->test_question_id = $test_question->id;
                    $answer_sheet_test_question->title = $test_question->title;
                    $answer_sheet_test_question->body = $test_question->body;
                    $answer_sheet_test_question->body_html = $test_question->html;
                    $answer_sheet_test_question->student_outcome_id = $answer_sheet->student_outcome_id;
                    $answer_sheet_test_question->course_id = $test_question->course_id;
                    $answer_sheet_test_question->difficulty_level_id = $test_question->difficulty_level_id;
                    $answer_sheet_test_question->performance_criteria_id = $test_question->performance_criteria_id;

                    $answer_sheet_test_question->save();

                    foreach ($test_question->random_choices as $choice) {
                        $choice->html = $choice->getHtml();

                        $answer_sheet_test_question_choice = new AnswerSheetTestQuestionChoice();
                        $answer_sheet_test_question_choice->answer_sheet_test_question_id = $answer_sheet_test_question->id;
                        $answer_sheet_test_question_choice->test_question_id = $test_question->id;
                        $answer_sheet_test_question_choice->body = $choice->body;
                        $answer_sheet_test_question_choice->body_html = $choice->html;
                        $answer_sheet_test_question_choice->is_correct = $choice->is_correct;

                        $answer_sheet_test_question_choice->save();
                    }
                }


                DB::commit();
                // all good

                return view('s.assessments.show', compact('courses', 'answer_sheet'));
            } catch (\Exception $e) {
                DB::rollback();
                // something went wrong
                return response()->json(['message' => 'An error occured'], 500);
            }

        }


        
    }
}
