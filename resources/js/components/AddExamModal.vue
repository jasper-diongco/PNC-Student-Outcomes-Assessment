<template>
    <div>
        <!-- Modal -->
        <div
            class="modal fade"
            id="addExamModal"
            tabindex="-1"
            role="dialog"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background: #9dda58">
                        <h5 class="modal-title">
                            Generate new Exam <i class="fa fa-file-alt"></i>
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
                        v-on:submit.prevent="saveExam"
                        v-on:keydown="form.onKeydown($event)"
                    >
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea
                                            v-model="form.description"
                                            name="description"
                                            class="form-control"
                                            :readonly="isLoading || isFinished"
                                            :class="{
                                                'is-invalid': form.errors.has(
                                                    'description'
                                                )
                                            }"
                                        ></textarea>
                                        <has-error
                                            :form="form"
                                            field="description"
                                        ></has-error>
                                    </div>

                                    <div class="form-group">
                                        <label>Time Limit (in minutes)</label>
                                        <input
                                            v-model="form.time_limit"
                                            type="number"
                                            name="time_limit"
                                            class="form-control"
                                            :readonly="isLoading || isFinished"
                                            :class="{
                                                'is-invalid': form.errors.has(
                                                    'time_limit'
                                                )
                                            }"
                                        />
                                        <has-error
                                            :form="form"
                                            field="time_limit"
                                        ></has-error>
                                    </div>

                                    <div class="form-group">
                                        <label
                                            >Passing Grade (in
                                            percentage)</label
                                        >
                                        <input
                                            v-model="form.passing_grade"
                                            type="number"
                                            name="passing_grade"
                                            class="form-control"
                                            :readonly="isLoading || isFinished"
                                            :class="{
                                                'is-invalid': form.errors.has(
                                                    'passing_grade'
                                                )
                                            }"
                                        />
                                        <has-error
                                            :form="form"
                                            field="passing_grade"
                                        ></has-error>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h5>
                                        List of Courses
                                    </h5>
                                    <span v-if="isLoading" class="text-muted"
                                        >Please wait, getting random test
                                        questions from test bank...
                                        <div class="spinner-grow" role="status">
                                            <span class="sr-only"
                                                >Loading...</span
                                            >
                                        </div></span
                                    >
                                    <div
                                        style="max-height: 400px; overflow-y: auto;"
                                    >
                                        <ul class="list-group">
                                            <li
                                                class="list-group-item"
                                                v-for="course in courses_requirements"
                                                :key="course.course.course_code"
                                            >
                                                <div
                                                    class="d-flex justify-content-between"
                                                >
                                                    <div>
                                                        <span
                                                            style="font-weight: 600"
                                                            >{{
                                                                course.course
                                                                    .course_code
                                                            }}</span
                                                        >
                                                        -
                                                        {{
                                                            course.test_question_count
                                                        }}
                                                        <i
                                                            class="fa fa-question-circle"
                                                            style="color: #b1a9a9"
                                                        ></i>
                                                    </div>
                                                    <div v-if="isLoading">
                                                        <div
                                                            v-if="
                                                                course.isLoading
                                                            "
                                                            class="spinner-border spinner-border-sm"
                                                            role="status"
                                                            style="color: #b1a9a9"
                                                        >
                                                            <span
                                                                class="sr-only"
                                                                >Loading...</span
                                                            >
                                                        </div>
                                                        <checked-icon
                                                            v-else
                                                        ></checked-icon>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button
                                type="button"
                                class="btn"
                                data-dismiss="modal"
                            >
                                Close
                            </button>
                            <button
                                class="btn btn-info"
                                :disabled="form.busy"
                                type="submit"
                            >
                                Generate <i class="fa fa-cog"></i>
                                <div
                                    v-if="form.busy || isLoading"
                                    class="spinner-border spinner-border-sm text-light"
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
        "curriculumId",
        "studentOutcomeId",
        "courses",
        "is_revised",
        "exam_id",
        "item_analysis_id",
        "program_id"
    ],
    data() {
        return {
            form: new Form({
                description: "",
                time_limit: 60,
                passing_grade: 60,
                curriculum_id: "",
                student_outcome_id: ""
            }),
            courses_requirements: [],
            isLoading: false,
            isFinished: false,
            response: ""
        };
    },
    methods: {
        addExam() {
            this.getRandomTestQuestions();

            var url = myRootURL + "/exams";

            if (this.is_revised) {
                url =
                    myRootURL +
                    "/exams/revise_exam/" +
                    this.exam_id +
                    "?item_analysis_id=" +
                    this.item_analysis_id;
            }

            this.form
                .post(url)
                .then(response => {
                    this.isFinished = true;
                    this.response = response;
                    //$("#addExamModal").modal("hide");

                    // this.$emit("new-exam-added");
                    console.log(response);
                })
                .catch(err => {
                    this.isFinished = false;
                    this.isLoading = false;
                    if (err.response.data.my_code == "insufficient") {
                        swal.fire({
                            type: "error",
                            title: "Oops, Error!",
                            text:
                                "Insufficient Test Questions! Please add more test questions."
                        });
                    }
                });
        },
        saveExam() {
            this.addExam();
        },
        getRandomTestQuestions() {
            this.isLoading = true;
            for (var i = 0; i < this.courses_requirements.length; i++) {
                this.courses_requirements[i].isLoading = true;
            }
            var index = 0;
            this.loop(index);
        },
        loop(index) {
            var rand = Math.round(Math.random() * (900 - 200)) + 200;
            setTimeout(() => {
                if (index < this.courses_requirements.length) {
                    this.courses_requirements[index].isLoading = false;
                    if (
                        index == this.courses_requirements.length - 1 &&
                        this.isFinished
                    ) {
                        if (this.is_revised) {
                            window.location.replace(
                                myRootURL +
                                    "/exams/" +
                                    this.response.data.exam.id +
                                    "?student_outcome_id=" +
                                    this.studentOutcomeId +
                                    "&curriculum_id=" +
                                    this.curriculumId +
                                    "&program_id=" +
                                    this.program_id
                            );
                        } else {
                            this.$emit("new-exam-added");
                            $("#addExamModal").modal("hide");
                            toast.fire({
                                type: "success",
                                title: "New Exam Successfully Created"
                            });
                        }
                    }
                    index++;
                }
                this.loop(index);
            }, rand);
        }
    },
    created() {
        this.form.curriculum_id = this.curriculumId;
        this.form.student_outcome_id = this.studentOutcomeId;
        this.courses_requirements = JSON.parse(JSON.stringify(this.courses));
    }
};
</script>
