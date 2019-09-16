<template>
    <div>
        <!-- Modal -->
        <div
            class="modal fade"
            id="customRecordedAssessmentModal"
            tabindex="-1"
            role="dialog"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background: #8bc34a">
                        <h5 class="modal-title" id="exampleModalLabel">
                            Add Recorded Assessment
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
                            <div class="form-group">
                                <label>Description</label>
                                <textarea
                                    v-model="form.description"
                                    type="text"
                                    name="description"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': form.errors.has(
                                            'description'
                                        )
                                    }"
                                    cols="10"
                                    rows="5"
                                ></textarea>
                                <has-error
                                    :form="form"
                                    field="description"
                                ></has-error>
                            </div>
                            <div class="form-group">
                                <label>Overall Score</label>
                                <input
                                    v-model="form.overall_score"
                                    type="number"
                                    name="overall_score"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': form.errors.has(
                                            'overall_score'
                                        )
                                    }"
                                />
                                <has-error
                                    :form="form"
                                    field="overall_score"
                                ></has-error>
                            </div>

                            <div class="form-group">
                                <label>Passing Grade (Percentage)</label>
                                <input
                                    v-model="form.passing_percentage"
                                    type="number"
                                    name="passing_percentage"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': form.errors.has(
                                            'passing_percentage'
                                        )
                                    }"
                                />
                                <has-error
                                    :form="form"
                                    field="passing_percentage"
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
    props: ["curriculum_id", "student_outcome_id"],
    data() {
        return {
            form: new Form({
                description: "",
                overall_score: "",
                passing_percentage: "",
                curriculum_id: "",
                student_outcome_id: ""
            })
        };
    },
    watch: {
        curriculum_id() {
            this.form.curriculum_id = this.curriculum_id;
            this.form.student_outcome_id = this.student_outcome_id;
        },
        student_outcome_id() {
            this.form.curriculum_id = this.curriculum_id;
            this.form.student_outcome_id = this.student_outcome_id;
        }
    },
    methods: {
        addRecordedAssessment() {
            this.form
                .post(myRootURL + "/custom_recorded_assessments")
                .then(response => {
                    this.$emit("custom_recorded_assessment_added");
                    $("#customRecordedAssessmentModal").modal("hide");

                    toast.fire({
                        type: "success",
                        title: "New Assessment Added"
                    });
                });
        }
    },
    created() {
        this.form.curriculum_id = this.curriculum_id;
        this.form.student_outcome_id = this.student_outcome_id;
    }
};
</script>
