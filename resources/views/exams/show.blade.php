@extends('layout.app', ['active' => 'test_questions'])

@section('title', 'Exam Details')

@section('content')
    <div id="app" v-cloak>
        
        
        <div class="card p-3 px-4 mb-3">
            <a href="{{ url('/exams?program_id='. request('program_id') .'&student_outcome_id=' . request('student_outcome_id'). '&curriculum_id='. request('curriculum_id')) }}" class="text-success"><i class="fa fa-arrow-left"></i> Back</a>


            {{-- <div class="mx-auto" style="width: 400px">
              <img src="{{ asset('svg/exam.svg') }}" class="w-100">
            </div> --}}


            <div class="mt-3 d-flex justify-content-between align-items-baseline">
                <div>
                    <h1 class="page-header mb-3">Exam Details</h1>
                </div>
                
            </div>

            <div class="d-flex mb-3 flex-wrap">

                <div class="mr-3"><label>Program: </label> <span class="text-info fs-19">{{ $program->program_code }}</span></div>
                <div class="mr-3"><label>Student Outcome: </label> <span class="text-info fs-19">{{ $student_outcome->so_code }}</span></div>
                <div class="mr-3"><label>Curriculum: </label> <span class="text-info fs-19">{{ $curriculum->name . ' ' . $curriculum->year . ' - revision no. ' . $curriculum->revision_no }}.0</span></div>
            </div>
            <div style="width: 200px">
                <pie-chart :data="pie_data"></pie-chart>
            </div>

            <div class="d-flex justify-content-end mt-3">
              @if(!$exam->is_active)
                <button v-on:click="activateExam" class="btn btn-sm btn-info mr-2"><i class="fa fa-history"></i> Activate</button>
              @else
                <button v-on:click="archiveExam" class="btn btn-sm mr-2"><i class="fa fa-archive"></i> Archive</button>
                <a href="{{ url('/exams/' . $exam->id . '/print_answer_key' ) }}" target="_blank" class="btn btn-sm btn-info mr-2">Print Answer Key <i class="fa fa-print"></i></a>

                <a href="{{ url('/exams/'. $exam->id .'/preview?program_id='. request('program_id') .'&student_outcome_id=' . request('student_outcome_id'). '&curriculum_id='. request('curriculum_id')) }}" class="btn btn-primary btn-sm">Preview <i class="fa fa-external-link-alt"></i></a>
                
              @endif
            </div>
        </div>
        
        @if(!$exam->is_active)
          <div class="alert alert-warning">
            <i class="fa fa-exclamation-triangle"></i> This exam is deactivated
          </div>
        @endif

        
        <div id="main-nav-tabs">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#exam-detail" role="tab" aria-controls="home" aria-selected="true">Exam Details</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#test-questions" role="tab" aria-selected="false">Test Questions</a>
              </li>
            </ul>
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade show active" id="exam-detail" role="tabpanel">
                {{-- exam details --}}

                <ul class="list-group mt-2">
                  <li class="list-group-item">
                      <label class="mb-0"><i class="fa fa-fingerprint"></i> Exam ID:</label>  {{ $exam->exam_code }}
                  </li>
                  <li class="list-group-item">
                      <label><i class="fa fa-file-alt text-dark"></i> Description:</label> {{ $exam->description }}
                  </li>
                  <li class="list-group-item">
                       <label><i class="fa fa-clock text-dark"></i> Time Limit :</label> {{ $exam->time_limit . ' minutes' }}
                  </li>
                  <li class="list-group-item">
                      <label><i class="fa fa-check-circle text-dark"></i> Passing Grade  :</label> {{ $exam->passing_grade . '%' }}
                  </li>
                  <li class="list-group-item">
                      <label><i class="fa fa-question-circle text-dark"></i> Test Questions  :</label> {{ $exam->examTestQuestions->count() }} test questions
                  </li>
                  <li class="list-group-item">
                      <label><i class="fa fa-calendar text-dark"></i> Created At  :</label> {{ $exam->created_at->format('M d, Y') }}
                  </li>
                  <li class="list-group-item">
                      <label><i class="fa fa-user text-dark"></i> Author:</label> {{ $exam->user->getFullName() }}
                  </li>
                </ul>
              </div>
              <div class="tab-pane fade" id="test-questions" role="tabpanel">

                <div class="mt-3 mb-3 d-flex justify-content-between align-items-baseline">
                    <div>
                        <h5>List of Test Questions <i class="fa fa-list text-info"></i></h5>
                    </div>   
                    
                </div>
                
                <div class="row">
                    <div class="col-md-3">
                          <div class="input-group mb-3" id="search-input-darker">
                            
                            <input type="search" v-on:input="searchTestQuestion" v-model="searchText" class="form-control" placeholder="Search test question...">

                            <div class="input-group-append">
                              <span class="input-group-text btn btn-primary"><i class="fa fa-search"></i></span>
                            </div>
                          </div>
                        </div>
                    <div class="col-md-9">
                      <div class="d-flex justify-content-end">
                        <div class="d-flex mr-3">
                          <div class="mr-2"><label class="col-form-label text-dark"><i class="fa fa-book text-success"></i> Filter By Course: </label></div>
                          <div>
                            <select class="form-control" v-model="filter_course_id" v-on:change="filterByCourse">
                              <option value="">All</option>
                              <option v-for="course in courses" :key="course.id" :value="course.id">@{{ course.course_code }}</option>
                            </select>
                          </div>
                        </div>
                        <div class="d-flex mr-2">
                          <div class="mr-2"><label class="col-form-label text-dark"><i class="fa fa-layer-group text-success"></i> Filter By Level: </label></div>
                          <div>
                            <select class="form-control" v-model="filter_difficulty_level_id" v-on:change="filterByDifficulty">
                              <option value="">All</option>
                              <option value="1">Easy</option>
                              <option value="2">Average</option>
                              <option value="3">Difficult</option>
                            </select>
                          </div>
                        </div>
                      </div>
                  </div>
                </div>
                <template v-if="show_test_questions.length <= 0 && !tableLoading">
                    <div class="bg-white p-3">No Test question found.</div>         
                </template>
                <template v-else-if="!tableLoading">
                    <ul class="list-group" id="list-exam-test-questions">
                      <li v-for="test_question in show_test_questions" :key="test_question.id" class="list-group-item">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <div class="d-flex">
                                    <div class="mr-3">
                                        {{-- <div class="avatar"><i class="fa fa-question-circle" :style="avatarStyle(test_question.difficulty_level_id)"></i></div> --}}
                                        <div class="avatar" :style="avatarStyle(test_question.difficulty_level_id)">
                                          @{{ test_question.counter }}
                                        </div>
                                    </div>
                                    <div>
                                        <div style="font-size: 18px">

                                          <div class="mb-1">{{-- <i class="fa fa-fingerprint"></i> --}} ID: @{{ test_question.tq_code }}</div>
                                          
                                          <div class="mb-1" style="font-weight: 600">
                                            <i class="fa fa-file-alt"></i> @{{ test_question.title }}
                                          </div> 
                                        </div>
                                        <div  class="text-muted mb-1">@{{ getDifficulty(test_question.difficulty_level_id) }} - @{{ test_question.choices.length }} choices | Correct Answer &mdash; <span class="text-success font-weight-bold">@{{ test_question.correct_answer }}</span></div>
                                        <div class="text-muted mb-1" style="font-size: 14px"><i class="fa fa-book"></i> @{{ test_question.course.description }} </div>
                                    </div>   
                                </div>
                                <div>
                                    <a v-on:click="getPreview(test_question.id)" href="#" data-toggle="modal" data-target="#previewModal" class="btn btn-sm">View <i class="fa fa-search"></i></a>                           
                                </div>
                            </div>
                        </li>
                    </ul>
                </template>
                <template v-else>
                    <div class="bg-white p-3">
                        <table-loading></table-loading>
                    </div>
                </template>

                <div class="text-muted mt-3">Showing @{{ show_test_questions.length }} records</div>

{{--                 <div v-if="!tableLoading" class="d-flex justify-content-end">
                    <nav aria-label="..." >
                      <ul class="pagination">
                        <li class="page-item" :class="{'disabled': current_page <= 1}">
                          <button class="page-link" v-on:click="paginate(current_page - 1)">Previous</button>
                        </li>

                        <li :class="{'active': num == current_page }" v-for="num in paginationCount" :key="num" class="page-item"><button v-on:click="paginate(num)" class="page-link">@{{ num }}</button></li>
                        <li class="page-item" :class="{'disabled': current_page == paginationCount}">
                          <button class="page-link" v-on:click="paginate(current_page + 1)">Next</button>
                        </li>
                      </ul>
                    </nav>
                </div> --}}

                {{-- <div class="card">
                    <div class="card-body">
                        
                        <div class="row">
                        <div class="col-md-3">
                          <div class="input-group mb-3" id="search-input">
                            
                            <input type="search" v-on:input="searchTestQuestion" v-model="searchText" class="form-control" placeholder="Search test question...">

                            <div class="input-group-append">
                              <span class="input-group-text btn btn-primary"><i class="fa fa-search"></i></span>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-9">
                          <div class="d-flex justify-content-end">
                            <div class="d-flex mr-3">
                              <div class="mr-2"><label class="col-form-label text-dark"><i class="fa fa-book text-success"></i> Filter By Course: </label></div>
                              <div>
                                <select class="form-control" v-model="filter_course_id" v-on:change="filterByCourse">
                                  <option value="">All</option>
                                  <option v-for="course in courses" :key="course.id" :value="course.id">@{{ course.course_code }}</option>
                                </select>
                              </div>
                            </div>
                            <div class="d-flex mr-2">
                              <div class="mr-2"><label class="col-form-label text-dark"><i class="fa fa-layer-group text-success"></i> Filter By Level: </label></div>
                              <div>
                                <select class="form-control" v-model="filter_difficulty_level_id" v-on:change="filterByDifficulty">
                                  <option value="">All</option>
                                  <option value="1">Easy</option>
                                  <option value="2">Average</option>
                                  <option value="3">Difficult</option>
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>      
                    </div>

                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th width="10%">
                                        ID
                                    </th>
                                    <th width="40%">
                                        Title
                                    </th>
                                    <th>
                                        Level
                                    </th>
                                    <th>
                                        Choices
                                    </th>
                                    <th>
                                        Created By
                                    </th>
                                    <th width="5%">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-if="show_test_questions.length <= 0 && !tableLoading">
                                    <tr>
                                        <td colspan="6">No record found.</td>
                                    </tr>          
                                </template>
                                <template v-else-if="!tableLoading">
                                    <tr v-for="test_question in paginated_test_questions" :key="test_question.id">
                                        <td>
                                            @{{ test_question.id }}
                                        </td>
                                        <td>
                                            @{{ test_question.title }}
                                        </td>
                                        <td>
                                            @{{ getDifficulty(test_question.difficulty_level_id) }}
                                        </td>
                                        <td>
                                            @{{ test_question.choices.length }}
                                        </td>
                                        <td>@{{ test_question.user.first_name + ' ' + test_question.user.last_name }}</td>
                                        <td>
                                            <a v-on:click="getPreview(test_question.id)" href="#" data-toggle="modal" data-target="#previewModal" class="btn btn-sm btn-secondary"><i class="fa fa-search"></i></a>
                                        </td>
                                    </tr>
                                </template>
                                
                                <template v-else>
                                    <tr>
                                        <td colspan="6">
                                            <table-loading></table-loading>
                                        </td>        
                                    </tr>
                                </template>
                            </tbody>
                        </table>

                        <div class="text-muted">Showing @{{ paginated_test_questions.length }} records</div>

                        <div v-if="!tableLoading" class="d-flex justify-content-end">
                            <nav aria-label="..." >
                              <ul class="pagination">
                                <li class="page-item" :class="{'disabled': current_page <= 1}">
                                  <button class="page-link" v-on:click="paginate(current_page - 1)">Previous</button>
                                </li>

                                <li :class="{'active': num == current_page }" v-for="num in paginationCount" :key="num" class="page-item"><button v-on:click="paginate(num)" class="page-link">@{{ num }}</button></li>
                                <li class="page-item" :class="{'disabled': current_page == paginationCount}">
                                  <button class="page-link" v-on:click="paginate(current_page + 1)">Next</button>
                                </li>
                              </ul>
                            </nav>
                        </div>
                        
                    </div>
                </div> --}}
              </div>
            </div>
        </div>



        

        
        {{-- preview modal --}}

        <div id="previewModal" class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-xl">
            <div class="modal-content">

              <div class="modal-body">
                <div class="d-flex justify-content-between mb-4">
                    <div>
                        <h5>Test Question Preview</h5>
                    </div>
                    <div>
                       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button> 
                    </div>
                    
                </div>
                
                <div v-if="!previewLoading">
                    <div v-html="previewBody"></div>
                </div>
                <div v-else>
                    <table-loading></table-loading>
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
                exam_id: '{{ $exam->id }}' ,
                test_questions: @json($test_questions),
                show_test_questions: [],
                paginated_test_questions: [],
                courses: @json($courses),
                searchText: '',
                filter_course_id: '',
                filter_difficulty_level_id: '',
                tableLoading: false,
                previewLoading: false,
                current_page: 1,
                previewBody: '',
                pie_data: {
                    datasets: [
                        {
                            data: [],
                            backgroundColor: ["#cbff90", "#fff375", "#f28b82"]
                        }
                    ],
                    labels: ["Easy", "Ave", "Difficult"]
                },
                isLoading: false

            },
            computed: {
                paginationCount() {
                    return Math.ceil(this.show_test_questions.length / 10);
                }
            },
            methods: {
                avatarStyle(difficulty_level_id) {
                    var backgroundColor = '';
                    if(difficulty_level_id == 1) {
                        backgroundColor = '#cbff90';
                        color = '#4caf50';
                    } else if (difficulty_level_id == 2) {
                        backgroundColor = '#fff375';
                        color = '#ffc107';
                    } else if (difficulty_level_id == 3) {
                        backgroundColor = '#f28b82';
                        color = '#d04b42';
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
                // searchTestQuestion() {
                    

                //     this.tableLoading = true;

                //     setTimeout(() => {
                //         this.tableLoading = false;
                //         if(this.searchText == '') {
                //             return this.filterByCourse();
                //         }

                //         this.show_test_questions = this.test_questions.filter(test_question => {
                //             var reg_exp = new RegExp(this.searchText,'i');
                //             return (
                //             test_question.title.search(reg_exp) > -1 );
                //         });

                //         this.paginate(1);
                //     },400);
                // },
                searchTestQuestion: _.debounce(() => {
                    

                    vm.tableLoading = true;

                    // setTimeout(() => {
                        vm.tableLoading = false;
                        if(vm.searchText == '') {
                            return vm.filterByCourse();
                        }

                        vm.show_test_questions = vm.test_questions.filter(test_question => {
                            var reg_exp = new RegExp(vm.searchText,'i');
                            return (
                            test_question.title.search(reg_exp) > -1 );
                        });

                        vm.paginate(1);
                    // },400);
                }, 400),  
                filterByCourse() {
                    this.tableLoading = true;

                    // setTimeout(() => {
                        this.tableLoading = false;
                        if(this.filter_course_id == '') {
                            if(this.filter_difficulty_level_id != '') {
                                this.show_test_questions = this.test_questions.filter(test_question => test_question.difficulty_level_id == this.filter_difficulty_level_id);
                                return this.paginate(1);
                            } else {
                                this.show_test_questions = this.test_questions;
                                return this.paginate(1);
                            }
                            
                        }

                        if(this.filter_difficulty_level_id != '') {
                            this.show_test_questions = this.test_questions.filter(test_question => test_question.course_id == this.filter_course_id && test_question.difficulty_level_id == this.filter_difficulty_level_id);
                        } else {
                            this.show_test_questions = this.test_questions.filter(test_question => test_question.course_id == this.filter_course_id);
                        }

                        this.paginate(1);
                        
                    // },400);
                    
                },
                filterByDifficulty() {
                    this.tableLoading = true;

                    // setTimeout(() => {
                        this.tableLoading = false;
                        if(this.filter_difficulty_level_id == '') {
                            if(this.filter_course_id != '') {
                                this.show_test_questions = this.test_questions.filter(test_question => test_question.course_id == this.filter_course_id); 
                                return this.paginate(1);
                            } else {
                                this.show_test_questions = this.test_questions;
                                return this.paginate(1);
                            }
                            
                        }

                        if(this.filter_course_id != '') {
                            this.show_test_questions = this.test_questions.filter(test_question => test_question.difficulty_level_id == this.filter_difficulty_level_id && test_question.course_id == this.filter_course_id);
                        } else {
                            this.show_test_questions = this.test_questions.filter(test_question => test_question.difficulty_level_id == this.filter_difficulty_level_id);
                        }

                        this.paginate(1);
                        
                    // },400);
                },
                displayCourseDesc() {
                    return this.courses.filter(course => {
                        return course.id == this.filter_course_id
                    })[0].description;
                },
                paginate(page='') {
                    this.current_page = page;
                    this.paginated_test_questions = this.show_test_questions.slice((page-1) * 10, page * 10);
                },
                getPreview(test_question_id) {
                    this.previewLoading = true;
                    ApiClient.get('/test_questions/' + test_question_id + '/preview')
                    .then(response => {
                        this.previewBody = response.data;
                        this.previewLoading = false;

                    })
                },
                countByDifficulty(difficulty_level_id) {
                    return this.test_questions.filter(test_question => {
                        return test_question.difficulty_level_id == difficulty_level_id
                    }).length;
                },
                archiveExam() {
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
                      ApiClient.post('/exams/' + this.exam_id + '/deactivate')
                      .then(response => {
                        this.isLoading = false;
                        window.location.reload();
                        console.log(response);
                      })
                      .catch(error => {
                        this.isLoading = false;
                        alert("An error has occured. Please try again");
                      })
                    }
                  });

                },
                activateExam() {
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
                      ApiClient.post('/exams/' + this.exam_id + '/activate')
                      .then(response => {
                        this.isLoading = false;
                        window.location.reload();
                        console.log(response);
                      })
                      .catch(error => {
                        this.isLoading = false;
                        alert("An error has occured. Please try again");
                      })
                    }
                  });
                },
                setNumber() {
                  for(var i = 0; i < this.test_questions.length; i++) {
                    //this.test_questions[i].counter = i + 1;
                    Vue.set(this.test_questions[i], 'counter', i+1);
                    this.getCorrectAnswer(this.test_questions[i]);
                  }
                },
                getCorrectAnswer(test_question) {
                  for(var i = 0; i < test_question.choices.length; i++) {
                    if(test_question.choices[i].is_correct) {
                      Vue.set(test_question, 'correct_answer', String.fromCharCode(i + 65));
                      Vue.set(test_question, 'course', this.getCourse(test_question.course_id))
                      break;
                    }
                  }
                },
                getCourse(course_id) {
                  for(var i = 0; i < this.courses.length; i++) {
                    if(this.courses[i].id == course_id) {
                      return this.courses[i];
                    }
                  }
                }
            },
            created() {
                //this.filter_course_id = this.courses[0].id;
                this.filter_course_id = "";
                this.filterByCourse();

                this.pie_data.datasets[0].data[0] = this.countByDifficulty(1);
                this.pie_data.datasets[0].data[1] = this.countByDifficulty(2);
                this.pie_data.datasets[0].data[2] = this.countByDifficulty(3);

                this.setNumber();

                setInterval(() => {
                    MathLive.renderMathInDocument();
                    Prism.highlightAll();
                },200);
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

