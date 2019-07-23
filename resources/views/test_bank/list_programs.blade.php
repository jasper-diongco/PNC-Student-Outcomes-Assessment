@extends('layout.app', ['active' => 'test_questions'])

@section('title', 'Test Question - Program listing')

@section('content')




<div class="card p-3 mb-3">
    

    <div class="d-flex justify-content-between mb-3">
      <div>
        <h1 class="page-header">Test Bank &mdash; Select Program</h1>
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

    <div class="mx-auto" style="width: 400px">
      <img src="{{ asset('svg/list.svg') }}" class="w-100">
    </div>
</div>



@if(count($programs) > 0) 
  {{-- <div class="list-group">
    
    @foreach($programs as $program)
      <a href="{{ url('/test_bank/' . $program->id . '/list_student_outcomes') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
      <div>
        {{ $program->program_code . ' - ' . $program->description }} 
        <br>
        <small class="text-muted">{{ $program->college->name }}</small>
      </div>
        <i class="fa fa-chevron-right"></i>
      </a>
    @endforeach  
  </div> --}}
  <div class="card">
    <div class="card-body">
      <h5 class="text-info">List of Programs</h5>
      <table class="table table-borderless">
        <thead>
          <tr>
            <th>ID</th>
            <th>Program Code</th>
            <th width="40%">Desciption</th>
            <th>Student Outcomes</th>
            <th>College</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($programs as $program)
            <tr>
              <td>{{ $program->id }}</td>
              <td>{{ $program->program_code }}</td>
              <td>{{ $program->description }}</td>
              <td>{{ count($program->studentOutcomes) }}</td>
              <td>{{ $program->college->college_code }}</td>
              <td><a href="{{ url('/test_bank/' . $program->id . '/list_student_outcomes') }}" class="btn btn-sm btn-info">Select</a></td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@else
  <div class="text-center bg-white p-3">No Program Found in Database.</div>
@endif

<div class="my-3 d-flex justify-content-end">
  {{ $programs->appends(request()->input())->links() }}
</div>

  
@endsection