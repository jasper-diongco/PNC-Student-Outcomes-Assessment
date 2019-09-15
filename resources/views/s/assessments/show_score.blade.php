@extends('layout.app', ['active' => 'assessments'])

@section('title', 'Assessment Score')

@section('content')

<div id="app">
    <div class="card">
        <div class="card-body pt-4">
            <a href="{{ url('s/home') }}" class="text-success"><i class="fa fa-arrow-left"></i> Back</a>
            <div class="mt-3">
                <h1 class="page-header">Assessment Score</h1>
                <div class="w-25">
                    <img src="{{ asset('svg/undraw_done_checking_ty9a.svg') }}" alt="done checking" class="w-100">
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

                <div class="w-md-50 mt-4">
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
</div>

@endsection

@push('scripts')
    <script>
        var vm = new Vue({
            el: '#app',
            data: {
                courses: @json($courses),
                answer_sheet: @json($answer_sheet),
                answer_sheet_test_questions: @json($answer_sheet_test_questions),
                scorePerCourses: []
            },
            methods: {
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
                }
            },
            created() {
                this.getScorePerCourses();
            }
        });
    </script>
@endpush