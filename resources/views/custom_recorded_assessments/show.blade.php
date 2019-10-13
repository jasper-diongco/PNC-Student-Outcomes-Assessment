@extends('layout.app', ['active' => 'assessments'])

@section('title', 'Assessment Results')


@section('content')
<div id="app" v-cloak>
    <div class="mb-3">
        <a href="{{ url('/assessment_results?college_id=' . request('college_id')) }}" class="text-success"><i class="fa fa-arrow-left"></i> Back</a>
    </div>

    <custom-recorded-assessment-record-modal :custom_recorded_assessment_id="custom_recorded_assessment_id" :student_outcome_id="student_outcome_id" :max_score="overall_score" :custom_recorded_assessment_records="custom_recorded_assessment_records" v-on:record-added="get_custom_recorded_assessment_records"></custom-recorded-assessment-record-modal>
    <h1 class="page-header"><i class="fa fa-file-alt text-info"></i> Custom Recorded Assessment</h1>

    <div class="card">
        <div class="card-body py-4">
            <h5 class="text-info">Details</h5>

            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <label>Exam Code: </label>
                    <span>#{{ $custom_recorded_assessment->ca_code }}</span> 
                </li>
            </ul>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <label>Name: </label>
                    <span>{{ $custom_recorded_assessment->name }}</span> 
                </li>
            </ul>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <label>Overall Score: </label>
                    <span>{{ $custom_recorded_assessment->overall_score }}</span> 
                </li>
            </ul>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <label>Passing grade: </label>
                    <span>{{ $custom_recorded_assessment->passing_percentage }}%</span> 
                </li>
            </ul>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <label><i class="fa fa-user text-dark"></i> Created by: </label>
                    <span>{{ $custom_recorded_assessment->user->getFullName() }}</span> 
                </li>
            </ul>

            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <label><i class="fa fa-file-alt text-dark"></i> Description: </label>
                    <span>{{ $custom_recorded_assessment->description }}</span> 
                </li>
            </ul>
        </div>
    </div>

    <ul class="nav nav-tabs mt-3" id="main-nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="record-tab" data-toggle="tab" href="#record" role="tab"  aria-selected="true">Records</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="reports-tab" data-toggle="tab" href="#report" role="tab"  aria-selected="false">Reports</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="record" role="tabpanel" aria-labelledby="record-tab">
            <div class="card mt-3">
                <div class="">
                    <div class="d-flex align-self-baseline justify-content-between">
                        <div>
                            <h5 class="text-info">Records</h5>
                        </div>
                        <div>
                            <button class="btn btn-info" v-on:click="openAddRecord">Add Record <i class="fa fa-edit"></i></button>
                        </div>
                    </div>


                    
                    <div class="row">
                        <div class="col-md-5">
                            <div class="input-group my-3" id="search-input">
                            
                                <input v-on:input="search" v-model="searchText" type="search" class="form-control" placeholder="Search assessment...">
                                <div class="input-group-append">
                                  <span class="input-group-text"><i class="fa fa-search"></i></span>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-md-3"></div> --}}
                        <div class="col-lg-7 d-md-flex justify-content-lg-end">
                            <div class="mt-3 mr-3 d-flex justify-content-lg-end align-items-baseline">
                                <label class="mr-2">Sort By Grade</label>
                                <select v-model="sort_grade" v-on:change="sort_by_grade">
                                    <option value="">Normal</option>
                                    <option value="1">ASCENDING</option>
                                    <option value="2">DESCENDING</option>
                                </select>
                            </div>
                            <div class="mt-3 d-flex justify-content-lg-end align-items-baseline">
                                <label class="mr-2">Filter Grade</label>
                                <select v-model="filter_grade" v-on:change="filter_by_grade">
                                    <option value="">All</option>
                                    <option value="1">Passed</option>
                                    <option value="2">Failed</option>
                                </select>
                            </div>
                        </div>
                    </div>



                    <div v-if="show_custom_recorded_assessment_records.length > 0" class="table-responsive mt-3">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Student ID</th>
                                    <th>Student Name</th>
                                    <th>Score</th>
                                    <th>Passed</th>
                                    <th>Date</th>
                                    <th>Update</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="custom_recorded_assessment_record in show_custom_recorded_assessment_records">
                                    <th>
                                        @{{ custom_recorded_assessment_record.code }}
                                    </th>
                                    <td>
                                        @{{ custom_recorded_assessment_record.student.student_id }}
                                    </td>
                                    <td>
                                        @{{ custom_recorded_assessment_record.student.user.first_name }} @{{ custom_recorded_assessment_record.student.user.last_name }}
                                    </td>
                                    <td>
                                        @{{ custom_recorded_assessment_record.score }} (@{{ getGrade(custom_recorded_assessment_record.score) }}%)
                                    </td>
                                    <td v-if="checkIfPassed(custom_recorded_assessment_record.score)">
                                        <strong class="text-success">Passed</strong>
                                    </td>
                                    <td v-else>
                                        <strong class="text-danger">Failed</strong>
                                    </td>
                                    <td>
                                        @{{ parseDate(custom_recorded_assessment_record.created_at) }}
                                    </td>
                                    <td>
                                        <button v-on:click="openUpdateModal(custom_recorded_assessment_record)" class="btn btn-sm btn-success">Update <i class="fa fa-edit"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-else class="p-3 bg-light text-center">
                        <h5>No Assessment</h5>
                    </div>
                    

                    
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="report" role="tabpanel" aria-labelledby="report-tab">
            <h5 class="mt-3"><i class="fa fa-chart-pie text-info"></i> Reports</h5>

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
                            <select v-model="topValue" v-on:change="get_top_custom_recorded_assessment_records">
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
                    
                    <ul class="list-group">
                        <li v-for="(record,index) in top_custom_recorded_assessment_records" class="list-group-item">
                            <div class="d-flex align-items-center mr-3">

                                <div>
                                  <span class="avatar-student-outcome mr-3" style="background:#86db67">@{{ index + 1 }}</span>
                                </div>
                                <span class="mr-3">
                                    <strong class="mr-2">@{{ record.score }} (@{{ getGrade(record.score) }}%)</strong>
                                    &mdash;
                                    @{{ record.student.student_id }} &mdash;
                                     @{{ record.student.user.first_name + ' ' + record.student.user.last_name}}</span>
                                 
                                

                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    

    <!-- Modal -->
    <div class="modal fade" id="updateScore" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header" style="background: #8bc34a">
            <h5 class="modal-title" id="exampleModalLabel">Update Score</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form v-on:submit.prevent="updateScore" v-on:keydown="form.onKeydown($event)">
          <div class="modal-body">
              
                <div class="form-group">
                  <label>New Score &mdash; Max - @{{ overall_score }}</label>
                  <input v-model="form.score" type="number" name="score"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('score') }" min="0">
                  <has-error :form="form" field="score"></has-error>
                </div>

                
              
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button :disabled="form.busy" type="submit" class="btn btn-info">Update <i class="fa fa-edit"></i></button>
          </div>
          </form>
        </div>
      </div>
    </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('js/chartjs-plugin-labels.js') }}"></script>
<script>
    var vm = new Vue({
        el: '#app',
        data: {
            custom_recorded_assessment_id: '{{ $custom_recorded_assessment->id }}',
            student_outcome_id: '{{ $custom_recorded_assessment->student_outcome_id }}',
            overall_score: {{ $custom_recorded_assessment->overall_score }},
            passing_percentage: {{ $custom_recorded_assessment->passing_percentage }},
            custom_recorded_assessment_records: [],
            show_custom_recorded_assessment_records: [],
            top_custom_recorded_assessment_records: [],
            topValue: 10,
            searchText: '',
            form: new Form({
                score: ''
            }),
            selected_id: '',
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
        methods: {
            openAddRecord() {
                $('#customRecordedAssessmentRecordModal').modal('show');
            },
            get_top_custom_recorded_assessment_records() {
                var result = [];
                var toBeSorted = this.custom_recorded_assessment_records;

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
                

                this.top_custom_recorded_assessment_records = result;
            },
            get_custom_recorded_assessment_records() {
                ApiClient.get('/custom_recorded_assessments/get_records/' + this.custom_recorded_assessment_id)
                .then(response => {
                    this.custom_recorded_assessment_records = response.data;
                    this.show_custom_recorded_assessment_records = this.custom_recorded_assessment_records;
                    this.get_passing_percentage();
                    this.get_top_custom_recorded_assessment_records();
                })
            },
            filter_by_grade() {
                var searchResult = [];
                for(var i = 0; i < this.custom_recorded_assessment_records.length; i++) {
                    if(this.filter_grade == "") {
                        return this.show_custom_recorded_assessment_records = this.custom_recorded_assessment_records;
                    } else if(this.filter_grade == 1) {
                        if(this.checkIfPassed(this.custom_recorded_assessment_records[i].score)) {
                            searchResult.push(this.custom_recorded_assessment_records[i]);
                        }
                    } else if(this.filter_grade == 2) {
                        if(!this.checkIfPassed(this.custom_recorded_assessment_records[i].score)) {
                            searchResult.push(this.custom_recorded_assessment_records[i]);
                        }
                    }
                    
                }
                
                this.show_custom_recorded_assessment_records = searchResult;
            },
            sort_by_grade() {
                if(this.sort_grade == '') {
                    this.show_custom_recorded_assessment_records = this.custom_recorded_assessment_records;
                } else if(this.sort_grade == 1) {
                    this.show_custom_recorded_assessment_records.sort((a, b) => {
                        return a.score - b.score;
                    });
                } else if(this.sort_grade == 2) {
                    this.show_custom_recorded_assessment_records.sort((a, b) => {  
                        return b.score - a.score;
                    });
                }
            },
            parseDate(date) {
                return moment(date).format('MMM DD, YYYY');
            },
            checkIfPassed(score) {
                return ((score / this.overall_score) * 100).toFixed(2) >= this.passing_percentage;
            },
            getGrade(score) {
                return ((score / this.overall_score) * 100).toFixed(2);
            },
            search() {
                if(this.searchText == '' || this.searchText == null) {
                    return this.show_custom_recorded_assessment_records = this.custom_recorded_assessment_records;
                }
                var searchResult = [];
                var regExp = new RegExp(this.searchText, 'i');
                for(var i = 0; i < this.custom_recorded_assessment_records.length; i++) {
                    if(this.custom_recorded_assessment_records[i].student.user.first_name.search(regExp) > -1 || this.custom_recorded_assessment_records[i].student.user.last_name.search(regExp) > -1 || this.custom_recorded_assessment_records[i].student.student_id.search(regExp) > -1 || this.custom_recorded_assessment_records[i].code.search(regExp) > -1 ) {

                        searchResult.push(this.custom_recorded_assessment_records[i]);
                    }
                }
                
                this.show_custom_recorded_assessment_records = searchResult;
            },
            updateScore() {
                this.form.post(myRootURL + '/custom_recorded_assessment_records/' + this.selected_id)
                .then(response => {
                    $('#updateScore').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: 'Successfully updated!'
                    });

                    this.get_custom_recorded_assessment_records();
                })
            },
            openUpdateModal(record) {
                this.selected_id = record.id;
                this.form.score = record.score;
                $('#updateScore').modal('show');
            },
            get_passing_percentage() {
                var passed = 0;
                var failed = 0;

                for(var i = 0; i < this.custom_recorded_assessment_records.length; i++) {
                    if(this.checkIfPassed(this.custom_recorded_assessment_records[i].score)) {
                        passed++;
                    } else {
                        failed++;
                    }
                }

                this.pie_passing_percentage.datasets[0].data[0] = passed;
                this.pie_passing_percentage.datasets[0].data[1] = failed;
            }
        },
        created() {
            this.get_top_custom_recorded_assessment_records();
            this.get_custom_recorded_assessment_records();
        }
    });
</script>
@endpush



