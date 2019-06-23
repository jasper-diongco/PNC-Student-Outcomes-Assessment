@extends('layout.app', ['active' => 'test_questions'])

@section('title', 'Test Questions')

@section('content')

<a href="{{ url('/test_bank/list_programs') }}" class="text-success"><i class="fa fa-arrow-left"></i> Back</a>

<div class="d-flex justify-content-between mb-3">
  <div>
    <h1 class="page-header mt-3">Test Bank &mdash; List of Student Outcomes</h1>
  </div>
</div>

@if(count($program->studentOutcomes) > 0) 
  <div class="list-group">
    
    @foreach($program->studentOutcomes as $student_outcome)
      {{-- <a href="{{ url('/test_questions?program_id=' . $program->id) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
      <div>
        {{ $program->program_code . ' - ' . $program->description }} 
        <br>
        <small class="text-muted">{{ $program->college->name }}</small>
      </div>
        <i class="fa fa-chevron-right"></i>
      </a> --}}
      
      <div class="card mb-2">
        <!-- Card Header - Accordion -->
        <a href="#so_{{$student_outcome->id}}" class="d-block card-header py-3 so_collapse" data-toggle="collapse" role="button">
          <div class="d-flex justify-content-between">
            <h6 class="m-0 font-weight-bold so_collapse mr-5">{{ $student_outcome->so_code }} &mdash; {{ $student_outcome->description }}</h6>


            
          </div>
          
        </a>
        
        <!-- Card Content - Collapse -->
        <div class="collapse show" id="so_{{$student_outcome->id}}">
          <div class="d-flex justify-content-between pt-2">
            <div class="pl-2"><label>Test Questions:</label></div> 
            <div>
              <a href="{{ url('/exams/?student_outcome=' . $student_outcome->id) }}" class="btn btn-sm btn-info mr-3 mb-2"><i class="fa fa-file-alt"></i> Exams <i class="fa fa-caret-right"></i> </a>
            </div>
          </div>
          
          
          <div class="">
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
                          <span class="badge {{ $curriculum_map->testQuestionCount() > 0? 'badge-success' : 'badge-dark'  }}">{{ $curriculum_map->testQuestionCount() }} questions</span>
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
      </div>
    @endforeach  
  </div>
@else
  <div class="text-center bg-white p-3">No Student Outcome Found in Database.</div>
@endif

{{-- <div class="my-3 d-flex justify-content-end">
  {{ $programs->appends(request()->input())->links() }}
</div> --}}

  
@endsection