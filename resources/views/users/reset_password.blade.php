@extends('layouts.sb_admin')

@section('title', 'Reset Password')

@section('content')


<div id="app">
  
  <div class="d-flex justify-content-between mb-2">
    <div>
      <h1 class="h3 text-gray-800">Reset Password</h1>
    </div>
  </div>
  
  <div class="row">
    <div class="col-md-8 offset-2">

      @if (Session::has('message'))
        <div class="alert alert-success" role="alert">
          <h4 class="alert-heading">Password successfully reset!</h4>
          <p>{!! Session::get('message') !!}</p>
          <hr>
          <p class="mb-0">Please make sure to change your password immediately after you logged in.</p>
        </div>
      @endif
      
      <form action="{{ url('users/reset_password') }}" method="get">
        <div class="form-group row">
          <div class="col-md-10">
            <input 
              type="email" 
              name="email" 
              class="form-control form-control-lg" 
              placeholder="Enter your email..."
              value="{{ request('email') }}" 
              autocomplete="off" 
              required>
          </div>
          <div class="Col-md-2">
            <button class="btn btn-primary btn-lg">Search <i class="fa fa-search"></i></button>
          </div>
        </div>
      </form>
      
      @if (request('email') != '')
        <div class="card shadow">
          <div class="card-body">
            
            @if ($user == null) 
              <div>
                <h4 class="text-center">No User Found in Database.</h4>
              </div>
            @else 
              <div><h5 class="text-success"><b>User Found: </b></h5></div>
              <img src="{{ asset('img/user.svg') }}" alt="user-icon" style="width: 50px" class="mb-2">
              <div>
                <label><b>Full Name:</b></label>
                <span class="ml-2">{{ $user->getFullName() }}</span>
              </div>
              <div>
                <label><b>Email:</b></label>
                <span class="ml-2">{{ $user->email }}</span>
              </div>
              <div>
                <label><b>User Type:</b></label>
                <span class="ml-2">{{ $user->userType->description }}</span>
              </div>
              
              @if (request('reset') != 'successful')
                <form action="{{ url('/users/' . $user->id . '/reset_password') }}" method="POST">
                  @csrf
                  <button class="btn btn-primary">Reset Password</button>
                </form>
              @endif
              
            @endif


            
            
            
          </div>
        </div>
      @endif
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
@endpush
