@extends('layout.app', ['active' => 'test_questions'])

@section('title', 'Exam Preview')

@section('content')

<div id="app" v-cloak>
    <a href="{{ url('/exams/'. $exam->id .'?program_id='. request('program_id') .'&student_outcome_id=' . request('student_outcome_id'). '&curriculum_id='. request('curriculum_id')) }}" class="text-success"><i class="fa fa-arrow-left"></i> Back</a>
    <div class="mt-5">
        <?php $counter = 1; ?>

        @foreach($courses as $course)
        <h4 class="text-dark"><i class="fa fa-book text-info"></i> {{ $course->course_code . ' - ' . $course->description }}</h4>
        <label class="mb-4"><i class="fa fa-question-circle text-success"></i> {{ $exam->countTestQuestionsByCourse($course->id) }} questions</label>
        @foreach($test_questions as $test_question)
        @if($course->id == $test_question->course_id)
            <div class="card mb-5">
                <div class="card-body">
                    <div class="test-question-body d-flex">
                        <div class="mr-2">
                            <div class="test-num">{{ $counter }}</div> 
                        </div>
                        <div class="mt-1">
                            {!! $test_question->getHtml() !!}
                        </div>
                    </div>
                    <hr>
                    <div class="choices">
                        <div class="row">
                            <?php $letter = 'A';  ?>
                            @foreach ($test_question->choices as $index => $choice)
                            <div class="col-6 mb-3">
                                <div class="choice {{ $choice->is_correct ? 'correct-choice' : '' }}" style="height: 100%;">
                                    <div class="d-flex">
                                        <div class="mr-2">
                                            <div class="choice-num {{ $choice->is_correct ? 'correct' : '' }}">
                                                {{ $letter }}
                                            </div>
                                        </div>
                                        <div>
                                            {!! $choice->getHtml() !!}  
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php $letter++;  ?>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <?php $counter++ ?>
        @endif
        @endforeach
        @endforeach
    </div>
</div>

@endsection

@push('scripts')
    <script>
        new Vue({
            el: '#app',
            data: {

            },
            created() {
                MathLive.renderMathInDocument();
            }
        });
    </script>
@endpush
