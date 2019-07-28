@extends('layout.app', ['active' => 'users'])

@section('title', $faculty->user->getFullName())

@section('content')
  <a href="{{ url('faculties') }}" class="text-success"><i class="fa fa-arrow-left"></i> Back</a>
  
  {{-- @if(Session::has('message'))
    @component('components.alert')
      {{ Session::get('message') }}
    @endcomponent
  @endif --}}

  
  
  <div id="app">
    <faculty-modal is-dean="{{ Gate::check('isDean') ? 'true' : 'false' }}" college-id="{{ Session::get('college_id') }}" :colleges='@json($colleges)' is-update="true" :faculty-id="{{ $faculty->id }}" :refresh-update="true"></faculty-modal>
    <div class="card mt-4" >

      <div class="card-body pt-4">
        {{-- <h1 class="page-header text-info">Faculty Information</h1> --}}
        <div class="d-flex justify-content-between">
          <div>
            <h4><i class="fa fa-user text-info"></i> {{ $faculty->user->getFullName()}}</h4>
          </div>
          @if(Gate::check('isDean') || Gate::check('isSAdmin'))
            <div class="d-flex">
              @if (!(Gate::check('isDean') && $faculty->user->user_type_id == 'dean'))
                <div>
                  <form v-on:submit.prevent="deactivateUser" action="{{ url('/users/'. $faculty->user->id .'/deactivate') }}" method="post">
                    @csrf
                    <input type="hidden" name="user_type" value="faculty">
                    <button class="btn btn-secondary btn-sm mr-2">Deactivate <i class="fa fa-user-slash"></i></button>
                  </form>
                </div>
              @endif
              <div>
                {{-- <a href="{{ url('faculties/' . $faculty->id . '/edit') }}" class="btn btn-success btn-sm">Update Information <i class="fa fa-edit"></i></a> --}}
                <button data-toggle="modal" data-target="#facultyModalUpdate" class="btn btn-success btn-sm">Update Information <i class="fa fa-edit"></i></button>
              </div>
            </div>
          @endif
        </div>
        
        <label class="text-info mt-3">Details</label>
        <ul class="list-group list-group-flush">
          <li class="list-group-item"><label>Full Name:</label> {{ $faculty->user->getFullName()}} </li>
          <li class="list-group-item"><label>Last Name:</label> {{ $faculty->user->last_name}} </li>
          <li class="list-group-item"><label>First Name:</label> {{ $faculty->user->first}} </li>
          <li class="list-group-item"><label>Middle Name:</label> {{ $faculty->user->middle_name}} </li>
          <li class="list-group-item"><label>Sex:</label> {{ $faculty->user->sex == 'M' ? 'Male' : 'Female' }}</li>
          <li class="list-group-item"><label>Date of Birth:</label> @{{ dateOfBirth() }}</li>
          <li class="list-group-item"><label>College:</label> {{ $faculty->college->name }}</li>
          <li class="list-group-item"><label>Email:</label> {{ $faculty->user->email }} </li>
          <li class="list-group-item"><label>User Type:</label> {{ $faculty->user->userType->description }}</li>
        </ul>
      </div>
    </div>
{{--     <div class="card mt-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Account Information</h4>
      </div>
      <div class="card-body">
        <ul class="list-group list-group-flush">
          <li class="list-group-item"><b>Email:</b> {{ $faculty->user->email }} </li>
          <li class="list-group-item"><b>User Type:</b> {{ $faculty->user->userType->description }}</li>
        </ul>
      </div>
    </div> --}}
  </div>
@endsection

@push('scripts')
  <script>
    new Vue({
      el: '#app',
      data: {

      },
      methods: {
        deactivateUser(event) {
          swal.fire({
            title: 'Do you want to deactivate this user?',
            text: "Please confirm",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#1cc88a',
            cancelButtonColor: '#858796',
            confirmButtonText: 'Yes',
            width: '350px'
          }).then((result) => {
            if (result.value) {
              event.target.submit();
            }
          });
        },
        parseDate(date) {
          return moment(date).format("MMM D YYYY");  
        },
        dateOfBirth() {
          return this.parseDate('{{ $faculty->user->date_of_birth  }}');
        }
      }
    });
  </script>
@endpush