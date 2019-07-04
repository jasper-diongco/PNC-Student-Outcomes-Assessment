@extends('layout.app', ['active' => 'courses'])

@section('title', $course->course_code)

@section('content')
  <a href="{{ url('/courses') }}" class="text-success"><i class="fa fa-arrow-left"></i> Back</a>

  <div id="app">

    <div class="card mt-4">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center justify-content-start mb-2">
            {{-- <div class="avatar-course mr-2" style="background: {{ $course->color  }}">
              {{ $course->course_code }}
            </div> --}}
            <div>
              <h4 class="card-title my-0"><i class="fa fa-book text-info"></i> {{ $course->description }}</h4>
            </div>
          </div>

          <!-- COURSE MODAL -->
          @if(Gate::check('isDean') || Gate::check('isSAdmin'))
            <course-modal college-id="{{ Session::get('college_id') }}" :colleges='@json($colleges)' :is-update="true" :course-prop='@json($course)'></course-modal>
          @endif
          <!-- END COURSE MODAL -->

        </div>
        
        {{-- <p class="card-text mt-3">{{ $course->description }}</p>  --}} 
        <h5 class="mt-3">Details</h5>    
        <ul class="list-group list-group-flush">
          <li class="list-group-item">
            <div class="row">
              <div class="col-md-2 text-md-left"><label>Course Code: </label></div>
              <div class="col-md-9">{{ $course->course_code }}</div>
            </div>
          </li>
          <li class="list-group-item">
            <div class="row">
              <div class="col-md-2 text-md-left"><label>Description: </label></div>
              <div class="col-md-9">{{ $course->description }}</div>
            </div>
          </li>
          <li class="list-group-item">
            <div class="row">
              <div class="col-md-2 text-md-left"><label>College: </label></div>
              <div class="col-md-9">{{ $course->college->name }}</div>
            </div>
          </li>
          <li class="list-group-item">
            <div class="row">
              <div class="col-md-2 text-md-left"><label>Lecture Unit: </label></div>
              <div class="col-md-9">{{ $course->lec_unit }}</div>
            </div>
          </li>
          <li class="list-group-item">
            <div class="row">
              <div class="col-md-2 text-md-left"><label>Laboratory Unit: </label></div>
              <div class="col-md-9">{{ $course->lab_unit }}</div>
            </div>
          </li>
          <li class="list-group-item">
            <div class="row">
              <div class="col-md-2 text-md-left"><label>Date Created: </label></div>
              <div class="col-md-9">{{ $course->created_at->format('M d, Y') }}</div>
            </div>
          </li>
{{--           <li class="list-group-item">
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
          </li> --}}
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