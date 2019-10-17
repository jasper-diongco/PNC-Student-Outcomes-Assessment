@extends('layout.app', ['active' => 'test_questions'])

@section('title', 'Add new Test Question')

@section('content')


<a href="{{ url('/test_questions?student_outcome_id='. request('student_outcome_id') . '&course_id=' . request('course_id') . '&program_id=' . request('program_id')) }}" class="text-success"><i class="fa fa-arrow-left"></i> Back</a>

<div class="d-flex justify-content-between mb-2 mt-3">
  <div>
    <h1 class="page-header">Test Question Details</h1>
  </div>
</div>

<div id="app" v-cloak>
    @if(!$test_question->is_active)
        <div class="alert alert-warning">
            <i class="fa fa-exclamation-triangle"></i> This test question is archived.
        </div>
    @endif
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div v-cloak>
            <h5 class="py-0 my-0">{{ $test_question->title }} <span class="badge badge-danger" v-if="countReportedNotification() > 0">@{{ countReportedNotification() }} reported problem</span></h5>
        </div>
        <div>
            @if((Auth::user()->user_type_id == 'dean' || Auth::user()->user_type_id == 's_admin') || (Auth::user()->user_type_id == 'prof' && Auth::user()->id == $test_question->user_id))
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
            @endif
            
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex flex-wrap mb-3">
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
            <div class="mb-3">
                <div class="mr-3">
                    <label><i class="fa fa-question-circle"></i> Type: </label> 
                    <span class="fs-19 text-info">
                        @if($test_question->type_id == 1)
                            Multiple choice
                        @elseif($test_question->type_id == 2)
                        True or False
                        @elseif($test_question->type_id == 3)
                        Multiple Select
                        @endif
                    </span>
                </div>
            </div>

            <div class="d-flex mb-3">
                <div class="d-flex mr-3"><label class="mr-2">Student Outcome: </label><h5 class="text-success">{{ $test_question->studentOutcome->so_code }}</h5></div>
                <div class="d-flex"><label class="mr-2">Course: </label><h5 class="text-success">{{ $test_question->course->course_code . ' - ' . $test_question->course->description }}</h5></div>
            </div>

            <div class="mb-2 p-3 pb-2">
            <h5><i class="fa fa-stream text-dark mb-3"></i> Count Answers</h5>
            <div class="w-75">
                <div>
                    <label><i class="fa fa-check-circle text-success"></i> Correct</label>
                    <div class="d-flex">
                        <div class="progress w-100 mr-3" style="height: 20px;">
                          <div class="progress-bar bg-success" role="progressbar" style="width: {{ $test_question->correctPercentage() }}%" aria-valuenow="{{ $test_question->correctPercentage() }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="mr-2">
                            {{ $test_question->correctPercentage() }}%
                        </div>
                        <div class="mr-2">
                            ({{ $test_question->countCorrectAnswer() }})
                        </div>
                    </div>
                </div>
                <div class="mt-2">
                    <label><i class="fa fa-times-circle text-dark"></i> Incorrect</label>
                    <div class="d-flex">
                        <div class="progress w-100 mr-3" style="height: 20px;">
                          <div class="progress-bar"  role="progressbar" style="width: {{ $test_question->incorrectPercentage() }}%; background: #9c9c9c;" aria-valuenow="{{ $test_question->incorrectPercentage() }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="mr-2">
                             {{ $test_question->incorrectPercentage() }}%
                        </div>
                        <div class="mr-2">
                            ({{ $test_question->countIncorrectAnswer() }})
                        </div>
                    </div>
                </div>
                <div class="mt-2 d-flex justify-content-end mr-2">
                    <span style="font-weight:600" class="mr-2">Total:</span> 100% ({{ $test_question->countTotalAnswers() }})
                </div>
            </div>
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
                                        @if($test_question->type_id == 1 || $test_question->type_id == 3)
                                        {{  chr($choice->pos_order + 64) }}
                                        @else
                                            <i class="fa fa-check-circle"></i>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    {!! $choice->getHtml() !!}  
                                </div>
                            </div>
                            <div>
                                <div class="d-flex mt-3 align-items-baseline">
                                    <div class="progress w-100 mr-2">
                                      <div class="progress-bar bg-success" role="progressbar" style="width: {{ $choice->getPercentageSelectedByStudent() }}%" aria-valuenow="{{ $choice->getPercentageSelectedByStudent() }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="mr-2">{{ $choice->getPercentageSelectedByStudent() }}%</div>
                                    <div>
                                        ({{ $choice->countSelectedByStudent() }})
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    @endforeach

                </div>
                <div class="d-flex justify-content-end">
                    <div class="mr-2" style="font-weight: 600">Total </div>
                    <div class="mr-1">100%</div>
                    <div>({{ $test_question->countTotalChoicesSelectedByStudent() }})</div>
                </div>
            </div>
        </div>
    </div>


    <div class="card my-4">
        <div class="card-body pt-1 pb-4">
            <h5 class="my-3"><i class="fa fa-exclamation-circle text-info"></i> Problem Reports</h5>

            <div v-if="reported_test_questions.length > 0">
            <ul class="list-group">
                <ul class="list-group">
                    <div v-for="reported_test_question in reported_test_questions">
                        <li :key="reported_test_question.id" class="list-group-item">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <div class="d-flex">
                                    <div class="mr-3">
                                        <div class="avatar" style="color:white; background: #4caf50;"><i class="fa fa-question"></i></div>
                                    </div>
                                    <div>
                                        <div style="font-size: 16px"><strong>@{{ reported_test_question.test_question.tq_code }}</strong></div>
                                        <div class="text-muted">(@{{ reported_test_question.student.student_id }}) @{{ reported_test_question.student.user.first_name }} @{{ reported_test_question.student.user.last_name }}: @{{ reported_test_question.message }}</div>
                                    </div>   
                                </div>
                                <div>
                                    <button v-on:click="resolveProblem(reported_test_question.id)" v-if="!reported_test_question.is_resolved" class="btn btn-success btn-sm">Resolve <i class="fa fa-check"></i></button>
                                    <span class="mr-2" v-else>Resolved <i class="fa fa-check-circle text-success"></i></span>
                                </div>
                            </div>
                        </li>
                    </div>
            </ul>
            </div>
            <div v-else class="p-3 bg-white">
                <h5>No report found.</h5>
            </div>


        </div>
    </div>
    
    @if(count($test_question->getArchives()) > 0)
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#historyModal">
          View History Changes
        </button>
    @endif

    <!-- Modal -->
    <div class="modal fade" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">History Changes</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            @foreach($test_question->getArchives() as $archive)
            <div class="card question">
                <div class="card-body pt-0">
                    <div class="bg-secondary p-2">
                        <h5>Changed on {{ $archive->created_at->format('M d, Y') .', ' . $archive->created_at->diffForHumans() }} by {{ $archive->user->first_name . ' ' . $archive->user->last_name}}</h5>
                    </div>
                    <div class="test-question-body">{!! $archive->html_body !!}</div>
                    <hr>
                    <div class="choices">
                        <div class="row">
                            @foreach ($archive->choiceArchives as $index => $choice)
                            <div class="col-6 mb-3">
                                <div class="choice {{ $choice->is_correct ? 'correct-choice' : '' }}" style="height: 100%;">
                                    <div class="d-flex">
                                        <div class="mr-2">
                                            <div class="choice-num {{ $choice->is_correct ? 'correct' : '' }}">
                                                @if($test_question->type_id == 1 || $test_question->type_id == 3)
                                                {{ chr($index + 65) }}
                                                @elseif($test_question->type_id == 2)
                                                    <i class="fa fa-check-circle"></i>
                                                @endif
                                            </div>
                                        </div>
                                        <div>
                                            {!! $choice->html_body !!}  
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
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
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
                test_question: @json($test_question),
                reported_test_questions: []
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

                },
                getReportedTestQuestions() {
                    ApiClient.get('/test_question_problems/get_test_question_problems?test_question_id=' + this.test_question.id)
                    .then(response => {
                        this.reported_test_questions = response.data;
                    })
                },
                resolveProblem(test_question_problem_id) {
                    ApiClient.post('/test_question_problems/' + test_question_problem_id +'/resolve')
                    .then(response => {
                        this.getReportedTestQuestions();
                        toast.fire({
                            type: 'success',
                            title: 'Successfully Resolved'
                        });
                    })
                },
                countReportedNotification() {
                    var count = 0;

                    for(var i = 0; i < this.reported_test_questions.length; i++) {
                        if(!this.reported_test_questions[i].is_resolved) {
                            count++;
                        }
                    }

                    return count;
                }
            },
            created() {
                MathLive.renderMathInDocument();
                this.getReportedTestQuestions();
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