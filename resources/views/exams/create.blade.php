@extends('layout.app', ['active' => 'test_questions'])

@section('title', 'Create Exam')

@section('content')
    
    <div id="app">
        
    
        <a href="{{ url('/exams?program_id='. request('program_id') .'&student_outcome_id=' . request('student_outcome_id'). '&curriculum_id='. request('curriculum_id')) }}" class="text-success"><i class="fa fa-arrow-left"></i> Back</a>
    
        
        


        <div class="row">
            <div class="col-md-3">
                {{-- <div class="mt-3">
                    <label>Available Test Questions:</label>
                </div> --}}
                {{-- <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="card border-left-success">
                            <div class="card-body">
                                <h5>Easy &mdash; <span class="text-success">20</span></h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card border-left-warning">
                            <div class="card-body">
                                <h5>Average &mdash; <span class="text-success">19</span></h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card border-left-danger">
                            <div class="card-body">
                                <h5>Difficult &mdash; <span class="text-success">30</span></h5>
                            </div>
                        </div>
                    </div>
                </div> --}}

                {{-- <ul class="list-group">
                  <li class="list-group-item"> Easy - <label class="text-success">{{ $count_easy }}</label></li>
                  <li class="list-group-item"> Average - <label class="text-success">{{ $count_average }}</label></li>
                  <li class="list-group-item"> Difficult - <label class="text-success">{{ $count_difficult }}</label></li>
                </ul> --}}

                {{-- <ul>
                    @foreach($curriculum_maps as $curriculum_map)
                    <li> 
                        {{ $curriculum_map->curriculumCourse->course->course_code }}
                    </li>
                    @endforeach
                </ul> --}}

                <div class="mt-3" >
                    <label>No of test questions per course:</label>
                    <label>Total:</label> {{ $total_test_questions }}
                    <ul class="list-group" style="font-size: 14px; list-style: none; padding-left: 0; cursor: pointer;">
                        @foreach($curriculum_course_requirements as $requirement)
                            <li data-toggle="collapse" data-target="#collapse{{ $requirement->curriculum_map->curriculumCourse->course->course_code }}" class="list-group-item"><i class="fa fa-caret-right text-success"></i> {{ $requirement->curriculum_map->curriculumCourse->course->course_code }} - <span >{{ $requirement->total }}</span>
                                
                            </li>
                            <div class="pl-3 bg-white collapse" id="collapse{{ $requirement->curriculum_map->curriculumCourse->course->course_code }}">
                                <ul style="list-style: none; padding-left: 10px;">
                                    <li><i class="fa fa-angle-right text-success"></i>  Easy - {{ $requirement->easy }}</li>
                                    <li><i class="fa fa-angle-right text-success"></i>  Average - {{ $requirement->average }}</li>
                                    <li><i class="fa fa-angle-right text-success"></i>  Difficult - {{ $requirement->difficult }}</li>
                                </ul>
                            </div>
                            
                        @endforeach


                    </ul>

                    
                </div>
                
            </div>
            <div class="col-md-9">
                <h1 class="page-header mb-3">Add new Exam <i class="fa fa-file-alt"></i></h1>
    
                <div class="d-flex mb-3">

                    <div class="mr-3"><label>Program: </label> <span class="text-info">{{ $program->program_code }}</span></div>
                    <div class="mr-3"><label>Student Outcome: </label> <span class="text-info">{{ $student_outcome->so_code }}</span></div>
                    <div class="mr-3"><label>Curriculum: </label> <span class="text-info">{{ $curriculum->name . ' ' . $curriculum->year . ' - v' . $curriculum->revision_no }}.0</span></div>
                </div>

                <form
                    v-on:submit.prevent="saveExam"
                    v-on:keydown="form.onKeydown($event)"
                >
                    <div class="form-group">
                        <label>Description</label>
                        <textarea
                            v-model="form.description"
                            name="description"
                            class="form-control"
                            :class="{
                                'is-invalid': form.errors.has(
                                    'description'
                                )
                            }"
                        ></textarea>
                        <has-error
                            :form="form"
                            field="description"
                        ></has-error>
                    </div>

                    <div class="form-group">
                        <label>Time Limit (in minutes)</label>
                        <input
                            v-model="form.time_limit"
                            type="number"
                            name="time_limit"
                            class="form-control"
                            :class="{
                                'is-invalid': form.errors.has(
                                    'time_limit'
                                )
                            }"
                        />
                        <has-error
                            :form="form"
                            field="time_limit"
                        ></has-error>
                    </div>

                    <div class="form-group">
                        <label>Passing Grade (in percentage)</label>
                        <input
                            v-model="form.passing_grade"
                            type="number"
                            name="passing_grade"
                            class="form-control"
                            :class="{
                                'is-invalid': form.errors.has(
                                    'passing_grade'
                                )
                            }"
                        />
                        <has-error
                            :form="form"
                            field="passing_grade"
                        ></has-error>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button
                            class="btn btn-success"
                            :disabled="form.busy"
                            type="submit"
                        >
                            Generate new exam
                            <div v-if="form.busy" class="spinner-border spinner-border-sm text-light" role="status">
                              <span class="sr-only">Loading...</span>
                            </div>
                        </button>
                    </div>
                    
                </form>
            </div>
        </div>



        


    </div>
    
@endsection

@push('scripts')
    <script>
        new Vue({
            el: '#app',
            data: {
                form: new Form({
                    description: "",
                    time_limit: 60,
                    passing_grade: 60,
                    curriculum_id: '{{ request('curriculum_id') }}',
                    student_outcome_id: '{{ request('student_outcome_id') }}'
                })
            },
            methods: {
                addExam() {
                    this.form.post(myRootURL + '/exams')
                    .then(response => {
                        //console.log(response);
                        window.location.replace(myRootURL + '/exams/' + response.data.exam.id + '?program_id='+ {{ request('program_id') }} +'&student_outcome_id='+ {{ request('student_outcome_id') }} +'&curriculum_id=' + {{ request('curriculum_id') }});
                    });
                },
                saveExam() {
                    this.addExam();
                }
            }
        });
    </script>
@endpush