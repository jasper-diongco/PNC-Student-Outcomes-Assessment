@extends('layout.app', ['active' => 'assessments'])

@section('title', 'Assessment Score')

@section('content')

<div id="app">
    <div class="card">
        <div class="card-body pt-4">
            <a href="{{ url('s/home') }}" class="text-success"><i class="fa fa-arrow-left"></i> Back</a>
            <div class="mt-3">
                <h1 class="page-header">Assessment Score</h1>
                <div class="w-md-31 mx-auto">
                    <img src="{{ asset('svg/undraw_segmentation_uioo1.svg') }}" alt="done checking" class="w-100">
                </div>
                <h2 class="mt-3 text-info text-center" style="font-size: 25px">{{ $student_outcome->description }}</h2>
                <div class="mt-4 d-flex flex-column align-items-center">
                    <div style="color:#a7a7a7;">YOUR SCORE</div>
                    <div style="font-size: 40px;font-weight: 600; {{ $assessment->checkIfPassed() ? '' : 'border-color:#b5b5b5;' }}" class="circle-border {{ $assessment->checkIfPassed() ? 'text-success' : 'text-danger' }}">{{ $assessment->computeScore() }}%</div>
                    
                    <div class="alert mt-4 mb-4" style="background: #f1f1f1">

                     <h5><strong>Performance Criteria:</strong> {{ $assessment->getPerformanceCriteriaText() }}<br>
                      <br>
                      Score: <strong>{{ $assessment->computeScore() }}% ({{ $assessment->getScoreLabel() }})</strong>  &mdash; {{ $assessment->getScoreDescription() }}</h5>
                    </div>

                    @if ($assessment->checkIfPassed())
                        <div class="text-center mb-3" style="font-size: 20px; color: #858886">
                            <strong>Congratulations, {{ $student->user->first_name . ' ' .$student->user->last_name  }} </strong>
                            <div>You passed the assessment for Student Outcome {{ $student_outcome->so_code }}</div> 
                        </div>
                    @else
                        <div class="text-center mb-3" style="font-size: 20px; color: #858886">
                            <strong>Sorry, {{ $student->user->first_name . ' ' .$student->user->last_name  }} </strong>
                            <div>You didn't passed the assessment for Student Outcome {{ $student_outcome->so_code }}</div> 
                        </div>
                    @endif


                </div>
                
                <h5 class="text-info mt-3">Details</h5>
                <ul class="list-group mt-3 list-student-outcomes">
                  <li class="list-group-item"><i class="fa fa-list"></i> {{ $assessment->assessmentDetails->count() }} total items </li>

                  <li class="list-group-item"><i class="fa fa-check-circle text-success"></i> {{ $assessment->getCorrectAnswers()->count() }} correct answers </li>
                  <li class="list-group-item"><i class="fa fa-times-circle text-danger"></i> {{ $assessment->getIncorrectAnswers()->count() }} incorrect answers</li>
                  <li class="list-group-item"><i class="fa fa-check-circle"></i> {{ $answer_sheet->passing_grade }}% passing grade </li>
                  <li class="list-group-item"><i class="fa fa-clock"></i> {{ $assessment->getDuration() }}</li>
                  <li class="list-group-item"><i class="fa fa-check-circle text-success"></i> {{ $assessment->getAnsweredTestQuestions()->count() }} test questions answered</li>
                  <li class="list-group-item"><i class="fa fa-times-circle"></i> {{ $assessment->getUnansweredTestQuestions()->count() }} unanswered test question</li>
                  
                </ul>
                
                <div class="d-flex justify-content-end mt-4">
                    @if(!$assessment->checkIfPassed())
                        {{-- <a href="{{ url('s/assessments/' . request('student_outcome_id') . '?retake_exam=yes') }}" class="btn btn-warning mr-2">Take Again</a> --}}
                        <form action="{{ url('s/assessments/retake_assessment/' . $student_outcome->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-warning mr-2" >Retake Assessment</button>
                        </form>
                    @endif
                    <a href="{{url('s/home') }}" class="btn btn-info">OK, Back to home</a>
                </div>
            </div>
            
        </div>
    </div>

    <div id="main-nav-tabs" class="mt-3">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          {{-- <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#details" role="tab" aria-selected="true">Details</a>
          </li> --}}
          <li class="nav-item">

            <a class="nav-link active" id="contact-tab" data-toggle="tab" href="#answers" role="tab" aria-controls="contact" aria-selected="false">Answers</a>
          </li>
          <li class="nav-item">

            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#reports" role="tab" aria-controls="contact" aria-selected="false">Reports</a>
          </li>
        </ul>
        <div class="tab-content" id="myTabContent">
          {{-- <div class="tab-pane fade show active" id="details" role="tabpanel">
              <ul class="list-group mt-3">
                  <li class="list-group-item ">
                      Exam ID: <span style="font-weight: 600">{{ $assessment->exam->exam_code }}</span>
                  </li>
                  
                  <li class="list-group-item ">
                      Grade: <span style="font-weight: 600">{{ $assessment->computeScore() }}%</span>
                  </li>
                  <li class="list-group-item ">
                      <i class="fa fa-file-alt"></i> Description: <span style="font-weight: 600">{{ $assessment->exam->description }}</span>
                  </li>
                  <li class="list-group-item ">

                    <i class="fa fa-calendar-alt"></i>  Date: <span style="font-weight: 600">{{ $assessment->created_at->format('M d Y h:m a') }} &mdash; {{ $assessment->created_at->diffForHumans() }}</span>
                  </li>
                  <li class="list-group-item"><i class="fa fa-list"></i> {{ $assessment->assessmentDetails->count() }} total items </li>

                  <li class="list-group-item"><i class="fa fa-check-circle text-success"></i> {{ $assessment->getCorrectAnswers()->count() }} correct answers </li>
                  <li class="list-group-item"><i class="fa fa-times-circle text-danger"></i> {{ $assessment->getIncorrectAnswers()->count() }} incorrect answers</li>
                  <li class="list-group-item"><i class="fa fa-check-circle"></i> {{ $answer_sheet->passing_grade }}% passing grade </li>
                  <li class="list-group-item"><i class="fa fa-clock"></i> {{ $assessment->getDuration() }}</li>
                  <li class="list-group-item"><i class="fa fa-check-circle text-success"></i> {{ $assessment->getAnsweredTestQuestions()->count() }} test questions answered</li>
                  <li class="list-group-item"><i class="fa fa-times-circle"></i> {{ $assessment->getUnansweredTestQuestions()->count() }} unanswered test question</li>
                </ul>
          </div> --}}
          <div class="tab-pane fade show active" id="answers" role="tabpanel">
            <div class="d-flex align-self-baseline justify-content-between">
                <div>
                    <h5 class="my-3 ml-2">Student's Answers</h5>
                </div>
                <div class="d-flex align-items-baseline">
                    <div>
                        <label class="mr-2"><i class="fa fa-book text-success"></i> Course</label>
                    </div>
                    <div>
                        <select v-model="course_id" class="form-control" v-on:change="selectTestQuestions">
                            <option value="all">All</option>
                            <option v-for="course in courses" :value="course.id" :key="course.id">@{{ course.course_code }}</option>
                        </select>

                    </div>
                    
                </div>
            </div>
            
            <div class="d-flex justify-content-end">
                <a href="{{ url('assessment_results/' . $assessment->id . '/print_assessment_result') }}" target="_blank" class="btn btn-sm btn-info mb-2"><i class="fa fa-print"></i> Print</a>
            </div>

            <div>
                
            </div>
            
            <div v-for="selected_test_question in selected_test_questions" :key="selected_test_question.id" class="card mb-4">
                <div class="card-body pt-3">
                    <div class="d-flex justify-content-between">
                        <div class="text-muted">
                            <i class="fa fa-book text-info"></i> @{{ getCourse(selected_test_question.course_id).description }}
                        </div>
                        <div>
                            <h5 v-if="checkIfCorrect(selected_test_question)" class="mb-3" style="text-decoration: underline;">Correct Answer <i class="fa fa-check text-success"></i></h5>
                            <h5 v-else-if="!checkIfAnswered(selected_test_question.id)" class="mb-3" style="text-decoration: underline;">No Answer <i class="fa fa-times text-danger"></i></h5>
                            <h5 v-else class="mb-3" style="text-decoration: underline;">Wrong Answer <i class="fa fa-times text-danger"></i></h5>
                        </div>
                    </div>
                    
                    <div class="test-question-body text-dark d-flex">

                        <div class="mr-2">
                            <div class="test-num">@{{ selected_test_question.counter }}</div> 
                        </div>
                        <div class="mt-1" v-html="selected_test_question.body_html">
                        </div>
                        
                        
                    </div>
                    <hr>
                    <div class="choices">
                        <div v-for="choice in selected_test_question.answer_sheet_test_question_choices" :key="choice.id" class="mb-3">
                            <div class="text-dark choice" :class="{ 'choice-correct': choice.is_correct  }" style="height: 100%;">
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex">
                                        <div class="mr-2">
                                            <div class="choice-num" :class="{ 'choice-selected': choice.is_selected }">
                                                @{{ choice.letter }}
                                            </div>
                                        </div>
                                        <div v-html="choice.body_html">
                                        </div>
                                    </div>
                                    <div v-if="choice.is_selected">
                                        <i style="font-size: 25px;" v-if="checkIfCorrect(selected_test_question)" class="fa fa-check-circle text-success"></i>
                                        <i style="font-size: 25px;" v-else class="fa fa-times-circle text-danger"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

          </div>
          <div class="tab-pane fade show" id="reports" role="tabpanel">
            <h3 class="my-3">Reports</h3>
            <div class="card mb-4">
                <div class="card-body py-4">
                    <div class="w-md-40">
                        <h5 class="mt-3">Percentage of scores in total score</h5>
                            <p class="text-info">This figure shows the percentage of scores in total score</p>
                        <pie-chart :data="scorePerCoursesData"></pie-chart>
                    </div>
                </div>
            </div>


            <div class="card mb-4">
                <div class="card-body py-4">
                    <div>
                        <h5 class="mt-3">Score per course</h5>
                        <p class="text-info">This figures show the percentage of scores per course</p>
                        <div v-for="courseScore in scorePerCourses" :key="courseScore.course.id" class="mb-3">
                            <div class="mr-2 mb-2"><span class="font-weight-bold"> @{{ courseScore.course.course_code }}</span> - @{{ courseScore.course.description }} &mdash; <span class="font-weight-bold">@{{ courseScore.totalItemsInCourse }}</span> items</div>
                            <div class="d-flex align-items-baseline">
                                <div class="progress w-100 mr-2">
                                  <div class="progress-bar bg-success" role="progressbar" :style="{ 'width': courseScore.scoreInPercentage + '%' }" :aria-valuenow="courseScore.scoreInPercentage" aria-valuemin="0" aria-valuemax="100">@{{ courseScore.scoreInPercentage.toFixed(2) }}%</div>
                                </div>
                                <div class="font-weight-bold text-dark">
                                    (@{{courseScore.score}})
                                </div>
                            </div>
                        </div>  
                        
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body py-4">
                    <div>
                        <h5 class="mt-3">Score per course (table view)</h5>
                        <p class="text-info">The table below shows the percentage of scores per course in more detailed.</p>
                        
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Course</th>
                                        <th>Total Items</th>
                                        <th>Easy Score</th>
                                        <th>Average Score</th>
                                        <th>Difficult Score</th>
                                        <th>Overall Score</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="scoreDetailed in scorePerCoursesDetailed">
                                        <td>@{{ scoreDetailed.course.course_code }}</td>
                                        <td>@{{ scoreDetailed.totalItemsInCourse }}</td>
                                        <td>@{{ scoreDetailed.easyScore }} (@{{ scoreDetailed.easyPercentage.toFixed(2) }}%)</td>
                                        <td>@{{ scoreDetailed.averageScore  }} (@{{ scoreDetailed.averagePercentage.toFixed(2) }}%)</td>
                                        <td>@{{ scoreDetailed.difficultScore }} (@{{ scoreDetailed.difficultPercentage.toFixed(2) }}%)</td>
                                        <td>@{{ scoreDetailed.score }} (@{{ scoreDetailed.scoreInPercentage.toFixed(2) }}%)</td>
                                    </tr>
                                </tbody>
                                <tfoot class="font-weight-bold">
                                    <tr>
                                        <td>Total</td>
                                        <td>@{{ scoreTotals.totalItems }}</td>
                                        <td>@{{ scoreTotals.easy }} (@{{ scoreTotals.easyPercentage.toFixed(2) }}%)</td>
                                        <td>@{{ scoreTotals.average }} (@{{ scoreTotals.averagePercentage.toFixed(2) }}%)</td>
                                        <td>@{{ scoreTotals.difficult }} (@{{ scoreTotals.difficultPercentage.toFixed(2) }}%)</td>
                                        <td>@{{ scoreTotals.overall }} (@{{ scoreTotals.overallPercentage.toFixed(2) }}%)</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
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
                assessment: @json($assessment),
                courses: @json($courses),
                counter: 1,
                templates: [],
                answer_sheet: @json($answer_sheet),
                answer_sheet_test_questions: @json($answer_sheet_test_questions),
                selected_test_questions: [],
                course_id: '',
                scorePerCourses: [],
                scorePerCoursesDetailed: [],
                scoreTotals: {
                    totalItems: 0,
                    easy: 0,
                    average: 0,
                    difficult: 0,
                    overall: 0
                },
                correct_answers: {{ $assessment->getCorrectAnswers()->count() }},
                scorePerCoursesData: {
                        labels: [],
                        datasets: [
                            {
                                label: "Score",
                                data: [],
                                backgroundColor: [
                                    "rgba(52, 172, 224,0.2)",
                                    "rgba(51, 217, 178,0.2)",
                                    "rgba(252, 92, 101,0.2)",
                                    "rgba(75, 123, 236,0.2)",
                                    "rgba(253, 150, 68,0.2)",
                                    "rgba(254, 211, 48,0.2)",
                                    "rgba(38, 222, 129,0.2)",
                                    "rgba(43, 203, 186,0.2)",
                                    "rgba(69, 170, 242,0.2)", 
                                    "rgba(136, 84, 208,0.2)",
                                    "rgba(75, 101, 132,0.2)",
                                    "rgba(64, 64, 122,0.2)",
                                    "rgba(112, 111, 211,0.2)"
                                ],
                                borderColor: [
                                    "rgba(52, 172, 224,1.0)",
                                    "rgba(51, 217, 178,1.0)",
                                    "rgba(252, 92, 101,1.0)",
                                    "rgba(75, 123, 236,1.0)",
                                    "rgba(253, 150, 68,1.0)",
                                    "rgba(254, 211, 48,1.0)",
                                    "rgba(38, 222, 129,1.0)",
                                    "rgba(43, 203, 186,1.0)",
                                    "rgba(69, 170, 242,1.0)", 
                                    "rgba(136, 84, 208,1.0)",
                                    "rgba(75, 101, 132,1.0)",
                                    "rgba(64, 64, 122,1.0)",
                                    "rgba(112, 111, 211,1.0)"

                                ],
                                borderWidth: 1
                            }
                        ]
                    }
            },
            methods: {
                getTestQuestionByCourse(course_id) {
                    return this.answer_sheet_test_questions.filter(answer_sheet_test_question => {
                        return answer_sheet_test_question.course_id == course_id;
                    });
                },
                convertToChar(index) {
                    return String.fromCharCode(index + 65);
                },
                createTemplate() {
                    var counter = 1;
                    for(var i = 0; i < this.courses.length; i++) {
                        var course_test_questions = this.getTestQuestionByCourse(this.courses[i].id);
                        this.templates.push({
                            course: this.courses[i],
                            test_questions: course_test_questions
                        });
                        for(var j = 0; j < course_test_questions.length; j++) {
                            //this.templates[i].test_questions[j].counter = counter;
                            //this.templates[i].test_questions[j].is_answered = false;
                            //this.templates[i].test_questions[j].is_marked = false;

                            // Vue.set(this.templates[i].test_questions[j], 'is_marked', false);
                            Vue.set(this.templates[i].test_questions[j], 'counter', counter);

                            if(this.checkIfAnswered(this.templates[i].test_questions[j].id)) {
                                Vue.set(this.templates[i].test_questions[j], 'is_answered', true);
                            } else {
                                Vue.set(this.templates[i].test_questions[j], 'is_answered', false);
                            }
                            
                            


                            var choices = this.templates[i].test_questions[j].answer_sheet_test_question_choices;
                            for(var k = 0; k < choices.length; k++) {
                                choices[k].letter = this.convertToChar(k);
                            }

                            counter++;
                        }                        
                    }
                },
                checkIfAnswered(test_question_id) {
                    var test_question;
                    var is_answered = false;
                    for(var i = 0; i < this.answer_sheet_test_questions.length; i++) {
                        if(this.answer_sheet_test_questions[i].id == test_question_id) {
                            test_question = this.answer_sheet_test_questions[i];
                            break;
                        }               
                    }


                    for(var i = 0; i < test_question.answer_sheet_test_question_choices.length; i++) {
                        if(test_question.answer_sheet_test_question_choices[i].is_selected) {
                            is_answered = true;
                            break;
                        }
                    }

                    return is_answered;   
                },
                checkIfCorrect(test_question) {
                    var is_correct = false;
                    
                    if(!this.checkIfAnswered(test_question.id)) {
                        return false;
                    }

                    for(var i = 0; i < test_question.answer_sheet_test_question_choices.length; i++) {
                        if(test_question.answer_sheet_test_question_choices[i].is_selected && test_question.answer_sheet_test_question_choices[i].is_correct) {
                            is_correct = true;
                            break;
                        }
                    }

                    return is_correct;
                },
                selectTestQuestions() {
                    if(this.course_id == "all") {
                        this.selected_test_questions = [];
                        for(var i = 0; i < this.courses.length; i++) {
                            var test_questions = this.getTestQuestionByCourse(this.courses[i].id);

                            this.selected_test_questions = this.selected_test_questions.concat(test_questions);
                        }
                        //this.selected_test_questions = this.answer_sheet_test_questions;
                    } else {
                        this.selected_test_questions = this.getTestQuestionByCourse(this.course_id);
                    }
                    
                },
                getCourse(course_id) {
                    for(var i = 0; i < this.courses.length; i++) {
                        if(this.courses[i].id == course_id) {
                            return this.courses[i];
                        }
                    }
                },
                fillScorePerCoursesData() {
                    this.getScorePerCoursesData();
                    for(var i = 0; i < this.courses.length; i++) {
                        // var percent = (this.scorePerCoursesData.datasets[0].data[i] / this.correct_answers) * 100;
                        this.scorePerCoursesData.labels.push(this.courses[i].course_code);
                    }
                    
                    // this.getScorePercentageInTotalScore();
                },
                getScorePerCoursesData() {
                    var scores = [];
                    for(var i = 0; i < this.courses.length; i++) {
                        // console.log(this.courses[i].id);
                        var score = 0;
                        for(var j = 0; j < this.answer_sheet_test_questions.length; j++) {
                            if(this.answer_sheet_test_questions[j].course_id == this.courses[i].id) {
                                if(this.checkIfCorrect(this.answer_sheet_test_questions[j])) {
                                    score++;
                                }
                                
                            }
                        }

                        scores.push(score);
                    }

                    this.scorePerCoursesData.datasets[0].data = scores;
                },
                getScorePercentageInTotalScore() {
                    var scores = [];
                    for(var i = 0; i < this.courses.length; i++) {

                        var score = 0;
                        for(var j = 0; j < this.answer_sheet_test_questions.length; j++) {
                            if(this.answer_sheet_test_questions[j].course_id == this.courses[i].id) {
                                if(this.checkIfCorrect(this.answer_sheet_test_questions[j])) {
                                    score++;
                                }

                                
                            }
                        }

                        var percent = (score / this.correct_answers) * 100;

                        scores.push(percent.toFixed(2));
                    }


                    this.scorePerCoursesData.datasets[0].data = scores;
                },
                getScorePerCourses() {
                    var scores = [];
                    for(var i = 0; i < this.courses.length; i++) {
                        // console.log(this.courses[i].id);
                        var totalItemsInCourse = 0;
                        var score = 0;
                        for(var j = 0; j < this.answer_sheet_test_questions.length; j++) {
                            if(this.answer_sheet_test_questions[j].course_id == this.courses[i].id) {
                                if(this.checkIfCorrect(this.answer_sheet_test_questions[j])) {
                                    score++;
                                }
                                totalItemsInCourse++;
                                
                            }
                        }

                        var scoreInPercentage = (score / totalItemsInCourse) * 100;

                        scores.push({
                            course: this.courses[i],
                            score: score,
                            totalItemsInCourse: totalItemsInCourse,
                            scoreInPercentage: scoreInPercentage
                        });
                    }

                    this.scorePerCourses = scores;
                    return scores;
                },
                getScorePerCoursesDetailed() {
                    var scores = [];
                    for(var i = 0; i < this.courses.length; i++) {
                        // console.log(this.courses[i].id);
                        var totalItemsInCourse = 0;
                        var easyScore = 0;
                        var averageScore = 0;
                        var difficultScore = 0;
                        var score = 0;
                        for(var j = 0; j < this.answer_sheet_test_questions.length; j++) {
                            if(this.answer_sheet_test_questions[j].course_id == this.courses[i].id) {
                                if(this.checkIfCorrect(this.answer_sheet_test_questions[j])) {
                                    score++;

                                    if(this.answer_sheet_test_questions[j].difficulty_level_id == 1) {
                                        easyScore++;
                                    } else if(this.answer_sheet_test_questions[j].difficulty_level_id == 2) {
                                        averageScore++;
                                    } else if(this.answer_sheet_test_questions[j].difficulty_level_id == 3) {
                                        difficultScore++;
                                    }

                                }
                                totalItemsInCourse++;
                                
                            }
                        }

                        var scoreInPercentage = (score / totalItemsInCourse || 0) * 100;

                        var easyPercentage = (easyScore / score || 0) * 100;
                        var averagePercentage = (averageScore / score || 0) * 100;
                        var difficultPercentage = (difficultScore / score || 0) * 100;

                        scores.push({
                            course: this.courses[i],
                            score: score,
                            totalItemsInCourse: totalItemsInCourse,
                            scoreInPercentage: scoreInPercentage,
                            easyScore: easyScore,
                            averageScore: averageScore,
                            difficultScore: difficultScore,
                            easyPercentage: easyPercentage,
                            averagePercentage: averagePercentage,
                            difficultPercentage: difficultPercentage
                        });
                    }

                    this.scorePerCoursesDetailed = scores;
                    // totalItems: 0,
                    // easy: 0,
                    // average: 0,
                    // difficult: 0,
                    // overall: 0
                    for(var i = 0; i < scores.length; i++) {
                        this.scoreTotals.totalItems += scores[i].totalItemsInCourse;
                        this.scoreTotals.easy += scores[i].easyScore;
                        this.scoreTotals.difficult += scores[i].difficultScore;
                        this.scoreTotals.average += scores[i].averageScore;
                        this.scoreTotals.overall += scores[i].score;
                    }

                    //get percentages
                    this.scoreTotals.easyPercentage = (this.scoreTotals.easy / this.scoreTotals.overall || 0) * 100;
                    this.scoreTotals.averagePercentage = (this.scoreTotals.average / this.scoreTotals.overall || 0) * 100;
                    this.scoreTotals.difficultPercentage = (this.scoreTotals.difficult / this.scoreTotals.overall || 0) * 100;
                    this.scoreTotals.overallPercentage = (this.scoreTotals.overall / this.scoreTotals.totalItems || 0) * 100;

                    return scores;
                }
            },
            created() {
                this.createTemplate();
                this.selected_test_questions = this.getTestQuestionByCourse(this.templates[0].course.id);
                this.course_id = this.courses[0].id;
                //this.answer_sheet_test_questions = this.answer_sheet_test_questions;
                this.fillScorePerCoursesData();
                this.getScorePerCourses();
                this.getScorePerCoursesDetailed();
                setInterval(() => {
                    MathLive.renderMathInDocument();
                    Prism.highlightAll();
                }, 100);
            }
        });
    </script>
@endpush