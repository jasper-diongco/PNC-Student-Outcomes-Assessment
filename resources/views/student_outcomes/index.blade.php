@extends('layouts.sb_admin')

@section('title', 'Student Outcomes - ' . $program->description)

@section('content')

<a href="{{ url('/student_outcomes/list_program?college_id='. Session::get('college_id')) }}" class="text-success"><i class="fa fa-arrow-left"></i> Back</a>

<div id="app" class="mt-3">

  <div class="d-flex justify-content-between mb-3">
    <div>
      <h1 class="h4 mb-4 text-gray-800">Student Outcomes &mdash; {{ $program->program_code }}</h1>
    </div>
    <div>
      @if(Gate::check('isDean') || Gate::check('isSAdmin'))
        <student-outcome-modal :programs='@json($programs)' :program-id="{{ $program->id }}"></student-outcome-modal>
      @endif
    </div>
  </div>


  @if(count($program->studentOutcomes) > 0) 
    <div class="list-group">
      
      @foreach($program->studentOutcomes as $student_outcome)
        <a href="{{ url('/student_outcomes/' . $student_outcome->id . '?program_id=' . request('program_id')) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
          <div class="d-flex justify-content-between align-items-center mr-3">
            <div>
              <span class="avatar-student-outcome mr-3">{{ $student_outcome->so_code }}</span>
            </div>
            <span>{{ $student_outcome->description }}</span></div>
          <div>
            <i class="fa fa-chevron-right"></i>
          </div>
        </a>
      @endforeach  
    </div>
  @else
    <div class="text-center bg-white p-3">No Student Outcome Found in Database.</div>
  @endif

</div>

  
@endsection

@push('scripts')
  <script>
    new Vue({
      el: '#app'
    });
  </script>
@endpush
