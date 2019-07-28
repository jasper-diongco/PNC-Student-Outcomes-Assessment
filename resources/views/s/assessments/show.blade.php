@extends('layout.app', ['active' => 'home-student', 'container_fluid' => true, 'hide_navigation' => true, 'brand' => '<i class="fa fa-edit"></i> Student Outcome A &mdash; Assessment', 'assessment' => true, 'fixed_top' => true, 'shadow' => true, 'custom_layout' => true])


@section('title', 'Assessment')

@section('content')

    <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow fixed-top">
      <a class="navbar-brand" href="#"><i class="fa fa-file-alt"></i> Student Outcome A - Assessment</a>
    </nav>
    {{-- <div id="app" v-cloak>
        <div class="row">
            <div class="col-md-8 p-3 pl-4" style="max-height: 100vh; overflow-y: auto;">
                
            </div>
            <div class="col-md-4 p-3 pr-4" style="max-height: 100vh; overflow-y: auto;">
                                
            </div>
        </div>
    </div> --}}
    <div id="app">
        <div class="exam-sidenav">
            <div class="card shadow">
                <div class="card-body pt-3 text-center ">
                    <h5 class="mx-0"><i class="fa fa-clock text-info"></i> Time Remaining</h5>
                    <div class="time-container text-muted">
                        21:30
                    </div>
                </div>
            </div>

            <div class="card shadow mt-3">
                <div class="card-body pt-3">
                    

                    <h5 class="mx-0"><i class="fa fa-question-circle text-info"></i> Questions</h5>
                    
                    <div v-for="template in templates" :key="template.course.course_code">
                        <label class="text-muted" style="font-weight: 400; font-size: 19px"><i class="fa fa-book"></i> @{{ template.course.course_code }}</label>
                        <div class="d-flex flex-wrap">
                            <div v-on:click="selectTestQuestion(test_question.id)" v-for="test_question in template.test_questions" class="question-avatar mr-2 mb-2" :class="{ 'question-avatar-answered': test_question.is_answered, 'active_test_question': test_question.id == selected_test_question.id, 'question-avatar-marked': test_question.is_marked  }">
                                @{{ test_question.counter }}              
                            </div>
                            
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>

        <div class="exam-main">
            <div class="card shadow">
                <div class="card-body pt-3">
                    <div class="text-muted mb-3 d-flex justify-content-start align-items-baseline"><i class="fa fa-book text-muted mr-2"></i> @{{ selected_course.course_code }} - @{{ selected_course.description }}</div>
                    <div class="test-question-body text-dark d-flex">

                        <div class="mr-2">
                            <div class="test-num">@{{ selected_test_question.counter }}</div> 
                        </div>
                        <div class="mt-1" v-html="selected_test_question.body_html">
                        </div>
                        
                        
                    </div>
                    <hr>
                    <div class="choices">
                        <div v-for="choice in selected_test_question.answer_sheet_test_question_choices" :key="choice.id" class="mb-3" v-on:click="selectChoice(selected_test_question.id, choice.id)">
                            <div class="text-dark choice" style="height: 100%;">
                                <div class="d-flex">
                                    <div class="mr-2">
                                        <div class="choice-num" :class="{ 'choice-selected': choice.is_selected }">
                                            @{{ choice.letter }}
                                        </div>
                                    </div>
                                    <div v-html="choice.body_html">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
        
                        <div class="d-flex justify-content-end">
                            <button v-on:click="markTestQuestion" class="btn btn-info mr-2"><i class="fa fa-bookmark"></i> Mark for review</button>
                            <button v-on:click="prevQuestion" class="btn btn-info mr-2" ><i class="fa fa-arrow-circle-left"></i> Back </button>

                            <button v-on:click="nextQuestion" class="btn btn-info">Next <i class="fa fa-arrow-circle-right"></i></button>
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
            courses: @json($courses),
            counter: 1,
            templates: [],
            answers: [],
            marks: [],
            answer_sheet: @json($answer_sheet),
            selected_test_question: '',
            selected_course: ''
        },
        methods: {
            getTestQuestionByCourse(course_id) {
                return this.answer_sheet.answer_sheet_test_questions.filter(answer_sheet_test_question => {
                    return answer_sheet_test_question.course_id == course_id;
                });
            },
            selectCourse(course_id) {
                for(var i = 0; i < this.courses.length; i++) {
                    if(this.courses[i].id == course_id) {
                        this.selected_course = this.courses[i];
                        break;
                    }               
                }
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
                        Vue.set(this.templates[i].test_questions[j], 'is_marked', false);
                        Vue.set(this.templates[i].test_questions[j], 'counter', counter);
                        Vue.set(this.templates[i].test_questions[j], 'is_answered', false);


                        var choices = this.templates[i].test_questions[j].answer_sheet_test_question_choices;
                        for(var k = 0; k < choices.length; k++) {
                            choices[k].letter = this.convertToChar(k);
                        }

                        counter++;
                    }                        
                }
            },
            selectTestQuestion(test_question_id) {
                for(var i = 0; i < this.answer_sheet.answer_sheet_test_questions.length; i++) {
                    if(this.answer_sheet.answer_sheet_test_questions[i].id == test_question_id) {
                        this.selected_test_question = this.answer_sheet.answer_sheet_test_questions[i];
                        this.selectCourse(this.selected_test_question.course_id);
                        break;
                    }               
                }
                MathLive.renderMathInDocument();
            },
            selectChoice(test_question_id, choice_id) {
                var test_question = this.selected_test_question;

                for(var i = 0; i < test_question.answer_sheet_test_question_choices.length; i++) {  if(test_question.answer_sheet_test_question_choices[i].id == choice_id) {
                        test_question.answer_sheet_test_question_choices[i].is_selected = 1;
                    } else {
                        test_question.answer_sheet_test_question_choices[i].is_selected = 0;
                    }         
                }

                test_question.is_answered = true;

            },
            nextQuestion() {
                var counter = this.selected_test_question.counter;
                for(var i = 0; i < this.templates.length; i++) {  
                    for(var j = 0; j < this.templates[i].test_questions.length; j++) {
                        if(this.templates[i].test_questions[j].counter == counter + 1) {
                            this.selected_test_question = this.templates[i].test_questions[j];

                        }
                    }       
                }


            },
            prevQuestion() {
                var counter = this.selected_test_question.counter;
                for(var i = 0; i < this.templates.length; i++) {  
                    for(var j = 0; j < this.templates[i].test_questions.length; j++) {
                        if(this.templates[i].test_questions[j].counter == counter - 1) {
                            this.selected_test_question = this.templates[i].test_questions[j];

                        }
                    }       
                }
            },
            markTestQuestion() {
                this.selected_test_question.is_marked = true;
            }
            // answerQuestion(test_question_id, choice_id) {

            //     for(var i = 0 ; i < this.answers.length; i++) {
            //         if(this.answers[i].test_question_id == test_question_id) {
            //             this.answers.splice(i,1);
            //             break;
            //         }
            //     }

            //     this.answers.push({
            //         test_question_id: test_question_id,
            //         choice_id: choice_id
            //     });
            // },
            // checkIfChoiceIsSelected(test_question_id,choice_id) {
            //     var is_selected = false;

            //     for(var i = 0 ; i < this.answers.length; i++) {
            //         if(this.answers[i].choice_id == choice_id) {
            //             is_selected = true;
            //             break;
            //         }
            //     }

            //     return is_selected;
            // },
            // scroll() {

            //     //$(function() { $("#top").on('click', function() { $("HTML, BODY").animate({ scrollTop: 0 }, 1000); }); });

            //     //var main = document.querySelector('#main-test-questions-content');

            //     setTimeout(() => {
            //         window.scroll(0, -100);
            //     }, 100);
                
            // },
            // markTestQuestion(test_question_id) {
            //     for(var i = 0; i < this.marks.length; i++) {
            //         if(test_question_id == this.marks[i]) {
            //             return this.marks.splice(i, 1);
            //         }       
            //     }
            //     this.marks.push(test_question_id);
            // },
            // checkIfMarked(test_question_id) {
            //     for(var i = 0; i < this.marks.length; i++) {
            //         if(test_question_id == this.marks[i]) {
            //             return true;
            //         }
            //     }

            //     return false;
            // }
        },
        created() {
            this.createTemplate();
            this.selected_test_question = this.templates[0].test_questions[0];
            this.selected_course = this.courses[0];
            setInterval(() => {
                MathLive.renderMathInDocument();
            }, 100);
        }
    });
</script>

@endpush