@extends('layout.app', ['active' => 'dashboard'])

@section('title', 'Professor Dashboard')

@section('content')
  
  
  @if(Session::has('message'))
    @component('components.alert')
      {{ Session::get('message') }}
    @endcomponent
  @endif
  <div id="app">
    @if (!$password_changed)
      <account-modal email="{{ Auth::user()->email }}" user_id="{{ Auth::user()->id }}"></account-modal>
    @endif

    <h1 class="page-header">Home</h1>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <strong>Hello {{ Auth::user()->first_name }}!</strong> Welcome back.
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>


    <div class="card mb-3">
      <div class="card-body py-3">
          <h5><i class="fa fa-book text-info"></i> My Courses</h5>
      </div>
    </div>

     @if($faculty_courses->count() > 0)
          <div class="d-block d-md-flex flex-wrap">
              @foreach($faculty_courses as $faculty_course)
                <div class="card shadow mb-4 mr-3 w-md-31">
                    <div class="card-body pt-3">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <div class="d-flex">
                                <div class="mr-2">
                                    <div class="avatar" style="background: #b3ea74; color:#585858;"><i class="fa fa-book"></i></div>
                                </div>
                                <div style="font-weight: 600">{{ $faculty_course->course->course_code }}</div>
                            </div>
                            
                        </div>
                        <div style="font-size: 13px" class="text-muted ml-2 mt-2">
                            {{ $faculty_course->course->description }}
                        </div>
                        <hr>
                        <div class="text-muted">
                           <i class="fa fa-database text-info"></i> {{ $faculty_course->course->testQuestions->count() }} Test Questions
                        </div>
                        <div class="mt-2 text-muted">
                          <i class="fa fa-question-circle text-success"></i> You have added {{ $faculty_course->getFacultyCourseTestQuestions()->count() }} questions for this course
                        </div>
                    </div>

                    <div class="card-footer">
                      <div class="d-flex justify-content-end">
                          <button v-on:click="viewTestQuestions({{ $faculty_course->id }})" class="btn btn-sm btn-info" class="btn btn-sm">
                              View my test questions
                          </button>
                        </div>
                    </div>
                </div>
              @endforeach
          </div>
        @else
          <div class="p-3 bg-white text-muted">
            <h5>You have no Assigned Courses :(</h5>
          </div>
        @endif
        

        <!-- Modal -->
        <div class="modal fade" id="myTestQuestionsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">

                  <h5 class="modal-title" id="exampleModalLabel">My Test Questions &mdash; @{{ selected_course.course_code }}</h5>


                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="d-flex justify-content-end mb-3">
                  <a href="{{ url('/test_bank') }}" class="btn btn-sm btn-info">Add Test Question <i class="fa fa-edit"></i></a>
                </div>
                <div v-if="test_questions_loading">
                  <table-loading></table-loading>
                </div>
                <div v-else>
                  <div v-if="test_questions.length > 0">
                    <ul class="list-group" id="list-exam-test-questions">
                      <li v-for="test_question in test_questions" :key="test_question.id" class="list-group-item">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <div class="d-flex">
                                    <div class="mr-3">
                                        <div class="avatar" :style="avatarStyle(test_question.difficulty_level_id)"><i class="fa fa-question" ></i></div>
                                    </div>
                                    <div>
                                        <div style="font-size: 18px">
                                          <div class="mb-1">{{-- <i class="fa fa-fingerprint"></i> --}} ID: @{{ test_question.tq_code }}</div>
                                          
                                          <div class="mb-1" style="font-weight: 600">
                                            <i class="fa fa-file-alt"></i> @{{ test_question.title }}
                                          </div> 
                                        </div>

                                        <div  class="text-muted mb-1">@{{ getDifficulty(test_question.difficulty_level_id) }} - @{{ test_question.choices.length }} choices | Correct Answer &mdash; <span class="text-success font-weight-bold">@{{ test_question.correct_answer }}</span></div>

                                        <div style="font-size: 13px" class="text-muted mt-1">
                                          <i class="fa fa-user"></i> @{{ test_question.user.first_name + ' ' + test_question.user.last_name }} | @{{ parseDate(test_question.created_at) }}
                                        </div>
                                    </div>  

                                </div>
                                <div>
                                     <div>
                                       <a :href="myRootURL + '/test_questions/' + test_question.id + '?student_outcome_id=' + test_question.student_outcome_id + '&course_id=' + test_question.course_id" class="btn btn-sm "> View <i class="fa fa-chevron-right"></i></a>
                                     </div>                        
                                </div>
                            </div>
                        </li>
                    </ul>
                  </div>
                  <div class="p-3 bg-light fs-19 text-center" v-else>
                      No Test Question Added.
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
        faculty_course_id: '',
        selected_course: '',
        test_questions: [],
        test_questions_loading: false,
        myRootURL: ''
      },
      methods: {
        viewTestQuestions(faculty_course_id) {
          this.faculty_course_id = faculty_course_id;
          // this.selected_course = course;
            $('#myTestQuestionsModal').modal('show');

            this.test_questions_loading = true;
            ApiClient.get('/faculty_courses/' + faculty_course_id + '/get_faculty_course_test_questions')
            .then(response => {
              // console.log(response);
              this.selected_course = response.data.course;
              this.test_questions = response.data.test_questions;
              this.test_questions_loading = false;

              for(var i = 0; i < vm.test_questions.length; i++) {
                  vm.getCorrectAnswer(vm.test_questions[i]);
                }
            })
        },
        parseDate(date) {
            return moment(date).format('MMM DD YYYY');
        },
        avatarStyle(difficulty_level_id) {
            var backgroundColor = '';
            var color = '';
            if(difficulty_level_id == 1) {
                backgroundColor = '#cbff90';
                color = '#4caf50';
            } else if (difficulty_level_id == 2) {
                backgroundColor = '#fff375';
                color = '#ff9800';
            } else if (difficulty_level_id == 3) {
                backgroundColor = '#f28b82';
                color = '#d2493f';
            }
            

            return {
                backgroundColor,
                color
            };
        },
        getDifficulty(difficulty_id) {
            if (difficulty_id == 1) {
                return 'Easy';
            } else if (difficulty_id == 2) {
                return 'Average';
            } else if (difficulty_id == 3) {
                return 'Difficult'
            }
        },
        getCorrectAnswer(test_question) {
            for(var i = 0; i < test_question.choices.length; i++) {
              if(test_question.choices[i].is_correct) {
                Vue.set(test_question, 'correct_answer', String.fromCharCode(i + 65));
                break;
              }
            }
          }
      },
      created() {
        this.myRootURL = window.myRootURL;
      }
    });
  </script>
  
  @if (!$password_changed)
    <script>
      $(document).ready(function() {
        $('#accountModal').modal('show');
      });
    </script>
  @endif
@endpush