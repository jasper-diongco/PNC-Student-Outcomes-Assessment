/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import Datepicker from "vuejs-datepicker";
import VueSelect from "vue-select";
import VeeValidate from "vee-validate";
import swal from "sweetalert2";

require("./bootstrap");

window.swal = swal;
window.Vue = require("vue");
window.axios = require("axios");
import Services from "./services/Services.js";
window.Services = Services;
import ApiClient from "./services/ApiClient.js";
window.ApiClient = ApiClient;
window.myRootURL = "/pnc_soa/public";

Vue.use(VeeValidate);

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

Vue.component("my-table", require("./components/Table.vue").default);

Vue.component(
  "table-loading",
  require("./components/TableLoading.vue").default
);

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
window.toast = swal.mixin({
  toast: true,
  position: "top-end",
  showConfirmButton: false,
  timer: 3000
});
