@extends('layout.app', ['active' => 'test_questions'])

@section('title', 'Test Bank')

@section('content')
<div id="app" v-cloak>
    <div class="card p-4 px-4 mb-3">
        {{-- <div class="mx-auto" style="width: 400px">
          <img src="{{ asset('svg/updates.svg') }}" class="w-100">
        </div> --}}

        <div class="d-flex justify-content-between mb-1">

          <div>
            <h1 class="page-header"><i class="fa fa-database" style="color: #a1a1a1"></i> Test Bank</h1>
          </div>
          <div class="d-flex align-items-baseline">
            <div>
                <label class="text-dark">Select Program</label>
            </div>
            <div class="ml-2">
                <select v-on:change="getStudentOutcomes" v-model="program_id" class="my-select">
                    @foreach($programs as $program)
                        <option value="{{ $program->id }}">{{ $program->program_code }}</option>
                    @endforeach
                </select>
                
            </div>
          </div>
        </div>

        <template v-if="!loadingStudentOutcomes">
            <template v-if="selected_student_outcome != ''">
                <div class="select-student-outcome d-flex align-items-center mt-1 justify-content-between" v-on:click="toggleDropDown">
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <div class="avatar-student-outcome bg-success">@{{ selected_student_outcome.so_code }}</div>
                        </div>
                        <div style="font-weight: 400">
                            @{{ selected_student_outcome.description }}
                        </div>
                    </div>
                    <div><i class="fa fa-caret-down"></i></div>
                </div>
            </template>
            <div v-else style="font-size: 18px;" class="text-center mt-4">
                No Student Outcome
            </div>
        </template>
        <template v-else>
            <div class="d-flex text-muted">
                <div class="spinner-grow text-dark text-center ml-2" role="status">
                  <span class="sr-only">Loading...</span>
                </div> 
            </div>
            
        </template>

        <ul v-show="showDropDown" class="list-group list-dropdown-so mt-1">
          <li v-on:click="selectStudentOutcome(student_outcome)" v-for="student_outcome in student_outcomes" :key="student_outcome.id" class="list-group-item d-flex align-items-center">
            <div class="mr-3">
                <div class="avatar-student-outcome bg-success">@{{ student_outcome.so_code }}</div>
            </div>
            <div>
                @{{ student_outcome.description }}
            </div>
          </li>
        </ul>
    </div>
    
    <div id="main-nav-tabs">
        <div class="d-flex justify-content-end">
            <div class="d-flex align-items-baseline">
                <div class="mr-2">  
                   <div class="text-dark" style="font-weight: 600">Curriculum:</div>
                </div>
                <select v-on:change="getCoursesMapped" class="form-control" v-model="selected_curriculum_id">
                    <option v-for="curriculum in curricula"  :key="curriculum.id" :value="curriculum.id">@{{ curriculum.name + ' ' + curriculum.year}}  &mdash; v@{{ curriculum.revision_no }}.0</option>
                </select>
            </div>
        </div>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#test-questions" role="tab" aria-selected="true">Test Questions</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#exams" role="tab" aria-controls="profile" aria-selected="false">Exams</a>
          </li>

        </ul>
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade show active" id="test-questions" role="tabpanel" aria-labelledby="home-tab">
            <div class="p-3">
                <h5 class="text-dark mb-3"><i class="fa fa-book text-info"></i> List of Courses </h5>
                <div v-if="isLoading" class="bg-white p-3">
                    <table-loading></table-loading>
                </div>
                <div v-else>
                    <div class="" v-if="courses_mapped.length > 0">
                        <ul class="list-group">
                            <li v-for="course_mapped in courses_mapped" :key="course_mapped.id" class="list-group-item">
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <div class="d-flex">
                                        <div class="mr-3">
                                            <div class="avatar" style="color:white; background: #4caf50;"><i class="fa fa-book"></i></div>
                                        </div>
                                        <div>
                                            <div style="font-size: 16px">@{{ course_mapped.course_code }} - @{{ course_mapped.description }} <i v-if="checkIfAssigned(course_mapped.id)" class="fa fa-check-circle text-success"></i></div>
                                            <div class="text-muted">@{{ course_mapped.test_question_count }} questions</div>
                                        </div>   
                                    </div>
                                    <div>
                                        <a v-if="checkIfAssigned(course_mapped.id)" :href="'/pnc_soa/public/test_questions?student_outcome_id=' + selected_student_outcome.id + '&course_id=' + course_mapped.id  + '&program_id=' + selected_student_outcome.program_id" class="btn btn-info btn-sm">Select <i class="fa fa-angle-right"></i></a>
                                        <button v-else class="btn btn-secondary btn-sm" disabled>Select <i class="fa fa-angle-right"></i></button>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        {{-- <div class="card mr-4 mb-4 shadow courses-test-bank" v-for="course_mapped in courses_mapped" :key="course_mapped.id">
                            <div class="card-body pt-3" >
                                <div class="d-flex align-items-baseline">
                                    <div class="mr-3">
                                        <div class="avatar" style="color:white; background: #4caf50;"><i class="fa fa-book"></i></div>
                                    </div>
                                    <div>
                                        <h5>@{{ course_mapped.course_code }}</h5>
                                        <div class="text-info">@{{ course_mapped.description }}</div>
                                    </div>    
                                </div>  
                                
                                
                                <div class="ml-1 mt-2 d-flex align-items-baseline">
                                    <div class="mr-1">
                                        <div class="avatar" style="background: #cbff90; color: #4caf50;"><i class="fa fa-question" ></i></div>
                                    </div> 
                                    <div style="font-weight: 600;">
                                        <span class="text-warning">@{{ course_mapped.test_question_count }}</span> test questions
                                    </div>
                                </div>                   
                            </div>
                            <div class="card-footer">
                                <div class="d-flex justify-content-end">
                                    <a :href="'/pnc_soa/public/test_questions?student_outcome_id=' + selected_student_outcome.id + '&course_id=' + course_mapped.id  + '&program_id=' + selected_student_outcome.program_id" class="btn btn-info btn-sm">Select <i class="fa fa-angle-right"></i></a>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                    <div v-else class="bg-white p-3">
                        No Courses Mapped.
                    </div>
                </div>
            </div>
          </div>
          <div class="tab-pane fade" id="exams" role="tabpanel">
              <div class="p-3">
                    {{-- <h5 class="text-dark"><i class="fa fa-file-alt text-info"></i> List of Curriculum </h5> --}}
                    <div v-if="isLoading" class="bg-white p-3">
                        <table-loading></table-loading>
                    </div>
                    <div v-else>
                        <div v-if="curricula.length > 0">
                            <ul class="list-group">
                                <div v-for="curriculum in curricula" :key="curriculum.id">
                                    <li v-if="curriculum.id == selected_curriculum_id" class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-baseline">
                                            <div class="d-flex">
                                                <div class="mr-3">
                                                    <div class="avatar bg-success"><i class="fa fa-book-open"></i></div>
                                                </div>
                                                <div>
                                                    <div style="font-size: 16px">@{{ curriculum.program.program_code }} - @{{ curriculum.name }}</div>
                                                    <div class="text-warning" style="font-size: 14px">Revision no. @{{ curriculum.revision_no }}.0</div>
                                                    <div class="text-muted">@{{ curriculum.exam_count }} exams</div>
                                                </div>   
                                            </div>
                                            <div>
                                                <a :href="'/pnc_soa/public/exams?student_outcome_id=' + selected_student_outcome.id + '&program_id=' + selected_student_outcome.program_id + '&curriculum_id=' + curriculum.id" class="btn btn-sm btn-info">View Exams</a>
                                            </div>
                                        </div>
                                    </li>
                                </div>
                            </ul>
                        </div>

                        <div v-else class="bg-white p-3">
                            No Curriculum found.
                        </div>
                    </div>
                </div>
          </div>
        </div>
    </div>

    <div class="mt-3 card">


    </div>
</div>
@endsection

@push('scripts')
    <script>
        var vm = new Vue({
            el: '#app',
            data: {
                programs: @json($programs),
                program_id: '',
                student_outcomes: [],
                showDropDown: false,
                selected_student_outcome: '',
                courses_mapped: [],
                isLoading: false,
                loadingStudentOutcomes: false,
                curricula: [],
                selected_curriculum_id: '',
                user: @json(Auth::user()),

                course_loads: []
            },
            methods: {
                getStudentOutcomes() {
                    this.isLoading = true;
                    this.loadingStudentOutcomes = true;
                    ApiClient.get("/test_bank/" + this.program_id + "/get_student_outcomes")
                    .then(response => {
                        this.isLoading = false;
                        this.loadingStudentOutcomes = false;
                        this.student_outcomes = response.data;
                        if(this.student_outcomes.length > 0) {
                            this.selected_student_outcome = this.student_outcomes[0];
                            // this.getCoursesMapped();
                            this.getCurricula();
                        } else {
                            this.selected_student_outcome = '';
                            this.courses_mapped = [];
                        }
                    })
                },
                toggleDropDown() {
                    this.showDropDown = !this.showDropDown;
                },
                selectStudentOutcome(student_outcome) {
                    this.toggleDropDown();
                    this.selected_student_outcome = student_outcome;
                    this.getCoursesMapped();
                    this.getCurricula();
                },
                getCoursesMapped() {
                    this.isLoading = true;
                    ApiClient.get("test_bank/get_curriculum_courses_mapped/" + this.selected_student_outcome.id + '?curriculum_id=' + this.selected_curriculum_id)
                    .then(response => {
                        this.isLoading = false;
                        this.courses_mapped = response.data;
                    })
                    .catch(err => {
                        alert("An Error has occured. Please try again");
                        this.isLoading = false;
                    })
                },
                getCurricula() {
                    this.isLoading = true;

                    ApiClient.get("test_bank/" + this.program_id + "/get_curricula?student_outcome_id=" + this.selected_student_outcome.id)
                    .then(response => {
                        this.curricula = response.data;

                        if(this.curricula.length > 0) {

                            this.selected_curriculum_id = this.curricula[0].id;
                            this.getCoursesMapped();
                        }
                        this.isLoading = false;
                    })
                    .catch(err => {
                        alert("An Error has occured. Please try again");
                        this.isLoading = false;
                    })
                },
                getCourseLoad() {
                    ApiClient.get('/faculty_courses?user_id=' + this.user.id)
                    .then(response => {
                        this.course_loads = response.data;
                    })
                },
                checkIfAssigned(course_id) {
                    if(this.user.user_type_id != 'prof') {
                        return true;
                    }

                    for(var i = 0; i < this.course_loads.length; i++) {
                        if(this.course_loads[i].course_id == course_id) {
                            return true;
                        }
                    }

                    return false;
                }
            },
            created() {
                if(this.programs.length > 0) {
                    this.program_id = this.programs[0].id;
                    this.getStudentOutcomes();
                }

                if(this.user.user_type_id == 'prof') {
                    this.getCourseLoad();
                }
                
            }
        });
    </script>
@endpush