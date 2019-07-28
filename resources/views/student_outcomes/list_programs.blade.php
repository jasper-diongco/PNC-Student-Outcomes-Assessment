@extends('layout.app', ['active' => 'student_outcomes'])

@section('title', 'Student Outcomes - Program listing')

@section('content')

<div class="d-flex justify-content-between mb-3">

  

</div>

@if(count($programs) > 0) 
  {{-- <div class="list-group">
    
    @foreach($programs as $program)
      <a href="{{ url('/student_outcomes?program_id=' . $program->id) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
      <div>
        {{ $program->program_code . ' - ' . $program->description }} 
        <span class="badge badge-success">{{ count($program->studentOutcomes) }}</span>
        <br>
        <small class="text-muted">{{ $program->college->name }}</small>
      </div>
        <i class="fa fa-chevron-right"></i>
      </a>
    @endforeach  
  </div> --}}
{{--   <div class="card">
    <div class="card-body">
      <div>
        <h1 class="page-header"><i class="fa fa-flag" style="color: #a1a1a1"></i> Student Outcomes</h1>
      </div>
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
              <td><a href="{{ url('/student_outcomes?program_id=' . $program->id) }}" class="btn btn-sm btn-info">View <i class="fa fa-search"></i></a></td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div> --}}
@else
  <div class="text-center bg-white p-3">No Program Found in Database.</div>
@endif

<h1 class="page-header"><i class="fa fa-flag" style="color: #a1a1a1"></i> Student Outcomes</h1>

@if($programs->count() > 0)
  <h5 class="text-dark mb-3">Select Program</h5>
  <div class="d-flex flex-wrap">
      @foreach($programs as $program)
        <div style="width: 31%" class="card shadow mb-4 mr-3">
            <div class="card-body pt-3">
                <div class="d-flex justify-content-between align-items-baseline">
                    <div class="d-flex">
                        <div class="mr-2">
                            <div class="avatar" style="background: #cbff90; color:#585858;"><i class="fa fa-graduation-cap"></i></div>
                        </div>
                        <div style="font-weight: 600">{{ $program->program_code }}</div>
                    </div>
                    <div class="ml-3">
                      <a class="btn btn-sm btn-info" href="{{ url('/student_outcomes?program_id=' . $program->id) }}"class="btn btn-sm">
                          <i class="fa fa-search"></i> Select
                      </a>
                    </div>
                </div>
                <div style="font-size: 13px" class="text-muted ml-2 mt-2">
                    {{ $program->college->name }}
                </div>
                <hr>
                <div class="text-muted">
                    {{ $program->description }}
                </div>
            </div>
        </div>
      @endforeach
  </div>
@else
  <div class="p-3 bg-white text-muted">
    No Program found.
  </div>
@endif

<div class="my-3 d-flex justify-content-end">
  {{ $programs->appends(request()->input())->links() }}
</div>

<div class="my-3 d-flex justify-content-end">
  {{ $programs->appends(request()->input())->links() }}
</div>

  
@endsection