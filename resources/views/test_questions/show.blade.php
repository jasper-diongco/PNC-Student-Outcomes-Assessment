@extends('layout.app', ['active' => 'test_questions'])

@section('title', 'Add new Test Question')

@section('content')


<a href="{{ url('/test_questions?student_outcome_id='. request('student_outcome_id') . '&course_id=' . request('course_id') . '&program_id=' . request('program_id')) }}" class="text-success"><i class="fa fa-arrow-left"></i> Back</a>

<div class="d-flex justify-content-between mb-2 mt-3">
  <div>
    <h1 class="page-header">Test Question <i class="fa fa-file-alt"></i></h1>
  </div>
</div>

<div id="app">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="py-0 my-0">{{ $test_question->title }}</h5>
        </div>
        <div>
            <a href="{{ url('/test_questions/' . $test_question->id . '/edit?student_outcome_id='. request('student_outcome_id') . '&course_id=' . request('course_id') . '&program_id=' . request('program_id')) }}" class="btn btn-sm btn-success"><i class="fa fa-edit"></i> Edit</a>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex mb-3">
                <div class="mr-3">
                    ID: {{ $test_question->id }}
                </div>
                <div class="mr-3">
                    <i class="fa fa-user text-dark"></i>
                    Created By:{{ $test_question->user->first_name . ' ' . $test_question->user->last_name }}
                </div>
                <div class="mr-3">
                    <i class="fa fa-layer-group text-dark"></i>
                    Level of Difficulty: {{ $test_question->difficultyLevel->description }} <i class="fa fa-circle {{ $test_question->difficulty_level_id == 1 ? 'text-success' : ''   }} {{ $test_question->difficulty_level_id == 2 ? 'text-warning' : ''   }}{{ $test_question->difficulty_level_id == 3 ? 'text-danger' : ''   }}"></i>
                </div>
                <div class="mr-3">
                    <i class="fa fa-calendar-plus text-dark"></i>
                    Date Created: {{ $test_question->created_at->format('M d, Y') .', ' . $test_question->created_at->diffForHumans() }}
                </div>
            </div>

            <div class="d-flex mb-3">
                <div class="d-flex mr-3"><b class="mr-2">Student Outcome: </b><h5 class="text-success">{{ $test_question->studentOutcome->so_code }}</h5></div>
                <div class="d-flex"><b class="mr-2">Course: </b><h5 class="text-success">{{ $test_question->course->course_code . ' - ' . $test_question->course->description }}</h5></div>
            </div>
        </div>
    </div>
    
    <div class="card question">
        <div class="card-body">
            <div class="test-question-body">{!! $test_question->getHtml() !!}</div>
            <hr>
            <div class="choices">
                <div class="row">
                    @foreach ($test_question->choices as $index => $choice)
                    <div class="col-6 mb-3">
                        <div class="choice {{ $choice->is_correct ? 'correct-choice' : '' }}" style="height: 100%;">
                            <div class="d-flex">
                                <div class="mr-2">
                                    <div class="choice-num {{ $choice->is_correct ? 'correct' : '' }}">
                                        {{ $index + 1 }}
                                    </div>
                                </div>
                                <div>
                                    {!! $choice->getHtml() !!}  
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script>
        var vm = new Vue({
            el: '#app',
            data: {
                
            },
            methods: {
            },
            created() {
                MathLive.renderMathInDocument();
            }
        });
    </script>
@endpush