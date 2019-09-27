@extends('layout.app', ['active' => 'test_questions'])

@section('title', 'Test Bank')

@section('content')
<div id="app" v-cloak>
    <machine-problem-modal :courses="courses"></machine-problem-modal>
    <div class="card bg-dark shadow">
        <div class="card-body py-4">
            <h5 class="text-white"><i class="fa fa-code text-success"></i> Test Bank</h5>
            <div class="d-md-flex">
                <div class="d-flex align-self-baseline mr-2">
                    <label class="text-white mr-2">Program: </label>
                    <div class="fs-19 text-info">{{ $program->program_code }}</div>
                </div>
                <div class="d-flex align-self-baseline">
                    <label class="text-white mr-2">Student Outcome: </label>
                    <div class="fs-19 text-info">{{ $student_outcome->so_code }}</div>
                </div>
            </div>
            <p style="color:#a0a0a0" class="fs-19"><i class="fa fa-file-alt"></i> {{ $student_outcome->description }}</p>
            

            
        </div>

        
    </div>

    <div class="row">
        <div class="col-md-3">
            <h5 class="mt-3">Courses</h5>
            <ul class="list-group">
                @foreach($student_outcome->getCoursesMapped($curriculum->id) as $course)
                <li class="list-group-item">
                    <i class="fa fa-book"></i> <strong>{{ $course->course_code }}</strong> - {{ $course->description }}
                </li>
                @endforeach
            </ul>
        </div>
        <div class="col-md-9">
            <ul class="nav nav-tabs mt-3" id="main-nav-tabs" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#code-questions" role="tab" aria-controls="home" aria-selected="true"><i class="fa fa-question-circle"></i> Machine Problems</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#exams" role="tab" aria-controls="profile" aria-selected="false"><i class="fa fa-file-alt"></i> Exams</a>
              </li>
            </ul>
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade show active" id="code-questions" role="tabpanel" aria-labelledby="home-tab">
                <div class="d-flex justify-content-between align-items-baseline my-3">
                    <h5>List of machine problems</h5>
                    <button class="btn btn-info" v-on:click="openMachineProblemModal">Add new</button>
                </div>
                  <div class="table-responsive">
                      <table class="table">
                          <thead>
                              <tr>
                                  <th>ID</th>
                                  <th>Description</th>
                                  <th>Test Cases</th>
                                  <th>Courses</th>
                                  <th>View</th>
                              </tr>
                          </thead>
                      </table>
                  </div>
              </div>
              <div class="tab-pane fade" id="exams" role="tabpanel" aria-labelledby="profile-tab">...</div>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
    <script src="https://pagecdn.io/lib/ace/1.4.5/ace.js" type="text/javascript" charset="utf-8"></script>
    <script src="https://pagecdn.io/lib/ace/1.4.5/ext-emmet.js"></script>
    <script src="https://pagecdn.io/lib/ace/1.4.5/ext-language_tools.js"></script>
    <script>
        var vm = new Vue({
            el: '#app',
            data: {
                courses: @json($student_outcome->getCoursesMapped($curriculum->id))
            },
            methods: {
                openMachineProblemModal() {
                    $('#machineProblemModal').modal('show');
                }
            }
        });
    </script>
@endpush