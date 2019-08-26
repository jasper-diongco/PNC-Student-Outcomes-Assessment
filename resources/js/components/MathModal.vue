<template>
    <div>
        <!-- Button trigger modal -->
        <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
          Launch demo modal
        </button> -->

        <!-- Modal -->
        <div
            class="modal fade"
            style="z-index: 1400;"
            :id="isUpdate ? 'mathModalUpdate' : 'mathModal'"
            tabindex="-1"
            role="dialog"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            Add Equation
                            <i class="fa fa-superscript text-success"></i>
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
                        <mathlive-mathfield
                            id="mf"
                            ref="mathfield"
                            :config="{ smartFence: false }"
                            @focus="ping"
                            :on-keystroke="displayKeystroke"
                            v-model="formula"
                            >f(x)=</mathlive-mathfield
                        >
                        <div>
                            <label>Keystroke:&nbsp;</label
                            ><kbd>{{ keystroke }}</kbd>
                        </div>
                        <div class="output">LaTeX: {{ formula }}</div>
                        <div class="output">
                            Spoken text: {{ asSpokenText() }}
                        </div>
                        <div>
                            <button class="btn-math" v-on:click="sayIt">
                                Say It
                            </button>
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
                            @click="saveEquation"
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
            formula: "g(x)",
            keystroke: ""
        };
    },
    watch: {
        id() {
            this.getEquation();
        }
    },
    methods: {
        sayIt: function(event) {
            this.$refs.mathfield.perform(["speak", "all"]);
        },
        ping: function() {
            console.log("ping");
        },
        displayKeystroke: function(keystroke, _ev) {
            this.keystroke = keystroke;
            return true;
        },
        asSpokenText: function() {
            return this.$refs.mathfield
                ? this.$refs.mathfield.text("spoken")
                : "";
        },
        addEquation() {
            if (this.formula.trim() == "") {
                alert("The field is required!");
            } else {
                ApiClient.post("/math_objects", {
                    formula: this.formula,
                    ref_id: this.refId
                }).then(response => {
                    toast.fire({
                        type: "success",
                        title: "Math Object Successfully Added."
                    });

                    $("#mathModal").modal("hide");
                    this.$emit("objects-added");
                });
            }
        },
        updateEquation() {
            if (this.formula.trim() == "") {
                alert("The field is required!");
            } else {
                ApiClient.put("/math_objects/" + this.id, {
                    formula: this.formula,
                    ref_id: this.refId
                }).then(response => {
                    toast.fire({
                        type: "success",
                        title: "Math Object Successfully Saved."
                    });

                    $("#mathModalUpdate").modal("hide");

                    this.$emit("objects-added");
                });
            }
        },
        saveEquation() {
            if (this.isUpdate) {
                this.updateEquation();
            } else {
                this.addEquation();
            }
        },
        getEquation() {
            ApiClient.get("/math_objects/" + this.id).then(response => {
                this.formula = response.data.formula;
            });
        }
    }
};
</script>

<style scoped>
body {
    font-family: sans-serif;
    color: #444;
    background-color: #f9f9f9;
}
main {
    max-width: 820px;
    margin: auto;
}
.modal .mathfield {
    border: 1px solid #ddd;
    padding: 5px;
    margin: 10px 0 10px 0;
    border-radius: 5px;
    background-color: #fff;
}
.modal .output {
    padding: 5px;
    margin: 20px 0 20px 0;
    border-radius: 5px;
    border: 1px solid #000;

    font-family: "Source Code Pro", Menlo, "Bitstream Vera Sans Mono", Monaco,
        Courier, "Andale Mono", monospace;
    color: #f0c674;
    background: #35434e;
}
.modal label {
    font-weight: bold;
}
.modal .btn-math {
    background: none;
    border: 1px solid rgba(0, 0, 0, 0.12);
    border-radius: 4px;
    color: #0066ce;
    fill: currentColor;
    position: relative;
    height: 36px;
    line-height: 36px;
    margin: 0 18px 0 0;
    min-width: 64px;
    padding: 0 16px;
    display: inline-block;
    overflow: hidden;
    will-change: box-shadow;
    transition: box-shadow 0.2s cubic-bezier(0.4, 0, 1, 1),
        background-color 0.2s cubic-bezier(0.4, 0, 0.2, 1),
        color 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    outline: none;
    cursor: pointer;
    text-decoration: none;
    text-align: center;
    vertical-align: middle;
    user-select: none;

    font-size: 16px;
    letter-spacing: 0.08929em;
    text-transform: uppercase;
    box-shadow: 0px 1px 5px 0px rgba(0, 0, 0, 0.2),
        0px 2px 2px 0px rgba(0, 0, 0, 0.14),
        0px 3px 1px -2px rgba(0, 0, 0, 0.12);
}
.modal .btn-math:first-child {
    margin-left: 0;
}
.modal .btn-math:hover {
    background-color: rgba(0, 0, 0, 0.08);
}
.modal .btn-math:active {
    background-color: white;
}
</style>
