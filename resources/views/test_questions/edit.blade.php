@extends('layouts.sb_admin')

@section('title', 'Add new Test Question')

@section('content')


<a href="{{ url('/test_questions?student_outcome_id='. request('student_outcome_id') . '&course_id=' . request('course_id') . '&program_id=' . request('program_id')) }}" class="text-success"><i class="fa fa-arrow-left"></i> Back</a>

<div class="d-flex justify-content-between mb-2 mt-3">
  <div>
    <h1 class="h3 mb-1 text-gray-800">Edit Test Question #{{ $test_question->id }} <i class="fa fa-question-circle text-primary"></i></h1>
  </div>
</div>




<div id="app" v-cloak>
    <image-modal></image-modal>
    <div class="row">
        <div class="col-md-8">
            <div class="form-group">
                <label class="text-dark"><b>Title</b></label>
                <input 
                    type="text" 
                    class="form-control" 
                    placeholder="Enter title..." 
                    v-model="title"
                    data-vv-name="title"
                    v-validate="{ required: true, regex: /^[A-Za-z\s\-0-9_.,()\'?!]+$/ }"
                    :class="{ 'is-invalid': errors.has('title') }">
                <div class="invalid-feedback">@{{ errors.first('title') }}</div>
            </div>
            <div class="form-group">
                <label class="text-dark"><b>Test question body</b></label>
                <div class="text-danger">@{{ errors.first('body') }}</div>
                {{-- <textarea name="content" id="editor">
                    &lt;p&gt;Here goes the initial content of the editor.&lt;/p&gt;
                </textarea> --}}
                <ckeditor 
                    placeholder="Enter content..." 
                    :editor="editor" 
                    v-model="editorData" 
                    :config="editorConfig"
                    data-vv-name="body"
                    v-validate="'required|max:1000'"
                    :class="{ 'is-invalid': errors.has('body') }"></ckeditor>
                
            </div>

            
            <hr class="mt-5">

            <h5 class="text-dark"><b>Choices</b> 
                <button v-on:click="addChoice" :disabled="this.choices.length >= 5" class="btn btn-sm btn-success">add <i class="fa fa-plus" style="font-size: 10px"></i> </button>
            </h5>

            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li v-for="(choice, index) in choices" :key="index" class="nav-item ">
                <a class="nav-link text-dark" :class="{ 'active': index == 0, 'border-bottom-danger': errors.has('choice ' + (index + 1))  }" id="home-tab" data-toggle="tab" :href="'#c' +(index + 1) ">Choice @{{ index + 1 }} 
                    <checked-icon v-if="choice.is_correct"></checked-icon>
                    <i v-else class="fa fa-check"></i>
                    <button v-if="choices.length > 3" v-on:click="removeChoice(index)" data-toggle="tooltip" data-placement="top" title="Remove" class="btn btn-sm btn-light"><i class="fa fa-minus text-danger"></i></button></a>
              </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div v-for="(choice, index) in choices" :key="index" class="tab-pane fade show" :class="{ 'active': index == 0 }" :id="'c' + (index + 1)" role="tabpanel">
                    <div class="text-danger">@{{ errors.first('choice ' + (index + 1)) }}</div>
                    <ckeditor 
                        :editor="choice.editor" 
                        v-model="choice.editorData" 
                        :config="choice.editorConfig"
                        :data-vv-name="'choice ' + (index+1)"
                        v-validate="'required|max:500'"
                        :class="{ 'is-invalid': errors.has('choice ' + (index+1)) }">    
                    </ckeditor>
                </div>
            </div>

            <div class="form-group mt-3">
                <label class="text-dark"><b>Select Correct Answer</b></label>
                <select 
                    class="form-control" 
                    v-model="correct_answer" 
                    v-on:change="selectCorrectAnswer"
                    data-vv-name="correct answer"
                    v-validate="'required'"
                    :class="{ 'is-invalid': errors.has('correct answer') }">
                    <option value="" class="d-none">Select Correct Answer</option>
                    <option v-for="(choice, index) in choices" :value="index">Choice @{{ index + 1 }}</option>
                </select>
                <div class="invalid-feedback">@{{ errors.first('correct answer') }}</div>
            </div>
            <div class="form-group mt-1">
                <label class="text-dark"><b>Select Level of Difficulty <i class="fa fa-circle" :class="{ 'text-success': level_of_difficulty == 1, 'text-warning': level_of_difficulty == 2, 'text-danger': level_of_difficulty == 3   }"></i></b></label>
                <select 
                    class="form-control" 
                    v-model="level_of_difficulty"
                    data-vv-name="level"
                    v-validate="'required'"
                    :class="{ 'is-invalid': errors.has('level') }">
                    <option value="" class="d-none">Select Option</option>
                    <option value="1">Easy</option>
                    <option value="2">Average</option>
                    <option value="3">Difficult</option>
                </select>
                <div class="invalid-feedback">@{{ errors.first('level') }}</div>
            </div>
            
            <div class="d-flex justify-content-end mt-3">
                <button class="btn btn-dark mr-2" :disabled="btnLoading">View Preview <i class="fa fa-eye"></i></button>
                <button 
                    class="btn btn-primary" 
                    v-on:click="saveTestQuestion"
                    :disabled="btnLoading">
                    Add to database 
                    <i class="fa fa-check"></i> 
                <div v-if="btnLoading" class="spinner-border spinner-border-sm text-light" role="status">
                  <span class="sr-only">Loading...</span>
                </div></button>
            </div>
            
        </div>
        <div class="col-md-4">
            <div class="d-flex justify-content-between align-items-baseline">
                <h5 class="text-dark d-flex align-items-center">

                    <div class="mr-2">
                        <b>Objects</b> 
                    </div>

                    <div class="dropdown dropright">
                      <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                       <i class="fa fa-plus-circle" style="font-size: 10px"></i> add  
                      </button>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a
                            class="dropdown-item"
                            href="#"
                            data-toggle="modal"
                            data-target="#imageModal"
                            ><i class="fa fa-image"></i> Image</a
                        >
                        <a class="dropdown-item" href="#"><i class="fa fa-code"></i> Code</a>
                        <a class="dropdown-item" href="#"><i class="fa fa-superscript"></i> Equation</a>
                      </div>
                    </div>
                </h5>
            </div>
            
            <ul class="list-group">
                <li class="list-group-item">Cras justo odio</li>
                <li class="list-group-item">Dapibus ac facilisis in</li>
                <li class="list-group-item">Morbi leo risus</li>
                <li class="list-group-item">Porta ac consectetur ac</li>
                <li class="list-group-item">Vestibulum at eros</li>
            </ul>
        </div>
    </div>
    
    
</div>

@endsection


@push('scripts')
    <script>
        var vm = new Vue({
            el: '#app',
            data: {
                question: '',
                choices: [
                @foreach ($test_question->choices as $index => $choice)
                    {
                        is_correct: {{ $choice->is_correct }},
                        editor: ClassicEditor,
                        editorData: '{!! $choice->body !!}',
                        editorConfig: {
                        }
                    },
                @endforeach
                ],
                editor: ClassicEditor,
                editorData: '{!! $test_question->body !!}',
                editorConfig: {
                    // The configuration of the editor.
                },
                title: '{{ $test_question->title }}',
                correct_answer: '',
                level_of_difficulty: '{{ $test_question->difficulty_level_id  }}',
                course_id: '{{ request('course_id') }}',
                student_outcome_id: '{{ request('student_outcome_id') }}',
                btnLoading: false
            },
            methods: {
                addChoice() {
                    this.choices.push({
                        is_correct: false,
                        editor: ClassicEditor,
                        editorData: '',
                        editorConfig: {
                            // The configuration of the editor.
                        }
                    });
                },
                removeChoice(index) {
                    swal.fire({
                        title: 'Do you want to remove this choice?',
                        text: "Please confirm",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#1cc88a',
                        cancelButtonColor: '#858796',
                        confirmButtonText: 'Yes',
                        width: '400px'
                      }).then((result) => {
                        if (result.value) {
                            this.errors.clear();
                            this.choices.splice(index, 1);
                            this.correct_answer = '';
                            // for(let i = 0; i < this.choices.length; i++) {
                            //     this.choices[i].is_correct = false;
                            // }
                            this.getCorrectAnswer();
                        }
                      });
                },
                selectCorrectAnswer() {
                    for(let i = 0; i < this.choices.length; i++) {
                        this.choices[i].is_correct = false;
                    }

                    this.choices[this.correct_answer].is_correct = true;

                },
                getCorrectAnswer() {
                    for(let i = 0; i < this.choices.length; i++) {
                        if(this.choices[i].is_correct) {
                            this.correct_answer = i;
                            break; 
                        }
                        
                    }
                },
                saveTestQuestion() {
                    this.btnLoading = true;
                    this.$validator.validateAll()
                    .then(isValid => {
                        
                        if(isValid) {
                            ApiClient.post('/test_questions', {
                                title: this.title,
                                question_body: this.editorData,
                                level_of_difficulty: this.level_of_difficulty,
                                course_id: this.course_id,
                                student_outcome_id: this.student_outcome_id,
                                choices: this.choices
                            })
                            .then(response => {
                                this.btnLoading = false;
                                window.location.replace(myRootURL + '/test_questions?student_outcome_id=' + this.student_outcome_id + '&course_id=' + this.course_id);

                            })
                        } else {
                            toast.fire({
                                title: 'Please enter valid data!',
                                type: 'error'
                              });
                            this.btnLoading = false;
                        }
                    })
                    .catch(err => {
                        console.log(err);
                        this.btnLoading = false;
                    })
                }
            },
            created() {  
                this.getCorrectAnswer();
            }
        });
    </script>
@endpush