@extends('layouts.sb_admin')

@section('title', $curriculum->name)

@section('content')
  <div id="app">
    <!-- Add Modal -->
    <curriculum-course-modal 
      v-on:refresh-curriculum="courseAddedSuccessfully" 
      :curriculum-id="{{ $curriculum->id }}" 
      :max-year-level="maxYearLevel" 
      :course="selectedCourse"
      :curriculum-courses="curriculum.curriculum_courses"></curriculum-course-modal>
    <!-- Add End Modal -->

    <!-- Update modal -->
    <curriculum-course-modal 
      v-on:refresh-curriculum="courseAddedSuccessfully" :curriculum-id="{{ $curriculum->id }}" 
      :max-year-level="maxYearLevel" 
      :is-update="true"
      :curriculum-course="selectedCurriculumCourse"
      :course="selectedCourse"
      :curriculum-courses="curriculum.curriculum_courses"></curriculum-course-modal>
    <!-- end update modal  -->

    <!-- Curriculum modal -->
    <curriculum-modal :is-revise="true" :revise-program='@json($curriculum->program)' :curriculum='@json($curriculum)'>
    </curriculum-modal>
    <!-- End curriculum modal -->
    
    <a href="{{ url('/curricula?college_id='. Session::get('college_id')) }}" class="btn btn-success mb-3 btn-sm"><i class="fa fa-arrow-left"></i> Back</a>

    <h1 class="h4">{{ $curriculum->name }}</h1>
    <p class="mr-5"><i class="fa fa-file-alt text-primary"></i> {{ $curriculum->description }}</p>
    <div class="d-flex">  
      <p class="mr-5">
      <i class="fa fa-calendar-alt text-secondary"></i> {{ $curriculum->year }}
      </p>
      <p class="mr-5"><i class="fa fa-book text-success"></i> {{ count($curriculum->curriculumCourses) }} courses</p>
    </div>

    @if (!$curriculum->checkIfLatestVersion())
      <div class="alert alert-warning">
        <i class="fa fa-exclamation-triangle"></i>
        This is not the latest version of <b>{{ $curriculum->program->program_code }}</b> curriculum. View the latest version <a href="{{ url('/curricula/' . $curriculum->getLatestVersion()->id) }}">here</a>
      </div>
    @endif

    @if (!$curriculum->is_saved)

      <div class="row">
        <div class="col-md-8">
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
                      @if(Gate::check('isDean') || Gate::check('isSAdmin'))
                        <button v-on:click="selectCourse(course)" 
                        class="btn btn-primary btn-sm" 
                        data-toggle="modal"
                        data-target="#curriculumCourseModal">Add</button>
                      @endif
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
        
        <div class="col-md-4 text-md-right">
          @if(Gate::check('isDean') || Gate::check('isSAdmin'))
            <!-- COURSE MODAL -->
              <course-modal 
                :college-id="college_id" 
                :colleges='@json($colleges)'
                :add-directly="true"
                v-on:open-curriculum-course="openCurriculumCourse"></course-modal>
            <!-- END MODAL -->


          @endif
        </div>
      </div>
    @else
      <div class="d-flex justify-content-end align-self-center my-3">
        <div>
            <button class="btn btn-secondary mr-2 btn-sm">Print <i class="fa fa-print"></i></button>    
        </div>
        @if ($curriculum->checkIfLatestVersion())
        @if(Gate::check('isDean') || Gate::check('isSAdmin'))
          <div>
            <form v-on:submit.prevent="updateCurriculum" action="{{ url('/curricula/' . $curriculum->id. '/edit') }}" method="post">
              @csrf
              <button class="btn btn-success btn-sm" type="submit">Edit <i class="fa fa-edit"></i></button>
            </form>
          </div>
        @endif
        @endif
      </div>
    @endif

    
    <div v-show="!isLoading">
      <div class="alert alert-success mt-3">
          <div class="row">
            <div class="col-md-2">
              <label><b>Total units: </b></label>
            </div>
            <div class="col-md-10">
              <span>@{{ getAllUnits() }}</span>
            </div>
          </div>
          <div class="row">
            <div class="col-md-2">
              <label><b>Total courses: </b></label>
            </div>
            <div class="col-md-10">
              <span>@{{ this.curriculum.curriculum_courses.length }}</span>
            </div>
          </div>

          
        </div>
        
        @if (count($curriculum->getDeactivatedCourses()))
          <div class="my-3 d-flex justify-content-end">
            <a href="{{ url('/curricula/' . $curriculum->id . '/deactivated_courses?curriculum_id=' . $curriculum->id) }}" class="btn btn-secondary btn-sm">View Deactivated Courses ({{ count($curriculum->getDeactivatedCourses()) }}) <i class="fa fa-archive"></i></a>
          </div>
        @endif
    </div>

    


    <div class="accordion" id="accordionExample">
      <div v-if="isLoading">
        <div class="card">
          <div class="card-body">
            <table-loading></table-loading>
          </div>
        </div>
        
      </div>
      <div v-else v-for="year in curriculum.year_level">
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
            <div>
              <button v-on:click="toggleExpand(year, sem)" class="btn btn-sm
               mr-3" :class="{ 'btn-success': !checkIfExpand(year, sem)  , 'btn-secondary': checkIfExpand(year, sem) }">
                <i class="fa fa-arrows-alt-v "></i>
              </button>
              
              <span class="text-success">
              Total units: <b>@{{ getTotalUnit(year, sem, 'all') }}</b>
              </span> 
            </div>
          </div>
          
          <div :id="year + '' + sem" class="collapse" :class="{ show: !checkIfExpand(year, sem)  }" aria-labelledby="headingOne" >

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
                      <th class="text-center" v-if="is_saved == 0">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="curriculumCourse in getSemCourses(year, sem)" :key="curriculumCourse.course_id">
                      <th>@{{ curriculumCourse.course_code }}</th>
                      <td>@{{ curriculumCourse.description }}</td>
                      <td>@{{ curriculumCourse.lec_unit }}</td>
                      <td>@{{ curriculumCourse.lab_unit }}</td>
                      <td>@{{ formatPreRequisites(curriculumCourse) }}</td>
                      @if(Gate::check('isDean') || Gate::check('isSAdmin'))
                        <td class="justify-content-end d-flex" v-if="is_saved == 0">
                          <div class="mr-2">
                            <button
                              data-toggle="modal"
                              data-target="#curriculumCourseModalUpdate"
                              class="btn btn-success btn-sm"
                              v-on:click="selectCurriculumCourse(curriculumCourse)"
                            >
                              <i class="fa fa-edit"></i>
                            </button>
                          </div>
                          
                        </td>
                      @endif
                        
                    </tr>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th>Total: </th>
                      <td></td>
                      <th class="text-success">@{{ getTotalUnit(year,sem, 'lec') }}</th>
                      <th class="text-success">@{{ getTotalUnit(year,sem, 'lab') }}</th>
                      <td></td>
                      <td v-if="is_saved == 0"></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
              <div class="h5 text-center" v-else>
                No courses yet.
              </div>
            </div>
          </div>
          
        </div>
        <!-- end card -->



        {{-- summer --}}
        <div :key="year + '' + 3" v-if="checkIfHasSummer(year)"  class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <div>
              <h2 class="mb-0">
                <button v-on:click="toggleAccordion(year + '' + 3)" class="btn btn-link" type="button" data-toggle="collapse" :data-target="'#' + year + '' + 3" aria-expanded="true" aria-controls="collapseOne">
                  <b>
                    @{{ formatIndex(year) + ' year' + '/summer' }}
                  </b>  

                </button>
              </h2>
            </div>
            <div>
              <button v-on:click="toggleExpand(year, 3)" class="btn btn-sm
               mr-3" :class="{ 'btn-success': !checkIfExpand(year, 3)  , 'btn-secondary': checkIfExpand(year, 3) }">
                <i class="fa fa-arrows-alt-v "></i>
              </button>
              
              <span class="text-success">
              Total units: <b>@{{ getTotalUnit(year, 3, 'all') }}</b>
              </span> 
            </div>
          </div>
          
          <div :id="year + '' + 3" class="collapse" :class="{ show: !checkIfExpand(year, 3)  }" aria-labelledby="headingOne" >

            <div class="card-body">
              <div v-if="getSemCourses(year, 3).length > 0" class="table-responsive">
                <table class="table">
                  <thead class="thead-light">
                    <tr>
                      <th>Course Code</th>
                      <th>Description</th>
                      <th>Lec Unit</th>
                      <th>Lab Unit</th>
                      <th>Pre requsite</th>
                      <th class="text-center" v-if="is_saved == 0">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="curriculumCourse in getSemCourses(year, 3)" :key="curriculumCourse.course_id">
                      <th>@{{ curriculumCourse.course_code }}</th>
                      <td>@{{ curriculumCourse.description }}</td>
                      <td>@{{ curriculumCourse.lec_unit }}</td>
                      <td>@{{ curriculumCourse.lab_unit }}</td>
                      <td>@{{ formatPreRequisites(curriculumCourse) }}</td>
                      @if(Gate::check('isDean') || Gate::check('isSAdmin'))
                        <td class="justify-content-end d-flex" v-if="is_saved == 0">
                          <div class="mr-2">
                            <button
                              data-toggle="modal"
                              data-target="#curriculumCourseModalUpdate"
                              class="btn btn-success btn-sm"
                              v-on:click="selectCurriculumCourse(curriculumCourse)"
                            >
                              <i class="fa fa-edit"></i>
                            </button>
                          </div>
                          
                        </td>
                      @endif
                        
                    </tr>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th>Total: </th>
                      <td></td>
                      <th class="text-success">@{{ getTotalUnit(year,3, 'lec') }}</th>
                      <th class="text-success">@{{ getTotalUnit(year,3, 'lab') }}</th>
                      <td></td>
                      <td v-if="is_saved == 0"></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
              <div class="h5 text-center" v-else>
                No courses yet.
              </div>
            </div>
          </div>
          
        </div>
        {{-- /end summer --}}
      </div>
    </div>


    <div v-if="!isLoading">   
      @if (!$curriculum->is_saved)
        <div class="d-flex justify-content-end">
          <form action="{{ url('/curricula/' . $curriculum->id . '/save_curriculum') }}" v-on:submit.prevent="saveCurriculum"
           method="post">
           @csrf
            <button
              type="submit" 
              href="#" 
              class="btn btn-success mb-5 mt-3">
                Save 
                <i class="fa fa-save"></i>
            </button>
          </form>
        </div>
      @endif
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
        isLoading: true,
        college_id: '{{ Session::get('college_id') }}',
        expanded_list: [],
        is_saved: '{{ $curriculum->is_saved }}'
      },
      watch: {
        expanded_list(value) {
          //this.toggleAccordion();
          for (let i = 0; i < value.length; i++) {
            this.showAccordion(value[i].year_level + '' + value[i].sem);  
          }
        }
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
            this.getCurriculum();
            this.isLoading = false;
          })
        },
        toggleAccordion(id) {
          $('#' + id).collapse('toggle');
        },
        hideAccordion(id) {
          $('#' + id).collapse('hide');
        },
        showAccordion(id) {
          $('#' + id).collapse('show');
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
        formatPreRequisites(curriculumCourse) {
          let result = "";
          if(curriculumCourse.pre_requisites.length <= 0) {
            return "none;"
          } else {
            for(let i = 0; i < curriculumCourse.pre_requisites.length; i++) {
              result += curriculumCourse.pre_requisites[i].pre_req_code;
              result += '; ';
            }
          }
          return result;
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
                swal.fire(
                  'Ooops... Error in deletion!',
                  'The course maybe is a pre requisite of other courses',
                  'error'
                )
              })
              
            }
          });
        },
        courseAddedSuccessfully() {
          this.getCurriculum();
          this.searchCourseText = '';
          this.searchCourses();
        },
        openCurriculumCourse(course) {
          // v-on:click="selectCourse(course)" 
          // class="btn btn-primary btn-sm" 
          // data-toggle="modal"
          // data-target="#curriculumCourseModal"
          this.selectCourse(course);
          $('#curriculumCourseModal').modal('show')
        },
        toggleExpand(year, sem) {
          //check if exists
          for (let i = 0; i < this.expanded_list.length; i++) {
            if(this.expanded_list[i].year_level == year && this.expanded_list[i].sem == sem) {
              return this.expanded_list.splice(i, 1);        
            } 
          }
          //then if not exists push
          this.expanded_list.push({
            year_level: year,
            sem: sem
          });
          
        },
        checkIfExpand(year, sem) {
          for (let i = 0; i < this.expanded_list.length; i++) {
            if(this.expanded_list[i].year_level == year && this.expanded_list[i].sem == sem) {
              return true;       
            } 
          }

          return false;
        },
        getAllUnits() {
          let total = 0;
          for(let i = 0; i < this.curriculum.curriculum_courses.length; i++) {
            total += this.curriculum.curriculum_courses[i].lec_unit;
            total += this.curriculum.curriculum_courses[i].lab_unit;
          }
          return total;
        },
        saveCurriculum(event) {
          swal.fire({
            title: 'Are you sure?',
            text: "Do you want to save this curriculum now?",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
          }).then((result) => {
            if (result.value) {
              event.target.submit();
            }
          });
        },
        reviseCurriculum(event) {
          // swal.fire({
          //   title: 'Are you sure?',
          //   text: "You want to revise this curriculum?",
          //   type: 'question',
          //   showCancelButton: true,
          //   confirmButtonColor: '#3085d6',
          //   cancelButtonColor: '#d33',
          //   confirmButtonText: 'Yes'
          // }).then((result) => {
          //   if (result.value) {
          //     //event.target.submit();

          //   }
          // });
          $('#curriculumModal').modal('show');
        },
        updateCurriculum(event) {
          swal.fire({
            title: 'Do you want to update this curriculum?',
            text: "Please confirm",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#1cc88a',
            cancelButtonColor: '#e74a3b',
            confirmButtonText: 'Yes'
          }).then((result) => {
            if (result.value) {
              event.target.submit();
            }
          });
        },
        checkIfHasSummer(year) {
          for(let i = 0; i < this.curriculum.curriculum_courses.length; i++) {
            if(this.curriculum.curriculum_courses[i].year_level == year && this.curriculum.curriculum_courses[i].semester == 3) {
              return true;
            }
          }
          return false;
        }
      },
      created() {
        
        setTimeout(() => {
          this.getCurriculum();
        }, 1000);  
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
