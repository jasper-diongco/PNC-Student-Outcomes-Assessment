@extends('layout.app', ['active' => 'test_questions'])

@section('title', 'Exam Details')

@section('content')
<div id="app" v-cloak>
    <revise-test-question-modal :test_question_id="selected_test_question_id" :item_analysis_detail_id="selected_item_analysis_detail_id" v-on:update-revise-status="updateReviseStatus"></revise-test-question-modal>
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
                <button :disabled="isStartingItemAnalysis || resultReceived" v-on:click="startItemAnalysis" class="btn btn-info">Start Item Analysis <i class="fa fa-stream"></i></button>
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

    <div v-if="resultReceived" class="mt-4">
        <div class="card">
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

            
        </div>
    </div>
    
    
    <h5 class="text-info mt-5">Result</h5>
    <div class="mb-2">
        <button class="btn btn-sm" :class="{ 'btn-success': viewStyle == 'list' }" v-on:click="viewStyle = 'list'"><i class="fa fa-list"></i> List View</button>
        <button class="btn btn-sm" :class="{ 'btn-success': viewStyle == 'table' }" v-on:click="viewStyle = 'table'"><i class="fa fa-table"></i> Table View</button>
    </div>
    <div class="card" v-if="viewStyle == 'table'">
        <div class="card-body">
            <div >
                <table class="table table-bordered">
                    <thead>
                        <th width="3%">No.</th>
                        <th width="10%">Test Question</th>
                        <th>Students with <br>Correct Answer</th>
                        <th>Difficulty Index</th>
                        <th>Difficulty Interpretation</th>
                        <th>Difficulty Action</th>
                        <th>Discrimination Index</th>
                        <th>Discrmination Interpretation</th>
                        <th>Discrimination Action</th>
                        {{-- <th>Recommended Action</th> --}}
                    </thead>
                    <tbody>
                        <tr v-for="(exam_test_question, index) in templates.exam_test_questions" :key="exam_test_question.id">
                            <th>
                                <div class="avatar" :style="avatarStyle(exam_test_question.test_question.difficulty_level_id)">
                                    @{{ exam_test_question.pos_order }}
                                </div>
                                
                            </th>
                            <td>
                                @{{ exam_test_question.test_question.tq_code }}
                            </td>
                            <td>
                                @{{ templates.correct_answers[index] }}
                            </td>
                            <td>
                                @{{ templates.difficulty_index[index] }}
                            </td>
                            <td>
                                @{{ difficultyIndexInterpretation(templates.difficulty_index_num[index]) }}
                            </td>
                            <td>
                                @{{ difficultyActionInterpretation(templates.difficulty_actions[index]) }}
                            </td>
                            <td>
                                @{{ templates.discrimination_index[index] }}
                            </td>
                            <td>@{{ discriminationInterpretation(templates.discrimination_index[index]) }}</td>
                            <td>@{{ discriminationAction(templates.discrimination_actions[index]) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <ul class="list-group" id="list-exam-test-questions" v-else>
          <li v-for="(exam_test_question, index) in templates.exam_test_questions" :key="exam_test_question.id" class="list-group-item">
                <div class="d-flex justify-content-between align-items-baseline">
                    <div class="d-flex">
                        <div class="mr-3">

                            <div class="avatar" :style="avatarStyle(exam_test_question.test_question.difficulty_level_id)">
                              @{{ exam_test_question.pos_order }}
                            </div>
                        </div>
                        <div>
                            <div style="font-size: 18px">
                            
                                <div class="mb-1">{{-- <i class="fa fa-fingerprint"></i> --}} ID: @{{ exam_test_question.test_question.tq_code }}</div>

                              
                                <div class="mb-1" style="font-weight: 600">
                                    <i class="fa fa-file-alt"></i> @{{ exam_test_question.test_question.title }}
                                  </div> 
                            </div>
                            <div  class="text-muted mb-1">Current Level: @{{ getDifficulty(exam_test_question.test_question.difficulty_level_id) }}</div>
                            <div><span class="text-dark" style="font-weight: 600">Students With Correct Answer: </span> <span class="text-success output-box fs-19" style="font-weight: 600">@{{ templates.correct_answers[index] }}</span></div>
                            <div>
                                <h5 style="text-decoration: underline;">Difficulty</h5>
                                <div class="d-flex">
                                    <div  class="mr-3 text-warning output-box"><span class="text-dark" style="font-weight: 600;">Difficulty Index:</span> <span>@{{ templates.difficulty_index[index] }}</span></div>
                                    <div  class="mr-3 text-warning output-box"><span class="text-dark" style="font-weight: 600;">Difficulty Interpretation:</span> <span>@{{ difficultyIndexInterpretation(templates.difficulty_index_num[index]) }}</span></div>
                                    <div  class="mr-3 text-warning output-box" :style="actionStyle('difficulty', templates.difficulty_actions[index])"><span class="text-dark" style="font-weight: 600;" >Difficulty Recommended Action:</span> <span>@{{ difficultyActionInterpretation(templates.difficulty_actions[index]) }}</span></div>
                                </div>
                            </div>

                            <div>
                                <h5 style="text-decoration: underline;">Discrmination</h5>
                                <div class="d-flex">
                                    <div  class="mr-3 text-warning output-box"><span class="text-dark" style="font-weight: 600;">Discrmination Index:</span> <span>@{{ templates.discrimination_index[index] }}</span></div>
                                    <div  class="mr-3 text-warning output-box"><span class="text-dark" style="font-weight: 600;">Discrmination Interpretation:</span> <span>@{{ discriminationInterpretation(templates.discrimination_index[index]) }}</span></div>
                                    <div :style="actionStyle('discrimination', templates.discrimination_actions[index])"  class="mr-3 text-warning output-box"><span class="text-dark" style="font-weight: 600;">Discrmination Recommended Action:</span> <span>@{{ discriminationAction(templates.discrimination_actions[index]) }}</span></div>
                                </div>
                            </div>

                            <div>
                                <h5 style="text-decoration: underline;">Recommended action</h5>
                                <div class="d-flex" v-if="!checkIfResolved(exam_test_question.test_question_id)">
                                    <button v-if="checkAction(templates.recommended_actions[index], 1)" v-on:click="retainTestQuestion(exam_test_question.test_question_id, templates.item_analysis_details[index].id)" class="btn btn-sm btn-success mr-2">Retain <i class="fa fa-check"></i></button>
                                    <button v-if="templates.difficulty_actions[index] == 2 && checkAction(templates.recommended_actions[index], 2)" v-on:click="changeLevelOfDifficulty(exam_test_question.test_question_id, templates.item_analysis_details[index].id, difficultyLevelId(difficultyIndexInterpretation(templates.difficulty_index_num[index])))" class="btn btn-sm btn-success mr-2">Change to @{{ difficultyIndexInterpretation(templates.difficulty_index_num[index]) }} <i class="fa fa-check"></i></button>

                                    <button v-if="checkAction(templates.recommended_actions[index], 3)" v-on:click="rejectTestQuestion(exam_test_question.test_question_id, templates.item_analysis_details[index].id)" class="btn btn-sm btn-warning mr-2">Reject <i class="fa fa-archive"></i></button>

                                    <button class="btn btn-sm btn-info" v-if="checkAction(templates.recommended_actions[index], 2)" v-on:click="openReviseModal(exam_test_question.test_question_id, templates.item_analysis_details[index].id)">Revise <i class="fa fa-edit"></i></button>
                                </div>
                                <div v-else class="fs-19">
                                    <i class="fa fa-check-circle text-success"></i> Resolved :
                                    <span class="text-warning" style="text-decoration: underline;">
                                        @{{ checkIfResolved(exam_test_question.test_question_id).action_resolved }}
                                    </span>
                                </div>
                            </div>
                        </div>   
                    </div>
                    <div>
                        {{-- <a v-on:click="getPreview(test_question.id)" href="#" data-toggle="modal" data-target="#previewModal" class="btn btn-sm">View <i class="fa fa-search"></i></a> --}}                           
                    </div>
                </div>
            </li>
        </ul>
    </div>
    
    <div class="d-flex justify-content-end mt-3">
        <button class="btn btn-info">Save <i class="fa fa-check"></i></button>
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
                resultReceived: false,
                validForItemAnalysis: {{ $exam->getAvailableForItemAnalysis()->count() >= 20 }},
                templates: {
                    exam_test_questions: []
                },
                viewStyle: 'list',
                selected_test_question_id: '',
                selected_item_analysis_detail_id: ''
            },
            methods: {
                getDifficulty(difficulty_id) {
                    if (difficulty_id == 1) {
                        return 'Easy';
                    } else if (difficulty_id == 2) {
                        return 'Average';
                    } else if (difficulty_id == 3) {
                        return 'Difficult'
                    }
                },
                avatarStyle(difficulty_level_id) {
                    var backgroundColor = '';
                    if(difficulty_level_id == 1) {
                        backgroundColor = '#cbff90';
                        color = '#4caf50';
                    } else if (difficulty_level_id == 2) {
                        backgroundColor = '#fff375';
                        color = '#ffc107';
                    } else if (difficulty_level_id == 3) {
                        backgroundColor = '#f28b82';
                        color = '#d04b42';
                    }
                    

                    return {
                        backgroundColor,
                        color
                    };
                },
                actionStyle(type, index) {
                    var backgroundColor = '#fff';

                    if(type == 'difficulty') {
                        if(index == 1) {
                            backgroundColor = '#83ce83';
                        } else if(index == 2) {
                            backgroundColor = '#89e3ec';
                        } else if(index == 3) {
                            backgroundColor = 'yellow';
                        }
                    } else if (type == 'discrimination') {
                        if(index == 1) {
                            backgroundColor = '#83ce83';
                        } else if(index == 2 || index == 3 || index == 4) {
                            backgroundColor = '#89e3ec';
                        } else if(index == 5) {
                            backgroundColor = 'yellow';
                        }
                    }

                    return {
                        backgroundColor
                    }
                },
                checkAction(actions, chk_action) {
                    for(var i = 0; i < actions.length; i++) {
                        if(actions[i] == chk_action) {
                            return true;
                        }
                    }

                    return false;
                },
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
                },
                difficultyIndexInterpretation(index_num) {
                    if(index_num == 5) {
                        return "Very Difficult";
                    } else if (index_num == 4) {
                        return "Difficult";
                    } else if (index_num == 3) {
                        return "Average";
                    } else if (index_num == 2) {
                        return "Easy";
                    } else if (index_num == 1) {
                        return "Very Easy";
                    } 
                },
                difficultyLevelId(diff_str) {
                    if(diff_str == "Easy") {
                        return 1;
                    } else if (diff_str == "Average") {
                        return 2;
                    } else if(diff_str == "Difficult") {
                        return 3;
                    }
                },
                difficultyActionInterpretation(action_num) {
                    if (action_num == 1) {
                        return "Retain";
                    } else if (action_num == 2) {
                        return "Revise";
                    } else if (action_num == 3) {
                        return "Discard";
                    }
                },
                discriminationInterpretation(discrimination_index) {
                    if(discrimination_index < 0.09) {
                        return "Very Poor";
                    } else if (discrimination_index >= 0.09 && discrimination_index <= 0.19) {
                        return "Poor";
                    } else if (discrimination_index >= 0.20 && discrimination_index <= 0.29) {
                        return "Fair";
                    } else if (discrimination_index >= 0.30 && discrimination_index <= 0.39) {
                        return "Good";
                    } else if (discrimination_index >= 0.40) {
                        return "Very Good";
                    }
                },
                discriminationAction(index) {
                    if(index == 5) {
                        return "Reject";
                    } else if (index == 4) {
                        return "Revise/Reject";
                    } else if (index == 3) {
                        return "Improve";
                    } else if (index == 2) {
                        return "Retain/Revise";
                    } else if (index == 1) {
                        return "Retain";
                    }
                },
                checkIfResolved(test_question_id) {
                    for(var i = 0; i < this.templates.item_analysis_details.length; i++) {
                        if (this.templates.item_analysis_details[i].test_question_id == test_question_id && this.templates.item_analysis_details[i].is_resolved) {
                            return this.templates.item_analysis_details[i];
                        }
                    }

                    return false;
                },
                rejectTestQuestion(test_question_id, item_analysis_detail_id) {
                    swal.fire({
                        title: 'Do you want to reject?',
                        text: "Please confirm",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#1cc88a',
                        cancelButtonColor: '#858796',
                        confirmButtonText: 'Yes',
                        width: '400px'
                    }).then((result) => {
                        if (result.value) {
                            ApiClient.post('/item_analysis/' + test_question_id + '/reject_test_question', {
                            item_analysis_detail_id: item_analysis_detail_id
                            })
                            .then(response => {
                                var item_analysis_detail = response.data;

                                for(var i = 0; i < this.templates.item_analysis_details.length; i++) {
                                    if(this.templates.item_analysis_details[i].id == item_analysis_detail.id) {
                                        this.templates.item_analysis_details.splice(i, 1, item_analysis_detail);
                                        break;
                                    }
                                }

                                toast.fire({
                                    type: 'success',
                                    title: 'Item is rejected'
                                });
                            })
                        }
                    });
                },
                retainTestQuestion(test_question_id, item_analysis_detail_id) {
                    ApiClient.post('/item_analysis/' + test_question_id + '/retain_test_question', {
                    item_analysis_detail_id: item_analysis_detail_id
                    })
                    .then(response => {
                        var item_analysis_detail = response.data;

                        for(var i = 0; i < this.templates.item_analysis_details.length; i++) {
                            if(this.templates.item_analysis_details[i].id == item_analysis_detail.id) {
                                this.templates.item_analysis_details.splice(i, 1, item_analysis_detail);
                                break;
                            }
                        }

                        toast.fire({
                            type: 'success',
                            title: 'Item is retained'
                        });
                    })
                },
                changeLevelOfDifficulty(test_question_id, item_analysis_detail_id, difficulty_level_id) {
                    ApiClient.post('/item_analysis/' + test_question_id + '/change_level_of_difficulty', {
                    item_analysis_detail_id: item_analysis_detail_id,
                    difficulty_level_id: difficulty_level_id
                    })
                    .then(response => {
                        var item_analysis_detail = response.data.item_analysis_detail;

                        for(var i = 0; i < this.templates.item_analysis_details.length; i++) {
                            if(this.templates.item_analysis_details[i].id == item_analysis_detail.id) {
                                this.templates.item_analysis_details.splice(i, 1, item_analysis_detail);
                                break;
                            }
                        }

                        // for(var i = 0; i < this.templates.exam_test_questions.length; i++) {
                        //     if(this.templates.exam_test_questions[i].test_question_id == test_question_id) {
                        //         this.templates.exam_test_questions[i].test_question = response.test_question
                        //         break;
                        //     }
                        // }

                        toast.fire({
                            type: 'success',
                            title: "Item's Difficulty Changed"
                        });
                    })
                },
                openReviseModal(test_question_id, item_analysis_detail_id) {
                    this.selected_test_question_id = test_question_id;
                    this.selected_item_analysis_detail_id = item_analysis_detail_id;

                    $('#reviseTestQuestionModal').modal('show');
                },
                updateReviseStatus(response) {
                    var item_analysis_detail = response.data;

                    for(var i = 0; i < this.templates.item_analysis_details.length; i++) {
                        if(this.templates.item_analysis_details[i].id == item_analysis_detail.id) {
                            this.templates.item_analysis_details.splice(i, 1, item_analysis_detail);
                            break;
                        }
                    }
                }
            },

            created() {
                if(this.exam.item_analysis) {
                    this.startItemAnalysis();
                }
            }
        });
    </script>
@endpush