@extends('layout.app', ['active' => 'test_questions'])

@section('title', 'Test Questions Index')

@section('content')
    
    
<div id="app">    
    <div class="card p-3 px-4 mb-3">
      {{-- <a href="{{ url('/test_bank/' . request('program_id') . '/list_student_outcomes') }}" class="text-success"><i class="fa fa-arrow-left"></i> Back</a> --}}
      <a href="{{ url('/test_bank?program_id=' . request('program_id') ) }}" class="text-success"><i class="fa fa-arrow-left"></i> Back</a>
        <div class="mx-auto" style="width: 400px">
          <img src="{{ asset('svg/questions1.svg') }}" class="w-100">
        </div>
        <div class="d-flex justify-content-between mt-3">
            <div>
              <h1 class="page-header mb-3">Test Questions</h1>
            </div>
            <div>
              @if(Gate::check('isDean') || Gate::check('isSAdmin'))
                {{-- <student-outcome-modal :programs='@json($programs)' :program-id="{{ $program->id }}"></student-outcome-modal> --}}
                <a href="{{ url('/test_questions/create?student_outcome_id='. request('student_outcome_id') . '&course_id=' . request('course_id') . '&program_id=' . request('program_id')) }}" class="btn btn-success-b">Add new Test Question</a>
              @endif
            </div>
        </div>

        <div class="d-flex mb-3 flex-wrap">

            <div class="mr-3"><label>Program: </label> <span class="text-info fs-19">{{ $student_outcome->program->program_code }}</span></div>
            <div class="mr-3"><label>Student Outcome: </label> <span  class="text-info fs-19">{{ $student_outcome->so_code }}</span></div>
            <div class="mr-3"><label>Course: </label> <span class="text-info fs-19">{{ $course->course_code . ' - ' . $course->description }}</span></div>
        </div>
        
        <div>
          <label>Easy: </label> <span class="text-success">{{ $easy_count }}</span> |
          <label>Average: </label> <span class="text-success">{{ $average_count }}</span> |
          <label>Difficult: </label> <span class="text-success">{{ $difficult_count }}</span> 
        </div>
        <div class="ml-2">
          <donut-chart :width="80" :height="100" :data="donut_data" :text-center="totalTestQuestions"></donut-chart>
        </div>

    </div>
    
    <div id="main-nav-tabs">
      <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" id="home-tab" data-toggle="tab" href="#test-questions" role="tab" aria-controls="home" aria-selected="true">Test Questions</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="profile-tab" data-toggle="tab" href="#archive" role="tab" aria-controls="profile" aria-selected="false">Archive</a>
        </li>
      </ul>
      <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="test-questions" role="tabpanel">
          <div class="row mt-3">
            <div class="col-md-4">
              <div class="input-group mb-3" id="search-input-darker">
                
                <input v-on:input="searchTestQuestion" v-model="search" type="search" class="form-control" placeholder="Search test question...">

                <div class="input-group-append">
                  <span class="input-group-text btn btn-primary"><i class="fa fa-search"></i></span>
                </div>
              </div>
            </div>
            <div class="col-md-8">
              <div class="d-flex justify-content-end">
                <div class="d-flex mr-2">
                  <div class="mr-2"><label class="col-form-label text-dark">
                    <i class="text-success fa fa-layer-group"></i> Filter By Level: </label></div>
                  <div>
                    <select class="form-control" v-model="difficulty_id" v-on:change="filterByDifficulty">
                      <option value="">All</option>
                      <option value="1">Easy</option>
                      <option value="2">Average</option>
                      <option value="3">Difficult</option>
                    </select>
                  </div>
                </div>
                <div class="d-flex">
                  <div class="mr-2"><label class="col-form-label text-dark"><i class="text-success fa fa-user"> </i> Filter By Author: </label></div>
                  <div>
                    <select class="form-control" v-model="user_id" v-on:change="filterByCreator">
                      <option value="">All</option>
                      <option v-for="creator in creators" :key="creator.id" :value="creator.id">@{{ creator.first_name + ' ' + creator.last_name }}</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>      
        </div>
          <template v-if="tableLoading">
            <div class="bg-white p-3">
              <table-loading></table-loading>
            </div>
          </template>
          <template v-else-if="test_questions.length <= 0">
            <div class="bg-white p-3">
              No Record Found in Database.
            </div>
          </template>
          <template v-else>
            <ul class="list-group">
              <li v-for="test_question in test_questions" :key="test_question.id" class="list-group-item">
                    <div class="d-flex justify-content-between align-items-baseline">
                        <div class="d-flex">
                            <div class="mr-3">
                                <div class="avatar bg-white" ><i class="fa fa-question-circle" :style="avatarStyle(test_question.difficulty_level_id)"></i></div>
                            </div>
                            <div>
                                <div style="font-size: 18px"><span style="font-weight: 600">#@{{ test_question.id }}</span> - @{{ test_question.title }}</div>

                                <div class="text-muted">@{{ getDifficulty(test_question.difficulty_level_id) }} - @{{ test_question.choices.length }} choices </div>
                                <div style="font-size: 13px" class="text-muted mt-1">
                                  <i class="fa fa-user"></i> @{{ test_question.user.first_name + ' ' + test_question.user.last_name }} | @{{ parseDate(test_question.created_at) }}
                                </div>
                            </div>   
                        </div>
                        <div>
                            <td>
                                <div class="dropdown dropleft">
                                  <button class="btn btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-ellipsis-v"></i>
                                  </button>
                                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" v-on:click="getPreview(test_question.id)" href="#" data-toggle="modal" data-target="#previewModal"><i class="fa fa-eye"></i> Preview </a>
                                    <a :href="'test_questions/' + test_question.id + '?student_outcome_id=' + student_outcome_id + '&course_id=' + course_id + '&program_id=' + program_id" class="dropdown-item"><i class="fa fa-search"></i> View Details</a>
                                    <a :href="'test_questions/' + test_question.id + '/edit?student_outcome_id=' + student_outcome_id + '&course_id=' + course_id + '&program_id=' + program_id" class="dropdown-item"><i class="fa fa-edit"></i> Update</a>
                                  </div>
                                </div>
                            </td>                          
                        </div>
                    </div>
                </li>
            </ul>
          </template>
          <nav v-show="search.trim() == ''" class="mt-3">
            <ul class="pagination justify-content-end">
              <li class="page-item" :class="{ disabled: meta.current_page == 1 }">
                <a class="page-link" href="#" v-on:click.prevent="paginate(meta.current_page - 1)">Previous</a>
              </li>
              <template v-for="num in totalPagination">
                <li class="page-item" :class="{ active : num == meta.current_page }">
                  <a class="page-link" href="#" v-on:click.prevent="paginate(num)">@{{ num }}</a>
                </li>
              </template>
              <li class="page-item" :class="{ disabled: meta.current_page == meta.last_page }">
                <a class="page-link" href="#" v-on:click.prevent="paginate(meta.current_page + 1)">Next</a>
              </li>
            </ul>
          </nav>
        </div>
        <div class="tab-pane fade" id="archive" role="tabpanel">
          {{-- deactivated test questions --}}
          <h5 class="mt-3">List of deactivated test questions </h5>
          <template v-if="deactivated_test_questions.length <= 0">
            <div class="bg-white p-3 text-center text-muted">
              No Archived Test Questions
            </div>
          </template>
          <template v-else>

            <ul class="list-group mt-3">
                <li v-for="test_question in deactivated_test_questions" :key="test_question.id" class="list-group-item">
                      <div class="d-flex justify-content-between align-items-baseline">
                          <div class="d-flex">
                              <div class="mr-3">
                                  <div class="avatar bg-white" ><i class="fa fa-question-circle" :style="avatarStyle(test_question.difficulty_level_id)"></i></div>
                              </div>
                              <div>
                                  <div style="font-size: 18px"><span style="font-weight: 600">#@{{ test_question.id }}</span> - @{{ test_question.title }}</div>

                                  <div class="text-muted">@{{ getDifficulty(test_question.difficulty_level_id) }} - @{{ test_question.choices.length }} choices </div>
                                  <div style="font-size: 13px" class="text-muted mt-1">
                                    <i class="fa fa-user"></i> Jasper Diongco | Jul 23, 2019
                                  </div>
                              </div>   
                          </div>
                          <div>
                              <td>
                                  <div class="dropdown dropleft">
                                    <button class="btn btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      <i class="fa fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                      <a class="dropdown-item" v-on:click="getPreview(test_question.id)" href="#" data-toggle="modal" data-target="#previewModal"><i class="fa fa-eye"></i> Preview </a>
                                      <a :href="'test_questions/' + test_question.id + '?student_outcome_id=' + student_outcome_id + '&course_id=' + course_id + '&program_id=' + program_id" class="dropdown-item"><i class="fa fa-search"></i> View Details</a>
                                      <a :href="'test_questions/' + test_question.id + '/edit?student_outcome_id=' + student_outcome_id + '&course_id=' + course_id + '&program_id=' + program_id" class="dropdown-item"><i class="fa fa-edit"></i> Update</a>
                                    </div>
                                  </div>
                              </td>                          
                          </div>
                      </div>
                  </li>
              </ul>
            </template>
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

    {{-- <div  class="card">

        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                  <div class="input-group mb-3" id="search-input">
                    
                    <input v-on:input="searchTestQuestion" v-model="search" type="search" class="form-control" placeholder="Search test question...">

                    <div class="input-group-append">
                      <span class="input-group-text btn btn-primary"><i class="fa fa-search"></i></span>
                    </div>
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="d-flex justify-content-end">
                    <div class="d-flex mr-2">
                      <div class="mr-2"><label class="col-form-label text-dark">
                        <i class="text-success fa fa-layer-group"></i> Filter By Level: </label></div>
                      <div>
                        <select class="form-control" v-model="difficulty_id" v-on:change="filterByDifficulty">
                          <option value="">All</option>
                          <option value="1">Easy</option>
                          <option value="2">Average</option>
                          <option value="3">Difficult</option>
                        </select>
                      </div>
                    </div>
                    <div class="d-flex">
                      <div class="mr-2"><label class="col-form-label text-dark"><i class="text-success fa fa-user"> </i> Filter By Author: </label></div>
                      <div>
                        <select class="form-control" v-model="user_id" v-on:change="filterByCreator">
                          <option value="">All</option>
                          <option v-for="creator in creators" :key="creator.id" :value="creator.id">@{{ creator.first_name + ' ' + creator.last_name }}</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>      
            </div>
            
            
            <div class="table-responsive">
              <table id="students-table" class="table table-borderless">
                <thead>
                  <tr>
                    <th scope="col">ID</th>
                    <th scope="col" width="35%">Title</th>
                    <th scope="col">Level</th>
                    <th scope="col">Choices</th>
                    <th scope="col">Author</th>
                    <th scope="col">Created At</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                    <template v-if="tableLoading">
                      <tr>
                        <td colspan="7"><table-loading></table-loading></td>
                      </tr>
                    </template>
                    <template v-else-if="test_questions.length <= 0">
                      <tr>
                        <td class="text-center" colspan="7">No Record Found in Database.</td>
                      </tr>
                    </template>
                    <template v-else>
                        <tr v-for="test_question in test_questions">
                            <td>@{{ test_question.id }}</td>
                            <td>@{{ test_question.title }}</td>
                            <td>@{{ test_question.difficulty_level_desc }}</td>
                            <td>@{{ test_question.choices_count }}</td>
                            <td>@{{ test_question.user.first_name + ' ' + test_question.user.last_name }}</td>
                            <td>@{{ parseDate(test_question.created_at) }}</td>
                            <td>
                                <a :href="'test_questions/' + test_question.id + '?student_outcome_id=' + student_outcome_id + '&course_id=' + course_id + '&program_id=' + program_id" class="btn btn-light btn-sm"><i class="fa fa-search"></i></a>
                                <a :href="'test_questions/' + test_question.id + '/edit?student_outcome_id=' + student_outcome_id + '&course_id=' + course_id + '&program_id=' + program_id" class="btn btn-success btn-sm"><i class="fa fa-edit"></i></a>
                            </td>
                        </tr>
                    </template>
                                           
                </tbody>
              </table>

              <nav v-show="search.trim() == ''">
                <ul class="pagination justify-content-end">
                  <li class="page-item" :class="{ disabled: meta.current_page == 1 }">
                    <a class="page-link" href="#" v-on:click.prevent="paginate(meta.current_page - 1)">Previous</a>
                  </li>
                  <template v-for="num in totalPagination">
                    <li class="page-item" :class="{ active : num == meta.current_page }">
                      <a class="page-link" href="#" v-on:click.prevent="paginate(num)">@{{ num }}</a>
                    </li>
                  </template>
                  <li class="page-item" :class="{ disabled: meta.current_page == meta.last_page }">
                    <a class="page-link" href="#" v-on:click.prevent="paginate(meta.current_page + 1)">Next</a>
                  </li>
                </ul>
              </nav>
            </div>
        </div>
    </div> --}}
</div>    
@endsection



@push('scripts')
    
    <script>
        var vm = new Vue({
            el: '#app',
            data: {
                test_questions: [],
                deactivated_test_questions: @json($deactivated_test_questions),
                course_id: '{{ request('course_id') }}',
                student_outcome_id: '{{ request('student_outcome_id') }}',
                tableLoading: true,
                search: '',
                meta: {
                  total: 0,
                  per_page: 0,
                  current_page: 1
                },
                links: {},
                totalPagination: 0,
                user_id: '',
                difficulty_id: '',
                program_id: '{{ request('program_id') }}',
                creators: [],
                totalTestQuestions: {{ $easy_count + $average_count + $difficult_count }},
                donut_data: {
                    datasets: [
                        {
                            data: [{{ $easy_count }}, {{ $average_count }}, {{ $difficult_count }}],
                            backgroundColor: ["#cbff90", "#fff375", "#d7aefb"],
                            label: "Difficulties"
                        }
                    ],
                    labels: ["Easy", "Average", "Difficult"]
                },
                previewLoading: false,
                previewBody: ''
            },
            methods: {
                getTestQuestions(page=1) {
                    this.tableLoading = true;
                    ApiClient.get('/test_questions?student_outcome_id=' + this.student_outcome_id + '&course_id=' + this.course_id + '&json=true' + '&page=' + page + '&user_id=' + vm.user_id + '&difficulty_id=' + vm.difficulty_id)
                        .then(response => {
                            this.test_questions = response.data.data;
                            this.meta = response.data.meta;
                            this.links = response.data.links;
                            this.totalPagination = Math.ceil(this.meta.total / this.meta.per_page);
                            this.tableLoading = false;
                        })
                        .catch(err => {
                            console.log(err);
                            this.tableLoading = false;
                        })
                },
                getCreators() {
                    ApiClient.get('/test_questions/get_creators?student_outcome_id=' + this.student_outcome_id + '&course_id=' + this.course_id)
                        .then(response => {
                            this.creators = response.data;
                        })
                },
                searchTestQuestion: _.debounce(() => {
                    vm.tableLoading = true;
                    if(vm.search.trim() == '') {
                      return vm.getTestQuestions();
                    }
                    ApiClient.get('/test_questions?student_outcome_id=' + vm.student_outcome_id + '&course_id=' + vm.course_id + '&json=true' + '&q=' + vm.search)
                    .then(response => {
                      vm.test_questions = response.data.data;
                      vm.tableLoading = false;
                    });
                }, 300),
                parseDate(date) {
                    return moment(date).format('MMM DD YYYY');
                },
                paginate(page) {
                  this.getTestQuestions(page);
                },
                filterByCreator() {
                    this.getTestQuestions();
                },
                filterByDifficulty() {
                    this.getTestQuestions();
                },
                avatarStyle(difficulty_level_id) {
                    var color = '';
                    if(difficulty_level_id == 1) {
                        color = '#cbff90';
                    } else if (difficulty_level_id == 2) {
                        color = '#fff375';
                    } else if (difficulty_level_id == 3) {
                        color = '#f28b82';
                    }
                    

                    return {
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
                setTimeout(() => {
                    this.getTestQuestions();
                    this.getCreators();
                }, 1000);
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