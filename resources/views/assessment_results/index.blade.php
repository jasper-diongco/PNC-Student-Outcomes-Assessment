@extends('layout.app', ['active' => 'assessments'])

@section('title', 'Assessment Results')


@section('content')
<div id="app" v-cloak>
        <div class="card p-4 px-4 mb-3">
        {{-- <div class="mx-auto" style="width: 400px">
          <img src="{{ asset('svg/updates.svg') }}" class="w-100">
        </div> --}}
        <div>
            <h1 class="page-header"><i class="fa fa-poll"></i> Assessment Results</h1>
        </div>


        <div class="d-lg-flex mb-2 ">

          
          <div class="d-flex align-items-baseline mr-3">
            <div>
                <label class="text-dark">Select Program</label>
            </div>
            <div class="ml-2">
                <select v-on:change="getStudentOutcomes" v-model="program_id" class="form-control">
                    @foreach($programs as $program)
                        <option value="{{ $program->id }}">{{ $program->program_code }}</option>
                    @endforeach
                </select>
                
            </div>
          </div>

          <div class="d-flex align-items-baseline">
            <div>
                <label class="text-dark">Select Curriculum</label>
            </div>
            <div class="ml-2">
                <select v-on:change="getAllAssessments" class="form-control" v-model="selected_curriculum_id">
                    <option v-for="curriculum in curricula"  :key="curriculum.id" :value="curriculum.id">@{{ curriculum.name + ' ' + curriculum.year}}  &mdash; v@{{ curriculum.revision_no }}.0</option>
                </select>
                
            </div>
          </div>
        </div>

        <template v-if="!loadingStudentOutcomes">
            <template v-if="selected_student_outcome != ''">
                <div class="select-student-outcome d-flex align-items-center mt-1 justify-content-between" v-on:click="toggleDropDown">
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <div class="avatar-student-outcome bg-success">@{{ selected_student_outcome.so_code }}</div>
                        </div>
                        <div style="font-weight: 400">
                            @{{ selected_student_outcome.description }}
                        </div>
                    </div>
                    <div><i class="fa fa-caret-down"></i></div>
                </div>
            </template>
            <div v-else style="font-size: 18px;" class="text-center mt-4">
                No Student Outcome
            </div>
        </template>
        <template v-else>
            <div class="d-flex text-muted">
                <div class="spinner-grow text-dark text-center ml-2" role="status">
                  <span class="sr-only">Loading...</span>
                </div> 
            </div>
            
        </template>

        <ul v-show="showDropDown" class="list-group list-dropdown-so mt-1">
          <li v-on:click="selectStudentOutcome(student_outcome)" v-for="student_outcome in student_outcomes" :key="student_outcome.id" class="list-group-item d-flex align-items-center">
            <div class="mr-3">
                <div class="avatar-student-outcome bg-success">@{{ student_outcome.so_code }}</div>
            </div>
            <div>
                @{{ student_outcome.description }}
            </div>
          </li>
        </ul>
    </div>
    


    <ul class="nav nav-tabs" id="main-nav-tabs" role="tablist">
        <li class="nav-item" v-if="selected_student_outcome.assessment_type_id == 1">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#assessments" role="tab" aria-controls="home" aria-selected="true"><i class="fa fa-file-alt"></i> Assessments</a>
        </li>
        
        <li class="nav-item" v-if="selected_student_outcome.assessment_type_id == 2">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#custom-assessments" role="tab" aria-controls="profile" aria-selected="false"><i class="fa fa-external-link-alt"></i> Custom Recorded Assessments</a>
        </li>
        <li class="nav-item" v-if="selected_student_outcome.assessment_type_id == 3">
            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#programming" role="tab" aria-controls="contact" aria-selected="false"><i class="fa fa-laptop-code"></i> Programming Assessments</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="report-tab" data-toggle="tab" href="#reports" role="tab" aria-controls="home" aria-selected="true"><i class="fa fa-chart-pie"></i> Reports</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="assessments" role="tabpanel" aria-labelledby="home-tab">
            

            <div class="d-flex justify-content-between align-items-baseline">
                
                {{-- <div class="d-flex align-items-baseline">
                    <label class="mr-2 text-dark">Program</label>
                    <select v-on:change="getAssessmentResults" v-model="program_id" class="form-control">
                        <option value="" class="d-none">Select Program</option>
                        <option v-for="program in programs" :key="program.id" :value="program.id">@{{ program.program_code }}</option>
                    </select>
                </div> --}}
            </div>
            
            <div class="row">
                <div class="col-md-5 mt-3">
                    <div class="input-group mb-3" id="search-input">
                    
                        <input v-on:input="searchAssessment" v-model="search" type="search" class="form-control" placeholder="Search student...">
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fa fa-search"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 d-md-flex justify-content-lg-end">
                    <div class="mt-3 mr-3 d-flex justify-content-lg-end align-items-baseline">
                        <label class="mr-2">Sort By Grade</label>
                        <select v-on:change="sort_by_grade" v-model="sort_grade">
                            <option value="">Normal</option>
                            <option value="1">ASCENDING</option>
                            <option value="2">DESCENDING</option>
                        </select>
                    </div>
                    <div class="mt-3 d-flex justify-content-lg-end align-items-baseline">
                        <label class="mr-2">Filter Grade</label>
                        <select v-on:change="filter_by_grade" v-model="filter_grade">
                            <option value="">All</option>
                            <option value="1">Passed</option>
                            <option value="2">Failed</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-borderless">
                    <thead>
                        <th>Asessment ID</th>
                        <th>Student ID</th>
                        <th>Student</th>
                        <th>Program</th>
                        <th>SO</th>
                        <th>Score</th>
                        <th>Remarks</th>
                        <th>Date</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        <template v-if="tableLoading">
                            <td colspan="9">
                                <table-loading></table-loading>
                            </td>
                        </template>
                        <template v-else>
                            <template v-if="assessments.length > 0">                                              
                                <tr v-for="assessment in assessments">
                                    <td>@{{ assessment.assessment_code }}</td>
                                    <td>@{{ assessment.student.student_id }}</td>
                                    <td>@{{ assessment.student.user.first_name + ' ' + assessment.student.user.last_name }}</td>
                                    <td>@{{ assessment.student_outcome.program.program_code }}</td>
                                    <th class="text-center">@{{ assessment.student_outcome.so_code }} - @{{ assessment.student_outcome.program.program_code }}</th>
                                    <td>@{{ assessment.score | score }}</td>
                                    <th v-if="assessment.is_passed" class="text-success">Passed</th>
                                    <th V-else class="text-danger">Failed</th>
                                    <td>@{{ assessment.created_at | date }}</td>
                                    <th><a :href="'assessment_results/' + assessment.id + '?college_id=' + college_id" class="btn btn-sm btn-info">View <i class="fa fa-search"></i></a></th>
                                </tr>
                            </template> 
                            <template v-else>
                                <tr>
                                    <td colspan="9" class="text-center">
                                        No Record Found.
                                    </td>
                                </tr>
                            </template>
                        </template>
                    </tbody>
                </table>
                <!-- Pagination -->
                  <div>Showing @{{ assessments.length }} records</div>
{{--                   <nav v-show="search.trim() == ''">
                    <ul class="pagination justify-content-end">
                      <li class="page-item" :class="{ disabled: meta.current_page == 1 }">
                        <a class="page-link" href="#" v-on:click.prevent="paginate(meta.current_page - 1)">Previous</a>
                      </li>
                      <template v-for="num in totalPagination">
                        <li class="page-item" :class="{ active : num == meta.current_page }">
                          <a class="page-link" href="#" v-on:click.prevent="paginate(num)">@{{ num }}</a>
                        </li>
                      </template>
                      <li class="page-item" :class="{ disabled: meta.current_page == meta.last_page }">
                        <a class="page-link" href="#" v-on:click.prevent="paginate(meta.current_page + 1)">Next</a>
                      </li>
                    </ul>
                  </nav> --}}
               <!-- End Pagination -->
            </div>
        </div>
        <div class="tab-pane fade" id="reports" role="tabpanel">
            <div id="passingPercentage" class="card shadow mt-4" style="background: #fbfbfb">
                <div class="card-body py-3">
                    <h5>Passing Percentage</h5>
                    <p class="text-info">This figure shows the percentage of passed and failed student</p>
                    
                    <div class="w-md-40">
                        <pie-chart :data="pie_passing_percentage"></pie-chart>
                    </div>
                </div>
            </div>

            <div id="topStudents" class="card shadow mt-4" style="background: #fbfbfb">
                <div class="card-body py-3">
                    <div class="d-flex align-items-baseline justify-content-between">
                        <div>
                            <h5 class="mb-3">Top students</h5>
                        </div>
                        <div>
                            <label class="mr-2">Filter</label>
                            <select v-model="topValue" v-on:change="get_top_assessments">
                                <option value="10">
                                    Top 10 students
                                </option>
                                <option value="5">
                                    Top 5 students
                                </option>
                                <option value="3">
                                    Top 3 students
                                </option>
                                <option value="-3">
                                    Top lower 3  students
                                </option>
                                <option value="-5">
                                    Top lower 5  students
                                </option>
                                <option value="-10">
                                    Top lower 10  students
                                </option>
                            </select>
                        </div>
                    
                    </div>
                    
                    <div v-if="assessments.length > 0">
                        <ul class="list-group">
                            <li v-for="(top_assessment,index) in top_assessments" class="list-group-item">
                                <div class="d-flex align-items-center mr-3">

                                    <div>
                                      <span class="avatar-student-outcome mr-3" style="background:#86db67">@{{ index + 1 }}</span>
                                    </div>
                                    <span class="mr-3">
                                        <strong class="mr-2">@{{ top_assessment.score | score }}</strong>
                                        &mdash;
                                        @{{ top_assessment.student.student_id }} &mdash;
                                         @{{ top_assessment.student.user.first_name + ' ' + top_assessment.student.user.last_name}}</span>
                                     
                                    

                                </div>
                            </li>
                        </ul>
                    </div>
                    <div v-else class="p-3 bg-light text-center">
                        <h5>No Assessment found</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="custom-assessments" role="tabpanel">
        

        <div class="mt-3">
            <div class="d-flex justify-content-between mb-3">
                <div>
                    <h5><i class="fa fa-external-link-alt text-info"></i> Custom Recorded Assessment</h5>
                </div>
            </div>
        
            <template v-if="custom_recorded_assessment_loading">
                <table-loading></table-loading>
            </template>
            <template v-else>
                
            
                <template v-if="custom_recorded_assessments.length > 0">           
                    <div class="d-flex align-items-stretch flex-wrap" :class="{ 'justify-content-between': custom_recorded_assessments.length > 2 }">

                        <div v-for="custom_recorded_assessment in custom_recorded_assessments" :key="custom_recorded_assessment.id" class="card shadow mb-4 w-md-31 mr-4" style="background: #f5f5f5;" :class="{ 'mr-4': custom_recorded_assessment.length <= 2 }">
                            <div class="card-body pt-3">
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <div class="d-flex">
                                        <div class="mr-2">
                                            <div class="avatar" style="background: #cbff90; color:#585858;"><i class="fa fa-file-alt"></i></div>
                                        </div>
                                        <div style="font-weight: 600">@{{ custom_recorded_assessment.name }}</div>
                                    </div>
                                    <div class="ml-3">
                                      <a class="btn btn-sm btn-info" :href="myRootURL + '/custom_recorded_assessments/' + custom_recorded_assessment.id">
                                          <i class="fa fa-edit"></i> Add Record
                                      </a>
                                    </div>
                                </div>
                                <div class="text-muted ml-2 mt-2"><i class="fa fa-file-alt"></i> @{{ custom_recorded_assessment.description }}</div>
                                <div style="font-size: 13px" class="text-muted ml-2 mt-2">
                                    <i class="fa fa-user"></i> @{{ custom_recorded_assessment.user.first_name }} @{{ custom_recorded_assessment.user.last_name }} 
                                    &mdash; @{{ parseDate(custom_recorded_assessment.created_at) }}
                                </div>
                                <hr>
                                
                                <div class="text-muted mt-2">
                                    <span class="mb-0">Overall Score: </span>
                                    @{{ custom_recorded_assessment.overall_score }}
                                </div>
                                <div class="text-muted mt-2">
                                    <span class="mb-0">Passing Grade: </span>
                                    @{{ custom_recorded_assessment.passing_percentage }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                <template v-else>
                    <div class="bg-white p-3 text-center text-muted">
                        No record found
                    </div>
                </template>
            </template>
        </div>
            
        </div>
        <div class="tab-pane fade" id="programming" role="tabpanel" aria-labelledby="contact-tab">...</div>
    </div>
    
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/chartjs-plugin-labels.js') }}"></script>
<script>
    var vm = new Vue({
        el: '#app',
        data: {
            programs: @json($programs),
            program_id: '',
            custom_assessment_program_id: '',
            assessments: @json($assessments),
            top_assessments: [],
            topValue: 10,
            college_id: '{{ request('college_id') }}',
            tableLoading: true,
            search: '',
            meta: {
              total: 0,
              per_page: 0,
              current_page: 1
            },
            links: {},
            totalPagination: 0,
            student_outcomes: [],
            selected_student_outcome: '',
            curricula: [],
            selected_curriculum_id: '',
            loadingStudentOutcomes: false,
            showDropDown: false,
            isLoading: false,
            custom_recorded_assessments: [],
            custom_recorded_assessment_loading: false,
            myRootURL: '',
            filter_grade: '',
            sort_grade: '',
            pie_passing_percentage: {
              datasets: [
                    {
                        data: [20, 80],
                        backgroundColor: ["#cbff90", "#ededed"]
                    }
                ],
                labels: ["Passed", "Failed"]
            }   
        },
        filters: {
            score(value) {
                return Math.round(value) + "%";
            },
            date(value) {
                return moment(value).format('MMM D, YYYY');
            }
        },
        methods: {
            sort_by_grade() {
                if(this.sort_grade == "") {
                    this.getAssessmentResults();
                } else if(this.sort_grade == 1) {
                    this.assessments.sort((a, b) => {
                        return a.score - b.score;
                    });
                } else if (this.sort_grade == 2) {
                    this.assessments.sort((a, b) => {
                        return b.score - a.score;
                    });
                }
                
            },
            filter_by_grade() {
                this.getAssessmentResults();
            },
            getAssessmentResults(page=1) {
                ApiClient.get('/assessment_results/get_assessments?page=' + page + '&program_id=' + this.program_id + '&student_outcome_id=' + this.selected_student_outcome.id + '&curriculum_id=' + this.selected_curriculum_id + '&filter_grade=' + this.filter_grade)
                    .then(response => {
                      this.assessments = response.data;
                      this.tableLoading = false;
                      this.get_passing_percentage();
                      this.get_top_assessments();
                      // this.meta.total = response.data.total;
                      // this.meta.per_page = response.data.per_page;
                      // this.meta.last_page = response.data.last_page;
                      // this.meta.current_page = response.data.current_page;
                      // // this.links = response.data.links;
                      // this.totalPagination = Math.ceil(this.meta.total / this.meta.per_page);
                    }).
                    catch(err => {
                      console.log(err);
                      //serverError();
                      this.tableLoading = false;
                    })
            },
            paginate(page) {
                this.getAssessmentResults(page);
            },
            searchAssessment : _.debounce(() => {
                if(vm.search == '') {
                  return vm.getAssessmentResults();
                }
                //vm.filter_by_college = '';
                //vm.filter_by_privacy = '';
                vm.tableLoading = true;
                ApiClient.get('/assessment_results/get_assessments/?q=' + vm.search)
                .then(response => {
                  vm.assessments = response.data.data;
                  vm.tableLoading = false;
                }).
                catch(err => {
                  console.log(err);
                  vm.tableLoading = false;
                })
              }, 400),
            getStudentOutcomes() {
                this.isLoading = true;
                this.loadingStudentOutcomes = true;
                ApiClient.get("/test_bank/" + this.program_id + "/get_student_outcomes")
                .then(response => {
                    this.isLoading = false;
                    this.loadingStudentOutcomes = false;
                    this.student_outcomes = response.data;
                    // this.student_outcomes = [];
                    // for(var i = 0; i < response.data.length; i++) {
                    //     if(response.data[i].assessment_type_id == 2) {
                    //         this.student_outcomes.push(response.data[i]);
                    //     }
                    // }

                    if(this.student_outcomes.length > 0) {
                        this.selected_student_outcome = this.student_outcomes[0];
                        // this.getCoursesMapped();
                        this.getCurricula();
                        
                    } else {
                        this.selected_student_outcome = '';
                        this.courses_mapped = [];
                    }

                    // this.getReportedTestQuestions();

                    
                })
            },
            toggleDropDown() {
                this.showDropDown = !this.showDropDown;
            },
            selectStudentOutcome(student_outcome) {
                this.toggleDropDown();
                this.selected_student_outcome = student_outcome;
                // this.getCoursesMapped();
                this.getCurricula();
                this.getAssessmentResults();
                
            },
            getCurricula() {
                this.isLoading = true;

                ApiClient.get("test_bank/" + this.program_id + "/get_curricula?student_outcome_id=" + this.selected_student_outcome.id)
                .then(response => {
                    this.curricula = response.data;

                    if(this.curricula.length > 0) {

                        this.selected_curriculum_id = this.curricula[0].id;
                        // this.getCoursesMapped();
                        this.getAssessmentResults();
                        if(this.selected_student_outcome.assessment_type_id == 2) {
                            this.getCustomRecordedAssessments();

                        }
                    }
                    this.isLoading = false;
                })
                .catch(err => {
                    alert("An Error has occured. Please try again");
                    this.isLoading = false;
                })
            },
            getCustomRecordedAssessments() {
                this.custom_recorded_assessment_loading = true;
                ApiClient.get('/custom_recorded_assessments?curriculum_id=' + this.selected_curriculum_id + '&student_outcome_id=' + this.selected_student_outcome.id)
                .then(response => {
                    this.custom_recorded_assessments = response.data;
                    this.custom_recorded_assessment_loading = false;
                });
            },
            getAllAssessments() {
                this.getCustomRecordedAssessments();
                this.getAssessmentResults();
            },
            parseDate(date) {
                return moment(date).format('MMMM DD, YYYY');
            },
            get_passing_percentage() {
                var passed = 0;
                var failed = 0;

                for(var i = 0; i < this.assessments.length; i++) {
                    if(this.assessments[i].is_passed) {
                        passed++;
                    } else {
                        failed++;
                    }
                }

                this.pie_passing_percentage.datasets[0].data[0] = passed;
                this.pie_passing_percentage.datasets[0].data[1] = failed;
            },
            get_top_assessments() {
                var result = [];
                var toBeSorted = this.assessments;

                if(this.topValue > 0) {
                    toBeSorted.sort((a, b) => {
                        return b.score - a.score;
                    });

                    var len = toBeSorted.length >= this.topValue ? this.topValue : toBeSorted.length;

                
                    for(var i = 0; i < len; i++) {
                        result.push(toBeSorted[i]);
                    }
                } else {
                    toBeSorted.sort((a, b) => {
                        return a.score - b.score;
                    });

                    var len = toBeSorted.length >= Math.abs(this.topValue) ? Math.abs(this.topValue) : toBeSorted.length;

                
                    for(var i = 0; i < len; i++) {
                        result.push(toBeSorted[i]);
                    }
                }
                

                this.top_assessments = result;
            }
        },

        created() {
            if(this.programs.length > 0) {
                this.program_id = this.programs[0].id;
                // this.custom_assessment_program_id = this.programs[0].id;
                this.getStudentOutcomes();
            }
            this.getAssessmentResults();
            this.myRootURL = myRootURL;
        }
    });
</script>
@endpush

