<template>
    <div>
        <!-- Button trigger modal -->
        <button
            type="button"
            class="btn btn-success-b"
            data-toggle="modal"
            data-target="#collegeModal"
            v-if="!isUpdate"
        >
            Add New College
        </button>

        <!-- Modal -->
        <div
            class="modal fade"
            :id="isUpdate ? 'collegeModalUpdate' : 'collegeModal'"
            tabindex="-1"
            role="dialog"
        >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
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
                    <div class="modal-body">
                        <form
                            @submit.prevent="saveCollege"
                            @keydown="form.onKeydown($event)"
                        >
                            <div class="form-group">
                                <label>College Code</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="form.college_code"
                                    :class="{
                                        'is-invalid': form.errors.has(
                                            'college_code'
                                        )
                                    }"
                                />
                                <has-error
                                    :form="form"
                                    field="college_code"
                                ></has-error>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label>College Name</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        v-model="form.name"
                                        :class="{
                                            'is-invalid': form.errors.has(
                                                'name'
                                            )
                                        }"
                                    />
                                    <has-error
                                        :form="form"
                                        field="name"
                                    ></has-error>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Dean</label>

                                <div v-if="showSearch">
                                    <input
                                        type="search"
                                        v-model="searchText"
                                        v-on:input="searchFaculty"
                                        placeholder="Search faculty..."
                                        class="form-control"
                                        :class="{
                                            'is-invalid': form.errors.has(
                                                'faculty_id'
                                            )
                                        }"
                                    />

                                    <template v-if="searchLoading">
                                        <table-loading></table-loading>
                                    </template>
                                    <ul class="list-group">
                                        <li
                                            v-for="f in faculties"
                                            class="list-group-item d-flex justify-content-between"
                                        >
                                            <div>
                                                <i class="fa fa-user"></i>
                                                {{ f.full_name }} -
                                                {{ f.user_type }}
                                            </div>
                                            <div>
                                                <button
                                                    v-on:click="
                                                        selectFaculty(f.id)
                                                    "
                                                    type="button"
                                                    class="btn btn-sm btn-success"
                                                >
                                                    Select
                                                </button>
                                            </div>
                                        </li>
                                    </ul>
                                    <ul
                                        v-if="
                                            faculties.length == 0 &&
                                                searchText != '' &&
                                                !searchLoading
                                        "
                                        class="list-group"
                                    >
                                        <li class="list-group-item">
                                            No record found.
                                        </li>
                                    </ul>
                                </div>

                                <div v-else>
                                    <label>Selected:</label>
                                    {{ faculty.full_name }}
                                    <button
                                        v-on:click="changeDean"
                                        type="button"
                                        class="btn btn-sm btn-primary"
                                    >
                                        Change <i class="fa fa-edit"></i>
                                    </button>
                                </div>
                                <input
                                    type="hidden"
                                    v-model="form.faculty_id"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': form.errors.has(
                                            'faculty_id'
                                        )
                                    }"
                                />
                                <has-error
                                    :form="form"
                                    field="faculty_id"
                                ></has-error>
                            </div>
                        </form>
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
                            @click="saveCollege"
                            type="button"
                            class="btn btn-success"
                            :disabled="form.busy"
                        >
                            {{ btnTitle }}
                            <div
                                v-if="form.busy"
                                class="spinner-border spinner-border-sm text-light"
                                role="status"
                            >
                                <span class="sr-only">Loading...</span>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["isUpdate", "collegeId"],
    data() {
        return {
            form: new Form({
                id: "",
                faculty_id: "",
                college_code: "",
                college_name: ""
            }),
            faculties: [],
            searchText: "",
            showSearch: true,
            faculty: "",
            searchLoading: false
        };
    },
    watch: {
        collegeId() {
            //console.log(this.collegeId);
            this.form.id = this.collegeId;
            this.getCollege();
        }
    },
    computed: {
        btnTitle() {
            return this.isUpdate ? "Update College" : "Add College";
        },
        modalTitle() {
            return this.isUpdate ? "Update College" : "Add new College";
        }
    },
    methods: {
        getFaculty() {
            ApiClient.get("/faculties/" + this.form.faculty_id).then(
                response => {
                    this.faculty = response.data.data;
                }
            );
        },
        getCollege() {
            ApiClient.get("/colleges/" + this.collegeId + "?json=true").then(
                response => {
                    this.form.college_code = response.data.college_code;
                    this.form.name = response.data.name;
                    this.form.faculty_id = response.data.faculty_id;
                    this.getFaculty();
                    this.showSearch = false;
                }
            );
        },
        searchFaculty() {
            this.searchLoading = true;
            if (this.searchText.trim() == "") {
                this.searchLoading = false;
                return (this.faculties = []);
            }

            ApiClient.get("/faculties?q=" + this.searchText).then(response => {
                //loading(false);
                //console.log(response.data);
                this.faculties = response.data.data;
                this.searchLoading = false;
            });
        },
        changeDean() {
            this.showSearch = true;
            this.faculty_id = "";
        },
        selectFaculty(faculty_id) {
            this.faculties = [];
            this.form.faculty_id = faculty_id;
            this.getFaculty();
            this.showSearch = false;
        },
        addCollege() {
            this.form.post("/pnc_soa/public/colleges").then(response => {
                $("#collegeModal").modal("hide");
                toast.fire({
                    type: "success",
                    title: "College Successfully Created"
                });
                this.$emit("refresh-colleges");
            });
        },
        updateCollege() {
            this.form
                .put("/pnc_soa/public/colleges/" + this.collegeId)
                .then(response => {
                    $("#collegeModalUpdate").modal("hide");
                    toast.fire({
                        type: "success",
                        title: "College Successfully Updated"
                    });
                    this.$emit("refresh-colleges");
                    this.$emit("update-college", response.data);
                });
        },
        saveCollege() {
            if (this.isUpdate) {
                this.updateCollege();
            } else {
                this.addCollege();
            }
        }
    },
    created() {
        //this.getFaculty();
        if (this.collegeId != "") {
            this.form.id = this.collegeId;
            this.getCollege();
        }
    }
};
</script>
