<!-- Modal -->
<template>
    <!-- Input Modal -->
    <div>
        <!--         <div
            class="modal fade"
            id="inputModal"
            tabindex="-1"
            role="dialog"
            aria-labelledby="exampleModalLabel"
            style="z-index: 1400;"
            aria-hidden="true"
        >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background: #8bc34a">
                        <h5 class="modal-title" id="exampleModalLabel">
                            Enter Input
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
                        <textarea cols="10" rows="5"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal"
                        >
                            Close
                        </button>
                        <button type="button" class="btn btn-info">
                            Run <i class="fa fa-play"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div> -->
        <!-- End input modal -->
        <div
            class="modal fade"
            id="machineProblemModal"
            tabindex="-1"
            role="dialog"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background: #8bc34a">
                        <h5 class="modal-title" id="exampleModalLabel">
                            Add Machine Problem
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
                            <label>Description</label>
                            <textarea
                                cols="30"
                                rows="10"
                                class="form-control"
                            ></textarea>
                        </div>

                        <div class="form-group">
                            <h5 class="font-weight-bold">
                                Programming Language
                            </h5>
                            <select
                                v-model="programming_language"
                                class="form-control"
                                v-on:change="changeProgrammingLanguage"
                            >
                                <option value="" class="d-none"
                                    >-- Please Select --</option
                                >
                                <option value="C">C</option>
                                <option value="C++">C++</option>
                                <option value="Java">Java</option>
                                <option value="Python">Python</option>
                                <option value="CS">CS</option>
                                <option value="VB">VB</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <div class="d-flex justify-content-between mb-3">
                                <h5 class="font-weight-bold">Test Cases</h5>
                                <button
                                    class="btn btn-sm btn-info"
                                    @click="addTestCase"
                                >
                                    Add
                                </button>
                            </div>
                            <div v-if="test_cases.length > 0">
                                <ul class="list-group">
                                    <li
                                        class="list-group-item"
                                        v-for="(test_case, index) in test_cases"
                                    >
                                        <h5>Test {{ index + 1 }}</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div>
                                                    <label>Input</label>
                                                    <textarea
                                                        :readonly="
                                                            test_case.is_saved
                                                        "
                                                        cols="10"
                                                        rows="5"
                                                        class="form-control"
                                                        v-model="
                                                            test_case.input
                                                        "
                                                    ></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div>
                                                    <label>Output</label>
                                                    <textarea
                                                        :readonly="
                                                            test_case.is_saved
                                                        "
                                                        cols="10"
                                                        rows="5"
                                                        class="form-control"
                                                        v-model="
                                                            test_case.output
                                                        "
                                                    ></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            class="d-flex justify-content-end my-2"
                                        >
                                            <button
                                                v-if="!test_case.is_saved"
                                                @click="saveTestCase(test_case)"
                                                class="btn btn-sm btn-success"
                                            >
                                                Save
                                            </button>
                                            <button
                                                v-else
                                                @click="removeTestCase(index)"
                                                class="btn btn-sm btn-success"
                                            >
                                                Remove
                                            </button>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div v-else class="p-3 bg-light text-center">
                                <h5>No Test Case</h5>
                            </div>
                        </div>

                        <div class="form-group">
                            <h5 class="font-weight-bold">Sample Solution</h5>
                            <!-- prettier-ignore -->
                            <div id="smyles_editor_wrap">

<div id="smyles_editor">
//type code
</div>
                            <div id="smyles_dragbar"></div>
                        </div>

                            <div class="d-flex justify-content-end mt-3">
                                <div>
                                    <div class="mr-2">
                                        <label>Enter input here:</label>
                                        <textarea
                                            cols="30"
                                            rows="5"
                                            class="form-control"
                                            v-model="input"
                                        ></textarea>
                                    </div>
                                    <div>
                                        <button
                                            v-on:click="runTestCases"
                                            class="btn btn-sm btn-info"
                                            :disabled="test_cases.length <= 0"
                                        >
                                            <div
                                                v-if="compileLoading"
                                                class="spinner-border spinner-border-sm"
                                                role="status"
                                            >
                                                <span class="sr-only"
                                                    >Loading...</span
                                                >
                                            </div>
                                            Run Test Cases
                                            <i class="fa fa-play"></i>
                                        </button>
                                        <button
                                            v-on:click="runSampleSolution"
                                            class="btn btn-sm btn-success"
                                        >
                                            <div
                                                v-if="compileLoading"
                                                class="spinner-border spinner-border-sm"
                                                role="status"
                                            >
                                                <span class="sr-only"
                                                    >Loading...</span
                                                >
                                            </div>
                                            Run <i class="fa fa-play"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div v-if="std_output">
                                <label>Output:</label>
                                <div class="p-3 shadow bg-dark mt-2">
                                    <pre style="color: #fff">{{
                                        std_output
                                    }}</pre>
                                </div>
                            </div>

                            <div
                                class="row"
                                v-if="test_case_results.length > 0"
                            >
                                <!-- <ul class="list-group">
                                    <li
                                        v-for="(test_case_result,
                                        index) in test_case_results"
                                        class="list-group-item"
                                    >
                                        <strong>Test {{ index + 1 }}</strong>
                                        <div>
                                            Input:
                                            <pre>{{
                                                test_case_result.test_case.input
                                            }}</pre>
                                        </div>
                                        <div>
                                            Output:
                                            <pre>{{
                                                test_case_result.test_case
                                                    .output
                                            }}</pre>
                                        </div>
                                    </li>
                                </ul> -->
                                <div
                                    class="col-md-3"
                                    v-for="(test_case_result,
                                    index) in test_case_results"
                                >
                                    <div class="bg-light shadow p-3">
                                        <strong>Test {{ index + 1 }}</strong>
                                        <div
                                            v-if="
                                                test_case_result.result ==
                                                    'success'
                                            "
                                            class="bg-success p-1"
                                        >
                                            Passed
                                        </div>
                                        <div v-else class="bg-danger p1">
                                            Failed
                                        </div>
                                        <div>
                                            Input:
                                            <pre>{{
                                                test_case_result.test_case.input
                                            }}</pre>
                                        </div>
                                        <div>
                                            Output:
                                            <pre>{{
                                                test_case_result.output
                                            }}</pre>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <h5 class="font-weight-bold">Courses</h5>
                            <div class="row">
                                <div
                                    class="col-md-3 mb-3"
                                    v-for="course in inner_courses"
                                >
                                    <div
                                        class="p-3 shadow"
                                        :style="{
                                            background: course.is_checked
                                                ? '#8bc34a'
                                                : '#ededed'
                                        }"
                                    >
                                        <input
                                            type="checkbox"
                                            :id="course.id"
                                            v-model="course.is_checked"
                                        />
                                        <label :for="course.id">{{
                                            course.course_code
                                        }}</label>
                                        <div>{{ course.description }}</div>
                                    </div>
                                </div>
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
                        <button type="button" class="btn btn-info">Add</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["courses"],
    data() {
        return {
            description: "",
            test_cases: [],
            inner_courses: [],
            programming_language: "",
            editor: "",
            std_output: "",
            input: "",
            compileLoading: false,
            pointer: 0,
            test_case_results: []
        };
    },
    methods: {
        changeProgrammingLanguage() {
            if (
                this.programming_language == "C" ||
                this.programming_language == "C++"
            ) {
                this.editor.session.setMode("ace/mode/c_cpp");
            } else if (this.programming_language == "Java") {
                this.editor.session.setMode("ace/mode/java");
            } else if (this.programming_language == "Python") {
                this.editor.session.setMode("ace/mode/python");
            } else if (this.programming_language == "CS") {
                this.editor.session.setMode("ace/mode/csharp");
            } else if (this.programming_language == "VB") {
                this.editor.session.setMode("ace/mode/vbscript");
            }
        },
        addTestCase() {
            this.test_cases.push({
                input: ``,
                output: ``,
                is_passed: false,
                is_saved: false
            });
        },
        saveTestCase(test_case) {
            if (test_case.input.trim() == "" || test_case.output.trim() == "") {
                alert("Please fill out input and output!");
            } else {
                test_case.is_saved = true;
            }
        },
        removeTestCase(index) {
            this.test_cases.splice(index, 1);
        },
        runSampleSolution() {
            var inputRadio = "true";
            var code = this.editor.getValue();
            this.compileLoading = true;
            axios
                .post("http://localhost:8080/compilecode", {
                    lang: this.programming_language,
                    code: code,
                    input: this.input,
                    inputRadio: inputRadio
                })
                .then(response => {
                    if (response.data.data.error) {
                        this.std_output = response.data.data.error;
                    } else {
                        this.std_output = response.data.data.output;
                    }

                    this.compileLoading = false;
                });
        },
        runTestCase(index) {
            var inputRadio = "true";
            var code = this.editor.getValue();
            this.compileLoading = true;
            axios
                .post("http://localhost:8080/compilecode", {
                    lang: this.programming_language,
                    code: code,
                    input: this.test_cases[index].input,
                    inputRadio: inputRadio
                })
                .then(response => {
                    // if (response.data.data.error) {
                    //     this.std_output = response.data.data.error;
                    // } else {
                    //     this.std_output = response.data.data.output;
                    // }

                    // this.compileLoading = false;
                    this.compileLoading = false;
                    console.log(response);
                    var out;
                    if (response.data.data.error) {
                        out = response.data.data.error;
                    } else {
                        out = response.data.data.output;
                    }

                    // x.replace(/\n/gm, '<br>');

                    // if (out.substr(out.length - 2, 2) == "\r\n") {
                    // }
                    // {
                    //     console.log(out.substr(out.length - 2, 2));
                    //     out = out.substr(0, out.length - 2);
                    // }
                    var out_compare = out.replace(
                        /(?:\\[rn]|[\r\n]+)+/g,
                        "<br>"
                    );
                    var test_compare = this.test_cases[index].output.replace(
                        /(?:\\[rn]|[\r\n]+)+/g,
                        "<br>"
                    );

                    console.log(out_compare, test_compare);
                    // value = value.replace(/[\r\n]/g, "");

                    if (
                        out_compare == test_compare ||
                        out_compare == test_compare + "<br>"
                    ) {
                        this.test_case_results.push({
                            test_case: this.test_cases[index],
                            result: "success",
                            output: out
                        });
                    } else {
                        this.test_case_results.push({
                            test_case: this.test_cases[index],
                            result: "failed",
                            output: out
                        });
                    }
                    if (this.pointer < this.test_cases.length - 1) {
                        this.pointer++;
                        this.runTestCase(this.pointer);
                    } else {
                        this.pointer = 0;
                    }
                });
        },
        runTestCases() {
            var vm = this;
            this.test_case_results = [];
            this.runTestCase(this.pointer);
            /*this.test_cases.forEach(function(test_case, index) {
                var inputRadio = "true";
                var code = vm.editor.getValue();
                // this.compileLoading = true;
                axios
                    .post("http://localhost:8080/compilecode", {
                        lang: vm.programming_language,
                        code: code,
                        input: test_case.input,
                        inputRadio: inputRadio
                    })
                    .then(response => {
                        var out;
                        if (response.data.data.error) {
                            out = response.data.data.error;
                        } else {
                            out = response.data.data.output;
                        }

                        if (out.substr(out.length - 2, 2) == "\r\n") {
                        }
                        {
                            var out = out.substr(0, out.length - 2);
                        }

                        if (out == test_case.output) {
                            console.log("passed");
                        } else {
                            console.log("failed");
                        }
                        // console.log(
                        //     this.std_output.substr(
                        //         0,
                        //         this.std_output.length - 2
                        //     )
                        // );

                        // this.compileLoading = false;
                        console.log(response);

                        // if (
                        //     test_case.output ==
                        //     this.removeReturnLine(response.data.data.ouput)
                        // ) {
                        //     console.log("passed");
                        // } else {
                        //     console.log("failed");
                        // }
                    });
                    
            });
            */
            /*var inputRadio = "true";
            var code = vm.editor.getValue();
            for (let i = 0; i < this.test_cases.length; i++) {
                axios
                    .post("http://localhost:8080/compilecode", {
                        lang: vm.programming_language,
                        code: code,
                        input: this.test_cases[i].input,
                        inputRadio: inputRadio
                    })
                    .then(response => {
                        var out;
                        if (response.data.data.error) {
                            out = response.data.data.error;
                        } else {
                            out = response.data.data.output;
                        }

                        if (out.substr(out.length - 2, 2) == "\r\n") {
                        }
                        {
                            var out = out.substr(0, out.length - 2);
                        }

                        if (out == this.test_cases[i].output) {
                            console.log("passed");
                        } else {
                            console.log("failed");
                        }
                        // console.log(
                        //     this.std_output.substr(
                        //         0,
                        //         this.std_output.length - 2
                        //     )
                        // );

                        // this.compileLoading = false;
                        console.log(response);

                        // if (
                        //     test_case.output ==
                        //     this.removeReturnLine(response.data.data.ouput)
                        // ) {
                        //     console.log("passed");
                        // } else {
                        //     console.log("failed");
                        // }
                    });

            }
            */
        },
        removeReturnLine(str) {
            if (str[str.length - 1] == "\n" || str[str.length - 1] == "\r") {
                return str.substr(0, str.length - 2);
            } else {
                return str;
            }
        }
    },
    created() {
        this.inner_courses = JSON.parse(JSON.stringify(this.courses));

        for (var i = 0; i < this.inner_courses.length; i++) {
            Vue.set(this.inner_courses[i], "is_checked", false);
        }

        setTimeout(() => {
            // editor.setTheme("ace/theme/monokai");
            // editor.session.setMode("ace/mode/javascript");
            var editor = ace.edit("smyles_editor");
            console.log(editor);
            this.editor = editor;
            var dragging = false;
            var wpoffset = 0;

            // If using WordPress uncomment line below as we have to
            // 32px for admin bar, minus 1px to center in 2px slider bar
            // wpoffset = 31;
            editor.setTheme("ace/theme/chaos");

            ace.require("ace/ext/language_tools");
            editor.setOptions({
                enableBasicAutocompletion: true,
                enableSnippets: true,
                enableLiveAutocompletion: false
            });
            // inline must be true to syntax highlight PHP without opening <?php tag
            // editor.getSession().setMode({ path: "ace/mode/php", inline: true });
            // editor.session.setMode("ace/mode/javascript");

            $("#smyles_dragbar").mousedown(function(e) {
                e.preventDefault();
                window.dragging = true;

                var smyles_editor = $("#smyles_editor");
                var top_offset = smyles_editor.offset().top - wpoffset;

                // Set editor opacity to 0 to make transparent so our wrapper div shows
                smyles_editor.css("opacity", 0);

                // handle mouse movement
                $(document).mousemove(function(e) {
                    var actualY = e.pageY - wpoffset;
                    // editor height
                    var eheight = actualY - top_offset;

                    // Set wrapper height
                    $("#smyles_editor_wrap").css("height", eheight);

                    // Set dragbar opacity while dragging (set to 0 to not show)
                    $("#smyles_dragbar").css("opacity", 0.15);
                });
            });

            $(document).mouseup(function(e) {
                if (window.dragging) {
                    var smyles_editor = $("#smyles_editor");

                    var actualY = e.pageY - wpoffset;
                    var top_offset = smyles_editor.offset().top - wpoffset;
                    var eheight = actualY - top_offset;

                    $(document).unbind("mousemove");

                    // Set dragbar opacity back to 1
                    $("#smyles_dragbar").css("opacity", 1);

                    // Set height on actual editor element, and opacity back to 1
                    smyles_editor.css("height", eheight).css("opacity", 1);

                    // Trigger ace editor resize()
                    editor.resize();
                    window.dragging = false;
                }
            });
        }, 1000);
    }
};
</script>

<style scoped>
#smyles_editor {
    height: 300px;
}

#smyles_editor_wrap {
    background-color: #cccccc;
    border-bottom: 1px solid #222222;
}

#smyles_dragbar {
    background-color: #222222;
    width: 100%;
    height: 2px;
    cursor: row-resize;
    opacity: 1;
}
</style>
