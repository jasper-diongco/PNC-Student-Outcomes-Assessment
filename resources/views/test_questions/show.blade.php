@extends('layouts.sb_admin')

@section('title', 'Add new Test Question')

@section('content')


<a href="{{ url('/test_questions?student_outcome_id='. request('student_outcome_id') . '&course_id=' . request('course_id') . '&program_id=' . request('program_id')) }}" class="text-success"><i class="fa fa-arrow-left"></i> Back</a>

<div class="d-flex justify-content-between mb-2 mt-3">
  <div>
    <h1 class="h3 mb-1 text-gray-800">Test Question #{{ $test_question->id }} <i class="fa fa-question-circle text-primary"></i></h1>
  </div>
</div>

<div id="app">
    <div class="d-flex mb-3">
        <div class="mr-3">
            <i class="fa fa-user text-dark"></i>
            <b>Created By:</b> <a href="#">{{ $test_question->user->first_name . ' ' . $test_question->user->last_name }}</a>
        </div>
        <div class="mr-3">
            <i class="fa fa-layer-group text-dark"></i>
            <b>Level of Difficulty:</b> {{ $test_question->difficultyLevel->description }} <i class="fa fa-circle {{ $test_question->difficulty_level_id == 1 ? 'text-success' : ''   }} {{ $test_question->difficulty_level_id == 2 ? 'text-warning' : ''   }}{{ $test_question->difficulty_level_id == 3 ? 'text-danger' : ''   }}"></i>
        </div>
        <div class="mr-3">
            <i class="fa fa-calendar-plus text-dark"></i>
            <b>Date Created:</b> {{ $test_question->created_at->format('M d, Y') .', ' . $test_question->created_at->diffForHumans() }}
        </div>
        <div class="mr-3">
            <i class="fa fa-calendar-day text-dark"></i>
            <b>Last Updated:</b> {{ $test_question->updated_at->format('M d, Y') .', ' . $test_question->created_at->diffForHumans() }}
        </div>
    </div>

    <div class="d-flex mb-3">
        <div class="d-flex mr-3"><b class="mr-2">Student Outcome: </b><h5 class="text-secondary"><b>{{ $test_question->studentOutcome->so_code }}</b></h5></div>
        <div class="d-flex"><b class="mr-2">Course: </b><h5 class="text-secondary"><b>{{ $test_question->course->course_code . ' - ' . $test_question->course->description }}</b></h5></div>
    </div>
    
    
    <div class="card question">
        <div class="card-header text-white bg-success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="py-0 my-0">{{ $test_question->title }}</h4>
                </div>
                <div>
                    <a href="#" class="btn btn-sm btn-dark"><i class="fa fa-edit"></i> Edit</a>
                </div>
            </div>
            
        </div>
        <div class="card-body">
            <div>{!! $test_question->body !!}</div>
            <hr>
            <div class="choices">
                <div class="row">
                    @foreach ($test_question->choices as $index => $choice)
                    <div class="col-6">
                        <div class="choice {{ $choice->is_correct ? 'correct-choice' : '' }}">
                            <div class="d-flex align-items-center">
                                <div class="mr-2">
                                    <div class="choice-num {{ $choice->is_correct ? 'correct' : '' }}">
                                        {{ $index + 1 }}
                                    </div>
                                </div>
                                <div>
                                    {!! $choice->body !!}  
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    {{-- <div class="col-6">
                        <div class="choice correct-choice">
                            <div class="d-flex align-items-center">
                                <div class="mr-2">
                                    <div class="choice-num correct">
                                        2
                                    </div>
                                </div>
                                <div>
                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nemo, aperiam.   
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="choice">
                            <div class="d-flex align-items-center">
                                <div class="mr-2">
                                    <div class="choice-num">
                                        3
                                    </div>
                                </div>
                                <div>
                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nemo, aperiam.   
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="choice">
                            <div class="d-flex align-items-center">
                                <div class="mr-2">
                                    <div class="choice-num">
                                        4
                                    </div>
                                </div>
                                <div>
                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nemo, aperiam.   
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection