@extends('layout.app', ['active' => 'users'])

@section('title', 'Update Student Information')


@section('content')

    <a href="{{ url('/students') }}" class="text-success"><i class="fa fa-arrow-left"></i> Back</a>
    
    <div id="app">
        <div class="row">
            <div class="col-md-8 mx-auto mt-3">
                <div class="card shadow">
                    <div class="card-header">
                        <h3>Update Academic Information</h3>
                    </div>

                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <div>
                                <img src="{{ asset('img/user.svg') }}" alt="user-icon" style="width: 40px" class=" mr-3">
                            </div>
                            <div>
                                <h5>{{ $student->student_id }} - <b>{{ $student->user->getFullName() }}</b></h5>
                            </div>
                        </div>
                        <form autocomplete="off" v-on:submit.prevent="updateAcademic" v-on:keydown="form.onKeydown($event)">
                            
                            
                            <!-- Field for college  -->
                            <div class="form-group row">
                                <label
                                  for="college"
                                  class="col-md-2 col-form-label text-md-right"
                                  ><b>College</b></label
                                >

                                <div class="col-md-10">
                                  <select
                                    id="college"
                                    type="text"
                                    class="form-control"
                                    :class="{ 'is-invalid': form.errors.has('college') }"
                                    v-model="form.college"
                                    v-on:change="collegeChanged"
                                  />
                                    <option value="" style="display: none;">Select College</option>
                                    <option v-for="college in colleges" :value="college.id">@{{ college.name }}</option>
                                  </select>
                                  <has-error :form="form" field="college"></has-error>
                                </div>
                            </div>
                            <!-- /end Field for college -->

                            <!-- Field for program  -->
                            <div class="form-group row">
                                <label
                                  for="program"
                                  class="col-md-2 col-form-label text-md-right"
                                  ><b>Program</b></label
                                >

                                <div class="col-md-10">
                                  <select
                                    id="program"
                                    type="text"
                                    class="form-control"
                                    :class="{ 'is-invalid': form.errors.has('program') }"
                                    v-model="form.program"
                                    v-on:change="programChanged"

                                  />
                                    <option value="" class="d-none">Select Program</option>
                                    <option v-if="form.college == ''" value="" disabled>Select college first</option>
                                    <option v-else-if="selectProgramsByCollege(form.college).length <= 0" value="" disabled>No Available program</option>
                                    <template v-else>
                                        <option v-for="program in selectProgramsByCollege(form.college)" :value="program.id">@{{ program.description }}</option>
                                    </template>
                                  </select>
                                  <has-error :form="form" field="program"></has-error>
                                </div>
                            </div>
                            <!-- /end Field for program -->


                            <!-- Field for curriculum  -->
                            <div class="form-group row">
                                <label
                                  for="curriculum"
                                  class="col-md-2 col-form-label text-md-right"
                                  ><b>Curriculum</b></label
                                >

                                <div class="col-md-10">
                                  <select
                                    id="curriculum"
                                    type="text"
                                    class="form-control"
                                    :class="{ 'is-invalid': form.errors.has('curriculum') }"
                                    v-model="form.curriculum"
                                  />
                                    <option value="" class="d-none">Select Curriculum</option>
                                    <option v-if="form.program == ''" value="" disabled>Select program first</option>
                                    <option v-else-if="selectCurriculumsByProgram(form.program).length <= 0" value="" disabled>No Available curriculum</option>
                                    <template v-else>
                                        <option v-for="curriculum in selectCurriculumsByProgram(form.program)" :value="curriculum.id">@{{ curriculum.name + ' - ' + curriculum.year }}</option>
                                    </template>
                                  </select>
                                  <has-error :form="form" field="curriculum"></has-error>
                                </div>
                            </div>
                            <!-- /end Field for curriculum --> 

                            <div class="d-flex justify-content-end">
                                <a href="{{ url('/students') }}" class="btn btn-dark mr-2" :disabled="form.busy">Cancel</a>
                                <button class="btn btn-primary" :disabled="form.busy">Update Information <div v-show="form.busy" class="spinner-border text-light spinner-border-sm" role="status">
                                  <span class="sr-only">Loading...</span>
                                </div></button>
                            </div>
                            
                        </form>

                    </div>
                </div>
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
                    id: '{{ $student->id }}',
                    college: '{{ $student->program->college->id }}',
                    program: '{{ $student->program_id }}',
                    curriculum: '{{ $student->curriculum_id }}'
                }),
                colleges: @json($colleges),
                programs: @json($programs),
                curriculums: @json($curriculums)
            },
            methods: {
                selectProgramsByCollege(college_id) {
                    return this.programs.filter(program => {
                        return program.college_id == college_id;
                    });
                },
                selectCurriculumsByProgram(program_id) {
                    return this.curriculums.filter(curriculum => {
                        return curriculum.program_id == program_id;
                    });
                },
                updateAcademic() {
                    this.form.put('../../students/' + this.form.id + '/update_academic')
                        .then(response => {
                            window.location.replace(myRootURL + '/students/' + response.data.id);

                            //console.log(response);
                        })
                        .catch(err => {
                            console.log(err);
                        });
                },
                collegeChanged() {
                  this.form.program = '';
                  this.form.curriculum = '';
                },
                programChanged() {
                  this.form.curriculum = '';
                }
            }
        })
    </script>
@endpush