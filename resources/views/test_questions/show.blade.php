@extends('layout.app', ['active' => 'test_questions'])

@section('title', 'Add new Test Question')

@section('content')


<a href="{{ url('/test_questions?student_outcome_id='. request('student_outcome_id') . '&course_id=' . request('course_id') . '&program_id=' . request('program_id')) }}" class="text-success"><i class="fa fa-arrow-left"></i> Back</a>

<div class="d-flex justify-content-between mb-2 mt-3">
  <div>
    <h1 class="page-header">Test Question Details</h1>
  </div>
</div>

<div id="app">
    @if(!$test_question->is_active)
        <div class="alert alert-warning">
            <i class="fa fa-exclamation-triangle"></i> This test question is archived.
        </div>
    @endif
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="py-0 my-0">{{ $test_question->title }}</h5>
        </div>
        <div>
            @if($test_question->is_active)
                <button :disabled="isLoading" v-on:click="archiveTestQuestion" class="btn btn-sm mr-2">
                    <i class="fa fa-archive"></i> 
                    Archive
                    <div v-if="isLoading" class="spinner-border spinner-border-sm text-dark" role="status">
                      <span class="sr-only">Loading...</span>
                    </div>
                </button>
                <a href="{{ url('/test_questions/' . $test_question->id . '/edit?student_outcome_id='. request('student_outcome_id') . '&course_id=' . request('course_id') . '&program_id=' . request('program_id')) }}" class="btn btn-sm btn-success"><i class="fa fa-edit"></i> Edit</a>
            @else
                <button :disabled="isLoading" v-on:click="activateTestQuestion" class="btn btn-sm mr-2 btn-info">
                    <i class="fa fa-history"></i> 
                    Activate 
                    <div v-if="isLoading" class="spinner-border spinner-border-sm text-light" role="status">
                      <span class="sr-only">Loading...</span>
                    </div>
                </button>
            @endif
            
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex mb-3">
                <div class="mr-3">
                    <label>ID:</label> {{ $test_question->tq_code }}
                </div>
                <div class="mr-3">
                    <i class="fa fa-user text-dark"></i>
                    Author: {{ $test_question->user->first_name . ' ' . $test_question->user->last_name }}
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
                <div class="d-flex mr-3"><label class="mr-2">Student Outcome: </label><h5 class="text-success">{{ $test_question->studentOutcome->so_code }}</h5></div>
                <div class="d-flex"><label class="mr-2">Course: </label><h5 class="text-success">{{ $test_question->course->course_code . ' - ' . $test_question->course->description }}</h5></div>
            </div>
        </div>
    </div>
    
    <div class="card question">
        <div class="card-body">
            <div class="test-question-body">{!! $test_question->getHtml() !!}</div>
            <hr>
            <div class="choices">
                <div class="row">
                    @foreach ($test_question->choices as $choice)
                    <div class="col-6 mb-3">
                        <div class="choice {{ $choice->is_correct ? 'correct-choice' : '' }}" style="height: 100%;">
                            <div class="d-flex">
                                <div class="mr-2">
                                    <div class="choice-num {{ $choice->is_correct ? 'correct' : '' }}">
                                        {{  chr($choice->pos_order + 64) }}
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
                isLoading: false,
                test_question: @json($test_question)
            },
            methods: {
                archiveTestQuestion() {
                    swal.fire({
                    title: 'Do you want to archive?',
                    text: "Please confirm",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#1cc88a',
                    cancelButtonColor: '#858796',
                    confirmButtonText: 'Yes',
                    width: '400px'
                  }).then((result) => {
                    if (result.value) {
                      this.isLoading = true;
                      ApiClient.post('/test_questions/' + this.test_question.id + '/archive')
                      .then(response => {
                        this.isLoading = false;
                        // this.curriculum_mapping_status = response.data;
                        // toast.fire({
                        //     type: 'success',
                        //     title: 'You can now update this curriculum'
                        // });
                        window.location.reload();
                      })
                      .catch(error => {
                        this.isLoading = false;
                        alert("An error has occured. Please try again");
                      })
                    }
                  });

                },
                activateTestQuestion() {
                    swal.fire({
                    title: 'Do you want to activate?',
                    text: "Please confirm",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#1cc88a',
                    cancelButtonColor: '#858796',
                    confirmButtonText: 'Yes',
                    width: '400px'
                  }).then((result) => {
                    if (result.value) {
                      this.isLoading = true;
                      ApiClient.post('/test_questions/' + this.test_question.id + '/activate')
                      .then(response => {
                        this.isLoading = false;
                        // this.curriculum_mapping_status = response.data;
                        // toast.fire({
                        //     type: 'success',
                        //     title: 'You can now update this curriculum'
                        // });
                        //console.log(response);
                        window.location.reload();
                      })
                      .catch(error => {
                        this.isLoading = false;
                        alert("An error has occured. Please try again");
                      })
                    }
                  });

                }
            },
            created() {
                MathLive.renderMathInDocument();
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