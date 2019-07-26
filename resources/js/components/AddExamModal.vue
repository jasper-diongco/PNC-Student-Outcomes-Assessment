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
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            Generate new Exam
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
                            <div class="form-group">
                                <label>Description</label>
                                <textarea
                                    v-model="form.description"
                                    name="description"
                                    class="form-control"
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
                                <label>Passing Grade (in percentage)</label>
                                <input
                                    v-model="form.passing_grade"
                                    type="number"
                                    name="passing_grade"
                                    class="form-control"
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
                        <div class="modal-footer">
                            <button
                                type="button"
                                class="btn btn-secondary"
                                data-dismiss="modal"
                            >
                                Close
                            </button>
                            <button
                                class="btn btn-success"
                                :disabled="form.busy"
                                type="submit"
                            >
                                Generate new exam
                                <div
                                    v-if="form.busy"
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
    props: ["curriculumId", "studentOutcomeId"],
    data() {
        return {
            form: new Form({
                description: "",
                time_limit: 60,
                passing_grade: 60,
                curriculum_id: "",
                student_outcome_id: ""
            })
        };
    },
    methods: {
        addExam() {
            this.form.post(myRootURL + "/exams").then(response => {
                toast.fire({
                    type: "success",
                    title: "New Exam Successfully Created"
                });

                $("#addExamModal").modal("hide");

                this.$emit("new-exam-added");
            });
        },
        saveExam() {
            this.addExam();
        }
    },
    created() {
        this.form.curriculum_id = this.curriculumId;
        this.form.student_outcome_id = this.studentOutcomeId;
    }
};
</script>
