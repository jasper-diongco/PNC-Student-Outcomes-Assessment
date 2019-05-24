@extends('layouts.sb_admin')

@section('title', 'Update Student Information')


@section('content')

    <a href="{{ url('/students') }}" class="text-success"><i class="fa fa-arrow-left"></i> Back</a>
    
    <div id="app">
        <div class="row">
            <div class="col-md-8 mx-auto mt-3">
                <div class="card shadow">
                    <div class="card-header">
                        <h3>Update Student Information</h3>
                    </div>

                    <div class="card-body">
                        <img src="{{ asset('img/user.svg') }}" alt="user-icon" style="width: 40px" class="mb-2">
                        <form autocomplete="off" v-on:submit.prevent="updateStudent" v-on:keydown="form.onKeydown($event)">
                            <!-- Field for year student_id  -->
                            <div class="form-group row">
                                <label
                                  for="student_id"
                                  class="col-md-2 col-form-label text-md-right"
                                  ><b>Student ID</b></label
                                >

                                <div class="col-md-10">
                                  <input
                                    id="student_id"
                                    type="text"
                                    class="form-control"
                                    :class="{ 'is-invalid': form.errors.has('student_id') }"
                                    v-model="form.student_id"
                                    placeholder="Enter Student ID"
                                  />
                                  <has-error :form="form" field="student_id"></has-error>
                                </div>
                            </div>
                            <!-- /end Field for student_id -->
                            
                            <!-- Field for year last_name  -->
                            <div class="form-group row">
                                <label
                                  for="last_name"
                                  class="col-md-2 col-form-label text-md-right"
                                  ><b>Last Name</b></label
                                >

                                <div class="col-md-10">
                                  <input
                                    id="last_name"
                                    type="text"
                                    class="form-control"
                                    :class="{ 'is-invalid': form.errors.has('last_name') }"
                                    v-model="form.last_name"
                                    placeholder="Enter Last Name"
                                    v-uppercase
                                  />
                                  <has-error :form="form" field="last_name"></has-error>
                                </div>
                            </div>
                            <!-- /end Field for last_name -->  

                            <!-- Field for year first_name  -->
                            <div class="form-group row">
                                <label
                                  for="first_name"
                                  class="col-md-2 col-form-label text-md-right"
                                  ><b>First Name</b></label
                                >

                                <div class="col-md-10">
                                  <input
                                    id="first_name"
                                    type="text"
                                    class="form-control"
                                    :class="{ 'is-invalid': form.errors.has('first_name') }"
                                    v-model="form.first_name"
                                    placeholder="Enter First Name"
                                    v-uppercase
                                  />
                                  <has-error :form="form" field="first_name"></has-error>
                                </div>
                            </div>
                            <!-- /end Field for first_name --> 

                            <!-- Field for year middle_name  -->
                            <div class="form-group row">
                                <label
                                  for="middle_name"
                                  class="col-md-2 col-form-label text-md-right"
                                  ><b>Middle Name</b></label
                                >

                                <div class="col-md-10">
                                  <input
                                    id="middle_name"
                                    type="text"
                                    class="form-control"
                                    :class="{ 'is-invalid': form.errors.has('middle_name') }"
                                    v-model="form.middle_name"
                                    placeholder="Enter Middle Name"
                                    v-uppercase
                                  />
                                  <has-error :form="form" field="middle_name"></has-error>
                                </div>
                            </div>
                            <!-- /end Field for middle_name --> 

                            <!-- Field for year sex  -->
                            <div class="form-group row">
                                <label
                                  for="sex"
                                  class="col-md-2 col-form-label text-md-right"
                                  ><b>Sex</b></label
                                >

                                <div class="col-md-10">
                                  <select
                                    id="sex"
                                    type="text"
                                    class="form-control"
                                    :class="{ 'is-invalid': form.errors.has('sex') }"
                                    v-model="form.sex"
                                  />
                                    <option value="" style="display: none;">Select Sex</option>
                                    <option value="M">Male</option>
                                    <option value="F">Female</option>
                                  </select>
                                  <has-error :form="form" field="sex"></has-error>
                                </div>
                            </div>
                            <!-- /end Field for sex --> 

                            <!-- Field for sex  -->
                            <div class="form-group row">
                                <label
                                  for="date_of_birth"
                                  class="col-md-2 col-form-label text-md-right"
                                  ><b>Date of Birth</b></label
                                >

                                <div class="col-md-10">
                                  <datepicker 
                                    id="date_of_birth"
                                    placeholder="Select Date" 
                                    name="date_of_birth" 
                                    :class="{ 'is-invalid': form.errors.has('date_of_birth') }"
                                    v-model="form.date_of_birth"></datepicker>
                                  <input 
                                    type="hidden" 
                                    v-model="form.date_of_birth" 
                                    class="form-control"
                                    :class="{ 'is-invalid': form.errors.has('date_of_birth') }">
                                  <has-error :form="form" field="date_of_birth"></has-error>
                                </div>
                            </div>
                            <!-- /end Field for date_of_birth --> 

                            <!-- Field for year email  -->
                            <div class="form-group row">
                                <label
                                  for="email"
                                  class="col-md-2 col-form-label text-md-right"
                                  ><b>Email</b></label
                                >

                                <div class="col-md-10">
                                    <div class="input-group">
                                      <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class='fa fa-envelope'></i></span>
                                      </div>
                                      <input
                                        id="email"
                                        type="text"
                                        class="form-control"
                                        :class="{ 'is-invalid': form.errors.has('email') }"
                                        v-model="form.email"
                                        placeholder="Enter Email"
                                      />
                                      <has-error :form="form" field="email"></has-error>
                                    </div>
                                  
                                </div>
                            </div>
                            <!-- /end Field for email -->
                            
                            {{-- <!-- Field for college  -->
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
                                        <option v-for="curriculum in selectCurriculumsByProgram(form.program)" :value="curriculum.id">@{{ curriculum.name }}</option>
                                    </template>
                                  </select>
                                  <has-error :form="form" field="curriculum"></has-error>
                                </div>
                            </div>
                            <!-- /end Field for curriculum -->  --}}

                            {{-- <hr>
                            <h5 class="text-primary mb-3"><b>Account Information</b></h5>

                            <!-- Field for year email  -->
                            <div class="form-group row">
                                <label
                                  for="email"
                                  class="col-md-2 col-form-label text-md-right"
                                  ><b>Email</b></label
                                >

                                <div class="col-md-10">
                                    <div class="input-group">
                                      <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class='fa fa-envelope'></i></span>
                                      </div>
                                      <input
                                        id="email"
                                        type="text"
                                        class="form-control"
                                        :class="{ 'is-invalid': form.errors.has('email') }"
                                        v-model="form.email"
                                        placeholder="Enter Email"
                                      />
                                      <has-error :form="form" field="email"></has-error>
                                    </div>
                                  
                                </div>
                            </div>
                            /end Field for email

                            <!-- Field for year password  -->
                            <div class="form-group row">
                                <label
                                  for="password"
                                  class="col-md-2 col-form-label text-md-right"
                                  ><b>Password</b></label
                                >

                                <div class="col-md-10">
                                    <div class="input-group">
                                      <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class='fa fa-lock'></i></span>
                                      </div>
                                      <input
                                        id="password"
                                        type="text"
                                        class="form-control"
                                        :class="{ 'is-invalid': form.errors.has('password') }"
                                        v-model="form.password"
                                        placeholder="Enter Password"
                                        readonly
                                      />
                                      <has-error :form="form" field="password"></has-error>
                                    </div>
                                  
                                </div>
                            </div>
                            <!-- /end Field for password --> --}}
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
                    student_id: '{{ $student->student_id }}',
                    last_name: '{{ $student->user->last_name }}',
                    first_name: '{{ $student->user->first_name }}',
                    middle_name: '{{ $student->user->middle_name }}',
                    sex: '{{ $student->user->sex }}',
                    date_of_birth: '{{ $student->user->date_of_birth }}',
                    email: '{{ $student->user->email }}'
                })
            },
            methods: {
                updateStudent() {
                    this.form.put('../../students/' + this.form.id)
                        .then(response => {
                            window.location.replace(myRootURL + '/students/' + response.data.id);
                        })
                        .catch(err => {
                            console.log(err);
                        });
                }
            }
        })
    </script>
@endpush