<template>
    <div>
        <!-- Modal -->
        <div
            class="modal fade"
            id="customRecordedAssessmentRecordModal"
            tabindex="-1"
            role="dialog"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background: #8bc34a">
                        <h5 class="modal-title" id="exampleModalLabel">
                            Add Record
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
                        @submit.prevent="addRecordedAssessment"
                        @keydown="form.onKeydown($event)"
                    >
                        <div class="modal-body px-3">
                            <div class="my-3">
                                <label><i>Search Student</i></label>
                                <input
                                    type="search"
                                    placeholder="Enter name or studentID to search..."
                                    class="form-control"
                                    v-model="searchText"
                                    v-on:input="searchStudent"
                                />

                                <div v-if="students.length > 0" class="shadow">
                                    <ul class="list-group">
                                        <li
                                            class="list-group-item"
                                            v-for="student in students"
                                            :key="student.id"
                                        >
                                            <div
                                                class="d-flex justify-content-between align-items-baseline"
                                            >
                                                <div>
                                                    {{ student.student_id }}
                                                    &mdash;
                                                    {{ student.full_name }}
                                                </div>
                                                <div>
                                                    <button
                                                        @click="
                                                            selectStudent(
                                                                student
                                                            )
                                                        "
                                                        class="btn btn-sm btn-info"
                                                    >
                                                        Select
                                                    </button>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Student</label>
                                <input
                                    v-model="form.student_name"
                                    name="student_id"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': form.errors.has(
                                            'student_id'
                                        )
                                    }"
                                    readonly
                                    placeholder="Please Select Student"
                                />
                                <has-error
                                    :form="form"
                                    field="student_id"
                                ></has-error>
                            </div>
                            <div
                                v-if="coursesGrade.length > 0"
                                class="table-responsive"
                            >
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Course Code</th>
                                            <th>Description</th>
                                            <th>Units</th>
                                            <th>Grade</th>
                                            <th>Remarks</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-weight: 400">
                                        <!-- @foreach($student_outcome->getCoursesGrade($student->curriculum_id, $student_outcome->id, $student->id) as $course_grade) -->
                                        <tr
                                            v-for="courseGrade in coursesGrade"
                                            :class="{
                                                'bg-success-light':
                                                    courseGrade.is_passed,
                                                'bg-danger-light':
                                                    courseGrade.remarks ==
                                                    'Failed'
                                            }"
                                            :key="courseGrade.course_code"
                                        >
                                            <td>
                                                {{ courseGrade.course_code }}
                                            </td>
                                            <td>
                                                {{ courseGrade.course_desc }}
                                            </td>
                                            <td>
                                                {{
                                                    courseGrade.lec_unit +
                                                        courseGrade.lab_unit
                                                }}
                                            </td>
                                            <td>
                                                {{ courseGrade.grade_text }}
                                            </td>
                                            <td>
                                                {{ courseGrade.remarks }}
                                            </td>

                                            <td>
                                                <i
                                                    v-if="courseGrade.is_passed"
                                                    class="fa fa-check text-success"
                                                ></i>

                                                <i
                                                    v-else
                                                    class="fa fa-times"
                                                ></i>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="form-group">
                                <label
                                    >Score &mdash; Max - {{ max_score }}
                                </label>
                                <input
                                    v-model="form.score"
                                    type="number"
                                    name="score"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': form.errors.has('score')
                                    }"
                                    min="0"
                                />
                                <has-error
                                    :form="form"
                                    field="score"
                                ></has-error>
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
                                :disabled="form.busy || !checkIfAvailble()"
                                type="submit"
                                class="btn btn-info"
                            >
                                Add
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
    props: ["custom_recorded_assessment_id", "student_outcome_id", "max_score"],
    data() {
        return {
            form: new Form({
                score: "",
                custom_recorded_assessment_id: "",
                student_name: "",
                student_id: ""
            }),
            searchText: "",
            students: [],
            coursesGrade: []
        };
    },
    watch: {
        searchText() {
            if (this.searchText == "" || this.searchText == null) {
                this.students = [];
            }
        }
    },
    methods: {
        addRecordedAssessment() {
            this.form
                .post(myRootURL + "/custom_recorded_assessment_records")
                .then(response => {
                    $("#customRecordedAssessmentRecordModal").modal("hide");
                    this.$emit("record-added");

                    toast.fire({
                        type: "success",
                        title: "Successfully Recorded"
                    });

                    this.student_name = "";
                    this.student_id = "";
                    this.coursesGrade = [];
                })
                .catch(err => {
                    console.log(err.response);
                });
        },
        searchStudent() {
            if (this.searchText == "" || this.searchText == null) {
                return (this.students = []);
            }

            ApiClient.get("/students?json=true&q=" + this.searchText).then(
                response => {
                    this.students = response.data.data;
                }
            );
        },
        selectStudent(student) {
            this.form.student_id = student.id;
            this.form.student_name =
                "(" + student.student_id + ") " + student.full_name;
            this.students = [];
            this.searchText = "";
            this.getCoursesGrade();
        },
        getCoursesGrade() {
            ApiClient.get(
                "custom_recorded_assessment_records/get_courses_grade?student_id=" +
                    this.form.student_id +
                    "&student_outcome_id=" +
                    this.student_outcome_id
            ).then(response => {
                this.coursesGrade = response.data;
            });
        },
        checkIfAvailble() {
            var is_available = true;
            for (var i = 0; i < this.coursesGrade.length; i++) {
                if (!this.coursesGrade[i].is_passed) {
                    is_available = false;
                    break;
                }
            }

            return is_available;
        }
    },
    created() {
        this.form.custom_recorded_assessment_id = this.custom_recorded_assessment_id;
    }
};
</script>
