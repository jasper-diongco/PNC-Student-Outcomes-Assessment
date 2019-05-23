@extends('layouts.sb_admin')

@section('title', 'Curriculum Mapping')


@section('content')
  <div id="app" v-cloak>
    <a href="{{ url('/curriculum_mapping') }}" class="btn btn-success btn-sm mb-2"><i class="fa fa-arrow-left"></i> Back</a>
    <div class="d-flex justify-content-between mb-2">
      <div>
        <h1 class="h3 mb-1 text-gray-800">Curriculum Mapping</h1>
      </div>
    </div>


    @if (count($curriculum->program->studentOutcomes) > 0 && count($curriculum->curriculumCourses) > 0)

    <div class="text-primary mb-2"><strong>Legends</strong></div>

    <div class="legends d-flex mb-4">
      <div class="d-flex align-items-center mr-5" data-toggle="tooltip" data-placement="bottom" title="Students are introduced to knowledge and skills and are able to remember and understand what they have learned">
        <div class="learning-legend" style="background:#d8faee;"></div>
        <div class="mx-1">&mdash;</div>
        <div class="text-success"><b>Introduced</b></div>
      </div>
      <div class="d-flex align-items-center mr-5" data-toggle="tooltip" data-placement="bottom" title="Students are practice through activities that help them learn how to apply their learning or skills">
        <div class="learning-legend" style="background:#fcebc0;"></div>
        <div class="mx-1">&mdash;</div>
        <div class="text-success"><b>Reinforced</b></div>
      </div>
      <div class="d-flex align-items-center mr-5" data-toggle="tooltip" data-placement="bottom" title="Students are able to integrate the knowledge and skills in order to accumulate, evaluate, and create new ideas">
        <div class="learning-legend" style="background:#fad6d2;"></div>
        <div class="mx-1">&mdash;</div>
        <div class="text-success"><b>Demonstrated</b></div>
      </div>
    </div>
    
      <div class="card shadow">
        <div class="card-header d-flex justify-content-between">
          <h2 class="h5 text-primary">Curriculum Map &mdash; @{{ curriculum.name }}</h2>
          
          @if (Gate::check('isDean') || Gate::check('isSAdmin'))

          <div v-if="curriculum_mapping_status.status == 1">
            <form action="{{ url('/curriculum_mapping/'. $curriculum->id .'/edit') }}" method="post" v-on:submit.prevent="editCurriculumMapping">
              @csrf
              <button type="submit" class="btn btn-sm btn-primary">Edit <i class="fa fa-edit"></i></button>
            </form>
          </div>

          @endif

        </div>
        <div class="card-body">
          <div class="row mb-3" v-if="curriculum_mapping_status.status == 0">
            <div class="col-md-2">
              <div class="d-flex align-items-center">
                <div class="mr-2">
                  <label class="col-form-label"><b>Select Year:</b></label>
                </div>
                <div>
                <select v-model="selectedYear" class="form-control" v-on:change="getSemCourses">
                  <option v-for="num in curriculum.year_level" :value="num">@{{ formatIndex(num) }}</option>
                </select>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="d-flex align-items-center">
                <div class="mr-2">
                  <label class="col-form-label"><b>Select Semester:</b></label>
                </div>
                <div>
                <select v-model="selectedSem" class="form-control" v-on:change="getSemCourses">
                  <option value="1">1st Sem</option>
                  <option value="2">2nd Sem</option>
                  <option value="3">Summer</option>
                </select>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="d-flex align-items-center">
                <div class="mr-2">
                  <label class="col-form-label"><b>Total Units:</b></label>
                </div>
                <div class="text-success">
                  <b>@{{ getTotalSubUnit(selectedYear, selectedSem, 'all') }}</b>
                </div>
              </div>
            </div>
            {{-- <div class="col-md-4">
              <div class="d-flex justify-content-end"> 
                <div class="form-group">
                  <input type="checkbox" id="show_all" v-model="show_all" v-on:change="getSemCourses">
                  <label for="show_all"><b>Show All</b></label>
                </div>
              </div>
             
            </div> --}}
          </div>


          
          @if ($curriculum_mapping_status->status == 0)
            <transition name="fade">

            <div class="table-responsive" v-if="!isLoading" id="my-table">
              <div v-for="year in curriculum.year_level">
                <div v-for="sem in 3">
                  <div v-if="curriculum_mapping_status.status == true || year == selectedYear && sem == selectedSem">
                    <div v-if="selectCurriculumCourses(year, sem).length > 0" class="mb-1" style="text-decoration: underline;"><b>@{{ formatIndex(year)  + ' year/' + formatIndex(sem) + ' sem' }}</b></div>
                    <table class="table table-bordered" v-if="selectCurriculumCourses(year, sem).length > 0">
                      <thead>
                        <tr>
                          <th></th>
                            <th v-for="student_outcome in student_outcomes" :key="student_outcomes.id" class="cursor-pointer text-warning" data-toggle="popover" :title="student_outcome.so_code" :data-content="student_outcome.description" data-placement="bottom" style="min-width: 50px;">
                              @{{ student_outcome.so_code }}
                            </th>
                        </tr>
                      </thead>
                        <tbody >
                            <tr :id="curriculum_courses.id" v-for="curriculum_course in selectCurriculumCourses(year, sem)" :key="curriculum_courses.id">
                              
                                <th width="100px" data-toggle="popover" :title="curriculum_course.course.course_code" :data-content="curriculum_course.course.description" data-placement="right">
                                  <div class="text-primary"><b>@{{ curriculum_course.course.course_code }}</b></div>
                                  <div>@{{ parseInt(curriculum_course.course.lec_unit) + parseInt(curriculum_course.course.lab_unit)  }} units</div>
                                  {{-- <small class="text-muted" style="font-size: 10px">@{{ curriculum_course.course.description}}</small> --}}
                                </th>
                                <td v-for="student_outcome in student_outcomes" :class="{'' : !checkMap(curriculum_course.id, student_outcome.id), 'introduced': checkMap(curriculum_course.id, student_outcome.id).learning_level_id == 1, 'reinforced': checkMap(curriculum_course.id, student_outcome.id).learning_level_id == 2, 'demonstrated': checkMap(curriculum_course.id, student_outcome.id).learning_level_id == 3 }" style="cursor: pointer;">
                                  <div>
                                    <div v-if="curriculum_mapping_status.status == 0"  class="d-flex justify-content-end align-items-start">
                                      <div v-if="checkMap(curriculum_course.id, student_outcome.id)" v-on:click="mapCourse({target: { checked: true }}, curriculum_course, student_outcome )" class="mr-2">
                                        <i class="fa fa-edit" style="font-size: 10px;"></i>
                                      </div>
                                      <div class="custom-control custom-checkbox">
                                        <input v-on:change="mapCourse($event, curriculum_course, student_outcome)" type="checkbox" class="custom-control-input" :id="curriculum_course.id + '' + student_outcome.id" :checked="checkMap(curriculum_course.id, student_outcome.id)" style="cursor: pointer;">
                                        <label class="custom-control-label" :for="curriculum_course.id + '' + student_outcome.id"></label>
                                      </div>
                                    </div>
                                    <div v-else>
                                      <div class="d-flex justify-content-end align-items-start">
                                        <checked-icon v-if="checkMap(curriculum_course.id, student_outcome.id)"></checked-icon>
                                      </div>
                                    </div>                                                    
                                  </div>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                          <tr>
                          <th></th>
                            <th v-for="student_outcome in student_outcomes" :key="student_outcomes.id" class="cursor-pointer text-warning" data-toggle="popover" :title="student_outcome.so_code" :data-content="student_outcome.description" data-placement="bottom">
                              @{{ student_outcome.so_code }}
                            </th>
                        </tr>
                        </tfoot>
                    </table>
                  </div>
                  {{-- <div class="text-center bg-dark text-white p-3" v-else-if="selectedCurriculumCourses.length > 0">
                    No Courses Or Student Outcome Found.
                  </div> --}}
                </div>
              </div>
              
            </div>
            <div v-else>
              <table-loading></table-loading>
            </div>
            </transition>  
          @else
            @for($year = 1; $year <= $curriculum->year_level; $year++)
              @for($sem = 1; $sem <= 3; $sem++)
                @if($curriculum->getSemCourses($year, $sem)->count() > 0)
                  <div  class="mb-1" style="text-decoration: underline;"><b>{{ $year . ' year/ ' . $sem . ' sem' }}</b></div>
                      <div class="table-responsive table-db" >
                        <table class="table table-bordered">
                          <thead>
                            <th></th>
                            @foreach ($curriculum->program->studentOutcomes as $student_outcome)
                              
                              <th 
                                class="cursor-pointer text-warning" 
                                data-toggle="popover" 
                                title="{{ $student_outcome->so_code }}" 
                                data-content="{{ $student_outcome->description }}"data-placement="bottom" 
                                style="min-width: 50px;">{{ $student_outcome->so_code }}</th>
                            @endforeach   
                          </thead>
                          <tbody>                    
                            @foreach($curriculum->getSemCourses($year, $sem) as $curriculum_course)

                                  <tr>
                                    <th width="100px" data-toggle="popover" 
                                    title="{{ $curriculum_course->course->course_code}}" data-content="{{$curriculum_course->course->description}}" data-placement="right">
                                      <div class="text-primary"><b>{{ $curriculum_course->course->course_code }}</b></div>
                                      <div>{{ $curriculum_course->course->lec_unit + $curriculum_course->course->lab_unit  }} units</div>
                                      {{-- <small class="text-muted" style="font-size: 10px">@{{ curriculum_course.course.description}}</small> --}}
                                    </th>
                                    @for($so = 0; $so < $curriculum->program->studentOutcomes->count(); $so++)
                                        @if($map = $curriculum->checkMap($curriculum_course->id, $curriculum->program->studentOutcomes[$so]->id))
                                          @if($map->learning_level_id == 1)
                                            <td class="introduced">
                                          @elseif ($map->learning_level_id == 2)
                                            <td class="reinforced">
                                          @elseif ($map->learning_level_id == 3)
                                            <td class="demonstrated">
                                          @endif
                                            <div class="d-flex justify-content-end align-items-start">
                                            <checked-icon></checked-icon>
                                          </div>
                                          </td>
                                        @else
                                          <td style="cursor: pointer;"></td>
                                        @endif
                                      <!--
                                      <td v-for="student_outcome in student_outcomes" :class="{'' : !checkMap(curriculum_course.id, student_outcome.id), 'introduced': checkMap(curriculum_course.id, student_outcome.id).learning_level_id == 1, 'reinforced': checkMap(curriculum_course.id, student_outcome.id).learning_level_id == 2, 'demonstrated': checkMap(curriculum_course.id, student_outcome.id).learning_level_id == 3 }" style="cursor: pointer;">
                                  <div>
                                    <div v-if="curriculum_mapping_status.status == 0"  class="d-flex justify-content-end align-items-start">
                                      <div v-if="checkMap(curriculum_course.id, student_outcome.id)" v-on:click="mapCourse({target: { checked: true }}, curriculum_course, student_outcome )" class="mr-2">
                                        <i class="fa fa-edit" style="font-size: 10px;"></i>
                                      </div>
                                      <div class="custom-control custom-checkbox">
                                        <input v-on:change="mapCourse($event, curriculum_course, student_outcome)" type="checkbox" class="custom-control-input" :id="curriculum_course.id + '' + student_outcome.id" :checked="checkMap(curriculum_course.id, student_outcome.id)" style="cursor: pointer;">
                                        <label class="custom-control-label" :for="curriculum_course.id + '' + student_outcome.id"></label>
                                      </div>
                                    </div>
                                    <div v-else>
                                      <div class="d-flex justify-content-end align-items-start">
                                        <checked-icon v-if="checkMap(curriculum_course.id, student_outcome.id)"></checked-icon>
                                      </div>
                                    </div>                                                    
                                  </div>
                                </td>
                              -->
                                    @endfor

                                  </tr>
                                  
                            @endforeach
                          </tbody>
                          <tfoot>
                            <tr>
                              <th></th>
                              @foreach ($curriculum->program->studentOutcomes as $student_outcome)
                                <th 
                                  class="cursor-pointer text-warning" 
                                  data-toggle="popover" 
                                  title="{{ $student_outcome->so_code }}" 
                                  data-content="{{ $student_outcome->description }}"data-placement="bottom" 
                                  style="min-width: 50px;">{{ $student_outcome->so_code }}</th>
                              @endforeach 
                            </tr>
                          </tfoot>
                      </table>
                    </div>
                  @endif
              @endfor
            @endfor
          @endif

      </div>
    </div>
    {{-- end card --}}
    @if(Gate::check('isDean') || Gate::check('isSAdmin'))

      <div v-if="curriculum_mapping_status.status == 0" class="d-flex justify-content-end">
        <button class="btn btn-success btn-lg my-4" v-on:click="saveCurriculumMap">Save <i class="fa fa-save"></i></button>
      </div>

    @endif

    @else
      <div class="text-center bg-dark text-white p-3 mt-3">
        No Courses Or Student Outcome Found.
      </div>
    @endif
    

    <!-- Button trigger modal -->
    {{-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
      Launch demo modal
    </button> --}}

    <!-- Modal -->
    <div class="modal fade" id="CourseMappingModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalCenterTitle">Map Course</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body ">
            <!-- Field for course_code  -->
              <div class="form-group row">
                <label
                  for="so_code"
                  class="col-md-3 col-form-label text-md-right"
                  ><b>Student Outcome</b></label
                >
                <div class="col-md-9">
                  <input type="text" class="form-control" v-model="so_code" readonly>
                  <small class="text-primary">@{{ so_desc }}</small>
                </div>
              </div>
              <!-- /end Field for course_code -->

              <!-- Field for so_code  -->
              <div class="form-group row">
                <label
                  for="course_code"
                  class="col-md-3 col-form-label text-md-right"
                  
                  ><b>Course Code</b></label
                >
                <div class="col-md-9">
                  <input type="text" class="form-control" v-model="course_code" readonly>
                  <small class="text-primary">@{{ course_desc }}</small>
                </div>
              </div>
              <!-- /end Field for so_code -->


            <!-- Field for learning_level  -->
              <div class="form-group row">
                <label
                  for="course_code"
                  class="col-md-3 col-form-label text-md-right"
                  ><b>Learning Level</b></label
                >

                <div class="col-md-9">
                  <select
                    id="learning_level"
                    class="form-control"
                    v-model="learning_level">
                    <option value="1">Introduced</option>
                    <option value="2">Reinforced</option>
                    <option value="3">Demonstrated</option>
                  </select>
                </div>
              </div>
              <!-- /end Field for learning_level -->
            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-success" v-on:click="confirmMap">Confirm <i class="fa fa-check"></i></button>
          </div>
        </div>
      </div>
    </div>

  </div>

@endsection

@push('scripts')
    <script>
    var vm = new Vue({
      el: '#app',
      data: {
        curriculum: @json($curriculum),
        student_outcomes: @json($curriculum->program->studentOutcomes),
        curriculum_courses: @json($curriculum_courses),
        selectedYear: 1,
        selectedSem: 1,
        selectedCurriculumCourses: [],
        isLoading: false,
        is_checked: true,
        curriculum_maps: @json($curriculum_maps),
        learning_level: 1,
        course_id: '',
        course_code: '',
        course_desc:'',
        so_id: '',
        so_code: '',
        so_desc: '',
        curriculum_mapping_status: @json($curriculum_mapping_status),
        show_all: false
      },
      methods: {
        getCurriculum() {

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
        getSemCourses() {
          if(this.show_all) {
            this.selectedCurriculumCourses = this.curriculum_courses.filter(curriculumCourse => {
              return true;
            });
          } else {
            this.selectedCurriculumCourses = this.curriculum_courses.filter(curriculumCourse => {
              return curriculumCourse.year_level == this.selectedYear && curriculumCourse.semester == this.selectedSem;
            });
          }
          

          this.isLoading = true;

          setTimeout(function() {
            vm.isLoading = false;
            $(function () {
              $('[data-toggle="popover"]').popover();
              $('#my-table').doubleScroll({
                resetOnWindowResize: true
              });
              $('.popover-dismiss').popover({
                trigger: 'focus'
              });
             
            });

          }, 300);
        },
        selectCurriculumCourses(year, sem) {
          return this.curriculum_courses.filter(curriculumCourse => {
              return curriculumCourse.year_level == year && curriculumCourse.semester == sem;
            });
        },
        getTotalSubUnit(year, sem, type) {
          let total = 0;
          const courses = this.selectedCurriculumCourses;

          if (courses.length > 0) {
            for (let i = 0; i < courses.length; i++) {
              if (type == 'lec') {
                total += courses[i].course.lec_unit;
              } else if (type == 'lab') {
                total += courses[i].course.lab_unit;
              } else if(type == 'all') {
                total += courses[i].course.lab_unit;
                total += courses[i].course.lec_unit;
              }
              
            }
          }

          return total;
        },
        mapCourse(event, curriculum_course, student_outcome) {
          if(this.getMap(curriculum_course.id, student_outcome.id)) {
            this.learning_level = this.getMap(curriculum_course.id, student_outcome.id).learning_level_id;
          }
          if(event.target.checked) {
            this.course_id = curriculum_course.id;
            this.course_code = curriculum_course.course.course_code;
            this.course_desc = curriculum_course.course.description;
            this.so_id = student_outcome.id;
            this.so_code = student_outcome.so_code;
            this.so_desc = student_outcome.description;
            this.openModal();
            event.target.checked = false;
          } else {
            // swal.fire({
            //   type: 'error',
            //   title: 'unchecked'
            // });
            event.target.checked = true;
            swal.fire({
              title: 'Do you want to remove?',
              text: "Please confirm",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#1cc88a',
              cancelButtonColor: '#858796',
              confirmButtonText: 'Yes',
              width: '350px'
            }).then((result) => {
              if (result.value) {
                let map = this.getMap(curriculum_course.id, student_outcome.id);
                map.is_checked = false;
              }
            });
          }
        },
        checkMap(curriculum_course_id, student_outcome_id) {
          for (let i = 0; i < this.curriculum_maps.length; i++) {
            if(this.curriculum_maps[i].student_outcome_id == student_outcome_id && this.curriculum_maps[i].curriculum_course_id == curriculum_course_id && this.curriculum_maps[i].is_checked) {
              return this.curriculum_maps[i];
            }
          }

          return false;
        },
        getMap(curriculum_course_id, student_outcome_id) {
          for (let i = 0; i < this.curriculum_maps.length; i++) {
            if(this.curriculum_maps[i].student_outcome_id == student_outcome_id && this.curriculum_maps[i].curriculum_course_id == curriculum_course_id) {
              return this.curriculum_maps[i];
            }
          }

          return false;
        },
        confirmMap() {
          //check if exists
          let exists = false;
          for(let i = 0; i < this.curriculum_maps.length; i++) {
            if(this.getMap(this.course_id, this.so_id)) {
              exists = true;
              break;
            }
          }

          if(!exists) {
              this.curriculum_maps.push({
                id: null,
                student_outcome_id: this.so_id,
                curriculum_course_id: this.course_id,
                is_checked: true,
                learning_level_id: this.learning_level
              });
            } else {
              let map = this.getMap(this.course_id, this.so_id);
              map.is_checked = true;
              map.learning_level_id = this.learning_level;
            }

          
          this.closeModal();
        },
        openModal() {
          $('#CourseMappingModal').modal('show');
        },
        closeModal() {
          $('#CourseMappingModal').modal('hide');
        },
        saveCurriculumMap() {
          swal.fire({
            title: 'Do you want to save?',
            text: "Please confirm",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#1cc88a',
            cancelButtonColor: '#858796',
            confirmButtonText: 'Yes',
            width: '400px'
          }).then((result) => {
            if (result.value) {
              ApiClient.post("/curriculum_mapping/save_maps", {
                curriculum_maps: this.curriculum_maps,
                curriculum_mapping_status: this.curriculum_mapping_status
              }).then(response => {
                window.location.replace(myRootURL + "/curriculum_mapping/" + this.curriculum.id);
              }).catch(err=> {
                console.log(err);
                serverError();
              })
            }
          });
        },
        editCurriculumMapping(event) {
          swal.fire({
            title: 'Do you want to update this curriculum mapping?',
            text: "Please confirm",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#1cc88a',
            cancelButtonColor: '#858796',
            confirmButtonText: 'Yes',
            width: '400px'
          }).then((result) => {
            if (result.value) {
              event.target.submit();
            }
          });
        }
      },
      created() {
        this.getSemCourses();
      }
    });
  </script>

  <script>
    $(document).ready(function() {
      $('.double-scroll').doubleScroll();
      $('.tables-db').doubleScroll({
        resetOnWindowResize: true
      });
      $('#my-table').doubleScroll({
        resetOnWindowResize: true
      });
      $('.popover-dismiss').popover({
        trigger: 'focus'
      });
      $('#app').popover('hide');
    });

    /*
     * @name DoubleScroll
     * @desc displays scroll bar on top and on the bottom of the div
     * @requires jQuery
     *
     * @author Pawel Suwala - http://suwala.eu/
     * @author Antoine Vianey - http://www.astek.fr/
     * @version 0.5 (11-11-2015)
     *
     * Dual licensed under the MIT and GPL licenses:
     * https://www.opensource.org/licenses/mit-license.php
     * http://www.gnu.org/licenses/gpl.html
     * 
     * Usage:
     * https://github.com/avianey/jqDoubleScroll
     */
     (function( $ ) {
      
      jQuery.fn.doubleScroll = function(userOptions) {
      
        // Default options
        var options = {
          contentElement: undefined, // Widest element, if not specified first child element will be used
          scrollCss: {                
            'overflow-x': 'auto',
            'overflow-y': 'hidden'
          },
          contentCss: {
            'overflow-x': 'auto',
            'overflow-y': 'hidden'
          },
          onlyIfScroll: true, // top scrollbar is not shown if the bottom one is not present
          resetOnWindowResize: false, // recompute the top ScrollBar requirements when the window is resized
          timeToWaitForResize: 30 // wait for the last update event (usefull when browser fire resize event constantly during ressing)
        };
      
        $.extend(true, options, userOptions);
      
        // do not modify
        // internal stuff
        $.extend(options, {
          topScrollBarMarkup: '<div class="doubleScroll-scroll-wrapper" style="height: 20px;"><div class="doubleScroll-scroll" style="height: 20px;"></div></div>',
          topScrollBarWrapperSelector: '.doubleScroll-scroll-wrapper',
          topScrollBarInnerSelector: '.doubleScroll-scroll'
        });

        var _showScrollBar = function($self, options) {

          if (options.onlyIfScroll && $self.get(0).scrollWidth <= $self.width()) {
            // content doesn't scroll
            // remove any existing occurrence...
            $self.prev(options.topScrollBarWrapperSelector).remove();
            return;
          }
        
          // add div that will act as an upper scroll only if not already added to the DOM
          var $topScrollBar = $self.prev(options.topScrollBarWrapperSelector);
          
          if ($topScrollBar.length == 0) {
            
            // creating the scrollbar
            // added before in the DOM
            $topScrollBar = $(options.topScrollBarMarkup);
            $self.before($topScrollBar);

            // apply the css
            $topScrollBar.css(options.scrollCss);
            $self.css(options.contentCss);

            // bind upper scroll to bottom scroll
            $topScrollBar.bind('scroll.doubleScroll', function() {
              $self.scrollLeft($topScrollBar.scrollLeft());
            });

            // bind bottom scroll to upper scroll
            var selfScrollHandler = function() {
              $topScrollBar.scrollLeft($self.scrollLeft());
            };
            $self.bind('scroll.doubleScroll', selfScrollHandler);
          }

          // find the content element (should be the widest one)  
          var $contentElement;    
          
          if (options.contentElement !== undefined && $self.find(options.contentElement).length !== 0) {
            $contentElement = $self.find(options.contentElement);
          } else {
            $contentElement = $self.find('>:first-child');
          }
          
          // set the width of the wrappers
          $(options.topScrollBarInnerSelector, $topScrollBar).width($contentElement.outerWidth());
          $topScrollBar.width($self.width());
          $topScrollBar.scrollLeft($self.scrollLeft());
          
        }
      
        return this.each(function() {
          
          var $self = $(this);
          
          _showScrollBar($self, options);
          
          // bind the resize handler 
          // do it once
          if (options.resetOnWindowResize) {
          
            var id;
            var handler = function(e) {
              _showScrollBar($self, options);
            };
          
            $(window).bind('resize.doubleScroll', function() {
              // adding/removing/replacing the scrollbar might resize the window
              // so the resizing flag will avoid the infinite loop here...
              clearTimeout(id);
              id = setTimeout(handler, options.timeToWaitForResize);
            });

          }

        });

      }

    }( jQuery ));

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