@extends('layout.app', ['active' => 'test_questions'])

@section('title', 'Exam Preview')

@section('content')

<div id="app" v-cloak>
    <a href="{{ url('/exams/'. $exam->id .'?program_id='. request('program_id') .'&student_outcome_id=' . request('student_outcome_id'). '&curriculum_id='. request('curriculum_id')) }}" class="text-success"><i class="fa fa-arrow-left"></i> Back</a>
    <div class="mt-5">


        @foreach($exam_test_questions as $exam_test_question)

            <div class="card mb-5 shadow">
                <div class="card-body text-dark">
                    <div class="mb-3"><i class="fa fa-book text-info"></i> {{ $exam_test_question->testQuestion->course->description }}</div>
                    <div class="test-question-body text-dark d-flex">
                        <div class="mr-2">
                            <div class="test-num">{{ $exam_test_question->pos_order }}</div> 
                        </div>
                        <div class="mt-1">
                            {!! $exam_test_question->testQuestion->getHtml() !!}
                        </div>
                    </div>
                    <hr>
                    <div class="choices">
                        <div class="row">
                            @foreach ($exam_test_question->testQuestion->choices as $index => $choice)
                            <div class="col-6 mb-3">
                                <div class="text-dark choice {{ $choice->is_correct ? 'correct-choice' : '' }}" style="height: 100%;">
                                    <div class="d-flex">
                                        <div class="mr-2">
                                            <div class="choice-num {{ $choice->is_correct ? 'correct' : '' }}">
                                                {{ chr($choice->pos_order + 64) }}
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
                toast.fire({
                    type: 'success',
                    title: 'Exam Preview is Showing'
                  })
            }
        });
    </script>

    @if(Session::has('message'))
        <script>
          toast.fire({
            type: 'success',
            title: '{{ Session::get('message') }}'
          })
        </script>
      @endif
@endpush
