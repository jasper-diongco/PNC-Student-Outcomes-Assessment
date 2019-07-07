<template>
  <div>
    <!-- Modal -->
    <div
      class="modal fade"
      :id="this.isUpdate ? 'facultyModalUpdate' : 'facultyModal'"
      tabindex="-1"
      role="dialog"
    >
      <div class="modal-dialog modal-lg" role="document">
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
          <form
            autocomplete="off"
            v-on:submit.prevent="saveFaculty"
            v-on:keydown="form.onKeydown($event)"
          >
            <div class="modal-body">
              <!-- Field for year last_name  -->
              <div class="form-group">
                <label for="last_name">Last Name</label>

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
                <label for="first_name">First Name</label>

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
                <label for="middle_name">Middle Name</label>

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

              <!-- Field for year sex  -->
              <div class="form-group">
                <label for="sex">Sex</label>

                <div>
                  <select
                    id="sex"
                    type="text"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.has('sex') }"
                    v-model="form.sex"
                  >
                    <option value style="display: none;">Select Sex</option>
                    <option value="M">Male</option>
                    <option value="F">Female</option>
                  </select>
                  <has-error :form="form" field="sex"></has-error>
                </div>
              </div>
              <!-- /end Field for sex -->

              <!-- Field for date of birth  -->
              <div class="form-group">
                <label for="date_of_birth">Date of Birth</label>

                <div>
                  <datepicker
                    id="date_of_birth"
                    placeholder="Select Date"
                    name="date_of_birth"
                    :class="{ 'is-invalid': form.errors.has('date_of_birth') }"
                    v-model="form.date_of_birth"
                  ></datepicker>
                  <input
                    type="hidden"
                    v-model="form.date_of_birth"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.has('date_of_birth') }"
                  />
                  <has-error :form="form" field="date_of_birth"></has-error>
                </div>
              </div>
              <!-- /end Field for date_of_birth -->

              <!-- Field for year email  -->
              <div class="form-group">
                <label for="email">
                  <b>Email</b>
                </label>

                <div>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="basic-addon1">
                        <i class="fa fa-envelope"></i>
                      </span>
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

              <!-- Field for year college  -->
              <div class="form-group">
                <label for="sex">College</label>

                <div>
                  <select
                    id="college"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.has('college') }"
                    v-model="form.college"
                    :disabled="isDean == true"
                  >
                    <option value="" style="display: none;"
                      >Select College</option
                    >
                    <template v-for="college in colleges">
                      <option :value="college.id" :key="college.id">{{
                        college.name
                      }}</option>
                    </template>
                  </select>
                  <has-error :form="form" field="college"></has-error>
                </div>
              </div>
              <!-- /end Field for college -->
            </div>
            <div class="modal-footer">
              <button
                type="button"
                class="btn btn-secondary"
                data-dismiss="modal"
              >
                Close
              </button>
              <button class="btn btn-success" :disabled="form.busy">
                {{ btnTitle }}
                <div
                  v-show="form.busy"
                  class="spinner-border text-light spinner-border-sm"
                  role="status"
                >
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
  props: [
    "colleges",
    "isDean",
    "collegeId",
    "isUpdate",
    "facultyId",
    "refreshUpdate"
  ],
  data() {
    return {
      form: new Form({
        id: "",
        last_name: "",
        first_name: "",
        middle_name: "",
        sex: "",
        date_of_birth: "",
        email: "",
        college: "",
        password: "DefaultPass123"
      })
    };
  },
  computed: {
    btnTitle() {
      return this.isUpdate ? "Update Faculty" : "Add Faculty";
    },
    modalTitle() {
      return this.isUpdate ? "Update Faculty" : "Add New Faculty";
    }
  },
  watch: {
    facultyId() {
      this.getFaculty();
    }
  },
  methods: {
    // updateStudent() {
    //   this.form
    //     .put(myRootURL + "/students/" + this.form.id)
    //     .then(response => {
    //       if (this.refreshUpdate) {
    //         swal
    //           .fire({
    //             title: "Success",
    //             text: "Student Information Successfully Updated",
    //             type: "success",
    //             confirmButtonText: "OK"
    //           })
    //           .then(() => {
    //             window.location.reload();
    //           });
    //       } else {
    //         toast.fire({
    //           type: "success",
    //           title: "Student Information Successfully Updated"
    //         });
    //         $("#updateStudentModal").modal("hide");
    //         this.$emit("refresh-students");
    //       }
    //     })
    //     .catch(err => {
    //       console.log(err);
    //     });
    // },
    getFaculty() {
      ApiClient.get(
        "/faculties/get_faculty/" + this.facultyId + "?json=true"
      ).then(response => {
        this.form.id = response.data.id;
        this.form.last_name = response.data.user.last_name;
        this.form.first_name = response.data.user.first_name;
        this.form.middle_name = response.data.user.middle_name;
        this.form.sex = response.data.user.sex;
        this.form.date_of_birth = response.data.user.date_of_birth;
        this.form.email = response.data.user.email;
        this.form.college = response.data.college_id;
      });
    },
    addFaculty() {
      this.form.post(myRootURL + "/faculties").then(response => {
        toast.fire({
          type: "success",
          title: "Faculty Successfully Created"
        });

        $("#facultyModal").modal("hide");

        this.$emit("refresh-faculties");
      });
    },
    updateFaculty() {
      this.form
        .put(myRootURL + "/faculties/" + this.facultyId)
        .then(response => {
          if (!this.refreshUpdate) {
            toast.fire({
              type: "success",
              title: "Faculty Successfully Updated"
            });

            $("#facultyModalUpdate").modal("hide");

            this.$emit("refresh-faculties");
          } else {
            swal
              .fire({
                title: "Success",
                text: "Faculty Successfully Updated",
                type: "success",
                confirmButtonText: "OK"
              })
              .then(() => {
                window.location.reload();
              });
          }
        });
    },
    saveFaculty() {
      if (this.isUpdate) {
        this.updateFaculty();
      } else {
        this.addFaculty();
      }
    }
  },
  created() {
    if (this.isDean == true) {
      this.form.college = this.collegeId;
    }

    if (this.refreshUpdate == true) {
      this.getFaculty();
    }
  }
};
</script>
