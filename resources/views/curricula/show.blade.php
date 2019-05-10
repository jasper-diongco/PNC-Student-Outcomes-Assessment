@extends('layouts.master')

@section('title', $curriculum->name)

@section('content')
  <div id="app">
    <!-- Modal -->
    <curriculum-course-modal v-on:refresh-curriculum="courseAddedSuccessfully" :curriculum-id="{{ $curriculum->id }}" :max-year-level="maxYearLevel" :course="selectedCourse"></curriculum-course-modal>
    <!-- End Modal -->

    <!-- Update modal -->
    <curriculum-course-modal 
      v-on:refresh-curriculum="courseAddedSuccessfully" :curriculum-id="{{ $curriculum->id }}" 
      :max-year-level="maxYearLevel" 
      :is-update="true"
      :curriculum-course="selectedCurriculumCourse"
      :course="selectedCourse"></curriculum-course-modal>
    <!-- end update modal  -->
    

    <h1 class="h4">{{ $curriculum->name }}</h1>
    <p><i class="fa fa-file-alt"></i> {{ $curriculum->description }}</p>
    <div class="alert alert-info"><i class="fa fa-info-circle"></i> You can search existing courses to add to this curriculum or you add a new course if you want.
    <br>
    <b>Once this is saved. You cannot edit this, but you can revised instead.</b>
    </div>
    <div class="d-flex justify-content-between">
      <div style="width: 80%">
        <div class="form-group row">
          <div class="col-md-2 text-md-right">
            <label class="col-form-label "><b>Search Courses: </b></label>
          </div>
          <div style="width: 100%" class="d-flex flex-column col-md-9">
            <div >
              <input v-on:input="searchCourses" v-model="searchCourseText" class="form-control" type="search" name="search_course" placeholder="Type to search courses...">
            </div>

            <div v-if="searched_courses.length > 0" class="search-course">
              <ul class="list-group mt-1" style="">
                <li v-for="course in searched_courses" v-on:click="selectCourse(course)" :key="course.id" class="list-group-item d-flex align-items-center justify-content-between">
                  <div class="d-flex">
                    <div class="avatar-course mr-2" :style="{ background: course.color }"> @{{ course.course_code }}</div>
                    @{{ course.course_code }}
                    &mdash; 
                    <div class="d-flex flex-column"> 
                      <div>@{{ course.description }}</div>
                      <small class="text-muted">@{{ course.college }}</small>
                    </div>
                  </div>
                  <div>
                    <button v-on:click="selectCourse(course)" 
                    class="btn btn-primary btn-sm" 
                    data-toggle="modal"
                    data-target="#curriculumCourseModal">Add</button>
                  </div> 
                  </li>
              </ul>
            </div>
            <div v-else-if="this.searching">
              <table-loading></table-loading>
            </div>
          </div>
        </div>
      </div>
      
      <div>

        <button class="btn btn-primary btn-round">Add new Course <i class="fa fa-plus"></i></button>
      </div>
    </div>
    <div class="accordion" id="accordionExample">
      <div v-if="isLoading">
        <div class="card">
          <div class="card-body">
            <table-loading></table-loading>
          </div>
        </div>
        
      </div>
      <template v-else v-for="year in curriculum.year_level">
        <!-- card -->
        <div :key="year + '' + sem"  v-for="sem in 2" class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <div>
              <h2 class="mb-0">
                <button v-on:click="toggleAccordion(year + '' + sem)" class="btn btn-link" type="button" data-toggle="collapse" :data-target="'#' + year + '' + sem" aria-expanded="true" aria-controls="collapseOne">
                  <b>
                    @{{ formatIndex(year) + ' year' + '/' + formatIndex(sem) + ' sem' }}
                  </b>   
                </button>
              </h2>
            </div>
            <div class="text-success">
              Total units: <b>@{{ getTotalUnit(year, sem, 'all') }}</b>
            </div>
          </div>

          <div :id="year + '' + sem" class="collapse show" aria-labelledby="headingOne">
            <div class="card-body">
              <div v-if="getSemCourses(year, sem).length > 0" class="table-responsive">
                <table class="table">
                  <thead class="thead-light">
                    <tr>
                      <th>Course Code</th>
                      <th>Description</th>
                      <th>Lec Unit</th>
                      <th>Lab Unit</th>
                      <th>Pre requsite</th>
                      <th class="text-center">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="curriculumCourse in getSemCourses(year, sem)" :key="curriculumCourse.course_id">
                      <th>@{{ curriculumCourse.course_code }}</th>
                      <td>@{{ curriculumCourse.description }}</td>
                      <td>@{{ curriculumCourse.lec_unit }}</td>
                      <td>@{{ curriculumCourse.lab_unit }}</td>
                      <td>none</td>
                      <td class="justify-content-end d-flex">
                        <div class="mr-2">
                          <button v-on:click="removeCurriculumCourse(curriculumCourse.id)" class="btn btn-secondary btn-sm">Remove <i class="fa fa-minus-circle text-danger"></i></button>
                          <button
                            data-toggle="modal"
                            data-target="#curriculumCourseModalUpdate"
                            class="btn btn-success btn-sm"
                            v-on:click="selectCurriculumCourse(curriculumCourse)"
                          >
                            Update <i class="fa fa-edit"></i>
                          </button>
                        </div>
                        
                      </td>
                        
                    </tr>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th>Total: </th>
                      <td></td>
                      <th class="text-success">@{{ getTotalUnit(year,sem, 'lec') }}</th>
                      <th class="text-success">@{{ getTotalUnit(year,sem, 'lab') }}</th>
                      <td></td>
                      <td></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
              <div class="h5 text-center" v-else>
                No courses yet.
              </div>
            </div>
          </div>
          <!-- end card -->
        </template>
      </div>
    </div>
    
  </div>
@endsection

@push('scripts')
  <script>
    var vm = new Vue({
      el: '#app',
      data: {
        searching: false,
        searchCourseText: '',
        searched_courses: [],
        maxYearLevel: {{ $curriculum->year_level }},
        selectedCourse: {
          id: '',
          course_code: '',
          description: ''
        },
        selectedCurriculumCourse: '',
        curriculum_id: {{ $curriculum->id }},
        curriculum: {
          author: '',
          created_at: '',
          curriculum_courses: [],
          description: '',
          id: '',
          name: '',
          program_id: '',
          user_id: '',
          year: '',
          year_level: ''
        },
        isLoading: true
      },
      methods: {
        searchCourses :_.debounce(() => {
            if(vm.searchCourseText == '') {
              return vm.searched_courses = [];
            }

            vm.searching = true;
            ApiClient.get('/courses/?q=' + vm.searchCourseText)
            .then(response => {

              vm.searched_courses = response.data.data;
              vm.searching = false;
            }).
            catch(err => {
              console.log(err);
              vm.searched_courses = false;
            });
          }, 400),
        selectCourse(selectedCourse) {
          this.selectedCourse = selectedCourse;
        },
        selectCurriculumCourse(selectedCurriculumCourse) {
          this.selectedCurriculumCourse = Object.assign({}, selectedCurriculumCourse);
          const course = Object.assign({}, this.selectedCurriculumCourse);
          this.selectedCourse.id = course.course_id;
          this.selectedCourse.course_code = course.course_code;
          this.selectedCourse.description = course.description;
        },
        getCurriculum() {
          this.isLoading = true;
          ApiClient.get('curricula/' + this.curriculum_id + '?json=yes')
          .then(response => {
            this.curriculum = response.data.data;
            this.isLoading = false;
          })
          .catch(err => {
            console.log(err);
            serverError();
            this.isLoading = false;
          })
        },
        toggleAccordion(id) {
          $('#' + id).collapse('toggle');
        },
        formatIndex(num) {
          if (num == 1) {
            return '1st';
          } else if (num == 2) {
            return '2nd';
          } else if (num == 3) {
            return '3rd';
          } else if (num >= 4) {
            return  num + 'th';
          }
        },
        getSemCourses(year, sem) {
          return this.curriculum.curriculum_courses.filter(curriculumCourse => {
            return curriculumCourse.year_level == year && curriculumCourse.semester == sem;
          });
        },
        getTotalUnit(year, sem, type) {
          let total = 0;
          const courses = this.getSemCourses(year, sem);

          if (courses.length > 0) {
            for (let i = 0; i < courses.length; i++) {
              if (type == 'lec') {
                total += courses[i].lec_unit;
              } else if (type == 'lab') {
                total += courses[i].lab_unit;
              } else if(type == 'all') {
                total += courses[i].lab_unit;
                total += courses[i].lec_unit;
              }
              
            }
          }

          return total;
        },
        removeCurriculumCourse(id) {
          swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
          }).then((result) => {
            if (result.value) {
              ApiClient.delete('curriculum_courses/' + id)
              .then(response => {
                swal.fire(
                  'Deleted!',
                  'The course has been removed.',
                  'success'
                ).then(() => {
                  this.getCurriculum();//refresh
                });
                
              })
              .catch(err => {
                swalError();
              })
              
            }
          });
        },
        courseAddedSuccessfully() {
          this.getCurriculum();
          this.searchCourseText = '';
          this.searchCourses();
        }
      },
      created() {
        setTimeout(() => {
          this.getCurriculum();
        }, 400);  
      }
    });
  </script>
  @if(Session::has('message'))
    <script>
      toast.fire({
        type: 'success',
        title: '{{ Session::get('message') }}'
      })
    </script>
  @endif
@endpush