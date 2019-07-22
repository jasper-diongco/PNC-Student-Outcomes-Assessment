<template>
    <div>
        <div
            class="modal fade"
            :id="
                this.is_update
                    ? 'learningLevelModalUpdate'
                    : 'learningLevelModal'
            "
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
                        @submit.prevent="saveLearningLevel"
                        @keydown="form.onKeydown($event)"
                    >
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Letter</label>
                                <input
                                    v-model="form.letter"
                                    type="text"
                                    name="letter"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': form.errors.has('letter')
                                    }"
                                />
                                <has-error
                                    :form="form"
                                    field="letter"
                                ></has-error>
                            </div>
                            <div class="form-group">
                                <label>Name</label>
                                <input
                                    v-model="form.name"
                                    type="text"
                                    name="name"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': form.errors.has('name')
                                    }"
                                />
                                <has-error
                                    :form="form"
                                    field="name"
                                ></has-error>
                            </div>
                            <div class="form-group">
                                <label>Color</label>
                                <i
                                    class="fa fa-circle text-light"
                                    :style="{
                                        color: form.color + ' !important'
                                    }"
                                ></i>
                                <select
                                    v-model="form.color"
                                    name="color"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': form.errors.has('color')
                                    }"
                                >
                                    <option
                                        v-for="color in availableColors"
                                        :value="color.hex"
                                        >{{ color.text }}</option
                                    >
                                </select>
                                <has-error
                                    :form="form"
                                    field="color"
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
                                type="submit"
                                :disabled="form.busy"
                                class="btn btn-success"
                            >
                                {{ btnName }}
                                <div
                                    v-if="isLoading"
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
    props: ["program_id", "learning_levels", "is_update", "learning_level"],
    data() {
        return {
            form: new Form({
                letter: "",
                name: "",
                color: ""
            }),
            colors: [
                { text: "Green", hex: "#cbff90" },
                { text: "Blue", hex: "#cbf0f8" },
                { text: "Dark Blue", hex: "#aecbfa" },
                { text: "Red", hex: "#f28b82" },
                { text: "Yellow", hex: "#fff375" },
                { text: "Orange", hex: "#fbbd04" },
                { text: "Teel", hex: "#a7ffeb" },
                { text: "Purple", hex: "#d7aefb" },
                { text: "Pink", hex: "#fdcfe8" }
            ],
            isLoading: false
        };
    },
    watch: {
        learning_level() {
            this.form.letter = this.learning_level.letter;
            this.form.name = this.learning_level.name;
            this.form.color = this.learning_level.color;
        }
    },
    computed: {
        modalTitle() {
            return this.is_update
                ? "Update Learning Level"
                : "Add Learning Level";
        },
        btnName() {
            return this.is_update ? "Update" : "Add Learning Level";
        },
        availableColors() {
            var return_colors = [];
            for (var i = 0; i < this.colors.length; i++) {
                var found = false;
                for (var j = 0; j < this.learning_levels.length; j++) {
                    if (
                        this.is_update &&
                        this.learning_level.color == this.colors[i].hex
                    ) {
                        found = false;
                        break;
                    }
                    if (this.colors[i].hex == this.learning_levels[j].color) {
                        found = true;
                        break;
                    }
                }
                if (!found) {
                    return_colors.push(this.colors[i]);
                }
            }

            return return_colors;
        }
    },
    methods: {
        saveLearningLevel() {
            if (this.is_update) {
                this.updateLearningLevel();
            } else {
                this.addLearningLevel();
            }
        },
        addLearningLevel() {
            this.isLoading = true;
            this.form
                .post(
                    myRootURL +
                        "/programs/" +
                        this.program_id +
                        "/add_learning_level"
                )
                .then(response => {
                    this.isLoading = false;
                    console.log(response);
                    toast.fire({
                        type: "success",
                        title: "Learning level Successfully added."
                    });
                    $("#learningLevelModal").modal("hide");
                    this.$emit("update-learning-levels");
                })
                .catch(error => {
                    this.isLoading = false;
                });
        },
        updateLearningLevel() {
            this.isLoading = true;
            this.form
                .put(
                    myRootURL +
                        "/programs/" +
                        this.program_id +
                        "/update_learning_level/" +
                        this.learning_level.id
                )
                .then(response => {
                    this.isLoading = false;
                    console.log(response);
                    toast.fire({
                        type: "success",
                        title: "Learning level Successfully updated."
                    });
                    $("#learningLevelModalUpdate").modal("hide");
                    this.$emit("update-learning-levels");
                })
                .catch(error => {
                    this.isLoading = false;
                });
        }
    }
};
</script>
