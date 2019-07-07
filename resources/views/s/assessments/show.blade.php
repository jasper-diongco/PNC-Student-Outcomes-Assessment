@extends('layout.app', ['active' => 'home-student', 'container_fluid' => true, 'hide_navigation' => true, 'brand' => 'IT01 Assessment', 'assessment' => true, 'fixed_top' => true])


@section('title', 'Assessment')

@section('content')
    <div id="app" v-cloak>
        <div class="row">

            <div class="col-md-3" style="max-height: 100vh; overflow-y: auto;">
                

                <div class="card">

                    <div class="card-body pt-3 pr-2">
                        <div class="d-flex align-items-center">
                            <div class="mr-1">
                                <label class="mb-0">List of Questions</label>
                            </div>
                            <div>
                                {{-- <img src="{{ asset('/img/list_md.svg') }}" style="width: 23px; height: 23px"> --}}
                                <i class="fa fa-question-circle text-info"></i>
                            </div>
                        
                        </div>
                        

                        <template v-for="template in templates">
                        <div class="mt-2 text-info">@{{ template.course.course_code }} - @{{ template.test_questions.length }} questions</div>
                        <ul class="list-group question-pallete">
                          <li v-for="test_question in template.test_questions" class="list-group-item px-1">
                              <div class="d-flex">
                                  <div class="mr-2">
                                      <div class="question-pallete-avatar">
                                          @{{ test_question.counter }}
                                      </div>
                                  </div>
                                  <div class="d-flex justify-content-between">
                                    <div class="mr-2">
                                        <a v-on:click="scroll" :href="'#tq' + test_question.id" class="text-dark">@{{ test_question.title }} </a>
                                    </div>
                                    <div v-if="checkIfMarked(test_question.id)" class="d-flex justify-content-end">
                                        <i class="fa fa-bookmark text-warning"></i>
                                    </div>
                                    
                                  </div>
                              </div>
                            </li>
                        </ul>
                        </template>
                    </div>
                </div>
            </div>
            
            <div id="main-test-questions-content" class="col-md-9" style="max-height: 130vh; overflow-y: auto;">
                <div class="mb-3"></div>
                <div v-for="template in templates" class="mb-5">
                    <h4><i class="fa fa-book text-info"></i> @{{ template.course.course_code + ' - ' + template.course.description }}</h4>
                    <label class="mb-4" style="font-weight: 400"><i class="fa fa-question-circle text-success"></i> @{{ template.test_questions.length }} questions</label>
                    <div :id="'tq' + test_question.id" v-for="test_question in template.test_questions" class="card mb-3">
                        <div class="card-body text-dark pt-4">
                            <div class="d-flex justify-content-end mb-1">
                                <button v-on:click="markTestQuestion(test_question.id)" class="btn btn-sm btn-light"><i class="fa fa-bookmark" :class="{ 'text-warning': checkIfMarked(test_question.id), 'text-primary': !checkIfMarked(test_question.id)   }"></i> Mark</button>
                            </div>
                            <div class="test-question-body text-dark d-flex">
                                <div class="mr-2">
                                    <div class="test-num">@{{ test_question.counter }}</div> 
                                </div>
                                <div class="mt-1" v-html="test_question.html">
                                </div>
                            </div>
                            <hr>
                            <div class="choices">
                                <div class="row">
                                    <template v-for="choice in test_question.random_choices">
                                        <div v-on:click="answerQuestion(test_question.id, choice.id)"  class="col-6 mb-3">
                                            <div class="text-dark choice" :class="{ 'is-selected-choice': checkIfChoiceIsSelected(test_question.id,choice.id) }" style="height: 100%;">
                                                <div class="d-flex">
                                                    <div class="mr-2">
                                                        <div class="choice-num">
                                                            @{{ choice.letter }}
                                                        </div>
                                                    </div>
                                                    <div v-html="choice.html">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
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
            exam: @json($exam),
            courses: @json($courses),
            test_questions: @json($test_questions),
            counter: 1,
            templates: [],
            answers: [],
            marks: []
        },
        methods: {
            getTestQuestionByCourse(course_id) {
                return this.test_questions.filter(test_question => {
                    return test_question.course_id == course_id;
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
                        this.templates[i].test_questions[j].counter = counter;
                        var choices = this.templates[i].test_questions[j].random_choices;
                        for(var k = 0; k < choices.length; k++) {
                            choices[k].letter = this.convertToChar(k);
                        }

                        counter++;
                    }                          
                }
            },
            answerQuestion(test_question_id, choice_id) {

                for(var i = 0 ; i < this.answers.length; i++) {
                    if(this.answers[i].test_question_id == test_question_id) {
                        this.answers.splice(i,1);
                        break;
                    }
                }

                this.answers.push({
                    test_question_id: test_question_id,
                    choice_id: choice_id
                });
            },
            checkIfChoiceIsSelected(test_question_id,choice_id) {
                var is_selected = false;

                for(var i = 0 ; i < this.answers.length; i++) {
                    if(this.answers[i].choice_id == choice_id) {
                        is_selected = true;
                        break;
                    }
                }

                return is_selected;
            },
            scroll() {

                //$(function() { $("#top").on('click', function() { $("HTML, BODY").animate({ scrollTop: 0 }, 1000); }); });

                //var main = document.querySelector('#main-test-questions-content');

                setTimeout(() => {
                    window.scroll(0, -100);
                }, 100);
                
            },
            markTestQuestion(test_question_id) {
                for(var i = 0; i < this.marks.length; i++) {
                    if(test_question_id == this.marks[i]) {
                        return this.marks.splice(i, 1);
                    }       
                }
                this.marks.push(test_question_id);
            },
            checkIfMarked(test_question_id) {
                for(var i = 0; i < this.marks.length; i++) {
                    if(test_question_id == this.marks[i]) {
                        return true;
                    }
                }

                return false;
            }
        },
        created() {
            this.createTemplate();
            setTimeout(() => {
                MathLive.renderMathInDocument();
            }, 300);
            
        }
    });
</script>

@endpush