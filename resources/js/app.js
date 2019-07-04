/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import Datepicker from "vuejs-datepicker";
import VueSelect from "vue-select";
import VeeValidate from "vee-validate";
import swal from "sweetalert2";
import moment from "moment";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import CKEditor from "@ckeditor/ckeditor5-vue";
//import Essentials from "@ckeditor/ckeditor5-essentials/src/essentials";
import VuePrismEditor from "vue-prism-editor";
import "vue-prism-editor/dist/VuePrismEditor.css"; // import the styles

import MathLive from "mathlive/dist/mathlive.js";
import Mathfield from "mathlive/dist/vue-mathlive.mjs";
import "mathlive/dist/mathlive.core.css";
import "mathlive/dist/mathlive.css";

require("./bootstrap");

require("startbootstrap-sb-admin-2/js/sb-admin-2.min.js");

window.moment = moment;
window.ClassicEditor = ClassicEditor;
window.swal = swal;
window.Vue = require("vue");
window.axios = require("axios");
import Services from "./services/Services.js";
window.Services = Services;
import ApiClient from "./services/ApiClient.js";
window.ApiClient = ApiClient;
window.myRootURL = "/pnc_soa/public";
window.MathLive = MathLive;

Vue.use(VeeValidate);
Vue.use(CKEditor);
Vue.use(Mathfield, MathLive);
window.VeeValidate = VeeValidate;

//ClassicEditor.builtinPlugins = [Essentials];

// vform
import { Form, HasError, AlertError } from "vform";
window.Form = Form;
Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);
// end vform

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component(
  "example-component",
  require("./components/ExampleComponent.vue").default
);
Vue.component("prism-editor", VuePrismEditor);
Vue.component("checked-icon", require("./components/CheckedIcon.vue").default);
Vue.component("course-modal", require("./components/CourseModal.vue").default);
Vue.component(
  "account-modal",
  require("./components/AccountModal.vue").default
);
Vue.component("image-modal", require("./components/ImageModal.vue").default);
Vue.component("code-modal", require("./components/CodeModal.vue").default);
Vue.component("math-modal", require("./components/MathModal.vue").default);

Vue.component("grade-modal", require("./components/GradeModal.vue").default);
Vue.component(
  "college-modal",
  require("./components/CollegeModal.vue").default
);
Vue.component(
  "curriculum-course-modal",
  require("./components/CurriculumCourseModal.vue").default
);
Vue.component(
  "curriculum-modal",
  require("./components/CurriculumModal.vue").default
);

Vue.component(
  "add-student-modal",
  require("./components/AddStudentModal.vue").default
);

Vue.component("my-table", require("./components/Table.vue").default);

Vue.component(
  "table-loading",
  require("./components/TableLoading.vue").default
);

Vue.component(
  "student-outcome-modal",
  require("./components/StudentOutcomeModal.vue").default
);

Vue.component("exam-modal", require("./components/ExamModal.vue").default);

Vue.component("datepicker", Datepicker);

Vue.component("vue-select", VueSelect);

Vue.directive("uppercase", {
  update(el) {
    el.value = el.value.toUpperCase();
  }
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

window.swalError = function() {
  swal.fire({
    type: "error",
    title: "Oops...",
    text: "Something went wrong!"
  });
};
window.serverError = function() {
  swal.fire({
    type: "error",
    title: "Oops...",
    text: "Something went wrong! Try refreshing the page."
  });
};

window.toast = swal.mixin({
  toast: true,
  position: "top-end",
  showConfirmButton: false,
  timer: 3000
});
