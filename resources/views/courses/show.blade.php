@extends('layouts.sb_admin')

@section('title', $course->course_code)

@section('content')
  <a href="{{ url('/courses') }}" class="text-success"><i class="fa fa-arrow-left"></i> Back</a>

  <div id="app">

    <div class="card mt-4">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center justify-content-start mb-2">
            <div class="avatar-course mr-2" style="background: {{ $course->color  }}">
              {{ $course->course_code }}
            </div>
            <div>
              <h4 class="card-title my-0">{{ $course->description }}</h4>
            </div>
          </div>

          <!-- COURSE MODAL -->
          @if(Gate::check('isDean') || Gate::check('isSAdmin'))
            <course-modal college-id="{{ Session::get('college_id') }}" :colleges='@json($colleges)' :is-update="true" :course-prop='@json($course)'></course-modal>
          @endif
          <!-- END COURSE MODAL -->

        </div>
        <small>created at {{ $course->created_at }}</small>
        {{-- <p class="card-text mt-3">{{ $course->description }}</p>  --}} 
        <h4 class="mt-3"><b>Details</b></h4>    
        <ul class="list-group list-group-flush">
          <li class="list-group-item">
            <div class="row">
              <div class="col-md-2 text-md-left"><b>Course Code: </b></div>
              <div class="col-md-9">{{ $course->course_code }}</div>
            </div>
          </li>
          <li class="list-group-item">
            <div class="row">
              <div class="col-md-2 text-md-left"><b>Description: </b></div>
              <div class="col-md-9">{{ $course->description }}</div>
            </div>
          </li>
          <li class="list-group-item">
            <div class="row">
              <div class="col-md-2 text-md-left"><b>College: </b></div>
              <div class="col-md-9">{{ $course->college->name }}</div>
            </div>
          </li>
          <li class="list-group-item">
            <div class="row">
              <div class="col-md-2 text-md-left"><b>Lecture Unit: </b></div>
              <div class="col-md-9">{{ $course->lec_unit }}</div>
            </div>
          </li>
          <li class="list-group-item">
            <div class="row">
              <div class="col-md-2 text-md-left"><b>Laboratory Unit: </b></div>
              <div class="col-md-9">{{ $course->lab_unit }}</div>
            </div>
          </li>
          <li class="list-group-item">
            <div class="row">
              <div class="col-md-2 text-md-left"><b>Privacy: </b></div>
              <div class="col-md-9">
                @if ($course->is_public)
                  <span class="badge badge-success">public <i class="fa fa-globe-americas"></i></span>
                @else
                  <span class="badge badge-secondary">private <i class="fa fa-lock"></i></span></td>
                @endif
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
@endsection

@push('scripts')

<script>
  new Vue({
    el: '#app'
  });
</script>


@if(Session::has('message'))
  <script>
    toast.fire({
      type: 'success',
      title: '{{ Session::get('message') }}'
    })
  </script>
@endif

@endpush