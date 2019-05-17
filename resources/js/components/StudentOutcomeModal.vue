<template>
  <div>
    <!-- Activator -->
    <button
      type="button"
      class="btn btn-success btn-round"
      data-toggle="modal"
      data-target="#courseModal"
    >
      Add new Student Outcome <i class="fa fa-plus"></i>
    </button>
    <!-- End Activator -->

    <!-- Modal -->
    <div
      class="modal fade"
      id="courseModal"
      tabindex="-1"
      role="dialog"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
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
              <div class="form-group row">
                <label
                  for="so_code"
                  class="col-md-3 col-form-label text-md-right"
                  ><b>SO Code</b></label
                >

                <div class="col-md-9">
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
                </div>
              </div>
              <!-- /end Field for so code -->

              <!-- Field for description  -->
              <div class="form-group row">
                <label
                  for="course_code"
                  class="col-md-3 col-form-label text-md-right"
                  ><b>Description</b></label
                >

                <div class="col-md-9">
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
              <div class="form-group row">
                <label
                  for="program"
                  class="col-md-3 col-form-label text-md-right"
                  ><b>Program</b></label
                >

                <div class="col-md-9">
                  <select
                    id="program"
                    class="form-control"
                    name="program"
                    v-model="form.program"
                    v-uppercase
                    :class="{ 'is-invalid': form.errors.has('program') }"
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
              <div class="form-group row">
                <label
                  for="performance_criteria"
                  class="col-md-3 col-form-label text-md-right"
                  ><b>Performance Criteria</b></label
                >

                <div class="col-md-9">
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
                  <div class="form-group row mt-3">
                    <label
                      for="unsatisfactory_desc"
                      class="col-md-3 col-form-label text-md-right"
                      ><b>Unsatisfactory Description</b></label
                    >

                    <div class="col-md-9">
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
                  <div class="form-group row mt-3">
                    <label
                      for="unsatisfactory_grade"
                      class="col-md-3 col-form-label text-md-right"
                      ><b>Grade</b></label
                    >

                    <div class="col-md-9">
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
                  <div class="form-group row mt-3">
                    <label
                      for="developing_desc"
                      class="col-md-3 col-form-label text-md-right"
                      ><b>Developing Description</b></label
                    >

                    <div class="col-md-9">
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
                  <div class="form-group row mt-3">
                    <label
                      for="developing_grade"
                      class="col-md-3 col-form-label text-md-right"
                      ><b>Grade</b></label
                    >

                    <div class="col-md-9">
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
                  <div class="form-group row mt-3">
                    <label
                      for="satisfactory_desc"
                      class="col-md-3 col-form-label text-md-right"
                      ><b>Satisfactory Description</b></label
                    >

                    <div class="col-md-9">
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
                  <div class="form-group row mt-3">
                    <label
                      for="satisfactory_grade"
                      class="col-md-3 col-form-label text-md-right"
                      ><b>Grade</b></label
                    >

                    <div class="col-md-9">
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
                  <div class="form-group row mt-3">
                    <label
                      for="exemplary_desc"
                      class="col-md-3 col-form-label text-md-right"
                      ><b>Exemplary Description</b></label
                    >

                    <div class="col-md-9">
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
                  <div class="form-group row mt-3">
                    <label
                      for="exemplary_grade"
                      class="col-md-3 col-form-label text-md-right"
                      ><b>Grade</b></label
                    >

                    <div class="col-md-9">
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
                class="btn btn-primary"
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
  props: ["programs"],
  data() {
    return {
      form: new Form({
        description: "",
        so_code: "",
        program: "",
        performance_criteria: "",
        unsatisfactory_desc: "unsatisfactory",
        unsatisfactory_grade: "25.00",
        developing_desc: "developing",
        developing_grade: "50.00",
        satisfactory_desc: "satisfactory",
        satisfactory_grade: "75.00",
        exemplary_desc: "exemplary",
        exemplary_grade: "95.00"
      })
    };
  },
  computed: {
    modalTitle() {
      return "Add new Student Outcome";
    },
    saveTitle() {
      return "Add to database";
    }
  },
  methods: {
    saveStudentOutcome() {
      this.addStudentOutcome();
    },
    addStudentOutcome() {
      this.form
        .post("student_outcomes")
        .then(({ data }) => {
          console.log(data);
          window.location.href = myRootURL + "/student_outcomes/";
        })
        .catch(err => {
          console.log(err);
        });
    }
  }
};
</script>

<style scoped></style>
