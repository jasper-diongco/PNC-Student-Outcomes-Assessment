@extends('layout.app', ['active' => 'test_questions'])

@section('title', 'Test Bank')

@section('content')

<a href="{{ url('/test_bank/list_programs') }}" class="text-success"><i class="fa fa-arrow-left"></i> Back</a>

<div class="d-flex justify-content-between mb-3">
  <div>
    <h1 class="page-header mt-3">Test Bank &mdash; {{ $program->program_code }} - List of Student Outcomes</h1>
  </div>
</div>


@if(count($program->studentOutcomes) > 0) 
  <div class="list-group">
    
    @foreach($program->studentOutcomes as $student_outcome)
      
      <div class="card mb-2">
        <!-- Card Header - Accordion -->
        <a href="#so_{{$student_outcome->id}}" class="d-block card-header py-3 so_collapse" data-toggle="collapse" role="button">
          <div class="d-flex justify-content-between">
            <h6 class="m-0 font-weight-bold so_collapse mr-5">{{ $student_outcome->so_code }} &mdash; {{ $student_outcome->description }}</h6>


            
          </div>
          
        </a>
        
        <!-- Card Content - Collapse -->
        <div class="collapse show" id="so_{{$student_outcome->id}}">

          <ul class="nav nav-tabs" id="myTab-{{$student_outcome->id}}" role="tablist">
            <li class="nav-item test-bank-nav">
              <a class="nav-link active" id="test-question-tab-{{$student_outcome->id}}" data-toggle="tab" href="#test_question_content-{{$student_outcome->id}}" role="tab" aria-selected="true">
                <label>Test Questions</label> <i class="fa fa-question-circle"></i>
              </a>
            </li>
            <li class="nav-item test-bank-nav">
              <a class="nav-link" id="exam-tab-{{$student_outcome->id}}" data-toggle="tab" href="#exam_content-{{$student_outcome->id}}" role="tab" aria-selected="false">
                <label>Exams</label> <i class="fa fa-file-alt"></i>
              </a>
            </li>
          </ul>
          <div class="tab-content testbank-tab-content">
            {{-- test questions --}}
            <div class="tab-pane fade show active" id="test_question_content-{{$student_outcome->id}}" role="tabpanel">
              <div class="">
                <label class="ml-3 mt-2">Select Course:</label>
                <div class="list-group">
                  @if($student_outcome->curriculumMaps->count() > 0)
                    @foreach ($student_outcome->curriculumMaps as $curriculum_map)
                      <a href="{{ url('/test_questions?student_outcome_id=' . $student_outcome->id . '&course_id=' . $curriculum_map->curriculumCourse->course->id . '&program_id=' . $student_outcome->program_id) }}" class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center justify-content-between">
                          <div class="d-flex align-items-center">
                            <div class="avatar mr-2" style="background: {{ $curriculum_map->curriculumCourse->course->color  }};">
                              <i class="fa fa-book"></i>
                            </div>
                            <div class="mr-3">
                              {{ $curriculum_map->curriculumCourse->course->course_code }} - {{ $curriculum_map->curriculumCourse->course->description }}
                            </div>
                            <div>
                              <span class="badge {{ $curriculum_map->testQuestionCount() > 0? 'badge-info' : 'badge-dark'  }}">{{ $curriculum_map->testQuestionCount() }} questions</span>
                            </div>
                          </div>

                          <div><i class="fa fa-chevron-right"></i></div>
                        </div>
                        
                      </a>
                    @endforeach
                  @else
                    <div class="list-group-item list-group-item-action">
                      No course mapped.
                    </div>
                  @endif

                </div>
              </div>
            </div>
            {{-- end test questios --}}


            {{-- exams --}}
            <div class="tab-pane fade" id="exam_content-{{$student_outcome->id}}" role="tabpanel">
              <label class="ml-3 mt-2">Select Curriculum:</label>
              {{-- <div class="list-group">
                @foreach($program->curricula as $curriculum)
                  <a href="{{ url('/exams?student_outcome_id=' . $student_outcome->id . '&program_id=' . $student_outcome->program_id . '&curriculum_id=' . $curriculum->id) }}" class="list-group-item list-group-item-action">
                    <div class="d-flex justify-content-between">
                      <div>
                        <i class="fa fa-book-open text-success"></i> #{{ $curriculum->id }} &mdash; {{ $curriculum->name }} - {{ $curriculum->year }} v{{ $curriculum->revision_no }}.0
                        <span class="badge badge-info">3 exams</span>
                      </div>
                      <div>
                        <i class="fa fa-chevron-right"></i>
                      </div>
                    </div>    
                  </a>
                @endforeach
              </div> --}}

              <table class="table table-borderless mx-2">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Revision No.</th>
                    <th>Year</th>
                    <th>Available Exams</th>
                    <th>Select</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($program->curricula as $curriculum)
                    <tr>
                      <td>
                        {{ $curriculum->id }}
                      </td>
                      <td>
                        {{ $curriculum->name }}
                      </td>
                      <td>
                        {{ $curriculum->revision_no }}
                      </td>
                      <td>
                        {{ $curriculum->year }}
                      </td>
                      <td>
                        <?php $count = $curriculum->countExam($student_outcome->id) ?>
                        <span class="badge {{ $count > 0 ? 'badge-info' : 'badge-dark' }}">{{ $count }} exam{{ $count > 1 ? 's' : '' }}</span>
                      </td>
                      <td>
                        <a href="{{ url('/exams?student_outcome_id=' . $student_outcome->id . '&program_id=' . $student_outcome->program_id . '&curriculum_id=' . $curriculum->id) }}" class="btn btn-sm btn-secondary">Select</a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            {{-- end exams --}}
          </div>
          
          

        </div>
      </div>
    @endforeach  
  </div>
@else
  <div class="text-center bg-white p-3">No Student Outcome Found in Database.</div>
@endif

  
@endsection