@extends('layouts.sb_admin')

@section('title', 'Add new Test Question')

@section('content')


<a href="{{ url('/test_qestions') }}" class="text-success"><i class="fa fa-arrow-left"></i> Back</a>

<div class="d-flex justify-content-between mb-2 mt-3">
  <div>
    <h1 class="h3 mb-1 text-gray-800">Add new Test Question <i class="fa fa-question-circle text-primary"></i></h1>
  </div>
</div>

<div id="app">
    <div class="row">
        <div class="col-md-8">
            <div class="form-group">
                <label class="text-dark"><b>Title</b></label>
                <input type="text" class="form-control" placeholder="Enter title...">
            </div>

            <div class="form-group">
                <label class="text-dark"><b>Test question body</b></label>
                {{-- <textarea name="content" id="editor">
                    &lt;p&gt;Here goes the initial content of the editor.&lt;/p&gt;
                </textarea> --}}
                <ckeditor placeholder="Enter content..." :editor="editor" v-model="editorData" :config="editorConfig"></ckeditor>
            </div>
            
            <hr class="mt-5">

            <h5 class="text-dark"><b>Choices</b> 
                <button v-on:click="addChoice" :disabled="this.choices.length >= 5" class="btn btn-sm btn-success">add <i class="fa fa-plus" style="font-size: 10px"></i> </button>
            </h5>

            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li v-for="(choice, index) in choices" :key="index" class="nav-item">
                <a class="nav-link" :class="{ 'active': index == 0 }" id="home-tab" data-toggle="tab" :href="'#c' +(index + 1) ">Choice @{{ index + 1 }} <button v-if="choices.length > 3" v-on:click="removeChoice(index)" data-toggle="tooltip" data-placement="top" title="Remove" class="btn btn-sm btn-light"><i class="fa fa-minus text-danger"></i></button></a>
              </li>
{{--               <li class="nav-item">
                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Choice B</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Choice C</a>
              </li> --}}
            </ul>
            <div class="tab-content" id="myTabContent">
                <div v-for="(choice, index) in choices" :key="index" class="tab-pane fade show" :class="{ 'active': index == 0 }" :id="'c' + (index + 1)" role="tabpanel">
                    <ckeditor :editor="choice.editor" v-model="choice.editorData" :config="choice.editorConfig"></ckeditor>
                </div>
                {{-- <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <textarea name="content" id="choice-b">
                        &lt;p&gt;Here goes the initial content of the editor.&lt;/p&gt;
                    </textarea>
                </div>
                <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                    <textarea name="content" id="choice-c">
                        &lt;p&gt;Here goes the initial content of the editor.&lt;/p&gt;
                    </textarea>
                </div> --}}
            </div>
            
            <div class="d-flex justify-content-end mt-3">
                <button class="btn btn-dark mr-2">View Preview <i class="fa fa-eye"></i></button>
                <button class="btn btn-primary ">Add to database <i class="fa fa-check"></i></button>
            </div>
            
        </div>
        <div class="col-md-4">
            <div class="d-flex justify-content-between align-items-baseline">
                <h5 class="text-dark"><b>Objects</b> 
                <button class="btn btn-sm btn-success">add <i class="fa fa-plus" style="font-size: 10px"></i> </button></h5>
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
        
        // ClassicEditor.create( document.querySelector( '#editor' ) )
        // .then( editor => {
        //     console.log( editor );
        // } )
        // .catch( error => {
        //     console.error( error );
        // } );

        /*

        for (let i = 0; i < pre_choices.length; i++) {
            ClassicEditor.create( document.querySelector( '#choice-' + pre_choices[i] ) )
            .then( editor => {
                //console.log( editor );
                pre_choices_obj.push(editor);
            } )
            .catch( error => {
                console.error( error );
            } );
        }*/
        
    </script>

    <script>
        new Vue({
            el: '#app',
            data: {
                question: '',
                choices: [
                    {
                        editor: ClassicEditor,
                        editorData: '',
                        editorConfig: {
                        }
                    },
                    {
                        editor: ClassicEditor,
                        editorData: '',
                        editorConfig: {
                            // The configuration of the editor.
                        }
                    },
                    {
                        editor: ClassicEditor,
                        editorData: '',
                        editorConfig: {
                            // The configuration of the editor.
                        }
                    }
                ],
                editor: ClassicEditor,
                editorData: '',
                editorConfig: {
                    // The configuration of the editor.
                }
            },
            methods: {
                addChoice() {
                    this.choices.push({
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
                          this.choices.splice(index, 1);
                        }
                      });
                } 
            },
            created() {  
                
            }
        });
    </script>
@endpush