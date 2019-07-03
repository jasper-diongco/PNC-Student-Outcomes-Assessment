@extends('layout.app', ['active' => 'test_questions'])

@section('title', 'Exam Details')

@section('content')
    <div id="app" v-cloak>
        <a href="{{ url('/exams?program_id='. request('program_id') .'&student_outcome_id=' . request('student_outcome_id'). '&curriculum_id='. request('curriculum_id')) }}" class="text-success"><i class="fa fa-arrow-left"></i> Back</a>
        
        <div class="mt-3 d-flex justify-content-between align-items-baseline">
            <div>
                <h1 class="page-header mb-3">Exam Details</h1>
            </div>
            
        </div>

        <div class="d-flex mb-3">

            <div class="mr-3"><label>Program: </label> <span class="text-info">{{ $program->program_code }}</span></div>
            <div class="mr-3"><label>Student Outcome: </label> <span class="text-info">{{ $student_outcome->so_code }}</span></div>
            <div class="mr-3"><label>Curriculum: </label> <span class="text-info">{{ $curriculum->name . ' ' . $curriculum->year . ' - v' . $curriculum->revision_no }}.0</span></div>
        </div>

        <div class="mt-2 card">
            <div class="card-body pt-3">
                <div class="d-flex">
                    <div class="mr-3">
                        <label>Exam ID:</label>  {{ $exam->id }}
                    </div>
                    <div class="mr-3">
                       <i class="fa fa-clock text-dark"></i> Time Limit : {{ $exam->time_limit . ' minutes' }}
                    </div>
                    <div class="mr-3">
                        <i class="fa fa-check-circle text-dark"></i> Passing Grade  : {{ $exam->passing_grade . '%' }}
                    </div>
                    <div class="mr-3">
                        <i class="fa fa-question-circle text-dark"></i> Test Questions  : 100 test questions
                    </div>
                    <div class="mr-3">
                        <i class="fa fa-calendar text-dark"></i> Created At  : {{ $exam->created_at->format('M d, Y') }}
                    </div>
                </div>
                <div class="mr-2 mb-2">
                        <i class="fa fa-user text-dark"></i> Author: {{ $exam->user->getFullName() }}
                    </div>
                <div class="mr-2">
                        <i class="fa fa-file-alt text-dark"></i> Description: {{ $exam->description }}
                    </div>
                
            </div>
        </div>

        
        <div class="mt-3 mb-3 d-flex justify-content-between align-items-baseline">
            <div>
                <h5>List of Test Questions <i class="fa fa-list text-info"></i></h5>
            </div>   
            <div>
                <a href="{{ url('/exams/'. $exam->id .'/preview?program_id='. request('program_id') .'&student_outcome_id=' . request('student_outcome_id'). '&curriculum_id='. request('curriculum_id')) }}" class="btn btn-info btn-sm">Preview <i class="fa fa-external-link-alt"></i></a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                
                <div class="row">
                <div class="col-md-3">
                  <div class="input-group mb-3">
                    
                    <input type="search" v-on:input="searchTestQuestion" v-model="searchText" class="form-control" placeholder="Search test question...">

                    <div class="input-group-append">
                      <button class="input-group-text btn btn-primary"><i class="fa fa-search"></i></button>
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
{{--                     <div class="d-flex">
                      <div class="mr-2"><label class="col-form-label">Filter By Creator: </label></div>
                      <div>
                        <select class="form-control" v-model="user_id" v-on:change="filterByCreator">
                          <option value="">All</option>
                          <option v-for="creator in creators" :key="creator.id" :value="creator.id">@{{ creator.first_name + ' ' + creator.last_name }}</option>
                        </select>
                      </div>
                    </div> --}}
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
                          <a class="page-link" href="#" v-on:click="paginate(current_page - 1)">Previous</a>
                        </li>

                        <li :class="{'active': num == current_page }" v-for="num in paginationCount" :key="num" class="page-item"><a v-on:click="paginate(num)" class="page-link" href="#">@{{ num }}</a></li>
                        <li class="page-item" :class="{'disabled': current_page == paginationCount}">
                          <a class="page-link" href="#" v-on:click="paginate(current_page + 1)">Next</a>
                        </li>
                      </ul>
                    </nav>
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
                previewBody: ''
            },
            computed: {
                paginationCount() {
                    return Math.ceil(this.show_test_questions.length / 10);
                }
            },
            methods: {
                getDifficulty(difficulty_id) {
                    if (difficulty_id == 1) {
                        return 'Easy';
                    } else if (difficulty_id == 2) {
                        return 'Average';
                    } else if (difficulty_id == 3) {
                        return 'Difficult'
                    }
                },
                searchTestQuestion() {
                    

                    this.tableLoading = true;

                    setTimeout(() => {
                        this.tableLoading = false;
                        if(this.searchText == '') {
                            return this.filterByCourse();
                        }

                        this.show_test_questions = this.test_questions.filter(test_question => {
                            var reg_exp = new RegExp(this.searchText,'i');
                            return (
                            test_question.title.search(reg_exp) > -1 );
                        });

                        this.paginate(1);
                    },400);
                },  
                filterByCourse() {
                    this.tableLoading = true;

                    setTimeout(() => {
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
                        
                    },400);
                    
                },
                filterByDifficulty() {
                    this.tableLoading = true;

                    setTimeout(() => {
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
                        
                    },400);
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
                }
            },
            created() {
                this.filter_course_id = this.courses[0].id;
                this.filterByCourse();

                setInterval(() => {
                    MathLive.renderMathInDocument();
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

