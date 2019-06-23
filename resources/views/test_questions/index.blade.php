@extends('layout.app', ['active' => 'test_questions'])

@section('title', 'Test Questions Index')

@section('content')
    
    <a href="{{ url('/test_bank/' . request('program_id') . '/list_student_outcomes') }}" class="text-success"><i class="fa fa-arrow-left"></i> Back</a>
    
    <div class="d-flex justify-content-between mb-3 mt-3">
        <div>
          <h1 class="page-header">Test Questions &mdash; {{ $student_outcome->so_code }} & {{ $course->course_code . ' - ' . $course->description }}</h1>
        </div>
        <div>
          @if(Gate::check('isDean') || Gate::check('isSAdmin'))
            {{-- <student-outcome-modal :programs='@json($programs)' :program-id="{{ $program->id }}"></student-outcome-modal> --}}
            <a href="{{ url('/test_questions/create?student_outcome_id='. request('student_outcome_id') . '&course_id=' . request('course_id') . '&program_id=' . request('program_id')) }}" class="btn btn-success-b">Add new Test Question</a>
          @endif
        </div>
    </div>

    <div id="app" class="card">

        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                  <div class="input-group mb-3">
                    
                    <input v-on:input="searchTestQuestion" v-model="search" type="search" class="form-control" placeholder="Search test question...">

                    <div class="input-group-append">
                      <button class="input-group-text btn btn-primary"><i class="fa fa-search"></i></button>
                    </div>
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="d-flex justify-content-end">
                    <div class="d-flex mr-2">
                      <div class="mr-2"><label class="col-form-label">Filter By Difficulty: </label></div>
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
                      <div class="mr-2"><label class="col-form-label">Filter By Creator: </label></div>
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
              <table id="students-table" class="table">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Title</th>
                    <th scope="col">Level</th>
                    <th scope="col">Choices</th>
                    <th scope="col">Created By</th>
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
                  {{-- @if ($programs->count() <= 0)
                    <tr>
                      <td class="text-center" colspan="6">No Record Found in Database.</td>
                    </tr>
                  @else
                    @foreach ($programs as $program)
                    <tr>
                        <td>
                          <div class="avatar mr-2" style="background: {{ $program->color  }}">
                            {{ substr($program->program_code, 0 , 2) == 'BS' ? substr($program->program_code, 2) :  $program->program_code }}
                          </div>
                        </td>
                        <td>{{ $program->id }}</td>
                        <td>{{ $program->program_code }}</td>
                        <td>{{ $program->description }}</td>
                        <td>{{ $program->college->college_code }}</td>
                        <td>
                          <a title="View Details" class="btn btn-primary btn-sm" href="{{ url('/programs/' . $program->id) }}">
                            <i class="fa fa-eye"></i>
                          </a>
                        </td>
                    </tr>
                    @endforeach
                  @endif --}}
                                           
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
    </div>
    
@endsection



@push('scripts')
    
    <script>
        var vm = new Vue({
            el: '#app',
            data: {
                test_questions: [],
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
                creators: []
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
                    return moment(date).format('MMM DD YYYY, h:mm a');
                },
                paginate(page) {
                  this.getTestQuestions(page);
                },
                filterByCreator() {
                    this.getTestQuestions();
                },
                filterByDifficulty() {
                    this.getTestQuestions();
                }
            },
            created() {
                setTimeout(() => {
                    this.getTestQuestions();
                    this.getCreators();
                }, 1000);
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