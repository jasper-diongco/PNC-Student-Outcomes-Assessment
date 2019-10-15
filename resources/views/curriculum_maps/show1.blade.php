@extends('layout.app',['active' => 'curriculum_mapping'])

@section('title', 'Curriculum Mapping')

@section('content')
    <div id="app" v-cloak>
        {{-- add --}}
        <learning-level-modal :program_id="program.id" :learning_levels="learning_levels" v-on:update-learning-levels="getLearningLevels"></learning-level-modal>
        {{-- update --}}
        <learning-level-modal :program_id="program.id" :learning_levels="learning_levels" v-on:update-learning-levels="getLearningLevels" :is_update="true" :learning_level="learning_level"></learning-level-modal>
        <div class="card p-3 mb-3">
            <a href="{{ url('/curriculum_mapping') }}" class="text-success"><i class="fa fa-arrow-left"></i> Back</a>
            {{-- <div class="mx-auto" style="width: 400px">
              <img src="{{ asset('svg/map.svg') }}" class="w-100">
            </div> --}}
            <div class="d-flex mt-3">
                <h1 class="page-header">Curriculum Mapping</h1>
            </div>
            
            <div class="d-flex">
                <div>
                    <label>Program: <span style="font-size: 20px" class="text-info">{{ $curriculum->program->program_code }}</span></label>
                </div>
                <div class="ml-3">
                    <label>Curriculum: <span style="font-size: 20px" class="text-info">{{ $curriculum->name }}</span></label>
                </div>
            </div>

            <div class="d-flex mt-2">
                <div class="mr-4"><i class="fa fa-code-branch text-primary"></i> <label>Revision no:</label> {{ $curriculum->revision_no }}.0</div>

                <div class="mr-4"><i class="fa fa-calendar-check text-primary"></i> <label>Year:</label> {{ $curriculum->year }}</div>
            </div>

      

          @if($curriculum->description) 
            <p class="mr-5"><i class="fa fa-file-alt text-primary"></i> <label>Description:</label> {{ $curriculum->description }}</p>
          @else
            <p class="mr-5"><i class="fa fa-file-alt text-primary"></i> <label>Description:</label> <i>No description.</i></p>
          @endif

          <div class="d-flex justify-content-end">
              <a href="{{ url('/curriculum_mapping/' . $curriculum->id . '/print_curriculum_mapping') }}" target="_blank" class="btn btn-sm btn-info"><i class="fa fa-print"></i> Print</a>
          </div>

          @if (!$curriculum->checkIfLatestVersion())
              <div class="alert alert-warning mt-3">
                <i class="fa fa-exclamation-triangle"></i>
                This is not the latest version of <b>{{ $curriculum->name }}</b> curriculum. View the latest version <a href="{{ url('/curriculum_mapping/' . $curriculum->getLatestVersion()->id) }}">here</a>
              </div>
            @endif
        </div>



        <div class="card">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-baseline">
                    <div>
                        <h5>Learning levels</h5>
                    </div>
                    <div>
                        @if ($curriculum->checkIfLatestVersion())
                        @if(Gate::check('isSAdmin') || Gate::check('isDean'))
                            <button class="btn btn-info btn-sm" v-on:click="openLearningLevelModal">Add Learning Level <i class="fa fa-plus-circle"></i></button>
                        @endif
                        @endif
                    </div>
                    
                </div>
                
                <template v-if="learning_levels.length > 0">
                    <ul class="list-group mt-2">
                        <li v-for="learning_level in learning_levels" :key="learning_level.id" class="list-group-item d-flex justify-content-between" :style="{ 'background': learning_level.color }">
                            <div>
                                @{{ learning_level.name }} - <label>@{{ learning_level.letter }}</label>                        
                            </div>
                            <div>
                                @if(Gate::check('isSAdmin') || Gate::check('isDean'))
                                <button v-on:click="openUpdateModal(learning_level)" class="btn btn-sm"><i class="fa fa-edit"></i></button>
                                @endif
                            </div>   
                        </li>
                    </ul>
                </template>
                <template v-else>
                    <div class="text-center py-3 text-warning">No Learning level. Please add.</div>
                </template>
            </div>
        </div>

        <div class="card mt-4">

            <div class="card-body">
                

                <div class="d-flex justify-content-between align-items-baseline">
                    <div>
                        <h5 class="text-dark">Curriculum Map <i class="fa fa-map text-success"></i></h5>
                    </div>
                    <div>
                        @if ($curriculum->checkIfLatestVersion())
                            @if (Gate::check('isDean') || Gate::check('isSAdmin'))

                            <div v-if="curriculum_mapping_status.status == 1">
                              {{-- <form action="{{ url('/curriculum_mapping/'. $curriculum->id .'/edit') }}" method="post" v-on:submit.prevent="editCurriculumMapping">
                                @csrf
                                <button type="submit" class="btn btn-info">Update Curriculum Mapping <i class="fa fa-edit"></i></button>
                              </form> --}}
                              <button :disabled="isLoading"  v-on:click="editCurriculumMapping" type="submit" class="btn btn-info">
                                <div v-if="isLoading" class="spinner-border spinner-border-sm text-light" role="status">
                                  <span class="sr-only">Loading...</span>
                                </div>
                                Update Curriculum Mapping <i class="fa fa-edit"></i>
                              </button>
                            </div>
                            <button :disabled="isLoading" v-if="curriculum_mapping_status.status == 0" class="btn btn-info" v-on:click="saveCurriculumMaps">
                                <div v-if="isLoading" class="spinner-border spinner-border-sm text-light" role="status">
                                  <span class="sr-only">Loading...</span>
                                </div>
                                Save Curriculum Mapping <i class="fa fa-check-circle"></i>
                            </button>
                            @endif
                        @endif
                    </div>
                </div>

                <div v-for="term in template">
                    <label class="text-info mt-4">@{{ term.year_sem }}</label>
                    <div class="table-responsive">      
                        <table class="table table-bordered">
                            <thead>
                                <th></th>
                                <th v-for="student_outcome in student_outcomes" :key="student_outcome.id" :title="student_outcome.description">@{{ student_outcome.so_code }}</th>
                            </thead>
                            <tbody>
                                <tr v-for="curriculum_course in term.curriculum_courses" :key="curriculum_course.id">
                                    <th width="8%" :title="curriculum_course.course.description">@{{ curriculum_course.course.course_code }} <div class="text-muted" style="font-size: 13px; font-weight: 300">@{{ curriculum_course.course.lec_unit + curriculum_course.course.lab_unit }} units</div></th>
                                    <template v-for="curriculum_map in curriculum_course.curriculum_maps">
                                    <td align="center" valign="middle" v-if="curriculum_map.is_checked != false" :key="curriculum_map.id"  :style="{'background': curriculum_map.learning_level.color}">
                                       <span v-if="curriculum_mapping_status.status == 1"> @{{ curriculum_map.learning_level.letter }}</span>

                                        <label v-else class="check-container">
                                          <input v-on:change="removeMap($event, curriculum_map)" type="checkbox" :checked="curriculum_map.is_checked">
                                          <span class="checkmark"></span>
                                        </label>

                                        <i v-if="curriculum_mapping_status.status == 0" v-on:click="openMapModal($event, curriculum_map)" class="fa fa-edit ml-5" style="font-size: 13px;cursor: pointer;"></i>

                                       
                                    </td>
                                        <td v-else>
                                            <label v-if="curriculum_mapping_status.status == 0" class="check-container">
                                              <input v-on:change="openMapModal($event, curriculum_map)" type="checkbox" :checked="curriculum_map.is_checked">
                                              <span class="checkmark"></span>
                                            </label>
                                        </td>
                                    </template>
                                </tr>
                            </tbody>
                            <thead>
                                <th></th>
                                <th v-for="student_outcome in student_outcomes" :key="student_outcome.id" :title="student_outcome.description">@{{ student_outcome.so_code }}</th>
                            </thead>
                        </table>
                    </div>  
                </div>
                
                <div class="d-flex justify-content-end">
                    @if ($curriculum->checkIfLatestVersion())
                        @if(Gate::check('isDean') || Gate::check('isSAdmin'))
                            <button :disabled="isLoading" v-if="curriculum_mapping_status.status == 0" class="btn btn-info" v-on:click="saveCurriculumMaps">
                                <div v-if="isLoading" class="spinner-border spinner-border-sm text-light" role="status">
                                  <span class="sr-only">Loading...</span>
                                </div>
                                Save Curriculum Mapping <i class="fa fa-check-circle"></i>
                            </button>
                        @endif
                    @endif
                </div>
                
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="mapModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
              
              <div class="modal-body">
                <h5 class="modal-title mb-2" id="exampleModalLabel">Select Learning Level</h5>
                <div class="form-group">
                    <div>
                        <label v-if="curriculum_course" class="text-dark">Course: <span class="text-info" style="font-size: 20px;">@{{ curriculum_course.course.course_code }}</span></label>
                    </div>
                    <div>
                        <label v-if="student_outcome" class="text-dark">Student Outcome: <span class="text-info" style="font-size: 20px;">@{{ student_outcome.so_code }}</span></label>
                    </div>
                          
                    <select class="form-control" v-model="learning_level_id">
                        <option value="" class="d-none">Please select</option>
                        <option :value="learning_level.id" v-for="learning_level in learning_levels">@{{ learning_level.name }}</option>
                    </select>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary " data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success ml-2" v-on:click="mapCourse">Save</button>
                </div>
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
                learning_levels: @json($learning_levels),
                program: @json($curriculum->program),
                learning_level: '',
                curriculum: @json($curriculum),
                student_outcomes: @json($curriculum->program->studentOutcomes),
                curriculum_courses: @json($curriculum_courses),
                curriculum_maps: @json($curriculum_maps),
                curriculum_mapping_status: @json($curriculum_mapping_status),
                template: [],
                curriculum_course: '',
                student_outcome: '',
                learning_level_id: '',
                isLoading: false,
                isFaculty: '@if(Gate::check('isProf')) {{ 1 }} @else {{ 0 }} @endif'
            },
            methods: {
                openLearningLevelModal() {
                    $("#learningLevelModal").modal("show");
                },
                getLearningLevels() {
                    ApiClient.get("/programs/" + this.program.id + "/get_learning_levels")
                    .then(response => {
                        this.learning_levels = response.data;
                        this.generateTemplate();
                    });
                },
                openUpdateModal(learning_level) {
                    this.learning_level = learning_level;
                    $("#learningLevelModalUpdate").modal('show');
                },
                generateTemplate() {
                    this.template = [];

                    for(var year_level = 1; year_level <= this.curriculum.year_level; year_level++) {
                        var first_sem_courses = this.getCourses(year_level, 1);
                        var second_sem_courses = this.getCourses(year_level, 2);
                        var summer_courses = this.getCourses(year_level, 3);


                        this.template.push({
                            year_sem: this.getIndexNum(year_level) + ' year/1st sem',
                            curriculum_courses: first_sem_courses
                        });

                        this.template.push({
                            year_sem: this.getIndexNum(year_level) + ' year/2nd sem',
                            curriculum_courses: second_sem_courses
                        });

                        if(summer_courses.length > 0) {
                            for(var i = 0; i < summer_courses.length; i++) {
                                summer_maps = this.getCurriculumMaps(summer_courses[i].id);
                            }
                            this.template.push({
                                year_sem: this.getIndexNum(year_level) + ' year/summer',
                                curriculum_courses: summer_courses
                            });
                        }

                    }
                },
                getCourses(year_level, semester) {
                    var courses = [];
                    for (var i = 0; i < this.curriculum_courses.length; i++) {
                        if(this.curriculum_courses[i].year_level == year_level && this.curriculum_courses[i].semester == semester) {
                            var curriculum_maps = this.getCurriculumMaps(this.curriculum_courses[i].id);
                            this.curriculum_courses[i].curriculum_maps = curriculum_maps;
                            courses.push(this.curriculum_courses[i]);
                        }
                    }

                    return courses;
                },
                getCurriculumMaps(curriculum_course_id) {
                    var maps = [];
                    //for(var i = 0; i < this.curriculum_courses.length; i++) {
                        for(var j = 0; j < this.student_outcomes.length; j++) {
                            maps.push(this.getCurriculumMap(curriculum_course_id,this.student_outcomes[j].id));
                        }
                    //}

                    return maps;
                },
                getCurriculumMap(curriculum_course_id, student_outcome_id) {
                    var curriculum_course = this.getCurriculumCourse(curriculum_course_id);
                    var student_outcome = this.getStudentOutcome(student_outcome_id);

                    for(var i = 0; i < this.curriculum_maps.length; i++) {
                        if(this.curriculum_maps[i].curriculum_course_id == curriculum_course_id && this.curriculum_maps[i].student_outcome_id == student_outcome_id) {
                            this.curriculum_maps[i].learning_level = this.getLearningLevel(this.curriculum_maps[i].learning_level_id);
                            this.curriculum_maps[i].curriculum_course = curriculum_course;
                            this.curriculum_maps[i].student_outcome = student_outcome;
                            return this.curriculum_maps[i];
                        }
                    }

                    return {
                        id: null,
                        curriculum_course_id: curriculum_course_id,
                        student_outcome_id: student_outcome_id,
                        curriculum_course: curriculum_course,
                        student_outcome: student_outcome,
                        is_checked: false
                    };

                },
                getCurriculumMapNull(curriculum_course_id, student_outcome_id) {
                    var curriculum_course = this.getCurriculumCourse(curriculum_course_id);
                    var student_outcome = this.getStudentOutcome(student_outcome_id);

                    for(var i = 0; i < this.curriculum_maps.length; i++) {
                        if(this.curriculum_maps[i].curriculum_course_id == curriculum_course_id && this.curriculum_maps[i].student_outcome_id == student_outcome_id) {
                            this.curriculum_maps[i].learning_level = this.getLearningLevel(this.curriculum_maps[i].learning_level_id);
                            this.curriculum_maps[i].curriculum_course = curriculum_course;
                            this.curriculum_maps[i].student_outcome = student_outcome;
                            return this.curriculum_maps[i];
                        }
                    }

                    return null;

                },
                getLearningLevel(learning_level_id) {
                    for(var i = 0 ; i < this.learning_levels.length; i++) {
                        if(this.learning_levels[i].id == learning_level_id) {
                            return this.learning_levels[i];
                        }
                    }
                },
                getIndexNum(num) {
                    if(num == 1) {
                        return '1st';
                    } else if (num == 2) {
                        return '2nd';
                    } else if(num == 3) {
                        return '3rd';
                    } else if (num == 4) {
                        return '4th';
                    } else if (num == 5) {
                        return '5th'
                    } else {
                        return num;
                    }
                },
                openMapModal(event, curriculum_map) {
                    //alert(event.target.checked);
                    event.target.checked = false;
                    $('#mapModal').modal("show");
                    this.curriculum_course = curriculum_map.curriculum_course;
                    this.student_outcome = curriculum_map.student_outcome;

                    var curriculum_map = this.getCurriculumMapNull(this.curriculum_course.id, this.student_outcome.id);

                    if(curriculum_map) {
                        this.learning_level_id = curriculum_map.learning_level_id;
                    }
                },
                mapCourse() {
                    if(this.learning_level_id == "") {
                        return alert("Please select learning level");
                    }
                    var curriculum_map = this.getCurriculumMapNull(this.curriculum_course.id, this.student_outcome.id);

                    if(curriculum_map) {
                        curriculum_map.is_checked = true;
                        curriculum_map.learning_level_id = this.learning_level_id;
                        curriculum_map.learning_level = this.getLearningLevel(this.learning_level_id);
                    } else {
                        this.curriculum_maps.push({
                            id: null,
                            student_outcome_id: this.student_outcome.id,
                            curriculum_course_id: this.curriculum_course.id,
                            is_checked: true,
                            learning_level_id: this.learning_level_id,
                            learning_level: this.getLearningLevel(this.learning_level_id)
                          });                       
                    }

                    setTimeout(() => {
                        this.generateTemplate();
                    }, 300);

                    $('#mapModal').modal("hide");
                    
                    
                },
                removeMap(event, curriculum_map) {
                    event.target.checked = true;
                    swal.fire({
                        type: 'warning',
                        title: 'Please confirm',
                        text: 'do you want to remove?',
                        showCancelButton: true,
                        width: '400px',
                        confirmButtonColor: '#11c26d'
                      }).
                      then(isConfirmed => {
                        if(isConfirmed.value) {
                            curriculum_map.is_checked = false;
                            setTimeout(() => {
                                this.generateTemplate();
                            }, 300);
                        }
                      })
                      .catch(error => {
                        alert("An Error Has Occured. Please try again.");
                        console.log(error);
                      })
                    
                },
                getCurriculumCourse(curriculum_course_id) {
                    for(var i = 0 ; i < this.curriculum_courses.length; i++) {
                        if(this.curriculum_courses[i].id == curriculum_course_id) {
                            return this.curriculum_courses[i];
                        }
                    }
                },
                getStudentOutcome(student_outcome_id) {
                    for(var i = 0 ; i < this.student_outcomes.length; i++) {
                        if(this.student_outcomes[i].id == student_outcome_id) {
                            return this.student_outcomes[i];
                        }
                    }
                },
                saveCurriculumMaps() {
                    // return alert(this.checkIfAllLearningLevelsInclude());

                    if(this.checkIfAllLearningLevelsInclude()) {
                        return swal.fire({
                            type: 'warning',
                            title: 'Warning! Please confirm',
                            html: this.checkIfAllLearningLevelsInclude(),
                            showCancelButton: true,
                            confirmButtonColor: '#1cc88a',
                            cancelButtonColor: '#858796',
                            confirmButtonText: 'Confirm'
                        }).then(result => {
                            if(result.value) {
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
                                      this.isLoading = true;
                                      for(var i = 0 ; i < this.curriculum_maps.length; i++) {
                                        this.curriculum_maps[i].curriculum_course = null;
                                      }

                                      ApiClient.post("/curriculum_mapping/save_maps", {
                                        curriculum_maps: this.curriculum_maps,
                                        curriculum_mapping_status: this.curriculum_mapping_status
                                      })
                                      .then(response => {
                                        // window.location.replace(myRootURL + "/curriculum_mapping/" + this.curriculum.id);
                                        this.curriculum_mapping_status.status = 1;
                                        this.isLoading = false;
                                        toast.fire({
                                            type: 'success',
                                            title: 'Curriculum mapping successfully saved.'
                                        });
                                      }).
                                      catch(error => {
                                        this.isLoading = false;
                                        alert("An Error has occured. Try to refresh the page");
                                      })
                                    }
                                  });

                            }
                        })
                    } else {
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
                              this.isLoading = true;
                              for(var i = 0 ; i < this.curriculum_maps.length; i++) {
                                this.curriculum_maps[i].curriculum_course = null;
                              }

                              ApiClient.post("/curriculum_mapping/save_maps", {
                                curriculum_maps: this.curriculum_maps,
                                curriculum_mapping_status: this.curriculum_mapping_status
                              })
                              .then(response => {
                                // window.location.replace(myRootURL + "/curriculum_mapping/" + this.curriculum.id);
                                this.curriculum_mapping_status.status = 1;
                                this.isLoading = false;
                                toast.fire({
                                    type: 'success',
                                    title: 'Curriculum mapping successfully saved.'
                                });
                              }).
                              catch(error => {
                                this.isLoading = false;
                                alert("An Error has occured. Try to refresh the page");
                              })
                            }
                          });
                    }
                  
                },
                editCurriculumMapping(event) {
                  swal.fire({
                    title: 'Do you want to update?',
                    text: "Please confirm",
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#1cc88a',
                    cancelButtonColor: '#858796',
                    confirmButtonText: 'Yes',
                    width: '400px'
                  }).then((result) => {
                    if (result.value) {
                      this.isLoading = true;
                      ApiClient.post('/curriculum_mapping/' + this.curriculum.id + '/edit')
                      .then(response => {
                        this.isLoading = false;
                        this.curriculum_mapping_status = response.data;
                        toast.fire({
                            type: 'success',
                            title: 'You can now update this curriculum'
                        });
                      })
                      .catch(error => {
                        this.isLoading = false;
                        alert("An error has occured. Please try again");
                      })
                    }
                  });
                },
                checkIfAllLearningLevelsInclude() {
                    // var student_outcomes = [];
                    var warningText = "";
                    for(var i = 0; i < this.student_outcomes.length; i++) {
                        for(var j = 0; j < this.learning_levels.length; j++) {
                            Vue.set(this.student_outcomes[i], 'count'+ this.learning_levels[j].id, 0);
                        }    
                    }


                    for(var i = 0; i < this.student_outcomes.length; i++) {
                        for(var j = 0; j < this.curriculum_maps.length; j++) {
                            if(this.student_outcomes[i].id == this.curriculum_maps[j].student_outcome_id) {
                                this.student_outcomes[i]["count" + this.curriculum_maps[j].learning_level_id]++;
                            }
                        }
                    }

                    //check
                    warningText += "<ul>";
                    for(var i = 0; i < this.student_outcomes.length; i++) {
                        for(var j = 0; j < this.learning_levels.length; j++) {
                            if (this.student_outcomes[i]['count'+ this.learning_levels[j].id] <= 0) {
                                warningText += `<li class="text-left"><strong>Student outcome ${this.student_outcomes[i].so_code}</strong> has no learning level <strong>${this.learning_levels[j].name}</strong></li>`;
                            }
                        } 
                    }
                    warningText += "</ul>";

                    return warningText;
                    
                }
            },
            created() {
                this.generateTemplate();
                
                if(this.isFaculty) {
                    this.curriculum_mapping_status.status = 1;
                }
            }
        });
    </script>
@endpush