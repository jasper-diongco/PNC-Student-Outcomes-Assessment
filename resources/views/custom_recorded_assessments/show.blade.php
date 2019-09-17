@extends('layout.app', ['active' => 'assessments'])

@section('title', 'Assessment Results')


@section('content')
<div id="app" v-cloak>
    <custom-recorded-assessment-record-modal :custom_recorded_assessment_id="custom_recorded_assessment_id" :student_outcome_id="student_outcome_id" :max_score="overall_score"></custom-recorded-assessment-record-modal>
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
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="custom_recorded_assessment_record in custom_recorded_assessment_records">
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
                        </tr>
                    </tbody>
                </table>
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
            custom_recorded_assessment_records: []
        },
        methods: {
            openAddRecord() {
                $('#customRecordedAssessmentRecordModal').modal('show');
            },
            get_custom_recorded_assessment_records() {
                ApiClient.get('/custom_recorded_assessments/get_records/' + this.custom_recorded_assessment_id)
                .then(response => {
                    this.custom_recorded_assessment_records = response.data;
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
            }
        },
        created() {
            this.get_custom_recorded_assessment_records();
        }
    });
</script>
@endpush



