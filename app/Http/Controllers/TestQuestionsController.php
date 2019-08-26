<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gate;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Program;
use App\Curriculum;
use App\StudentOutcome;
use App\Course;
use App\TestQuestion;
use App\Choice;
use App\User;
use App\College;
use App\ItemAnalysisDetail;
use App\Rules\TextOnly;
use App\Http\Resources\TestQuestionResource;


class TestQuestionsController extends Controller
{

    public function __construct() {

        $this->middleware('auth');

    }

    public function index() {

        //authenticate
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        }

        $student_outcome = StudentOutcome::findOrFail(request('student_outcome_id'));
        $course = Course::findOrFail(request('course_id'));

        if(request()->ajax() && request('json') == true) {

            //search
            if(request('q') != '') {
                $searched_test_questions = TestQuestion::where('student_outcome_id', $student_outcome->id)
                ->where('course_id', $course->id)
                ->where('title', 'LIKE' ,'%' . request('q') . '%')
                ->where('is_active', true)
                ->with('choices')
                ->latest()
                ->get();

                return TestQuestionResource::collection($searched_test_questions);
            }

            if(request('user_id') != '' && request('difficulty_id') != '') {
                $test_questions = TestQuestion::where('student_outcome_id', $student_outcome->id)
                ->where('course_id', $course->id)
                ->where('user_id', request('user_id'))
                ->where('difficulty_level_id', request('difficulty_id'))
                ->where('is_active', true)
                ->latest()
                ->paginate(10);
            }
            else if(request('user_id') != '') {
                $test_questions = TestQuestion::where('student_outcome_id', $student_outcome->id)
                ->where('course_id', $course->id)
                ->where('user_id', request('user_id'))
                ->where('is_active', true)
                ->latest()
                ->paginate(10);
            } else if(request('difficulty_id') != '') {
                $test_questions = TestQuestion::where('student_outcome_id', $student_outcome->id)
                ->where('course_id', $course->id)
                ->where('difficulty_level_id', request('difficulty_id'))
                ->where('is_active', true)
                ->latest()
                ->paginate(10);
            } else {
                $test_questions = TestQuestion::where('student_outcome_id', $student_outcome->id)
                ->where('course_id', $course->id)
                ->where('is_active', true)
                ->latest()
                ->paginate(10);

                
            }

            

            return TestQuestionResource::collection($test_questions);
        }

        $easy_count = TestQuestion::countEasy(request('student_outcome_id'), request('course_id'));
        $average_count = TestQuestion::countAverage(request('student_outcome_id'), request('course_id'));
        $difficult_count = TestQuestion::countDifficult(request('student_outcome_id'), request('course_id'));

        $deactivated_test_questions = TestQuestion::where('student_outcome_id', $student_outcome->id)
                ->where('course_id', $course->id)
                ->where('is_active', false)
                ->latest()
                ->with('choices')
                ->get();

        return view('test_questions.index', compact('student_outcome', 'course', 'easy_count', 'average_count', 'difficult_count', 'deactivated_test_questions'));
    }

    public function get_test_question(TestQuestion $test_question) {
        $test_question->load('choices');

        return $test_question;
    }

    // public function set_choices_order() {
    //     $test_questions = TestQuestion::all();

    //     foreach ($test_questions as $test_question) {
    //         $choices = $test_question->choices;
    //         $counter = 1;

    //         foreach ($choices as $choice) {
    //             $choice->pos_order = $counter;
    //             $choice->save();

    //             $counter++;
    //         }
    //     }

    //     return "OK";
    // }

    public function search_deactivated() {

        //authenticate
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        }

        $student_outcome = StudentOutcome::findOrFail(request('student_outcome_id'));
        $course = Course::findOrFail(request('course_id'));


        $deactivated_test_questions = TestQuestion::where('student_outcome_id', $student_outcome->id)
                ->where('title', 'LIKE', '%' . request('query') . '%')
                ->where('course_id', $course->id)
                ->where('is_active', false)
                ->latest()
                ->with('choices')
                ->get();
    }

    public function show(TestQuestion $test_question) {

        //authenticate
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        }
        
        return view('test_questions.show', compact('test_question'));
    }

    public function preview(TestQuestion $test_question) {

        //authenticate
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        }

        return view('test_questions.preview', compact('test_question'));
    }

    public function edit(TestQuestion $test_question) {

        //authenticate
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        }

        $student_outcome = StudentOutcome::findOrFail(request('student_outcome_id'));
        $course = Course::findOrFail(request('course_id'));

        return view('test_questions.edit', compact('test_question', 'student_outcome', 'course'));
    }

    public function getCreators() {

        //authenticate
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        }

        $student_outcome = StudentOutcome::findOrFail(request('student_outcome_id'));
        $course = Course::findOrFail(request('course_id'));

        return User::select('users.*')
            ->join('test_questions', 'test_questions.user_id', '=', 'users.id')
            ->where('student_outcome_id', $student_outcome->id)
            ->where('course_id', $course->id)
            ->distinct('users.id')
            ->get();
    }

    public function listProgram() {

        //authenticate
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        }

        // if (request('college_id') == '') {
        //     return redirect('/test_questions/list_program?college_id='. Session::get('college_id'));
        // } else if (request('college_id') == 'all') {
        //     if(Gate::check('isSAdmin')) {
        //         $programs = Program::orderBy('college_id')->paginate(10);
        //     } else {
        //         return redirect('/test_questions/list_program?college_id='. Session::get('college_id'));
        //     }
            
        // } else {
        //     if(!Gate::check('isSAdmin') && Session::get('college_id') != request('college_id')) {
        //         return abort('401', 'Unauthorized');
        //     }

        //     $programs = Program::where('college_id', request('college_id'))->paginate(10);
        // }

        $colleges = College::all();
        $programs = Program::paginate(20);

        

        return view('test_questions.list_programs', compact('colleges', 'programs'));
    }

    public function listStudentOutcome(Program $program) {

        return view('test_questions.list_student_outcomes', compact('program'));
    }

    public function create() {

        //authenticate
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        }

        $student_outcome = StudentOutcome::findOrFail(request('student_outcome_id'));
        $course = Course::findOrFail(request('course_id'));

        return view('test_questions.create', compact('student_outcome', 'course'));
    }

    public function store() {

        //authenticate
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        }

       $data = $this->validateData();




        DB::beginTransaction();

        try {

            $so = StudentOutcome::findOrFail($data['student_outcome_id']);

            $test_question = TestQuestion::create([
                'tq_code' => $this->generate_test_question_code(),
                'title' => $data['title'],
                'body' => $data['question_body'],
                'student_outcome_id' => $data['student_outcome_id'],
                'course_id' => $data['course_id'],
                'difficulty_level_id' => $data['level_of_difficulty'],
                'user_id' => auth()->user()->id,
                'is_active' => true,
                'performance_criteria_id' => $so->performanceCriterias[0]->id,
                'ref_id' => $data['ref_id']
            ]);

            $test_question->parent_id = $test_question->id;
            $test_question->save();

            $counter = 1;

            foreach (request('choices') as $choice) {
                Choice::create([
                    'ch_code' => $this->generate_choice_code(),
                    'test_question_id' => $test_question->id,
                    'body' => $choice['editorData'],
                    'is_correct' => $choice['is_correct'],
                    'is_active' => true,
                    'user_id' => auth()->user()->id,
                    'pos_order' => $counter
                ]);

                $counter++;
            }

            

            

            DB::commit();
            // all good

            Session::flash('message', 'New Test question successfully added to database');

            return $test_question;

        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
        }

        

    }

    public function update(TestQuestion $test_question) {

        //authenticate
        if(!Gate::allows('isDean') && !Gate::allows('isSAdmin') && !Gate::allows('isProf')) {
            return abort('401', 'Unauthorized');
        }
        
        $data = $this->validateData();

        //$so = StudentOutcome::findOrFail($data['student_outcome_id']);

        DB::beginTransaction();

        try {

            $test_question->update([
                'title' => $data['title'],
                'body' => $data['question_body'],
                'student_outcome_id' => $data['student_outcome_id'],
                'course_id' => $data['course_id'],
                'difficulty_level_id' => $data['level_of_difficulty']
            ]);

            $counter = 1;

            foreach (request('choices') as $choice) {


                if($choice['id'] != null) {
                    $choice_retrieved = Choice::findOrFail($choice['id']);

                    $choice_retrieved->update([
                        'body' => $choice['editorData'],
                        'is_correct' => $choice['is_correct'],
                        'is_active' => true,
                        'is_correct' => $choice['is_correct'],
                        'pos_order' => $counter
                    ]);

                } else {    
                    Choice::create([
                        'test_question_id' => $test_question->id,
                        'ch_code' => $this->generate_choice_code(),
                        'body' => $choice['editorData'],
                        'is_correct' => $choice['is_correct'],
                        'is_active' => true,
                        'user_id' => auth()->user()->id,
                        'pos_order' => $counter
                    ]);
                }

                $counter++;
            }

            foreach (request('choices_deactivated') as $choice_deactivated) {

                if($choice_deactivated['id'] != null) {
                    $choice_retrieved_ = Choice::findOrFail($choice_deactivated['id']);

                    $choice_retrieved_->update([
                        'body' => $choice_deactivated['editorData'],
                        'is_correct' => false,
                        'is_active' => false
                    ]);

                }
            }

            if(request('is_revised') == true) {

                $item_analysis_detail_id = request('item_analysis_detail_id');
                
                $item_analysis_detail = ItemAnalysisDetail::findOrFail($item_analysis_detail_id);

                $item_analysis_detail->is_resolved = true;
                $item_analysis_detail->action_resolved = "Item is revised";
                $item_analysis_detail->save();

                DB::commit();

                return $item_analysis_detail;

            } else {
                Session::flash('message', 'Test question successfully updated from database');
            }

            DB::commit();
            // all good


            


            return $test_question;

        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
        }
    }

    public function validateData() {
         return request()->validate([
            'title' => ['required', "regex: /^[A-Za-z\s\-0-9_.,()\'?!]+$/", 'max:255'],
            'question_body' => ['required', 'max:1000'],
            'level_of_difficulty' => 'required',
            'course_id' => 'required',
            'student_outcome_id' => 'required',
            'ref_id' => 'required'
        ]);
    }

    public function archive(TestQuestion $test_question) {

        $test_question->is_active = false;
        $test_question->save();

        Session::flash('message', 'Test question successfully archived');

        return $test_question;
    }

    public function activate(TestQuestion $test_question) {
        
        $test_question->is_active = true;
        $test_question->save();

        Session::flash('message', 'Test question successfully activated');

        return $test_question;
    }

    private function generate_test_question_code() {
        $code = 'TQ0000001';

        $last_test_question = TestQuestion::orderBy('tq_code', 'DESC')->first();

        if($last_test_question) {
            $last_code = $last_test_question->tq_code;

            $num = substr($last_code, 2);  
            $num = intval($num);
            $num += 1;

            $new_code = "TQ" . sprintf("%'.07d\n", $num);

            $code = $new_code;
        }

        return $code;
    }

    private function generate_choice_code() {
        $code = 'CH000000001';

        $last_choice = Choice::orderBy('ch_code', 'DESC')->first();

        if($last_choice) {
            $last_code = $last_choice->ch_code;

            $num = substr($last_code, 2);
            $num = intval($num);
            $num += 1;

            $new_code = "CH" . sprintf("%'.09d\n", $num);

            $code = $new_code;
        }

        return $code;
    }


    // public function generate_codes() {
    //     $test_questions = TestQuestion::get();

    //     $counter = 1;

    //     foreach ($test_questions as $test_question) {
    //         $test_question->tq_code = "TQ" . sprintf("%'.07d\n", $counter);
    //         $test_question->save();
    //         $counter++;
    //     }

    //     return "success";
    // }

    // public function generate_codes_choices() {
    //     $choices = Choice::get();

    //     $counter = 1;

    //     foreach ($choices as $choice) {
    //         $choice->ch_code = "CH" . sprintf("%'.09d\n", $counter);
    //         $choice->save();
    //         $counter++;
    //     }

    //     return "success";
    // }
}
