@extends('layout.app', ['active' => 'assessments'])

@section('title', 'Assessment Results')


@section('content')
<div id="app" v-cloak>
    <div class="card">
        <div class="card-body pt-4">
            <a href="{{ url('/assessment_results?college_id=' . request('college_id')) }}" class="text-success"><i class="fa fa-arrow-left"></i> Back</a>
            <h1 class="page-header mb-3 mt-3">Assessment Result</h1>
            <ul class="list-group">
              <li class="list-group-item">
                  <label class="text-dark mr-2"><i class="fa fa-fingerprint"></i> Assessment ID: <span class="fs-19 text-info">{{ $assessment->assessment_code }}</span></label>
              </li>
              <li class="list-group-item">
                  <label class="text-dark mr-2"><i class="fa fa-flag"></i> Student Outcome: <span class="fs-19 text-info">{{ $assessment->studentOutcome->so_code }}</span></label>
              </li>
              <li class="list-group-item">
                  <label class="text-dark mr-2"><i class="fa fa-id-card"></i> Student ID: <span class="fs-19 text-info">{{ $assessment->student->student_id }}</span></label>
              </li>
              <li class="list-group-item">
                  <label class="text-dark mr-2"><i class="fa fa-user"></i> Student: <span class="fs-19 text-info">{{ $assessment->student->user->getFullName() }}</span></label>
              </li>
              <li class="list-group-item">
                  <label class="text-dark"><i class="fa fa-graduation-cap"></i> Program: <span class="fs-19 text-info">{{ $assessment->student->program->program_code }}</span></label>
              </li>
              
            </ul>
        </div>
    </div>
    
    <div id="main-nav-tabs" class="mt-3">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#details" role="tab" aria-selected="true">Details</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#answers" role="tab" aria-controls="contact" aria-selected="false">Answers</a>
          </li>
        </ul>
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade show active" id="details" role="tabpanel">
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

                    <i class="fa fa-calendar-alt"></i>  Date: <span style="font-weight: 600">{{ $assessment->created_at->format('M d Y h:m a') }} | {{ $assessment->created_at->diffForHumans() }}</span>
                  </li>
                  <li class="list-group-item"><i class="fa fa-list"></i> {{ $assessment->assessmentDetails->count() }} total items </li>

                  <li class="list-group-item"><i class="fa fa-check-circle text-success"></i> {{ $assessment->getCorrectAnswers()->count() }} correct answers </li>
                  <li class="list-group-item"><i class="fa fa-times-circle text-danger"></i> {{ $assessment->getIncorrectAnswers()->count() }} incorrect answers</li>
                  <li class="list-group-item"><i class="fa fa-check-circle"></i> {{ $answer_sheet->passing_grade }}% passing grade </li>
                  <li class="list-group-item"><i class="fa fa-clock"></i> {{ $assessment->getDuration() }}</li>
                  <li class="list-group-item"><i class="fa fa-check-circle text-success"></i> {{ $assessment->getAnsweredTestQuestions()->count() }} test questions answered</li>
                  <li class="list-group-item"><i class="fa fa-times-circle"></i> {{ $assessment->getUnansweredTestQuestions()->count() }} unanswered test question</li>
                </ul>
          </div>
          <div class="tab-pane fade" id="answers" role="tabpanel">
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
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
var vm = new Vue({
    el: '#app',
    data: {
        assessment: @json($assessment),
        courses: @json($courses),
        counter: 1,
        templates: [],
        answer_sheet: @json($answer_sheet),
        selected_test_questions: [],
        course_id: ''
    },
    methods: {
        getTestQuestionByCourse(course_id) {
            return this.answer_sheet.answer_sheet_test_questions.filter(answer_sheet_test_question => {
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
            for(var i = 0; i < this.answer_sheet.answer_sheet_test_questions.length; i++) {
                if(this.answer_sheet.answer_sheet_test_questions[i].id == test_question_id) {
                    test_question = this.answer_sheet.answer_sheet_test_questions[i];
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
                //this.selected_test_questions = this.answer_sheet.answer_sheet_test_questions;
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
        }
    },
    created() {
        this.createTemplate();
        this.selected_test_questions = this.getTestQuestionByCourse(this.templates[0].course.id);
        this.course_id = this.courses[0].id;

        setInterval(() => {
            MathLive.renderMathInDocument();
            Prism.highlightAll();
        }, 100);
    }
});
</script>
@endpush

