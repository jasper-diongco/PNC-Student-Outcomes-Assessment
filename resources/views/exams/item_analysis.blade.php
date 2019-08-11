@extends('layout.app', ['active' => 'test_questions'])

@section('title', 'Exam Details')

@section('content')
<div id="app">
    <div class="card">
        <div class="card-body pt-4">
            <a href="#" class="text-success"><i class="fa fa-arrow-left"></i> Back</a>
            <h1 class="page-header mt-3"><i class="fa fa-cog text-info"></i> Item Analysis</h1>
            <div class="d-flex mb-3 flex-wrap">

                <div class="mr-3"><label>Program: </label> <span class="text-info fs-19">{{ $exam->studentOutcome->program->program_code }}</span></div>
                <div class="mr-3"><label>Student Outcome: </label> <span class="text-info fs-19">{{ $exam->studentOutcome->so_code }}</span></div>
                <div class="mr-3"><label>Curriculum: </label> <span class="text-info fs-19">{{ $exam->curriculum->name . ' ' . $exam->curriculum->year . ' - revision no. ' . $exam->curriculum->revision_no }}.0</span></div>
            </div>
            <h5>Exam Details</h5>
            <ul class="list-group">
                <li class="list-group-item"><span style="font-weight: 600"><i class="fa fa-fingerprint"></i> Exam ID:</span> {{ $exam->exam_code }}</li>
                <li class="list-group-item"><span style="font-weight: 600"><i class="fa fa-file-alt"></i> Description:</span> {{ $exam->description }}</li>
                <li class="list-group-item"><span style="font-weight: 600"><i class="fa fa-file"></i> Taken:</span> {{ $exam->countTaken() }} times <i class="fa fa-check-circle text-success"></i></li>
            </ul>

            <div class="d-flex justify-content-end mt-3">
                <button :disabled="isStartingItemAnalysis" v-on:click="startItemAnalysis" class="btn btn-info">Start Item Analysis <i class="fa fa-stream"></i></button>
            </div>
        </div>
    </div>  

    <div v-if="isStartingItemAnalysis" class="card mt-4">
        <div class="card-body pt-4">
            <h4 class="text-muted text-center">Please Wait... <div class="spinner-border text-dark" role="status">
              <span class="sr-only">Loading...</span>
            </div></h4>
        </div>
    </div>

    <div v-if="resultReceived" class="card mt-4">
        <div class="card-body pt-4">
            <h5><i class="fa fa-poll"></i> Result</h5>

            
            <div>
                <div style="font-size: 18px; font-weight: 600; text-decoration: underline;" class="text-success">UPPER GROUP</div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th></th>
                                <th :colspan="templates.exam_test_questions.length">Test Questions and Scores</th>
                                <th></th>
                            </tr>
                            <tr>
                                <th>Student ID</th>
                                <th v-for="exam_test_question in templates.exam_test_questions" :key="exam_test_question.id">
                                    <div class="text-right">@{{ exam_test_question.pos_order }}</div>
                                    <div style="font-weight: 300; font-size: 12px" class="text-info">@{{ exam_test_question.test_question.tq_code }}</div>
                                </th>
                                <th>Total Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="upper_template in templates.upper_group" :key="upper_template.assessment.id">
                                <th>@{{ upper_template.assessment.student.student_id }}</th>
                                <td v-for="assessment_detail in upper_template.assessment_details" :key="assessment_detail.id" align="right">
                                    @{{ assessment_detail.is_correct ? '1' : '0' }}
                                </td>
                                <th>@{{ upper_template.total_score }}</th>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Total(U)</th>
                                <th v-for="upper_group_total in templates.upper_group_totals" class="text-success text-right">@{{ upper_group_total }}</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                <div style="font-size: 18px; font-weight: 600; text-decoration: underline;" class="text-success">LOWER GROUP</div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th></th>
                                <th :colspan="templates.exam_test_questions.length">Test Questions and Scores</th>
                                <th></th>
                            </tr>
                            <tr>
                                <th>Student ID</th>
                                <th v-for="exam_test_question in templates.exam_test_questions" :key="exam_test_question.id">
                                    <div class="text-right">@{{ exam_test_question.pos_order }}</div>
                                    <div style="font-weight: 300; font-size: 12px" class="text-info">@{{ exam_test_question.test_question.tq_code }}</div>
                                </th>
                                <th>Total Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="lower_template in templates.lower_group" :key="lower_template.assessment.id">
                                <th>@{{ lower_template.assessment.student.student_id }}</th>
                                <td v-for="assessment_detail in lower_template.assessment_details" :key="assessment_detail.id" align="right">
                                    @{{ assessment_detail.is_correct ? '1' : '0' }}
                                </td>
                                <th>@{{ lower_template.total_score }}</th>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Total(L)</th>
                                <th v-for="lower_group_total in templates.lower_group_totals" class="text-success text-right">@{{ lower_group_total }}</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                <div style="font-size: 18px; font-weight: 600; text-decoration: underline;" class="text-success">INTERPRETATION</div>
                <table class="table table-bordered">
                    <thead>
                        <th width="3%">No.</th>
                        <th width="10%">Test Question</th>
                        <th>Students with <br>Correct Answer</th>
                        <th>Difficulty Index</th>
                    </thead>
                    <tbody>
                        <tr v-for="(exam_test_question, index) in templates.exam_test_questions" :key="exam_test_question.id">
                            <th class="text-right">
                                @{{ exam_test_question.pos_order }}
                            </th>
                            <td>
                                @{{ exam_test_question.test_question.tq_code }}
                            </td>
                            <td>
                                @{{ templates.upper_group_totals[index] + templates.lower_group_totals[index] }}
                            </td>
                            <td>
                                @{{ Math.round(((templates.upper_group_totals[index] + templates.lower_group_totals[index]) / (templates.upper_group.length + templates.lower_group.length)) * 100) }}
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
    <script>
        var vm = new Vue({
            el: '#app',
            data: {
                exam: @json($exam),
                isStartingItemAnalysis: false,
                resultReceived: true,
                validForItemAnalysis: {{ $exam->getAvailableForItemAnalysis()->count() >= 20 }},
                templates: {
                    exam_test_questions: []
                }
            },
            methods: {
                startItemAnalysis() {
                    this.isStartingItemAnalysis = true;

                    if(this.validForItemAnalysis) {
                        ApiClient.post('exams/' + this.exam.id + '/start_item_analysis')
                        .then(response => {
                            console.log(response);
                            this.templates = response.data;
                            this.isStartingItemAnalysis = false;
                            this.resultReceived = true;
                        })
                        .catch(err => {
                            this.resultReceived = false;
                            this.isStartingItemAnalysis = false;
                        });
                    }
                }
            }
        });
    </script>
@endpush