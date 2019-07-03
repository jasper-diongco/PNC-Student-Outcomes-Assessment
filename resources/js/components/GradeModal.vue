<template>
    <div class="modal fade" id="gradeModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Add Grade
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
                    <div class="form-group">
                        <label>Course</label>
                        <input
                            type="text"
                            class="form-control"
                            readonly
                            :value="courseCode"
                        />
                    </div>
                    <div class="form-group">
                        <label>Grade</label>
                        <select
                            v-model="grade_value_id"
                            class="form-control"
                            :class="{ 'is-invalid': grade_value_id_error }"
                        >
                            <option value="" class="d-none"
                                >Select Grade</option
                            >
                            <option
                                v-for="gradeValue in gradeValues"
                                :key="gradeValue.id"
                                :value="gradeValue.id"
                            >
                                {{ gradeValue.value }}
                            </option>
                        </select>
                        <div class="invalid-feedback">Please Select Grade</div>
                    </div>
                    <div class="form-group">
                        <label>Professor</label>
                        <input
                            type="search"
                            placeholder="Search Professor..."
                            class="form-control"
                            v-model="search_professor"
                            @input="searchProfessor"
                            :class="{ 'is-invalid': selected_faculty_error }"
                        />
                        <div class="invalid-feedback">
                            Please select professor.
                        </div>
                        <template v-if="search_professor_loading">
                            <table-loading></table-loading>
                        </template>
                        <template v-else>
                            <ul v-if="faculties.length > 0" class="list-group">
                                <li
                                    v-for="faculty in faculties"
                                    class="list-group-item"
                                >
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            {{
                                                faculty.user.last_name +
                                                    ", " +
                                                    faculty.user.first_name +
                                                    " " +
                                                    faculty.user.middle_name
                                            }}
                                            - {{ faculty.college.college_code }}
                                        </div>
                                        <div>
                                            <button
                                                @click="selectFaculty(faculty)"
                                                class="btn btn-sm btn-success"
                                            >
                                                Select
                                            </button>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </template>
                        <div v-if="selected_faculty">
                            <label class="mt-3">Selected: </label>
                            <span
                                style="font-size: 120%"
                                class="text-primary"
                                >{{
                                    selected_faculty.user.last_name +
                                        ", " +
                                        selected_faculty.user.first_name +
                                        " " +
                                        selected_faculty.user.middle_name
                                }}</span
                            >
                            <i class="fa fa-check-circle text-success"></i>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal"
                    >
                        Close
                    </button>
                    <button
                        @click="addGrade"
                        type="button"
                        class="btn btn-success"
                        :disabled="isLoading"
                    >
                        Add Grade
                        <div
                            class="spinner-border spinner-border-sm text-light"
                            role="status"
                            v-if="isLoading"
                        >
                            <span class="sr-only">Loading...</span>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["studentId", "courseId", "courseCode", "gradeValues"],
    data() {
        return {
            grade_value_id: "",
            grade_value_id_error: false,
            search_professor: "",
            search_professor_loading: false,
            faculties: [],
            selected_faculty: null,
            selected_faculty_error: false,
            isLoading: false
        };
    },
    methods: {
        addGrade() {
            this.isLoading = true;
            if (this.grade_value_id == "") {
                this.grade_value_id_error = true;
            } else {
                this.grade_value_id_error = false;
            }

            if (this.selected_faculty == null) {
                this.selected_faculty_error = true;
            } else {
                this.selected_faculty_error = false;
            }

            if (!this.grade_value_id_error && !this.selected_faculty_error) {
                ApiClient.post("/grades", {
                    grade_value_id: this.grade_value_id,
                    student_id: this.studentId,
                    course_id: this.courseId,
                    faculty_id: this.selected_faculty.id
                })
                    .then(response => {
                        $("#gradeModal").modal("hide");
                        toast.fire({
                            type: "success",
                            title: "Grade Successfully Added"
                        });
                        console.log(response);
                        this.isLoading = false;
                        this.$emit("grade-added");
                    })
                    .catch(error => {
                        alert("An error has occured. Please try again");
                        this.isLoading = false;
                    });
            }
        },
        searchProfessor() {
            var vm = this;
            // _.debounce(() => {
            if (vm.search_professor == "") {
                return (vm.faculties = []);
            }
            vm.search_professor_loading = true;
            ApiClient.get(
                "/faculties/search_faculties?q=" + vm.search_professor
            ).then(response => {
                vm.search_professor_loading = false;
                vm.faculties = response.data;
            });
            // }, 300);
        },
        selectFaculty(faculty) {
            this.selected_faculty = faculty;
            this.faculties = [];
            this.search_professor = "";
        }
    }
};
</script>
