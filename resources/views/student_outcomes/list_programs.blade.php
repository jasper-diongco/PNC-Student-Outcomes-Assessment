@extends('layouts.sb_admin')

@section('title', 'Student Outcomes - College listing')

@section('content')


<div class="d-flex justify-content-between mb-3">
  <div>
    <h1 class="h3 mb-4 text-gray-800">Student Outcomes &mdash; Select Program</h1>
  </div>
</div>

@if(count($programs) > 0) 
  <div class="list-group">
    
    @foreach($programs as $program)
      <a href="{{ url('/student_outcomes?program_id=' . $program->id) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
      <div>
        {{ $program->program_code . ' - ' . $program->description }} 
        <span class="badge badge-warning">{{ count($program->studentOutcomes) }}</span>
        <br>
        <small class="text-muted">{{ $program->college->name }}</small>
      </div>
        <i class="fa fa-chevron-right"></i>
      </a>
    @endforeach  
  </div>
@else
  <div class="text-center bg-white p-3">No Program Found in Database.</div>
@endif

<div class="my-3 d-flex justify-content-end">
  {{ $programs->appends(request()->input())->links() }}
</div>

  
@endsection