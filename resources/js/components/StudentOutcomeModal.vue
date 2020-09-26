<template>
  <div>
    <!-- Activator -->
    <button
      v-if="isUpdate"
      type="button"
      class="btn btn-primary btn-sm"
      data-toggle="modal"
      data-target="#courseModal"
    >
      Update <i class="fa fa-edit"></i>
    </button>
    <button
      v-else
      type="button"
      class="btn btn-success-b"
      data-toggle="modal"
      data-target="#courseModal"
    >
      Add new Student Outcome
    </button>
    <!-- End Activator -->

    <!-- Modal -->
    <div
      class="modal fade bd-example-modal-lg"
      id="courseModal"
      tabindex="-1"
      role="dialog"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg" role="document">
        <form
          @submit.prevent="saveStudentOutcome"
          @keydown="form.onKeydown($event)"
          autocomplete="off"
        >
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">
                {{ modalTitle }}
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
            <div class="modal-body">
              <!-- Field for so code  -->
              <div class="form-group">
                <label for="so_code">Letter</label>

<!--                 <div>
                  <input
                    id="so_code"
                    type="text"
                    class="form-control"
                    name="so_code"
                    v-model="form.so_code"
                    v-uppercase
                    :class="{ 'is-invalid': form.errors.has('so_code') }"
                  />
                  <has-error :form="form" field="so_code"></has-error>
                </div> -->
                <div>
                  <select id="so_code" type="text"
                    class="form-control"
                    name="so_code"
                    v-model="form.so_code"
                    :class="{ 'is-invalid': form.errors.has('so_code') }">
                    <option v-for="so in student_outcomes" :value="so.so_code">{{ so.so_code }}</option>
                  </select>
                  <has-error :form="form" field="so_code"></has-error>
                </div>
              </div>
              <!-- <button @click="reorderPosition">Reorder</button> -->
              <!-- /end Field for so code -->

              <!-- Field for description  -->
              <div class="form-group">
                <label for="course_code">Description</label>

                <div>
                  <input
                    id="course_code"
                    type="text"
                    class="form-control"
                    name="course_code"
                    v-model="form.description"
                    :class="{ 'is-invalid': form.errors.has('description') }"
                  />
                  <has-error :form="form" field="description"></has-error>
                </div>
              </div>
              <!-- /end Field for course code -->

              <!-- Field for program  -->
              <div class="form-group">
                <label for="program">Program</label>

                <div>
                  <select
                    id="program"
                    class="form-control"
                    name="program"
                    v-model="form.program"
                    v-uppercase
                    :class="{ 'is-invalid': form.errors.has('program') }"
                    disabled
                  >
                    <option value="" style="display: none"
                      >Select Program</option
                    >
                    <option v-for="program in programs" :value="program.id">{{
                      program.program_code
                    }}</option>
                  </select>
                  <has-error :form="form" field="program"></has-error>
                </div>
              </div>
              <!-- /end Field for program -->
              <hr />
              <p><b>Performance Indicators</b></p>

              <!-- Field for performance criteria  -->
              <div class="form-group">
                <label for="performance_criteria">Performance Criteria</label>

                <div>
                  <textarea
                    id="performance_criteria"
                    type="text"
                    class="form-control"
                    name="performance_criteria"
                    v-model="form.performance_criteria"
                    :class="{
                      'is-invalid': form.errors.has('performance_criteria')
                    }"
                  >
                  </textarea>
                  <has-error
                    :form="form"
                    field="performance_criteria"
                  ></has-error>
                </div>
              </div>
              <!-- /end Field for performance criteria -->

              <!-- Performance indicators -->
              <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                  <a
                    class="nav-link active"
                    :class="{
                      'border-bottom-danger':
                        form.errors.has('unsatisfactory_desc') ||
                        form.errors.has('unsatisfactory_grade')
                    }"
                    id="unsatisfactory-link"
                    data-toggle="tab"
                    href="#unsatisfactory-tab"
                    role="tab"
                    aria-controls="home"
                    aria-selected="true"
                    >Unsatisfactory</a
                  >
                </li>
                <li class="nav-item">
                  <a
                    class="nav-link"
                    id="developing-link"
                    data-toggle="tab"
                    href="#developing-tab"
                    role="tab"
                    aria-controls="developing-tab"
                    aria-selected="false"
                    :class="{
                      'border-bottom-danger':
                        form.errors.has('developing_desc') ||
                        form.errors.has('developing_grade')
                    }"
                    >Developing</a
                  >
                </li>
                <li class="nav-item">
                  <a
                    class="nav-link"
                    id="satisfactory-link"
                    data-toggle="tab"
                    href="#satisfactory-tab"
                    role="tab"
                    aria-controls="contact"
                    aria-selected="false"
                    :class="{
                      'border-bottom-danger':
                        form.errors.has('satisfactory_desc') ||
                        form.errors.has('satisfactory_grade')
                    }"
                    >Satisfactory</a
                  >
                </li>
                <li class="nav-item">
                  <a
                    class="nav-link"
                    id="exemplary-link"
                    data-toggle="tab"
                    href="#exemplary-tab"
                    role="tab"
                    aria-controls="exemplary-tab"
                    aria-selected="false"
                    :class="{
                      'border-bottom-danger':
                        form.errors.has('exemplary_desc') ||
                        form.errors.has('exemplary_grade')
                    }"
                    >Exemplary</a
                  >
                </li>
              </ul>
              <div class="tab-content" id="myTabContent">
                <div
                  class="tab-pane fade show active"
                  id="unsatisfactory-tab"
                  role="tabpanel"
                  aria-labelledby="unsatisfactory-tab"
                >
                  <!-- Field for unsatisfactory_desc  -->
                  <div class="form-group mt-3">
                    <label for="unsatisfactory_desc"
                      >Unsatisfactory Description</label
                    >

                    <div>
                      <textarea
                        id="unsatisfactory_desc"
                        type="text"
                        class="form-control"
                        name="unsatisfactory_desc"
                        v-model="form.unsatisfactory_desc"
                        :class="{
                          'is-invalid': form.errors.has('unsatisfactory_desc')
                        }"
                      >
                      </textarea>
                      <has-error
                        :form="form"
                        field="unsatisfactory_desc"
                      ></has-error>
                    </div>
                  </div>
                  <!-- /end Field for unsatisfactory_desc -->

                  <!-- Field for unsatisfactory_grade  -->
                  <div class="form-group mt-3">
                    <label for="unsatisfactory_grade">Grade</label>

                    <div>
                      <input
                        id="unsatisfactory_grade"
                        type="number"
                        class="form-control"
                        name="unsatisfactory_grade"
                        v-model="form.unsatisfactory_grade"
                        :class="{
                          'is-invalid': form.errors.has('unsatisfactory_grade')
                        }"
                      />
                      <has-error
                        :form="form"
                        field="unsatisfactory_grade"
                      ></has-error>
                    </div>
                  </div>
                  <!-- /end Field for unsatisfactory_grade -->
                </div>
                <div
                  class="tab-pane fade"
                  id="developing-tab"
                  role="tabpanel"
                  aria-labelledby="developing-tab"
                >
                  <!-- Field for developing_desc  -->
                  <div class="form-group mt-3">
                    <label for="developing_desc">Developing Description</label>

                    <div>
                      <textarea
                        id="developing_desc"
                        type="text"
                        class="form-control"
                        name="developing_desc"
                        v-model="form.developing_desc"
                        :class="{
                          'is-invalid': form.errors.has('developing_desc')
                        }"
                      >
                      </textarea>
                      <has-error
                        :form="form"
                        field="unsatisfactory_desc"
                      ></has-error>
                    </div>
                  </div>
                  <!-- /end Field for developing_desc -->

                  <!-- Field for developing_grade  -->
                  <div class="form-group mt-3">
                    <label for="developing_grade">Grade</label>

                    <div>
                      <input
                        id="developing_grade"
                        type="number"
                        class="form-control"
                        name="developing_grade"
                        v-model="form.developing_grade"
                        :class="{
                          'is-invalid': form.errors.has('developing_grade')
                        }"
                      />
                      <has-error
                        :form="form"
                        field="developing_grade"
                      ></has-error>
                    </div>
                  </div>
                  <!-- /end Field for developing_grade -->
                </div>
                <div
                  class="tab-pane fade"
                  id="satisfactory-tab"
                  role="tabpanel"
                  aria-labelledby="contact-tab"
                >
                  <!-- Field for satisfactory_desc  -->
                  <div class="form-group mt-3">
                    <label for="satisfactory_desc"
                      >Satisfactory Description</label
                    >

                    <div>
                      <textarea
                        id="satisfactory_desc"
                        type="text"
                        class="form-control"
                        name="satisfactory_desc"
                        v-model="form.satisfactory_desc"
                        :class="{
                          'is-invalid': form.errors.has('satisfactory_desc')
                        }"
                      >
                      </textarea>
                      <has-error
                        :form="form"
                        field="satisfactory_desc"
                      ></has-error>
                    </div>
                  </div>
                  <!-- /end Field for satisfactory_desc -->

                  <!-- Field for satisfactory_grade  -->
                  <div class="form-group mt-3">
                    <label for="satisfactory_grade">Grade</label>

                    <div>
                      <input
                        id="satisfactory_grade"
                        type="number"
                        class="form-control"
                        name="satisfactory_grade"
                        v-model="form.satisfactory_grade"
                        :class="{
                          'is-invalid': form.errors.has('satisfactory_grade')
                        }"
                      />
                      <has-error
                        :form="form"
                        field="satisfactory_grade"
                      ></has-error>
                    </div>
                  </div>
                  <!-- /end Field for satisfactory_grade -->
                </div>

                <div
                  class="tab-pane fade"
                  id="exemplary-tab"
                  role="tabpanel"
                  aria-labelledby="exemplary-tab"
                >
                  <!-- Field for exemplary_desc  -->
                  <div class="form-group mt-3">
                    <label for="exemplary_desc">Exemplary Description</label>

                    <div>
                      <textarea
                        id="exemplary_desc"
                        type="text"
                        class="form-control"
                        name="exemplary_desc"
                        v-model="form.exemplary_desc"
                        :class="{
                          'is-invalid': form.errors.has('exemplary_desc')
                        }"
                      >
                      </textarea>
                      <has-error
                        :form="form"
                        field="exemplary_desc"
                      ></has-error>
                    </div>
                  </div>
                  <!-- /end Field for satisfactory_desc -->

                  <!-- Field for exemplary_grade  -->
                  <div class="form-group mt-3">
                    <label for="exemplary_grade">Grade</label>

                    <div>
                      <input
                        id="exemplary_grade"
                        type="number"
                        class="form-control"
                        name="exemplary_grade"
                        v-model="form.exemplary_grade"
                        :class="{
                          'is-invalid': form.errors.has('exemplary_grade')
                        }"
                      />
                      <has-error
                        :form="form"
                        field="exemplary_grade"
                      ></has-error>
                    </div>
                  </div>
                  <!-- /end Field for exemplary_grade -->
                </div>
              </div>
              <!-- End Performance indicators -->
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
              <button
                class="btn btn-success"
                :disabled="form.busy"
                type="submit"
              >
                {{ saveTitle }}
                <div
                  v-show="form.busy"
                  class="spinner-border spinner-border-sm text-light"
                  role="status"
                >
                  <span class="sr-only">Loading...</span>
                </div>
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <!-- End Modal -->
  </div>
</template>

<script>
export default {
  props: [
    "programs",
    "isUpdate",
    "studentOutcome",
    "performanceCriteria",
    "performanceIndicators",
    "programId",
    "studentOutcomes"
  ],
  data() {
    return {
      form: new Form({
        id: "",
        description: "",
        so_code: "",
        program: "",
        performance_criteria: "",
        unsatisfactory_desc: "",
        unsatisfactory_grade: "25.00",
        developing_desc: "",
        developing_grade: "50.00",
        satisfactory_desc: "",
        satisfactory_grade: "75.00",
        exemplary_desc: "",
        exemplary_grade: "100.00",
        student_outcomes: []
      }),
      student_outcomes: [],
      prev_so: ''
    };
  },
  computed: {
    modalTitle() {
      return this.isUpdate
        ? "Update Student Outcome"
        : "Add new Student Outcome";
    },
    saveTitle() {
      return this.isUpdate ? "Update from database" : "Add to database";
    }
  },
  methods: {
    saveStudentOutcome() {
      if (this.isUpdate) {
        this.updateStudentOutcome();
      } else {
        this.addStudentOutcome();
      }
    },
    addStudentOutcome() {
      var oldStudentOutcomes = this.reorderPosition();
      this.form.student_outcomes = this.student_outcomes;
      this.form
        .post("student_outcomes")
        .then(({ data }) => {
          console.log(data);
          window.location.href =
            myRootURL +
            "/student_outcomes/" +
            data.id +
            "?program_id=" +
            data.program_id;
        })
        .catch(err => {
          this.student_outcomes = oldStudentOutcomes;
          console.log(err);
          
        });
    },
    updateStudentOutcome() {
      // var oldStudentOutcomes = this.reorderPositionUpdate();
      this.form.student_outcomes = this.student_outcomes;
      this.form
        .put("../student_outcomes/" + this.form.id)
        .then(({ data }) => {
          window.location.href =
            myRootURL +
            "/student_outcomes/" +
            data.id +
            "?program_id=" +
            data.program_id;
        })
        .catch(err => {
          this.student_outcomes = oldStudentOutcomes;
          console.log(err);
        });
    },
    reorderPosition() {
      var last_pos = this.student_outcomes.length - 1;
      var last_student_outcome = this.student_outcomes[last_pos];


      var oldStudentOutcomes = JSON.parse(JSON.stringify(this.student_outcomes));

      if(!(this.form.so_code == last_student_outcome.so_code)) {
        //find pos of selected letter
        var pos = 0;
        for(var i = 0; i < this.student_outcomes.length; i++) {
          if(this.form.so_code == this.student_outcomes[i].so_code) {
            pos = i;
            break;
          }
        }

        for(var i = pos; i < this.student_outcomes.length; i++) {
          this.student_outcomes[i].so_code = String.fromCharCode((this.student_outcomes[i].so_code.charCodeAt(0) + 1));
        }
        // console.log(this.studentOutcomes);
      }

      return oldStudentOutcomes;
    },
    reorderPositionUpdate() {
      // var last_pos = this.student_outcomes.length - 1;
      // var last_student_outcome = this.student_outcomes[last_pos];

      var end_index = 0;

      for(var i = 0; i < this.studentOutcomes.length; i++) {
        if(this.studentOutcomes.so_code == this.prev_so) {
          end_index = i;
          break;
        }
      }

      var oldStudentOutcomes = JSON.parse(JSON.stringify(this.student_outcomes));


      //find pos of selected letter
      var pos = 0;
      for(var i = 0; i < this.student_outcomes.length; i++) {
        if(this.form.so_code == this.student_outcomes[i].so_code) {
          pos = i;
          break;
        }
      }

      for(var i = pos; i < end_index; i++) {
        this.student_outcomes[i].so_code = String.fromCharCode((this.student_outcomes[i].so_code.charCodeAt(0) + 1));
      }
      // console.log(this.studentOutcomes);

      return oldStudentOutcomes;
    }
  },
  created() {
    this.form.program = this.programId;
    this.student_outcomes = this.studentOutcomes;
    if (this.isUpdate) {
      this.prev_so = this.studentOutcome.so_code;
      this.form.id = this.studentOutcome.id;
      this.form.so_code = this.studentOutcome.so_code;
      this.form.description = this.studentOutcome.description;
      this.form.program = this.studentOutcome.program_id;
      this.form.performance_criteria = this.performanceCriteria.description;

      for (let i = 0; i < this.performanceIndicators.length; i++) {
        if (this.performanceIndicators[i].performance_indicator_id == 1) {
          //unsatisfactory
          this.form.unsatisfactory_desc = this.performanceIndicators[
            i
          ].description;
          this.form.unsatisfactory_grade = this.performanceIndicators[
            i
          ].score_percentage;
        } else if (
          this.performanceIndicators[i].performance_indicator_id == 2
        ) {
          //developing
          this.form.developing_desc = this.performanceIndicators[i].description;
          this.form.developing_grade = this.performanceIndicators[
            i
          ].score_percentage;
        } else if (
          this.performanceIndicators[i].performance_indicator_id == 3
        ) {
          //satisfactory
          this.form.satisfactory_desc = this.performanceIndicators[
            i
          ].description;
          this.form.satisfactory_grade = this.performanceIndicators[
            i
          ].score_percentage;
        } else if (
          this.performanceIndicators[i].performance_indicator_id == 4
        ) {
          //exemplary
          this.form.exemplary_desc = this.performanceIndicators[i].description;
          this.form.exemplary_grade = this.performanceIndicators[
            i
          ].score_percentage;
        }
      }
    } else {
      // console.log(this.studentOutcomes);
      if(this.studentOutcomes.length <= 0) {
        this.studentOutcomes.push({
          so_code: "A"
        });
      } else {
        var letter = String.fromCharCode(this.studentOutcomes[this.studentOutcomes.length - 1].so_code.charCodeAt(0) + 1);
        this.form.so_code = letter;
        this.studentOutcomes.push({
          so_code: letter
        });
      }
    }
  }
};
</script>

<style scoped></style>
