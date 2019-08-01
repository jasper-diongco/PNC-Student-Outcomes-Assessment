<?php

namespace App\Http\Controllers\s;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\StudentOutcome;
use Illuminate\Support\Facades\DB;
use App\AnswerSheet;
use App\AnswerSheetTestQuestion;
use App\AnswerSheetTestQuestionChoice;
use App\Assessment;
use App\AssessmentDetail;
use Carbon\Carbon;

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
                        $answer_sheet_test_question_choice->choice_id = $choice->id;

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
                $answer_sheet->load('answerSheetTestQuestions');
                return view('s.assessments.show', compact('courses', 'answer_sheet'));
            } catch (\Exception $e) {
                DB::rollback();
                // something went wrong
                return response()->json(['message' => 'An error occured'], 500);
            }

        }


        
    }

    public function store(AnswerSheet $answer_sheet) {


        DB::beginTransaction();

        try {

            $answer_sheet->is_submitted = true;
            $answer_sheet->save();

            $answer_sheet_test_questions = request('answer_sheet_test_questions');

            foreach ($answer_sheet_test_questions as $answer_sheet_test_question) {
                foreach ($answer_sheet_test_question['answer_sheet_test_question_choices'] as $choice) {
                    if($choice['is_selected']) {
                        $answer_sheet_test_question = AnswerSheetTestQuestion::find($choice['answer_sheet_test_question_id']);
                        foreach ($answer_sheet_test_question->answerSheetTestQuestionChoices as $c) {
                            $c->is_selected = false;
                            $c->save();
                        }

                        $answer_sheet_test_question_choice = AnswerSheetTestQuestionChoice::find($choice['id']);
                        $answer_sheet_test_question_choice->is_selected = true;
                        $answer_sheet_test_question_choice->save();
                    }
                }
            }

            $this->recordAssessment($answer_sheet, $answer_sheet_test_questions);

            DB::commit();
            // all good

            return $answer_sheet;
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return response()->json(['message' => 'Server Error'], 500);
        }
    }

    private function recordAssessment($answer_sheet, $answer_sheet_test_questions) {
        $now = Carbon::now();
        $start_time = $answer_sheet->created_at;

        $total_duration = $now->diffInSeconds($start_time);

        if($total_duration > $answer_sheet->time_limit * 60) {
            $total_duration = $answer_sheet->time_limit * 60;
        }

        $assessment = Assessment::where('student_id', $answer_sheet->student_id)->where('student_outcome_id', $answer_sheet->student_outcome_id)->first();

        if($assessment) {
            $assessment->update([
                'exam_id' => $answer_sheet->id,
                'student_id' => $answer_sheet->student_id,
                'student_outcome_id' => $answer_sheet->student_outcome_id,
                'time_consumed' => $total_duration
            ]);

            foreach ($answer_sheet_test_questions as $answer_sheet_test_question) {
                $choice_id = $this->getChoiceId($answer_sheet_test_question['answer_sheet_test_question_choices']);
                $is_correct = $this->checkIfCorrect($answer_sheet_test_question['answer_sheet_test_question_choices'], $choice_id);

                $assessment_detail = AssessmentDetail::where('assessment_id', $assessment->id)
                                        ->where('test_question_id', $answer_sheet_test_question['test_question_id'])
                                        ->first();

                $assessment_detail->update([
                    'choice_id' => $choice_id,
                    'is_correct' => $is_correct
                ]);
            }



        } else {
            $assessment = Assessment::create([
                'exam_id' => $answer_sheet->id,
                'student_id' => $answer_sheet->student_id,
                'student_outcome_id' => $answer_sheet->student_outcome_id,
                'time_consumed' => $total_duration
            ]);

            foreach ($answer_sheet_test_questions as $answer_sheet_test_question) {
                $choice_id = $this->getChoiceId($answer_sheet_test_question['answer_sheet_test_question_choices']);
                $is_correct = $this->checkIfCorrect($answer_sheet_test_question['answer_sheet_test_question_choices'], $choice_id);
                AssessmentDetail::create([
                    'assessment_id' => $assessment->id,
                    'test_question_id' => $answer_sheet_test_question['test_question_id'],
                    'choice_id' => $choice_id,
                    'is_correct' => $is_correct
                ]);
            }
        }

        


    }

    private function getChoiceId($choices) {
        foreach ($choices as $choice) {
            if($choice['is_selected']) {
                return $choice['choice_id'];
            }
        }

        return null;
    }

    private function checkIfCorrect($choices, $choice_id) {
        if($choice_id == null) {
            return false;
        }

        foreach ($choices as $choice) {
            if($choice['is_correct'] && $choice['choice_id'] == $choice_id) {
                return true;
            }
        }

        return false;
    }
}
