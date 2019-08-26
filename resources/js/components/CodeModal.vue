<template>
    <div>
        <!-- Large modal -->
        <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">Large modal</button> -->

        <div
            :id="isUpdate ? 'codeModalUpdate' : 'codeModal'"
            class="modal fade bd-example-modal-lg"
            tabindex="-1"
            role="dialog"
            aria-labelledby="myLargeModalLabel"
            aria-hidden="true"
            style="z-index: 1400;"
        >
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            Add Code Object<i
                                class="fa fa-code text-success"
                            ></i>
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
                            <label><b>Description:</b></label>
                            <input
                                type="text"
                                name="description"
                                placeholder="Enter Description"
                                class="form-control"
                                v-model="description"
                                data-vv-name="description"
                                v-validate="'required|max:191'"
                                :class="{
                                    'is-invalid': errors.has('description')
                                }"
                            />
                            <div class="invalid-feedback">
                                {{ errors.first("description") }}
                            </div>
                        </div>
                        <div>
                            <label>Language:</label>
                            <select v-model="language">
                                <option
                                    v-for="lang in languages"
                                    :key="lang"
                                    :value="lang"
                                    >{{ lang }}</option
                                >
                            </select>
                        </div>

                        <prism-editor
                            :language="language"
                            v-model="code"
                            line-numbers
                            style="height: 350px;"
                        ></prism-editor>
                        <div class="form-group">
                            <input
                                type="hidden"
                                name="code"
                                placeholder="Enter code"
                                class="form-control"
                                v-model="code"
                                data-vv-name="code"
                                v-validate="'required|max:1500'"
                                :class="{ 'is-invalid': errors.has('code') }"
                            />
                            <div class="invalid-feedback">
                                {{ errors.first("code") }}
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
                            type="button"
                            class="btn btn-primary"
                            @click="saveCodeObject"
                        >
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["refId", "id", "isUpdate"],
    data() {
        return {
            code: "//type code here...",
            languages: [
                "html",
                "js",
                "css",
                "clike",
                "csharp",
                "java",
                "php",
                "sql",
                "c",
                "cpp"
            ],
            language: "js",
            description: ""
        };
    },
    watch: {
        id() {
            this.getCodeObject();
        }
    },
    methods: {
        addCodeObject() {
            ApiClient.post("/code_objects", {
                description: this.description,
                ref_id: this.refId,
                code: this.code,
                language: this.language
            }).then(response => {
                this.$emit("objects-added");
                $("#codeModal").modal("hide");
                this.code = "";
                this.description = "";

                toast.fire({
                    type: "success",
                    title: "Code Object successfully saved!"
                });
            });
        },
        updateCodeObject() {
            ApiClient.put("/code_objects/" + this.id, {
                description: this.description,
                ref_id: this.refId,
                code: this.code,
                language: this.language
            }).then(response => {
                this.$emit("objects-added");
                $("#codeModalUpdate").modal("hide");
                this.code = "";
                this.description = "";

                toast.fire({
                    type: "success",
                    title: "Code Object successfully updated!"
                });
            });
        },
        saveCodeObject() {
            this.$validator.validateAll().then(isValid => {
                if (isValid) {
                    if (this.isUpdate) {
                        this.updateCodeObject();
                    } else {
                        this.addCodeObject();
                    }
                } else {
                    toast.fire({
                        type: "error",
                        title: "Please enter a valid data!"
                    });
                }
            });
        },
        getCodeObject() {
            ApiClient.get("/code_objects/" + this.id).then(response => {
                this.description = response.data.description;
                this.code = response.data.code;
                this.language = response.data.language;
            });
        }
    }
};
</script>
