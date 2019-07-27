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
               Fetching student outcomes...
                <div class="spinner-grow text-dark ml-2" role="status">
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
                <h5 class="text-dark">List of Courses <i class="fa fa-book text-info"></i></h5>
                <div v-if="isLoading" class="bg-white p-3">
                    <table-loading></table-loading>
                </div>
                <div v-else>
                    <div v-if="courses_mapped.length > 0">
                        <ul class="list-group">
                            <li v-for="course_mapped in courses_mapped" :key="course_mapped.id" class="list-group-item">
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <div class="d-flex">
                                        <div class="mr-3">
                                            <div class="avatar new-green-bg" style="color:#858585"><i class="fa fa-book"></i></div>
                                        </div>
                                        <div>
                                            <div style="font-size: 16px">@{{ course_mapped.course_code }} - @{{ course_mapped.description }}</div>
                                            <div class="text-muted">@{{ course_mapped.test_question_count }} questions</div>
                                        </div>   
                                    </div>
                                    <div>
                                        <a :href="'/pnc_soa/public/test_questions?student_outcome_id=' + selected_student_outcome.id + '&course_id=' + course_mapped.id  + '&program_id=' + selected_student_outcome.program_id" class="btn btn-info btn-sm">Select</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div v-else class="bg-white p-3">
                        No Courses Mapped.
                    </div>
                </div>
            </div>
          </div>
          <div class="tab-pane fade" id="exams" role="tabpanel">
              <div class="p-3">
                    <h5 class="text-dark">List of Curriculum <i class="fa fa-file-alt text-info"></i></h5>
                    <div v-if="isLoading" class="bg-white p-3">
                        <table-loading></table-loading>
                    </div>
                    <div v-else>
                        <div v-if="curricula.length > 0">
                            <ul class="list-group">
                                <li v-for="curriculum in curricula" :key="curriculum.id" class="list-group-item">
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
                                            <a :href="'/pnc_soa/public/exams?student_outcome_id=' + selected_student_outcome.id + '&program_id=' + selected_student_outcome.program_id + '&curriculum_id=' + curriculum.id" class="btn btn-sm btn-info">Select</a>
                                        </div>
                                    </div>
                                </li>
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
                curricula: []
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
                            this.getCoursesMapped();
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
                    ApiClient.get("test_bank/get_curriculum_courses_mapped/" + this.selected_student_outcome.id)
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
                        this.isLoading = false;
                    })
                    .catch(err => {
                        alert("An Error has occured. Please try again");
                        this.isLoading = false;
                    })
                }
            },
            created() {
                if(this.programs.length > 0) {
                    this.program_id = this.programs[0].id;
                    this.getStudentOutcomes();
                }
                
            }
        });
    </script>
@endpush