<template>
    <div>
        <!-- Modal -->
        <div
            class="modal fade"
            id="reportTestQuestionModal"
            tabindex="-1"
            role="dialog"
            aria-labelledby="reportTestQuestionModal"
            aria-hidden="true"
        >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            Report Test Question
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
                        @submit.prevent="reportProblem"
                        @keydown="form.onKeydown($event)"
                    >
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Problem Description</label>

                                <textarea
                                    v-model="form.message"
                                    type="text"
                                    name="message"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': form.errors.has('message')
                                    }"
                                    cols="30"
                                    rows="10"
                                    placeholder="Please tell the problem for this test question."
                                ></textarea>
                                <has-error
                                    :form="form"
                                    field="message"
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
                                :disabled="form.busy"
                                type="submit"
                                class="btn btn-success"
                            >
                                Report
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
    props: ["test_question_id", "student_id"],
    data() {
        return {
            form: new Form({
                message: "",
                test_question_id: "",
                student_id: ""
            })
        };
    },
    watch: {
        test_question_id() {
            this.form.test_question_id = this.test_question_id;
            this.form.student_id = this.student_id;
        },
        student_id() {
            this.form.test_question_id = this.test_question_id;
            this.form.student_id = this.student_id;
        }
    },
    methods: {
        reportProblem() {
            this.form
                .post(myRootURL + "/test_question_problems")
                .then(response => {
                    swal.fire({
                        title: "Successfully Reported",
                        type: "success",
                        text: "The test question report has been submitted"
                    });

                    this.message = "";

                    this.$emit("reported", this.test_question_id);

                    $("#reportTestQuestionModal").modal("hide");
                });
        }
    }
};
</script>
