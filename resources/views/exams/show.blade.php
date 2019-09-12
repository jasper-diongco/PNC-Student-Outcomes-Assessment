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
                {{-- <pie-chart :data="pie_data"></pie-chart> --}}
            </div>

            <div class="d-flex justify-content-between mt-3 align-items-baseline">
              <div>
                <span class="d-inline-block mr-2 text-dark" style="font-weight: 600">Taken <span class="text-success">{{ $exam->countTaken() }}</span> times</span>

                @if ($exam->countTaken() >= 20)
                  <a href="{{ url('/exams/'. $exam->id .'/item_analysis?program_id='. request('program_id') .'&student_outcome_id=' . request('student_outcome_id'). '&curriculum_id='. request('curriculum_id')) }}" class="btn btn-sm btn-success">Start Item analysis <i class="fa fa-align-left text-dark"></i></a>
                @endif
              </div>

              <div>
                @if(!$exam->is_active)
                  <button v-on:click="activateExam" class="btn btn-sm btn-info mr-2"><i class="fa fa-history"></i> Activate</button>
                @else
                  <button v-on:click="archiveExam" class="btn btn-sm mr-2"><i class="fa fa-archive"></i> Archive</button>
                  <a href="{{ url('/exams/' . $exam->id . '/print_answer_key' ) }}" target="_blank" class="btn btn-sm btn-info mr-2">Print Answer Key <i class="fa fa-print"></i></a>

                  <a href="{{ url('/exams/'. $exam->id .'/preview?program_id='. request('program_id') .'&student_outcome_id=' . request('student_outcome_id'). '&curriculum_id='. request('curriculum_id')) }}" class="btn btn-primary btn-sm">Preview <i class="fa fa-external-link-alt"></i></a>
                  
                @endif
              </div>
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
              <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#reports" role="tab" aria-selected="false">Reports</a>
              </li>
            </ul>
            <div class="tab-content" id="myTabContent">

              <div class="tab-pane fade show active" id="exam-detail" role="tabpanel">
                <div class="card mb-3">
                  <div class="card-body py-3">
                    <h5>Items Count</h5>

                    <table class="table">
                      <thead>
                        <tr>
                          <th>Course</th>
                          <th>Easy</th>
                          <th>Average</th>
                          <th>Difficult</th>
                          <th>Total</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="itemCount in itemsCount" :key="itemCount.course.course_code">
                          <td>
                            @{{ itemCount.course.course_code }}
                          </td>
                          <td>
                            @{{ itemCount.easyCount }} (@{{ (itemCount.easyCount / (itemCount.totalCount || 0) * 100).toFixed(2) }}%)
                          </td>
                          <td>
                            @{{ itemCount.averageCount}} (@{{ (itemCount.averageCount / (itemCount.totalCount || 0) * 100).toFixed(2) }}%) 
                          </td>
                          <td>
                            @{{ itemCount.difficultCount }} (@{{ (itemCount.difficultCount / (itemCount.totalCount || 0) * 100).toFixed(2) }}%) 
                          </td>
                          <td>
                            @{{ itemCount.totalCount  }} (@{{ (itemCount.totalCount / (itemsCountSum.totalCount || 0) * 100).toFixed(2) }}%) 
                          </td>
                        </tr>
                      </tbody>
                      <tfoot class="font-weight-bold">
                        <tr>
                          <td>Total</td>
                          <td>@{{ itemsCountSum.easyCount }} (@{{(itemsCountSum.easyCount / itemsCountSum.totalCount * 100).toFixed(2)}}%)</td>
                          <td>@{{ itemsCountSum.averageCount }} (@{{(itemsCountSum.averageCount / itemsCountSum.totalCount * 100).toFixed(2)}}%)</td>
                          <td>@{{ itemsCountSum.difficultCount }} (@{{(itemsCountSum.difficultCount / itemsCountSum.totalCount * 100).toFixed(2)}}%)</td>
                          <td>@{{ itemsCountSum.totalCount }}</td>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                </div>

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
              {{-- reports --}}
              <div class="tab-pane fade" id="reports" role="tabpanel">
                <h4 class="py-4">Exam Reports</h4>
                
                <div class="card mb-4">

                  <div class="card-body py-4">
                    <h5>Items Difficulties Percentage</h5>
                    <p class="text-primary">This figure shows the percentage of difficulties of test questions. (Easy, Average, Difficult)</p>
                    <div class="w-25">
                        <pie-chart :data="pie_data"></pie-chart>
                    </div>
                  </div>
                </div>

                <div class="card mb-4">

                  <div class="card-body py-4">
                    <h5>Passing Percentage</h5>
                    <p class="text-primary">This figure shows the percentage of passed and failed students took this exam.</p>
                    <div class="w-25">
                      <pie-chart :data="passing_percentage_pie"></pie-chart>
                    </div>
                  </div>
                </div>

                <div class="card mb-4">

                  <div class="card-body py-4">
                    <h5>Average Scores per course</h5>
                    <p class="text-primary">This figures show the average percentage of scores per courses</p>
                    <div v-for="averageScoresPerCourse in averageScoresPerCourses" class="mb-3">
                          <div class="mr-2 mb-2"><span class="font-weight-bold"> @{{ averageScoresPerCourse.course_code }}</span> - @{{ averageScoresPerCourse.description }} &mdash; <span class="font-weight-bold">@{{ averageScoresPerCourse.totalItems }}</span> items</div>
                          <div class="d-flex align-items-baseline">
                              <div class="progress w-100 mr-2">
                                <div class="progress-bar bg-success" role="progressbar" :style="{'width' : averageScoresPerCourse.percentage + '%'}" :aria-valuenow="averageScoresPerCourse.percentage" aria-valuemin="0" aria-valuemax="100">@{{ averageScoresPerCourse.percentage.toFixed(2) }}%</div>
                              </div>
                              <div class="font-weight-bold text-dark">
                                  @{{ averageScoresPerCourse.average }}
                              </div>
                          </div>
                      </div> 
                  </div>
                </div>

                <div class="card mb-4">

                  <div class="card-body py-4">
                    <h5>Average percentage of scores in the total score</h5>
                    <p class="text-primary">This figure shows the average percentage of scores in the total score</p>
                    <div style="width: 40%">
                      <pie-chart :data="pie_average_percentage_of_scores"></pie-chart>
                    </div>
                  </div>
                </div>
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
    <script src="{{ asset('js/chartjs-plugin-labels.js') }}"></script>
    <script>
        var vm = new Vue({
            el: '#app',
            data: {
                exam_id: '{{ $exam->id }}' ,
                test_questions: @json($test_questions),
                exam_test_questions: @json($exam->examTestQuestions),
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
                passing_percentage_pie: {
                  datasets: [
                        {
                            data: [],
                            backgroundColor: ["#cbff90", "#ededed"]
                        }
                    ],
                    labels: ["Passed", "Failed"]
                },
                pie_average_percentage_of_scores: {
                  datasets: [
                        {
                            data: [],
                            backgroundColor: ["rgba(52, 172, 224,1.0)",
                            "rgba(51, 217, 178,1.0)",
                            "rgba(252, 92, 101,1.0)",
                            "rgba(75, 123, 236,1.0)",
                            "rgba(253, 150, 68,1.0)",
                            "rgba(254, 211, 48,1.0)",
                            "rgba(38, 222, 129,1.0)",
                            "rgba(43, 203, 186,1.0)",
                            "rgba(69, 170, 242,1.0)", 
                            "rgba(136, 84, 208,1.0)",
                            "rgba(75, 101, 132,1.0)",
                            "rgba(64, 64, 122,1.0)",
                            "rgba(112, 111, 211,1.0)"]
                        }
                    ],
                    labels: []
                },
                isLoading: false,
                itemsCount: [],
                itemsCountSum: {
                  easyCount: 0,
                  averageCount: 0,
                  difficultCount: 0,
                  totalCount: 0
                },
                assessmentsPassedCount: {{ $exam->countPassedAssessments() }},
                assessmentsFailedCount: {{ $exam->countFailedAssessments() }},
                assessments: @json($exam->getAssessments()),
                averageScoresPerCourses: []

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
                            test_question.title.search(reg_exp) > -1 ||  test_question.tq_code.search(reg_exp) > -1);
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
                    return this.exam_test_questions.filter(test_question => {
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
                },
                getItemsCount() {
                  for(var i = 0; i < this.courses.length; i++) {
                    var easyCount = 0;
                    var averageCount = 0;
                    var difficultCount = 0;
                    var totalCount = 0;

                    for(var j = 0; j < this.test_questions.length; j++) {
                      if(this.courses[i].id == this.test_questions[j].course_id) {
                        totalCount++;

                        if(this.test_questions[j].difficulty_level_id == 1) {
                          easyCount++;
                        } else if (this.test_questions[j].difficulty_level_id == 2) {
                          averageCount++;
                        }
                         else if (this.test_questions[j].difficulty_level_id == 3) {
                          difficultCount++;
                         }
                      }
                    }

                    this.itemsCount.push({
                      course: this.courses[i],
                      easyCount: easyCount,
                      averageCount: averageCount,
                      difficultCount: difficultCount,
                      totalCount: totalCount
                    });

                    
                  }

                  for(var i = 0; i < this.itemsCount.length; i++) {
                    this.itemsCountSum.easyCount += this.itemsCount[i].easyCount;
                    this.itemsCountSum.averageCount += this.itemsCount[i].averageCount;
                    this.itemsCountSum.difficultCount += this.itemsCount[i].difficultCount;
                    this.itemsCountSum.totalCount += this.itemsCount[i].totalCount;
                  }
                },
                getAverageScorePerCourses() {
                  templates = [];
                  //initialize array
                  var averageScoresPerCourses = [];
                  for(var x = 0; x < this.courses.length; x++) {
                    averageScoresPerCourses.push({
                      course_id: this.courses[x].id,
                      course_code: this.courses[x].course_code,
                      description: this.courses[x].description,
                      count: 0,
                      average: 0,
                      totalItems: 0,
                      percentage: 0
                    });
                  }

                  //count total items per course
                  if(this.assessments.length > 0) {
                    for(var i = 0; i < this.assessments[0].assessment_details.length; i++) {
                      for(var j = 0; j < averageScoresPerCourses.length; j++) {
                        if(averageScoresPerCourses[j].course_id == this.assessments[0].assessment_details[i].test_question.course_id) {
                          averageScoresPerCourses[j].totalItems += 1;
                        }
                      }
                    }
                  }


                  for(var i = 0; i < this.assessments.length; i++) {
                    var scoresPerCourses = [];
                    //initialize array
                    for(var x = 0; x < this.courses.length; x++) {
                      scoresPerCourses.push({
                        course_id: this.courses[x].id,
                        course_code: this.courses[x].course_code,
                        count: 0
                      });
                    }

                    for(var j = 0; j < this.assessments[i].assessment_details.length; j++) {
                      for(var k = 0; k < this.courses.length; k++) {
                        if(this.courses[k].id == this.assessments[i].assessment_details[j].test_question.course_id) {
                          if(this.assessments[i].assessment_details[j].is_correct) {
                            scoresPerCourses[k].count++;
                          }
                          //console.log(this.assessments[i].assessment_details[j].course.id);
                        }
                      }
                    }

                    templates.push(scoresPerCourses);
                  }


                  //get the average
                  for(var i = 0; i < templates.length; i++) {
                    for(var j = 0; j < templates[i].length; j++) {
                      averageScoresPerCourses[j].count += templates[i][j].count;
                    }
                  }

                  for(var i = 0; i < averageScoresPerCourses.length; i++) {
                    averageScoresPerCourses[i].average = averageScoresPerCourses[i].count / (this.assessments.length || 0);

                    //percentage
                    averageScoresPerCourses[i].percentage = averageScoresPerCourses[i].average / (averageScoresPerCourses[i].totalItems || 0) * 100;
                  }

                  //console.log(averageScoresPerCourses);

                  this.averageScoresPerCourses = averageScoresPerCourses;


                  for(var i = 0; i < this.averageScoresPerCourses.length; i++) {
                    this.pie_average_percentage_of_scores.labels[i] = this.averageScoresPerCourses[i].course_code
                    this.pie_average_percentage_of_scores.datasets[0].data[i] = averageScoresPerCourses[i].average;
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

                this.passing_percentage_pie.datasets[0].data[0] = this.assessmentsPassedCount;
                this.passing_percentage_pie.datasets[0].data[1] = this.assessmentsFailedCount;

                this.setNumber();
                this.getItemsCount();
                this.getAverageScorePerCourses();

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

