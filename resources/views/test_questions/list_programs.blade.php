@extends('layouts.sb_admin')

@section('title', 'Test Question - Program listing')

@section('content')


<div class="d-flex justify-content-between mb-3">
  <div>
    <h1 class="h3 mb-4 text-gray-800">Test Questions &mdash; Select Program</h1>
  </div>
  <div>
    {{-- <select class="form-control">
      <option value="">All</option>
      @foreach($colleges as $college)
        <option value="{{ $college->id }}" {{ request('college_id') == $college->id ? 'selected' : '' }}>{{ $college->college_code }}</option>
      @endforeach
    </select> --}}
  </div>
</div>



@if(count($programs) > 0) 
  <div class="list-group">
    
    @foreach($programs as $program)
      <a href="{{ url('/test_questions/' . $program->id . '/list_student_outcomes') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
      <div>
        {{ $program->program_code . ' - ' . $program->description }} 
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