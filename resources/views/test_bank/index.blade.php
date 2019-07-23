@extends('layout.app', ['active' => 'test_questions'])

@section('title', 'Test Bank')

@section('content')
<div id="app" v-cloak>
    <div class="card p-3 px-4 mb-3">
    

        <div class="d-flex justify-content-between mb-3">
          <div>
            <h1 class="page-header">Test Bank</h1>
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

        <div class="mx-auto" style="width: 400px">
          <img src="{{ asset('svg/database.svg') }}" class="w-100">
        </div>
        
        <template v-if="selected_student_outcome != ''">
            <div class="select-student-outcome d-flex align-items-center mt-4 justify-content-between" v-on:click="toggleDropDown">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <div class="avatar-student-outcome bg-success">@{{ selected_student_outcome.so_code }}</div>
                    </div>
                    <div>
                        @{{ selected_student_outcome.description }}
                    </div>
                </div>
                <div><i class="fa fa-caret-down"></i></div>
            </div>
        </template>
        <div v-else style="font-size: 18px;" class="text-center mt-4">
            No Student Outcome
        </div>

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
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Test Questions</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Exams</a>
          </li>

        </ul>
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
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
                                            <div class="avatar" :style="{ 'background': course_mapped.color }"><i class="fa fa-book"></i></div>
                                        </div>
                                        <div>
                                            <div style="font-size: 16px">@{{ course_mapped.course_code }} - @{{ course_mapped.description }}</div>
                                            <div class="text-muted">@{{ course_mapped.test_question_count }} questions</div>
                                        </div>   
                                    </div>
                                    <div>
                                        <button class="btn btn-info btn-sm">Select</button>
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
          <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
              <div class="p-3">
                    <h5 class="text-dark">List of Courses <i class="fa fa-book text-info"></i></h5>
                    <ul class="list-group list-student-outcomes">
                        <li class="list-group-item">Cras justo odio</li>
                        <li class="list-group-item">Dapibus ac facilisis in</li>
                        <li class="list-group-item">Morbi leo risus</li>
                        <li class="list-group-item">Porta ac consectetur ac</li>
                        <li class="list-group-item">Vestibulum at eros</li>
                    </ul>
                </div>
          </div>
        </div>
    </div>

    <div class="mt-3 card">

        {{-- <div class="card-body pt-4">
            <h5 class="text-dark">List of Courses <i class="fa fa-book text-info"></i></h5>
            <ul class="list-group">
                <li class="list-group-item">Cras justo odio</li>
                <li class="list-group-item">Dapibus ac facilisis in</li>
                <li class="list-group-item">Morbi leo risus</li>
                <li class="list-group-item">Porta ac consectetur ac</li>
                <li class="list-group-item">Vestibulum at eros</li>
            </ul>
        </div> --}}

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
                isLoading: false
            },
            methods: {
                getStudentOutcomes() {
                    this.isLoading = true;
                    ApiClient.get("/test_bank/" + this.program_id + "/get_student_outcomes")
                    .then(response => {
                        this.isLoading = false;
                        this.student_outcomes = response.data;
                        if(this.student_outcomes.length > 0) {
                            this.selected_student_outcome = this.student_outcomes[0];
                            this.getCoursesMapped();
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
                },
                getCoursesMapped() {
                    this.isLoading = true;
                    ApiClient.get("test_bank/get_curriculum_courses_mapped/" + this.selected_student_outcome.id)
                    .then(response => {
                        this.isLoading = false;
                        this.courses_mapped = response.data;
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