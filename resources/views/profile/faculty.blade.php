@extends('layouts.pnc_layout')

@section('title', $faculty->user->getFullName())

@section('content')
  
  @if(Session::has('message'))
    @component('components.alert')
      {{ Session::get('message') }}
    @endcomponent
  @endif

  <div id="app">
    <div class="card mt-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h4>My Information</h4>
        <a href="{{ url('faculties/' . $faculty->id . '/edit') }}" class="btn btn-primary">Update Information <i class="fa fa-edit"></i></a>
      </div>
      <div class="card-body">
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
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#accountModal">
          Update Account <i class="fa fa-edit"></i>
        </button>
      </div>
      <div class="card-body">
        <ul class="list-group list-group-flush">
          <li class="list-group-item"><b>Email:</b> @{{ email }} </li>
          <li class="list-group-item"><b>User Type:</b> {{ $faculty->user->userType->description }}</li>
        </ul>
      </div>
    </div>


    <!-- Account Modal -->
    <div
      class="modal fade"
      id="accountModal"
      tabindex="-1"
      role="dialog"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Update Account</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">

          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fa fa-envelope"></i></span>
            </div>
            <input 
              autocomplete="off" 
              type="email" 
              class="form-control" 
              placeholder="Email"
              name="email"
              v-model="email_edit"
              v-validate="'required|email'"
              :class="{ 'is-invalid' : errors.has('email') }" >
              <div class="invalid-feedback">@{{ errors.first('email') }}</div>
          </div>


          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fa fa-key"></i></span>
            </div>
            <input 
              type="password" 
              class="form-control" 
              placeholder="New Password"
              name="password"
              v-model="password"
              v-validate="'required|min:8'"
              :class="{ 'is-invalid' : errors.has('password') }"
              ref="password" >
              <div class="invalid-feedback">@{{ errors.first('password') }}</div>
          </div>



          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fa fa-key"></i></span>
            </div>
            <input 
              type="password" 
              class="form-control" 
              placeholder="Confirm Password"
              v-model="confirm_password"
              name="confirm_password"
              v-validate="'required|confirmed:password'"
              :class="{ 'is-invalid' : errors.has('confirm_password') }" >
              <div class="invalid-feedback">@{{ errors.first('confirm_password') }}</div>
          </div>




          </div>
          <div class="modal-footer">
            <button v-on:click="closeModal" type="button" class="btn btn-secondary" data-dismiss="modal" :disabled="btnLoading">Close</button>
            <button type="button" class="btn btn-success" v-on:click="updateAccount" :disabled="btnLoading">
              Save changes 
              <div v-show="btnLoading" class="spinner-border spinner-border-sm text-light" role="status">
                <span class="sr-only">Loading...</span>
              </div>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    new Vue({
      el: '#app',
      data: {
        faculty_id: '{{ $faculty->id }}',
        email: '{{ $faculty->user->email }}',
        password: '',
        confirm_password: '',
        email_edit: '{{ $faculty->user->email }}',
        btnLoading: false 
      },
      methods: {
        closeModal() {
          this.$validator.reset();
          this.password = '';
          this.confirm_password = '';
        },
        updateAccount() {
          this.$validator.validateAll()
          .then(isValid => {
            
            if(isValid) {
              this.btnLoading = true;
              ApiClient.put('/faculties/' + this.faculty_id + '/update_account', {
                id: this.faculty_id,
                email: this.email_edit,
                password: this.password,
                confirm_password: this.confirm_password
              }).
              then(response => {
                this.btnLoading = false;
                if(response.statusText == 'OK') {
                  this.email = response.data.email;
                  toast.fire({
                    type: 'success',
                    title: 'Account Successfully Updated.'
                  });
                  $('#accountModal').modal('hide');
                }
              });
            } else {
              toast.fire({
                type: 'error',
                title: 'Please enter valid data.'
              });
            }
          });
          
        }
      }
    });
  </script>
@endpush