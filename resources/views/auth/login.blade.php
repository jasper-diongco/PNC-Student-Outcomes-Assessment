@extends('layout.app', ['hide_header' => true, 'dark_bg' => true, 'hide_footer' => true, 'hide_navigation' => true])

@section('title', 'Login')

@section('content')
{{-- <div class="container">
    <div class="">
        <div class="col-xs-12 col-md-6 mx-auto">
            <div class="card mt-5">
                <div class="card-header"><h2 class="text-center">{{ __('Login to your account') }}</h2></div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Email') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
</div> --}}


<!-- Outer Row -->
{{-- <div class="row">
    
  <div class="col-md-6 offset-md-3">

    
    <div class="card mt-2">
        <div class="card-body">
            <div class="pt-2 p-4">
              <div class="text-center">
                <h1 class="page-header">Login to your account</h1>
              </div>
              <form class="user" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                   <label for="email">Email</label>
                  <input 
                    id="email" 
                    type="email" 
                    class="form-control form-control-user @error('email') is-invalid @enderror" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    autocomplete="off" 
                    autofocus
                    placeholder="Enter your email">

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                  <input 
                    id="password" 
                    type="password" 
                    class="form-control form-control-user @error('password') is-invalid @enderror" 
                    name="password" 
                    required 
                    autocomplete="current-password"
                    placeholder="Enter your password">

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                </div>
                <div class="form-group">
                  <div class="custom-control custom-checkbox small">
                    <input type="checkbox" class="custom-control-input" id="remember" {{ old('remember') ? 'checked' : '' }} name="remember">
                    <label class="custom-control-label" for="remember">Remember Me</label>
                  </div>
                </div>

                <button type="submit" class="btn btn-success btn-user btn-block">
                    Login
                </button>
              </form>
            </div>
        </div>
    </div>

  </div>

</div> --}}

<div id="app">
    {{-- <img src="{{ asset('img/pnc_logo_name_small.png') }}" width="200px"> --}}
    <div style="width: 130px" class="mb-3">
            <img src="{{ asset('img/pnc_logo_name_small.png') }}" style="width: 100%"> 
        </div>
    <div class="text-center mt-3" style="color:#11c26d; font-size: 23px;">PNC <span style="color: #908e8e; font-weight: 300">| Student Outcomes Assessment</span></div>

    <div class="row mt-3">
        {{-- <div class="col-md-7">
            <img src="{{ asset('svg/authentication.svg') }}"  class="w-75 mt-5">
        </div>  --}}
        <div class="col-md-8 col-lg-5 mx-auto">
            <div class="card shadow">
                
                <div class="card-body pt-5 pb-5 px-5">
                    
                    
                    <form v-on:submit.prevent="login" action="{{ route('login') }}" method="POST">
                        <div class="d-flex justify-content-center">
                            <i class="fa fa-user-circle text-success" style="font-size: 50px"></i>
                        </div>
                        <h1 class="login-title mt-1 mb-4 text-center" style="font-size: 25px;"> Login to your account</h1>
                        <div id="input-g-email" class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><i class="fa fa-user"></i></span>
                          </div>
                          <input v-model="email" type="text" class="form-control" :class="{ 'is-invalid': emailError || usernameErr }" placeholder="Enter your username or email" aria-describedby="basic-addon1">
                          <div class="invalid-feedback ml-5">@{{ emailError || usernameErr }}</div>
                        </div>
                        <div id="input-g-password" class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon2"><i class="fa fa-key"></i></span>
                          </div>
                          <input v-model="password" type="password" class="form-control" :class="{ 'is-invalid': passwordError }" placeholder="Enter your password" aria-label="Enter your password" aria-describedby="basic-addon2">
                          <div class="invalid-feedback ml-5">@{{ passwordError }}</div>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            <button id="btn-login" type="submit" class="btn btn-success" :disabled="isLoading">
                                    <div v-if="isLoading" class="spinner-border spinner-border-sm text-light" role="status">
                                      <span class="sr-only">Loading...</span>
                                    </div>
                                    Login 
                                    <img style="width: 25px; height: 25px;" src="{{ asset('img/login_btn_1.svg') }}" alt="login button icon">
                            </button>
                        </div>
                        
                    </form>
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
                email: '',
                password: '',
                login_str: '',
                isError: false,
                isLoading: false,
                emailError: null,
                passwordError: null,
                usernameErr: null
            },
            methods: {
                login() {
                    this.isLoading = true;
                    this.emailError = null;
                    this.usernameErr = null;
                    this.passwordError = null;
                    this.login_str = this.email;
                    ApiClient.post('/login', {
                        email: this.email,
                        login_str: this.login_str,
                        password: this.password
                    })
                    .then(response => {
                        //this.isLoading = false;
                        if(response.data.auth) {
                            window.location.replace(response.data.intended);
                        }
                    })
                    .catch(error => {
                        var response = error.response.data;
                        this.isLoading = false;
                        if(response.errors.password) {
                           this.passwordError = response.errors.password[0]; 
                        }
                        if(response.errors.email) {
                            this.emailError = response.errors.email[0];
                        }
                        if(response.errors.username) {
                            this.usernameErr = response.errors.username[0];
                        }
                        
                        //console.log(response);
                    });
                }
            }
        });
    </script>
@endpush
