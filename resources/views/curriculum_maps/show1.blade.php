@extends('layout.app',['active' => 'curriculum_mapping'])

@section('title', 'Curriculum Mapping')

@section('content')
    <div id="app" v-cloak>
        {{-- add --}}
        <learning-level-modal :program_id="program.id" :learning_levels="learning_levels" v-on:update-learning-levels="getLearningLevels"></learning-level-modal>
        {{-- update --}}
        <learning-level-modal :program_id="program.id" :learning_levels="learning_levels" v-on:update-learning-levels="getLearningLevels" :is_update="true" :learning_level="learning_level"></learning-level-modal>
        <div class="card p-3 mb-3">
            <div class="mx-auto" style="width: 400px">
              <img src="{{ asset('svg/map.svg') }}" class="w-100">
            </div>
            <div class="d-flex">
                <h1 class="page-header">Curriculum Mapping</h1>
            </div>
            
            <div class="d-flex">
                <div>
                    <label>Program: <span style="font-size: 20px" class="text-info">{{ $curriculum->program->program_code }}</span></label>
                </div>
                <div class="ml-3">
                    <label>Curriculum: <span style="font-size: 20px" class="text-info">{{ $curriculum->name }}</span></label>
                </div>
            </div>

            <div class="d-flex mt-2">
                <div class="mr-4"><i class="fa fa-code-branch text-primary"></i> <label>Revision no:</label> {{ $curriculum->revision_no }}.0</div>

                <div class="mr-4"><i class="fa fa-calendar-check text-primary"></i> <label>Year:</label> {{ $curriculum->year }}</div>
            </div>

      

          @if($curriculum->description) 
            <p class="mr-5"><i class="fa fa-file-alt text-primary"></i> <label>Description:</label> {{ $curriculum->description }}</p>
          @else
            <p class="mr-5"><i class="fa fa-file-alt text-primary"></i> <label>Description:</label> <i>No description.</i></p>
          @endif
        </div>



        <div class="card">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-baseline">
                    <div>
                        <h5>Learning levels</h5>
                    </div>
                    <div>
                        <button class="btn btn-info btn-sm" v-on:click="openLearningLevelModal">Add Learning Level <i class="fa fa-plus-circle"></i></button>
                    </div>
                    
                </div>
                
                <ul class="list-group mt-2">
                    <li v-for="learning_level in learning_levels" :key="learning_level.id" class="list-group-item d-flex justify-content-between" :style="{ 'background': learning_level.color }">
                        <div>
                            @{{ learning_level.name }} - <label>@{{ learning_level.letter }}</label>                        
                        </div>
                        <div>
                            <button v-on:click="openUpdateModal(learning_level)" class="btn btn-sm"><i class="fa fa-edit"></i></button>
                        </div>   
                    </li>
                </ul>
            </div>
        </div>

        <div class="card mt-4">

            <div class="card-body">
                <label class="text-dark">Curriculum Map <i class="fa fa-map text-success"></i></label>
                <table class="table table-bordered">
                    <thead>
                        <th></th>
                        <th>A</th>
                        <th>B</th>
                        <th>C</th>
                        <th>D</th>
                        <th>E</th>
                        <th>F</th>
                        <th>G</th>
                        <th>H</th>
                        <th>I</th>
                        <th>J</th>
                        <th>K</th>
                        <th>L</th>
                        <th>M</th>
                    </thead>
                    <tbody>
                        <tr>
                            <th>IT01</th>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
@endsection

@push('scripts')
    <script>
        new Vue({
            el: '#app',
            data: {
                learning_levels: @json($learning_levels),
                program: @json($curriculum->program),
                learning_level: ''
            },
            methods: {
                openLearningLevelModal() {
                    $("#learningLevelModal").modal("show");
                },
                getLearningLevels() {
                    ApiClient.get("/programs/" + this.program.id + "/get_learning_levels")
                    .then(response => {
                        this.learning_levels = response.data;
                    })
                },
                openUpdateModal(learning_level) {
                    this.learning_level = learning_level;
                    $("#learningLevelModalUpdate").modal('show');
                }
            }
        });
    </script>
@endpush