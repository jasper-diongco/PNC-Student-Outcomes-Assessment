<template>
    <div>
        <button data-toggle="modal" data-target="#addStudentModal" class="btn btn-success-b">Add New Student</button>

        <!-- Modal -->
        <div
            class="modal fade"
            id="addStudentModal"
            tabindex="-1"
            role="dialog"
        >
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            Add New Student
                        </h5>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form autocomplete="off" v-on:submit.prevent="addStudent" v-on:keydown="form.onKeydown($event)">
                    <div class="modal-body">
                        
                            <!-- Field for year student_id  -->
                            <div class="form-group">
                                <label
                                  for="student_id"
                                  >Student ID</label
                                >

                                <div>
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
                            <div class="form-group">
                                <label
                                  for="last_name"
                                  >Last Name</label
                                >

                                <div>
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
                            <div class="form-group">
                                <label
                                  for="first_name"
                                  >First Name</label
                                >

                                <div>
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
                            <div class="form-group">
                                <label
                                  for="middle_name"
                                  >Middle Name</label
                                >

                                <div>
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
                            
                            <div class="form-group">
                                <label>Sex</label>
                                <select class="form-control"
                                    :class="{ 'is-invalid': form.errors.has('sex') }"
                                    v-model="form.sex">
                                        <option value="" style="display: none;">Select Sex</option>
                                    <option value="M">Male</option>
                                    <option value="F">Female</option>
                                    </select>
                                <has-error :form="form" field="sex"></has-error>
                            </div>

                            <!-- Field for date_of_birth  -->
                            <div class="form-group">
                                <label
                                  for="date_of_birth"
                                  >Date of Birth</label
                                >

                                <div>
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
                            <div class="form-group">
                                <label
                                  >College</label
                                >
                                <select v-model="form.college" :class="{ 'is-invalid': form.errors.has('college') }" class="form-control">
                                    <option v-for="college in colleges" :value="college.id">{{ college.name }}</option>
                                </select>
                                <has-error :form="form" field="college"></has-error>
                            </div>
                            <!-- Field for college  -->

                            
                            <div class="form-group">
                                <label>Program</label>
                                <select class="form-control" :class="{ 'is-invalid': form.errors.has('program') }"
                                    v-model="form.program">
                                        <option value="" class="d-none">Select Program</option>
                                    <option v-if="form.college == ''" value="" disabled>Select college first</option>
                                    <option v-else-if="selectProgramsByCollege(form.college).length <= 0" value="" disabled>No Available program</option>
                                    <template v-else>
                                        <option v-for="program in selectProgramsByCollege(form.college)" :value="program.id">{{ program.description }}</option>
                                    </template>
                                    </select>
                                <has-error :form="form" field="program"></has-error>
                            </div>

                            
                            <div class="form-group">
                                <label>Curriculum</label>
                                <select v-model="form.curriculum" class="form-control" :class="{ 'is-invalid': form.errors.has('curriculum') }">
                                    <option value="" class="d-none">Select Curriculum</option>
                                    <option v-if="form.program == ''" value="" disabled>Select program first</option>
                                    <option v-else-if="selectCurriculumsByProgram(form.program).length <= 0" value="" disabled>No Available curriculum</option>
                                    <template v-else>
                                        <option v-for="curriculum in selectCurriculumsByProgram(form.program)" :value="curriculum.id">{{ curriculum.name + ' - ' + curriculum.year }}</option>
                                    </template>
                                </select>
                                <has-error :form="form" field="curriculum"></has-error>
                            </div>
                            

                            <!-- Field for year email  -->
                            <div class="form-group">
                                <label
                                  for="email"
                                  >Email</label
                                >

                                <div>
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
                            
                        
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal"
                            :disabled="form.busy"
                        >
                            Close
                        </button>
                        <button :disabled="form.busy" class="btn btn-success">
                            Add Student <div v-show="form.busy" class="spinner-border text-light spinner-border-sm" role="status">
                                  <span class="sr-only">Loading...</span>
                                </div>
                        </button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['colleges', 'programs', 'curriculums'],
    data() {
        return {
            form: new Form({
                student_id: '',
                last_name: '',
                first_name: '',
                middle_name: '',
                sex: '',
                date_of_birth: '',
                college: '',
                program: '',
                curriculum: '',
                email: '',
                password: 'DefaultPass123'
            })
        }
        
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
        addStudent() {
            this.form.post(myRootURL + '/students')
                .then(response => {
                    //window.location.replace(myRootURL + '/students/' + response.data.id);
                    $('#addStudentModal').modal('hide');
                    toast.fire({
                      type: 'success',
                      title: 'Student Successfully Created'
                    });
                    this.$emit('refresh-students');
                })
                .catch(err => {
                    console.log(err);
                });
        }
    }
}
</script>
