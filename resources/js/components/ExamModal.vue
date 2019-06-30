<template>
    <div>
        <!-- Button trigger modal -->
        <button
            type="button"
            class="btn btn-success-b"
            data-toggle="modal"
            data-target="#examModal"
        >
            Add New Exam
        </button>

        <!-- Modal -->
        <div
            class="modal fade"
            id="examModal"
            tabindex="-1"
            role="dialog"
            aria-hidden="true"
        >
            <div class="modal-dialog" role="document">
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
                        @submit.prevent="saveExam"
                        @keydown="form.onKeydown($event)"
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
                                {{ btnTitle }}
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
    data() {
        return {
            form: new Form({
                description: "",
                time_limit: 60,
                passing_grade: 60
            })
        };
    },
    computed: {
        modalTitle() {
            return "Add New Exam";
        },
        btnTitle() {
            return "Add to database";
        }
    },
    methods: {
        addExam() {
            alert("Test");
        },
        saveExam() {
            this.addExam();
        }
    }
};
</script>
