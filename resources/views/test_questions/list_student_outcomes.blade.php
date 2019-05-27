@extends('layouts.sb_admin')

@section('title', 'Test Questions')

@section('content')

<a href="{{ url('/curriculum_mapping') }}" class="text-success"><i class="fa fa-arrow-left"></i> Back</a>

<div class="d-flex justify-content-between mb-3">
  <div>
    <h1 class="h3 text-gray-800 my-3">Test Questions &mdash; List of Student Outcomes</h1>
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
        <a href="#so_{{$student_outcome->id}}" class="d-block card-header py-3" data-toggle="collapse" role="button">
          <h6 class="m-0 font-weight-bold text-success">{{ $student_outcome->description }}</h6>
        </a>
        <!-- Card Content - Collapse -->
        <div class="collapse show" id="so_{{$student_outcome->id}}">
          <div class="">
            <div class="list-group">
              <a href="#" class="list-group-item list-group-item-action">
                Cras justo odio
              </a>
              <a href="#" class="list-group-item list-group-item-action">Dapibus ac facilisis in</a>
              <a href="#" class="list-group-item list-group-item-action">Morbi leo risus</a>
              <a href="#" class="list-group-item list-group-item-action">Porta ac consectetur ac</a>
              <a href="#" class="list-group-item list-group-item-action disabled" tabindex="-1" aria-disabled="true">Vestibulum at eros</a>
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