@extends('layout.app')

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
<div class="row">
    
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

</div>
@endsection
