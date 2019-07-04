@extends('layout.app', ['active' => 'colleges'])

@section('title', $college->college_code)

@section('content')
  <a href="{{ url('colleges') }}" class="text-success"><i class="fa fa-arrow-left"></i> Back</a>
  
  {{-- @if(Session::has('message'))
    @component('components.alert')
      {{ Session::get('message') }}
    @endcomponent
  @endif --}}

  <div id="app" v-cloak>
    <college-modal :is-update="true" college-id="{{ $college->id }}" v-on:update-college="getCollege"></college-modal>
    <h1 class="page-header mt-3">{{ $college->name }}</h1>

    <div class="card">
      
      <div class="card-body pt-4">
        
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h5 class="text-info ml-2">Details</h5>
          {{-- <a href="{{ url('colleges/' . $college->id . '/edit') }}" class="btn btn-success btn-sm">Edit <i class="fa fa-edit"></i></a> --}}
          <button class="btn btn-success btn-sm mb-2" data-toggle="modal" data-target="#collegeModalUpdate">Update College <i class="fa fa-edit"></i></button>
        </div>
        

        <ul class="list-group list-group-flush">
          <li class="list-group-item"><label>Dean:</label>
            @{{ college.faculty.user.last_name + ', ' + college.faculty.user.first_name + ' ' + college.faculty.user.middle_name }}
            
          </li>
          <li class="list-group-item"><label>College Code:</label> @{{ college.college_code }}</li>
          <li class="list-group-item"><label>College Name:</label> @{{ college.name }}</li>
        </ul>
      </div>
    </div>


    <h4 class="mb-3 mt-5">List of Programs <i class="fa fa-graduation-cap text-primary"></i></h4>

      @if(count($college->programs) > 0) 
      <div class="list-group">
        
        @foreach($college->programs as $program)
          <a href="{{ url('/programs/' . $program->id) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
            <div class="d-flex justify-content-between align-items-center mr-3">
              <div>
                <div class="avatar mr-2" style="background: {{ $program->color  }}">
                  {{ substr($program->program_code, 0 , 2) == 'BS' ? substr($program->program_code, 2) :  $program->program_code }}
                </div>
              </div>
              <span>{{ $program->description }}</span></div>
            <div>
              <i class="fa fa-chevron-right"></i>
            </div>
          </a>
        @endforeach  
      </div>
    @else
      <div class="text-center bg-white p-3">No Program Found in Database.</div>
    @endif

  </div>
@endsection

@push('scripts')
  <script>
    new Vue({
      el: '#app',
      data: {
        college: @json($college)
      },
      methods: {
        getCollege(college) {
          this.college = college;
        }
      }
    });
  </script>
@endpush