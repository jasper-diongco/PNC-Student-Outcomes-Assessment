@extends('layout.app', ['active' => 'test_questions'])

@section('title', 'Exams')

@section('content')
    
    <div id="app" v-cloak>
        
    
        {{-- <a href="{{ url('test_bank/'. request('program_id') .'/list_student_outcomes') }}" class="text-success"><i class="fa fa-arrow-left"></i> Back</a> --}}
        <add-exam-modal v-on:new-exam-added="getExams" :curriculum-id="curriculum_id" :student-outcome-id="student_outcome_id" :courses="requirements_template"></add-exam-modal>
                

        <div class="card">
            <div class="card-body pt-4 pb-2">
                <a href="{{ url('test_bank?program_id='. request('program_id')) }}" class="text-success"><i class="fa fa-arrow-left"></i> Back</a>
                <div class="d-flex justify-content-between mt-3">

                    <div>
                        <h1 class="page-header mb-3">Exams</h1>
                    </div>          

                    <div>
                        {{-- <a href="{{ url('/exams/create?program_id='. request('program_id') .'&student_outcome_id=' . request('student_outcome_id') . '&curriculum_id='. request('curriculum_id')) }}" class="btn btn-success-b">Add New Exam</a> --}}
                        <button type="button" class="btn btn-success-b" data-toggle="modal" data-target="#addExamModal">
                          Add New Exam
                        </button>
                    </div>


                    
                   
                </div>
                
                <div class="d-flex flex-wrap mb-3">

                    <div class="mr-3"><label>Program: </label> <span class="text-info fs-19">{{ $program->program_code }}</span></div>
                    <div class="mr-3"><label>Student Outcome: </label> <span class="text-info fs-19">{{ $student_outcome->so_code }}</span></div>
                    <div class="mr-3"><label>Curriculum: </label> <span class="text-info fs-19">{{ $curriculum->name . ' ' . $curriculum->year . ' - revision no. ' . $curriculum->revision_no }}.0</span></div>
                </div>

                {{-- <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th>
                                ID
                            </th>
                            <th>
                                Description
                            </th>
                            <th>
                                Time Limit
                            </th>
                            <th>
                                Passing Grade
                            </th>
                            <th>
                                No of Questions
                            </th>
                            <th>
                                Date Created
                            </th>
                            <th>
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($exams->count() > 0)
                            @foreach($exams as $exam)
                                <tr>
                                    <td>{{ $exam->id }}</td>
                                    <td>{{ $exam->description }}</td>
                                    <td>{{ $exam->time_limit }} minutes</td>
                                    <td>{{ $exam->passing_grade }}%</td>
                                    <td>{{ $exam->examTestQuestions->count() }}</td>
                                    <td>{{ $exam->created_at->format('M d, Y') }}</td>
                                    <td><a href="{{ url('/exams/' . $exam->id .'?program_id='. request('program_id') .'&student_outcome_id=' . request('student_outcome_id') . '&curriculum_id='. request('curriculum_id')) }}" class="btn btn-sm btn-secondary"><i class="fa fa-search"></i></a></td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center">No Record Found In Database.</td>
                            </tr>
                        @endif


                    </tbody>
                </table>
                <div class="text-muted">Showing {{ $exams->count() }} records.</div> --}}
            </div>
        </div>
        
        <div id="main-nav-tabs" class="mt-3">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#exams" role="tab"  aria-selected="true">Exams</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#archive" role="tab"  aria-selected="false">Archive</a>
              </li>
            </ul>
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade show active" id="exams" role="tabpanel">
                  <h5 class="mt-3 ml-3"><i class="fa fa-list text-info"></i> List of Exams</h5>
                    <template v-if="exams.length > 0">           
                        <div class="d-flex align-items-stretch flex-wrap" :class="{ 'justify-content-between': exams.length > 2 }">

                            <div v-for="exam in exams" :key="exam.id" style="flex-basis: 32%" class="card shadow mb-4" :class="{ 'mr-4': exams.length <= 2 }">
                                <div class="card-body pt-3">
                                    <div class="d-flex justify-content-between align-items-baseline">
                                        <div class="d-flex">
                                            <div class="mr-2">
                                                <div class="avatar" style="background: #cbff90; color:#585858;"><i class="fa fa-file-alt"></i></div>
                                            </div>
                                            <div style="font-weight: 600">@{{ exam.description }}</div>
                                        </div>
                                        <div class="ml-3">
                                          <a class="btn btn-sm" :href="'exams/' + exam.id +'?program_id='+ program_id + '&student_outcome_id=' + student_outcome_id + '&curriculum_id=' + curriculum_id" class="btn btn-sm">
                                              <i class="fa fa-search"></i> View
                                          </a>
                                        </div>
                                    </div>
                                    <div style="font-size: 13px" class="text-muted ml-2 mt-2">
                                        <i class="fa fa-user"></i> @{{ exam.user.first_name + ' ' + exam.user.last_name }} | @{{ parseDate(exam.created_at) }}
                                    </div>
                                    <hr>
                                    <div class="text-muted">
                                        <i class="fa fa-question-circle"></i> @{{ exam.exam_test_questions.length }} test questions
                                    </div>
                                    <div class="text-muted">
                                        <i class="fa fa-clock"></i> @{{ exam.time_limit }} minutes
                                    </div>
                                    <div class="text-muted mt-2">
                                        <span class="mb-0">Passing Grade: </span>
                                        @{{ exam.passing_grade }}%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template v-else>
                        <div class="bg-white p-3 text-center text-muted">
                            No Exam Found.
                        </div>
                    </template>
              </div>
              <div class="tab-pane fade" id="archive" role="tabpanel" >
                  <h5 class="mt-3 ml-3">List of Deactivated Exams</h5>
                    
                    <template v-if="deactivated_exams.length > 0">
                        <div class="d-flex align-items-stretch flex-wrap" :class="{ 'justify-content-between': deactivated_exams.length > 2 }">

                            <div v-for="exam in deactivated_exams" :key="exam.id" style="flex-basis: 32%" class="card shadow mb-4" :class="{ 'mr-4': deactivated_exams.length <= 2 }">
                                <div class="card-body pt-3">
                                    <div class="d-flex justify-content-between align-items-baseline">
                                        <div class="d-flex">
                                            <div class="mr-2">
                                                <div class="avatar"><i class="fa fa-file-alt text-dark"></i></div>
                                            </div>
                                            <div style="font-weight: 600">@{{ exam.description }}</div>
                                        </div>
                                        <div class="ml-3">
                                          <a class="btn btn-sm" :href="'exams/' + exam.id +'?program_id='+ program_id + '&student_outcome_id=' + student_outcome_id + '&curriculum_id=' + curriculum_id" class="btn btn-sm">
                                              <i class="fa fa-search"></i> View
                                          </a>
                                        </div>
                                    </div>
                                    <div style="font-size: 13px" class="text-muted ml-2 mt-2">
                                        <i class="fa fa-user"></i> @{{ exam.user.first_name + ' ' + exam.user.last_name }} | @{{ parseDate(exam.created_at) }}
                                    </div>
                                    <hr>
                                    <div class="text-muted">
                                        <i class="fa fa-question-circle"></i> @{{ exam.exam_test_questions.length }} test questions
                                    </div>
                                    <div class="text-muted">
                                        <i class="fa fa-clock"></i> @{{ exam.time_limit }} minutes
                                    </div>
                                    <div class="text-muted mt-2">
                                        <span class="mb-0">Passing Grade: </span>
                                        @{{ exam.passing_grade }}%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template v-else>
                        <div class="bg-white p-3 text-center text-muted">
                            No Archive Found.
                        </div>
                    </template>
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
                exams: @json($exams),
                deactivated_exams: @json($deactivated_exams),
                student_outcome_id: '{{ request('student_outcome_id') }}',
                program_id: '{{ request('program_id') }}',
                curriculum_id: '{{ request('curriculum_id') }}',
                requirements_template: @json($requirements_template)
            },
            methods: {
                parseDate(date) {
                    return moment(date).format('MMM DD YYYY');
                },
                getExams() {
                    ApiClient.get("/exams/get_exams?curriculum_id=" + this.curriculum_id + "&student_outcome_id=" + this.student_outcome_id)
                    .then(response => {
                        this.exams = response.data;
                    })
                }
            }
        });
    </script>
@endpush