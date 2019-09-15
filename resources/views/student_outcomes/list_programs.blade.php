@extends('layout.app', ['active' => 'student_outcomes'])

@section('title', 'Student Outcomes - Program listing')

@section('content')

<div class="card mb-4">
  <div class="card-body pt-4">
    <h1 class="page-header mb-0"><i class="fa fa-flag" style="color: #a1a1a1"></i> Student Outcomes</h1>
  </div>
</div>


@if($programs->count() > 0)
  <h5 class="text-dark mb-3">Select Program</h5>
  <div class="d-flex flex-wrap">
      @foreach($programs as $program)
        <div class="card shadow mb-4 mr-3 w-md-31">
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
                          <i class="fa fa-search"></i> View
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

  
@endsection