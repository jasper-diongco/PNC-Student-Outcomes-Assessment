@extends('layout.app', ['active' => 'assessments'])

@section('title', 'Assessment Results')


@section('content')
<div id="app" v-cloak>
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

    <div class="card mt-3">
        <div class="card-body py-4">
            <div class="d-flex align-self-baseline justify-content-between">
                <div>
                    <h5 class="text-info">Records</h5>
                </div>
                <div>
                    <button class="btn btn-info" v-on:click="openAddRecord">Add Record <i class="fa fa-edit"></i></button>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="input-group my-3" id="search-input">
                    
                        <input v-on:input="search" v-model="searchText" type="search" class="form-control" placeholder="Search assessment...">
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fa fa-search"></i></span>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="custom_recorded_assessment_records.length > 0" class="table-responsive mt-3">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Score</th>
                            <th>Passed</th>
                            <th>Date</th>
                            <td>Update</td>
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
            searchText: '',
            form: new Form({
                score: ''
            }),
            selected_id: ''
        },
        methods: {
            openAddRecord() {
                $('#customRecordedAssessmentRecordModal').modal('show');
            },
            get_custom_recorded_assessment_records() {
                ApiClient.get('/custom_recorded_assessments/get_records/' + this.custom_recorded_assessment_id)
                .then(response => {
                    this.custom_recorded_assessment_records = response.data;
                    this.show_custom_recorded_assessment_records = this.custom_recorded_assessment_records;
                })
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
                if(this.textSearch == '' || this.textSearch == null) {
                    this.show_custom_recorded_assessment_records = this.custom_recorded_assessment_records;
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
            }
        },
        created() {
            this.get_custom_recorded_assessment_records();
        }
    });
</script>
@endpush



