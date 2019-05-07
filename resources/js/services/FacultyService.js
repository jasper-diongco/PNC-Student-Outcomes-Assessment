import ApiClient from "./ApiClient.js";

export default {
  getFaculties: function(page) {
    return ApiClient.get("/faculties?page=" + page);
  },
  searchFaculties: function(search) {
    return ApiClient.get("/faculties?q=" + search);
  }
};
