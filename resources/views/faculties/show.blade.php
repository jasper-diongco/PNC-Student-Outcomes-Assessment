@extends('layout.app', ['active' => 'users'])

@section('title', $faculty->user->getFullName())

@section('content')
  <a href="{{ url('faculties') }}" class="text-success"><i class="fa fa-arrow-left"></i> Back</a>
  
  @if(Session::has('message'))
    @component('components.alert')
      {{ Session::get('message') }}
    @endcomponent
  @endif
  
  <div id="app">
  
    <div class="card mt-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Faculty Information</h4>
        
        @if(Gate::check('isDean') || Gate::check('isSAdmin'))
          <div class="d-flex">
            @if (!(Gate::check('isDean') && $faculty->user->user_type_id == 'dean'))
              <div>
                <form v-on:submit.prevent="deactivateUser" action="{{ url('/users/'. $faculty->user->id .'/deactivate') }}" method="post">
                  @csrf
                  <input type="hidden" name="user_type" value="faculty">
                  <button class="btn btn-dark btn-sm mr-2">Deactivate <i class="fa fa-user-slash"></i></button>
                </form>
              </div>
            @endif
            <div>
              <a href="{{ url('faculties/' . $faculty->id . '/edit') }}" class="btn btn-primary btn-sm">Update Information <i class="fa fa-edit"></i></a>
            </div>
          </div>
        @endif

      </div>

      <div class="card-body">
        <div><h5 class="text-success ml-2"><b>User Profile: </b></h5></div>
        <img src="{{ asset('img/user.svg') }}" alt="user-icon" style="width: 50px" class="mb-2 ml-2">
        <ul class="list-group list-group-flush">
          <li class="list-group-item"><b>Full Name:</b> {{ $faculty->user->getFullName()}} </li>
          <li class="list-group-item"><b>Sex:</b> {{ $faculty->user->sex == 'M' ? 'Male' : 'Female' }}</li>
          <li class="list-group-item"><b>Date of Birth:</b> {{ $faculty->user->date_of_birth }}</li>
          <li class="list-group-item"><b>Address:</b> {{ $faculty->user->address }}</li>
          <li class="list-group-item"><b>Contact No:</b> {{ $faculty->user->contact_no }}</li>
          <li class="list-group-item"><b>College:</b> {{ $faculty->college->name }}</li>
        </ul>
      </div>
    </div>
    <div class="card mt-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Account Information</h4>
        {{-- <a href="{{ url('faculties/' . $faculty->id . '/edit') }}" class="btn btn-primary">Update Account <i class="fa fa-edit"></i></a> --}}
      </div>
      <div class="card-body">
        <ul class="list-group list-group-flush">
          <li class="list-group-item"><b>Email:</b> {{ $faculty->user->email }} </li>
          <li class="list-group-item"><b>User Type:</b> {{ $faculty->user->userType->description }}</li>
        </ul>
      </div>
    </div>
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
        }
      }
    });
  </script>
@endpush