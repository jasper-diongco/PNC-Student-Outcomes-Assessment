<template>
  <div>
    <!-- Activator -->
    <!-- <button
      v-if="isUpdate"
      data-toggle="modal"
      data-target="#curriculumCourseModal"
      class="btn btn-success btn-sm"
    >
      Update <i class="fa fa-edit"></i>
    </button> -->
    <!-- <button v-on:click="removeCurriculumCourse(curriculumCourse.id)" class="btn btn-success btn-sm">Update <i class="fa fa-edit"></i></button> -->
    <!-- End Activator -->

    <!-- Modal -->
    <div
      class="modal fade"
      :id="isUpdate ? 'curriculumCourseModalUpdate' : 'curriculumCourseModal'"
      tabindex="-1"
      role="dialog"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <form
          @submit.prevent="saveCourse"
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
              <!-- Field for year course_code  -->
              <div class="form-group row">
                <label
                  for="course_code"
                  class="col-md-3 col-form-label text-md-right"
                  ><b>Course Code</b></label
                >

                <div class="col-md-9">
                  <input
                    id="course_code"
                    type="text"
                    :value="course.course_code"
                    readonly
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.has('course_id') }"
                  />
                  <has-error :form="form" field="course_id"></has-error>
                </div>
              </div>
              <!-- /end Field for course_code -->

              <!-- Field for year course_code  -->
              <div class="form-group row">
                <label
                  for="description"
                  class="col-md-3 col-form-label text-md-right"
                  ><b>Description</b></label
                >

                <div class="col-md-9">
                  <input
                    id="description"
                    type="text"
                    :value="course.description"
                    readonly
                    class="form-control"
                  />
                </div>
              </div>
              <!-- /end Field for course_code -->

              <!-- Field for year level  -->
              <div class="form-group row">
                <label for="year" class="col-md-3 col-form-label text-md-right"
                  ><b>Year Level</b></label
                >

                <div class="col-md-9">
                  <select
                    id="year_level"
                    class="form-control"
                    name="year_level"
                    v-model="form.year_level"
                    :class="{ 'is-invalid': form.errors.has('year_level') }"
                  >
                    <!-- <option
                      v-for="num in maxYearLevel"
                      :key="num"
                      :value="num"
                      >{{ num == 1 ? : num + '3rd'}}</option
                    > -->
                    <option value="" style="display: none">Select Year</option>
                    <option value="1">1st year</option>
                    <option value="2">2nd year</option>
                    <option value="3">3rd year</option>
                    <option value="4">4th year</option>
                    <option v-if="maxYearLevel == 5" value="5">5th year</option>
                  </select>
                  <has-error :form="form" field="year_level"></has-error>
                </div>
              </div>
              <!-- /end Field for year -->

              <!-- Field for year level  -->
              <div class="form-group row">
                <label for="year" class="col-md-3 col-form-label text-md-right"
                  ><b>Semester</b></label
                >

                <div class="col-md-9">
                  <select
                    id="semester"
                    class="form-control"
                    name="semester"
                    v-model="form.semester"
                    :class="{ 'is-invalid': form.errors.has('semester') }"
                  >
                    <option value="" style="display: none"
                      >Select Semester</option
                    >
                    <option value="1">1st semester</option>
                    <option value="2">2nd semester</option>
                    <option value="3">Summer</option>
                  </select>
                  <has-error :form="form" field="semester"></has-error>
                </div>
              </div>
              <!-- /end Field for year level -->

              <!-- Pre requisite section -->
              <template>
                <hr />
                <p><b>Pre requisite(s)</b></p>
                <div class="form-group">
                  <div
                    v-if="isSelectPreqMode || form.errors.has('pre_requisites')"
                  >
                    <label>Select pre requisite</label>
                    <!--
                    <select
                      id="combobox"
                      class="form-control"
                      name="_select_pre_preq"
                      v-model="selectedPreReq"
                      @change="addPreRequisite"
                      :class="{
                        'is-invalid': form.errors.has('pre_requisites')
                      }"
                    >
                      <template v-if="selectPreRequsiteCourses().length > 0">
                        <option value="" style="display: none"
                          >Select pre-req</option
                        >
                        <option
                          v-for="c in selectPreRequsiteCourses()"
                          :key="c.id"
                          :value="c"
                        >
                          {{ c.course_code + " - " + c.description }}
                        </option>
                      </template>
                      <template v-else>
                        <option value="no-available" disabled
                          >No Available</option
                        >
                      </template>
                    </select>
                    -->
                    <vue-select
                      @input="addPreRequisite"
                      v-model="selectedPreReq"
                      :options="selectPreRequsiteCourses()"
                      :filter="filter"
                    >
                      <template slot="option" slot-scope="option">
                        <div class="d-center">
                          {{ option.course_code + " - " + option.description }}
                        </div>
                      </template>
                      <template slot="selected-option" slot-scope="option">
                        <div class="selected d-center">
                          {{ option.course_code + " - " + option.description }}
                        </div>
                      </template>
                    </vue-select>
                    <select
                      style="display: none"
                      class="form-control"
                      :class="{
                        'is-invalid': form.errors.has('pre_requisites')
                      }"
                    ></select>
                    <has-error :form="form" field="pre_requisites"></has-error>
                  </div>
                  <button
                    v-else
                    @click="isSelectPreqMode = true"
                    type="button"
                    class="btn btn-primary btn-sm mb-2"
                  >
                    Add pre req <i class="fa fa-plus"></i>
                  </button>
                </div>

                <ul class="list-group">
                  <template v-if="form.pre_requisites.length > 0">
                    <li
                      v-for="preReq in form.pre_requisites"
                      class="list-group-item d-flex justify-content-between align-items-center"
                    >
                      <div>
                        {{ preReq.course_code + " - " + preReq.description }}
                      </div>
                      <button
                        @click="removePreRequisite(preReq.id)"
                        type="button"
                        class="btn btn-sm btn-secondary"
                      >
                        <i class="fa fa-trash-alt"></i>
                      </button>
                    </li>
                  </template>
                  <template v-else>
                    <li class="list-group-item">No Pre-requisite</li>
                  </template>
                </ul>
              </template>
              <!-- /END Pre requisite section -->
            </div>
            <div class="modal-footer">
              <button
                type="button"
                class="btn btn-secondary"
                data-dismiss="modal"
                :disabled="form.busy"
                @click="closeModal"
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
  props: [
    "course",
    "maxYearLevel",
    "curriculumId",
    "isUpdate",
    "curriculumCourse",
    "curriculumCourses"
  ],
  data() {
    return {
      form: new Form({
        curriculum_course_id: "",
        course_id: "",
        curriculum_id: "",
        year_level: "",
        semester: "",
        pre_requisites: []
      }),
      isSelectPreqMode: true,
      selectedPreReq: "",
      curriculumCourseAjax: ""
    };
  },
  watch: {
    course(val) {
      this.form.course_id = val.id;
    },
    curriculumCourse(val) {
      this.form.curriculum_course_id = val.id;
      this.form.course_id = val.course_id;
      this.form.year_level = val.year_level;
      this.form.semester = val.semester;

      //get pre requisite through ajax
      this.getPreRequisites();
    }
  },
  computed: {
    modalTitle() {
      return this.isUpdate ? "Edit course" : "Add new Course to Curriculum";
    },
    saveTitle() {
      return this.isUpdate ? "Update" : "Add";
    }
  },
  methods: {
    saveCourse() {
      if (this.isUpdate) {
        this.updateCourse();
      } else {
        this.addCourse();
      }
    },
    addCourse() {
      this.form
        .post("../curriculum_courses")
        .then(({ data }) => {
          toast.fire({
            type: "success",
            title: "The course is successfully added."
          });
          this.$emit("refresh-curriculum");
          this.closeModal();
          this.form.pre_requisites = [];
        })
        .catch(err => {
          console.log(err);
          toast.fire({
            type: "error",
            title: "Please enter valid data."
          });
        });
    },
    updateCourse() {
      this.form
        .put("../curriculum_courses/" + this.form.curriculum_course_id)
        .then(({ data }) => {
          toast.fire({
            type: "success",
            title: "The course is successfully updated."
          });
          this.$emit("refresh-curriculum");
          this.closeModal();
        })
        .catch(err => {
          console.log(err);
          toast.fire({
            type: "error",
            title: "Please enter valid data."
          });
        });
    },
    closeModal() {
      if (this.isUpdate) {
        $("#curriculumCourseModalUpdate").modal("hide");
      } else {
        $("#curriculumCourseModal").modal("hide");
      }

      this.form.clear();
      this.form.year_level = "";
      this.form.semester = "";
    },
    addPreRequisite() {
      if (this.selectedPreReq != "" && this.selectedPreReq != null) {
        this.form.pre_requisites.push(this.selectedPreReq);
        this.selectedPreReq = "";
      }
    },
    removePreRequisite(id) {
      for (let i = 0; i < this.form.pre_requisites.length; i++) {
        if (this.form.pre_requisites[i].id == id) {
          return this.form.pre_requisites.splice(i, 1);
        }
      }
    },
    selectPreRequsiteCourses() {
      let available = [];

      for (let i = 0; i < this.curriculumCourses.length; i++) {
        let found = false;
        for (let j = 0; j < this.form.pre_requisites.length; j++) {
          if (this.curriculumCourses[i].id == this.form.pre_requisites[j].id) {
            found = true;
            break;
          }
        }
        if (!found) {
          available.push(this.curriculumCourses[i]);
        }
      }

      return available;
    },
    getPreRequisites() {
      ApiClient.get(
        "curriculum_courses/" + this.form.curriculum_course_id
      ).then(response => {
        const resp_pre_requisites = response.data.data.pre_requisites;
        this.form.pre_requisites = [];
        for (let i = 0; i < resp_pre_requisites.length; i++) {
          this.form.pre_requisites.push({
            id: response.data.data.pre_requisites[i].pre_req_id,
            course_code: resp_pre_requisites[i].pre_req_code,
            description: resp_pre_requisites[i].pre_req_desc,
            year_level: resp_pre_requisites[i].year_level,
            semester: resp_pre_requisites[i].semester,
            pre_req_id: resp_pre_requisites[i].id
          });
        }
      });
    },
    filter(options, search) {
      return options.filter(option => {
        //let label = this.getOptionLabel(option);
        return (
          option.description.toLowerCase().indexOf(search.toLowerCase()) >= 0 ||
          option.course_code.toLowerCase().indexOf(search.toLowerCase()) >= 0
        );
      });
    }
  },
  created() {
    this.form.curriculum_id = this.curriculumId;
    // if (this.isUpdate) {
    //   this.getCurriculumCourse();
    // }

    //update
    // if (this.isUpdate) {
    //   this.form.course_id = this.curriculumCourse.course_id;
    //   this.form.year_level = this.curriculumCourse.year_level;
    //   this.form.semester = this.curriculumCourse.semester;
    //   console.log(this.form);
    // }
  }
};
</script>
