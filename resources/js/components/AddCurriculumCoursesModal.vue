<template>
    <!-- Modal -->
    <div class="modal fade" id="addCurriculumCoursesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add Courses</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <h5>Added Courses</h5>
            <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Course Code</th>
                      <th>Description</th>
                      <th>Lec Unit</th>
                      <th>Lab Unit</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <template v-if="coursesAdded.length > 0">
                      <tr v-for="courseAdded in coursesAdded" :key="courseAdded.id">
                        <th>{{ courseAdded.course_code }}</th>
                        <td>{{ courseAdded.description }}</td>
                        <td>{{ courseAdded.lec_unit }}</td>
                        <td>{{ courseAdded.lab_unit }}</td>
                        <td><button class="btn btn-dark btn-sm" @click="removeCourse(courseAdded)">Remove</button></td>
                      </tr>
                    </template>
                    <template v-else>
                      <tr>
                        <td class="text-center" colspan="5">
                          No Course Added.
                        </td>
                      </tr>
                    </template>
                  </tbody>

            </table>
            <div class="my-2 d-flex justify-content-end">
                <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Close</button>
                <button :disabled="isLoading" type="button" class="btn btn-primary" @click="saveAllCourses()">Save Courses</button>
            </div>
            <hr>
            <h5>Search Courses</h5>
            <div class="form-group">
                <input type="search" v-model="searchText" placeholder="Filter Courses" class="form-control">
            </div>
            <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Course Code</th>
                      <th>Description</th>
                      <th>Lec Unit</th>
                      <th>Lab Unit</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="course in coursesShow" :key="course.id">
                      <th>{{ course.course_code }}</th>
                      <td>{{ course.description }}</td>
                      <td>{{ course.lec_unit }}</td>
                      <td>{{ course.lab_unit }}</td>
                      <td><button class="btn btn-success btn-sm" @click="addCourse(course)">Add</button></td>
                    </tr>
                  </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
    export default {
        props: ["courses", "curriculum_id", "year_level", "semester"],
        data(){
          return {
            coursesAdded: [],
            searchText: "",
            isLoading: false
          }
        },
        computed: {
          coursesShow() {
            var availableCourses = [];

            for(var i = 0; i < this.courses.length; i++) {
              var isFound = false;
              for(var j = 0; j < this.coursesAdded.length; j++) {
                if(this.courses[i].id == this.coursesAdded[j].id) {
                  isFound = true;
                  break;
                }
              }

              if(!isFound) {
                availableCourses.push(this.courses[i]);
              }
            }

            if(this.searchText) {
              return availableCourses.filter((course) => {
                var regExp = new RegExp(this.searchText, "im")
                return course.description.search(regExp) > -1 || course.course_code.search(regExp) > -1
              });
            }

            return availableCourses;
          },

          

          
        },
        methods: {
          addCourse(course) {
            this.coursesAdded.push(course);
          },
          removeCourse(course) {
            for(var i = 0; i < this.coursesAdded.length; i++) {
              if(course.id == this.coursesAdded[i].id) {
                return this.coursesAdded.splice(i,1);
              }
            }
          },
          saveAllCourses() {
            if(this.coursesAdded.length > 10) {
              swal.fire({
                title: 'Are you sure?',
                text: this.coursesAdded.length + " courses will be added?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
              }).then((result) => {
                if (result.value) {
                  this.isLoading = true;

                  ApiClient.post("/curriculum_courses/add_courses", {
                    courses: this.coursesAdded,
                    curriculum_id: this.curriculum_id,
                    year_level: this.year_level,
                    semester: this.semester
                  })
                  .then(response => {
                    window.location.reload();
                  })
                }
              })
            } else {
              this.isLoading = true;

                ApiClient.post("/curriculum_courses/add_courses", {
                  courses: this.coursesAdded,
                  curriculum_id: this.curriculum_id,
                  year_level: this.year_level,
                  semester: this.semester
                })
                .then(response => {
                  window.location.reload();
                });
            }



            
          }
        }
    }
</script>