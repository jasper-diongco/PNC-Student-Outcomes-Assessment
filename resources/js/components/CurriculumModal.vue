<template>
  <div>
    <!-- Activator -->
    <!-- <button
        type="button"
        class="btn btn-success btn-success btn-round"
        data-toggle="modal"
        data-target="#curriculumModal"
      >
        Add New Curriculum <i class="fa fa-plus"></i>
      </button> -->
    <div class="dropdown">
      <button
        class="btn btn-success btn-round"
        type="button"
        id="dropdownMenuButton"
        data-toggle="dropdown"
        aria-haspopup="true"
        aria-expanded="false"
      >
        Add New Curriculum <i class="fa fa-caret-down"></i>
      </button>
      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <a
          v-for="program in programs"
          :key="program.id"
          @click="selectProgram(program)"
          data-toggle="modal"
          data-target="#curriculumModal"
          class="dropdown-item d-flex justify-content-between"
          href="#"
        >
          <div>
            <i class="fa fa-chevron-right text-primary"></i>
            {{ program.program_code }}
          </div>
          <div>
            <i class="fa fa-plus text-success"></i>
          </div>
        </a>
        <!--           <a 
            data-toggle="modal"
            data-target="#curriculumModal" 
            class="dropdown-item d-flex justify-content-between" 
            href="#">
            <div>
            <i class="fa fa-chevron-right text-primary"></i> BSCS
            </div> 
            <div>
            <i class="fa fa-plus text-success"></i>
            </div>
          </a> -->
      </div>
    </div>
    <!-- End Activator -->

    <!-- Modal -->

    <!-- Modal -->
    <div
      class="modal fade"
      id="curriculumModal"
      tabindex="-1"
      role="dialog"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <form
          @submit.prevent="saveCurriculum"
          @keydown="form.onKeydown($event)"
          autocomplete="off"
        >
          <!-- <form action="" autocomplete="off"></form> -->
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">
                Add new Curriculum for {{ selectedProgram.program_code }}
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
              <!-- Field for name  -->
              <div class="form-group row">
                <label for="name" class="col-md-3 col-form-label text-md-right"
                  ><b>Name</b></label
                >

                <div class="col-md-9">
                  <input
                    id="name"
                    type="text"
                    class="form-control"
                    name="name"
                    v-model="form.name"
                    v-uppercase
                    :class="{ 'is-invalid': form.errors.has('name') }"
                  />
                  <has-error :form="form" field="name"></has-error>
                </div>
              </div>
              <!-- /end Field for name -->

              <!-- Field for description  -->
              <div class="form-group row">
                <label
                  for="description"
                  class="col-md-3 col-form-label text-md-right"
                  ><b>Description (optional)</b></label
                >

                <div class="col-md-9">
                  <textarea
                    id="description"
                    type="text"
                    class="form-control"
                    name="description"
                    v-model="form.description"
                    :class="{ 'is-invalid': form.errors.has('description') }"
                  ></textarea>
                  <has-error :form="form" field="description"></has-error>
                </div>
              </div>
              <!-- /end Field for description -->

              <!-- Field for year  -->
              <div class="form-group row">
                <label for="year" class="col-md-3 col-form-label text-md-right"
                  ><b>Year</b></label
                >

                <div class="col-md-9">
                  <select
                    id="year"
                    type="text"
                    class="form-control"
                    name="year"
                    v-model="form.year"
                    :class="{ 'is-invalid': form.errors.has('year') }"
                  >
                    <option :value="yearNow" selected>{{ yearNow }}</option>
                    <option :value="yearNow + 1">{{ yearNow + 1 }}</option>
                  </select>
                  <has-error :form="form" field="year"></has-error>
                </div>
              </div>
              <!-- /end Field for year -->
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
                Add to database
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

    <!-- End Modal -->
  </div>
</template>

<script>
export default {
  props: ["programs"],
  data() {
    return {
      form: new Form({
        program_id: "",
        name: "",
        description: "",
        year: new Date().getFullYear()
      }),
      selectedProgram: {},
      yearNow: ""
    };
  },
  computed: {},
  methods: {
    selectProgram(program) {
      this.form.program_id = program.id;
      this.selectedProgram = program;
    },
    createCurriculum() {
      this.form
        .post("curricula")
        .then(({ data }) => {
          window.location.href = myRootURL + "/curricula/" + data.id;
        })
        .catch(err => {
          console.log(err);
          toast.fire({
            type: "error",
            title: "Please Enter valid data!"
          });
        });
    },
    saveCurriculum() {
      this.createCurriculum();
    }
  },
  created() {
    this.yearNow = new Date().getFullYear();
  }
};
</script>
