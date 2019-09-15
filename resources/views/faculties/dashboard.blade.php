@extends('layout.app', ['active' => 'dashboard'])

@section('title', 'Professor Dashboard')

@section('content')
  
  
  @if(Session::has('message'))
    @component('components.alert')
      {{ Session::get('message') }}
    @endcomponent
  @endif
  <div id="app">
    @if (!$password_changed)
      <account-modal email="{{ Auth::user()->email }}" user_id="{{ Auth::user()->id }}"></account-modal>
    @endif

    <h1 class="page-header">Home</h1>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <strong>Hello Jasper!</strong> Welcome back.
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>


    <div class="card mb-3">
      <div class="card-body py-3">
          <h5><i class="fa fa-book text-info"></i> My Courses</h5>
      </div>
    </div>

     @if($faculty_courses->count() > 0)
          <div class="d-block d-md-flex flex-wrap">
              @foreach($faculty_courses as $faculty_course)
                <div class="card shadow mb-4 mr-3 w-md-31">
                    <div class="card-body pt-3">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <div class="d-flex">
                                <div class="mr-2">
                                    <div class="avatar" style="background: #b3ea74; color:#585858;"><i class="fa fa-book"></i></div>
                                </div>
                                <div style="font-weight: 600">{{ $faculty_course->course->course_code }}</div>
                            </div>
                            
                        </div>
                        <div style="font-size: 13px" class="text-muted ml-2 mt-2">
                            {{ $faculty_course->course->description }}
                        </div>
                        <hr>
                        <div class="text-muted">
                           <i class="fa fa-database text-info"></i> {{ $faculty_course->course->testQuestions->count() }} Test Questions
                        </div>
                        <div class="mt-2 text-muted">
                          <i class="fa fa-question-circle text-success"></i> You have added {{ $faculty_course->getFacultyCourseTestQuestions()->count() }} questions for this course
                        </div>
                    </div>

                    <div class="card-footer">
                      <div class="d-flex justify-content-end">
                          <a class="btn btn-sm btn-info" href="#" class="btn btn-sm">
                              View test questions <i class="fa fa-angle-right"></i>
                          </a>
                        </div>
                    </div>
                </div>
              @endforeach
          </div>
        @else
          <div class="p-3 bg-white text-muted">
            <h5>You have no Assigned Courses :(</h5>
          </div>
        @endif

    

      



        

  </div>
@endsection

@push('scripts')
  <script>
    new Vue({
      el: '#app',
      data: {
      },
      methods: {
      }
    });
  </script>
  
  @if (!$password_changed)
    <script>
      $(document).ready(function() {
        $('#accountModal').modal('show');
      });
    </script>
  @endif
@endpush