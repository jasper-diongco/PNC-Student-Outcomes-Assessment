@extends('layouts.master')

@section('title', $college->college_code)

@section('content')
  
  
  @if(Session::has('message'))
    @component('components.alert')
      {{ Session::get('message') }}
    @endcomponent
  @endif

  <h1 class="h3 mt-4 mb-3">{{ $college->name }}</h1>

  {{-- <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4>Details</h4>
      <a href="{{ url('colleges/' . $college->id . '/edit') }}" class="btn btn-light">Edit <i class="fa fa-edit"></i></a>
    </div>
    <div class="card-body">
      <ul class="list-group list-group-flush">
        <li class="list-group-item"><b>Dean:</b>
          <a href="{{ url('faculties/' . $college->faculty->id) }}">{{ $college->faculty->user->getFullName() }}</a>
          
        </li>
        <li class="list-group-item"><b>College Code:</b> {{ $college->college_code }}</li>
        <li class="list-group-item"><b>College Name:</b> {{ $college->name }}</li>
      </ul>
    </div>
  </div> --}}
  
  <div class="row">
    <div class="col-md-6">
      <div class="card text-dark bg-white mb-3">
        <div class="card-header"><h3 class="h4">Programs</h3></div>
        <div class="card-body">
          <h5 class="card-title text-center display-4 text-muted">2</h5>
          <p class="card-text text-center">Programs</p>
          <div class="d-flex justify-content-end">
            <a href="#" class="btn btn-primary btn-sm ml-auto">Manage programs <i class="fa fa-caret-right"></i></a>
          </div>
        </div>
      </div>
    </div>


    <div class="col-md-6">
      <div class="card text-dark bg-white mb-3">
        <div class="card-header"><h3 class="h4">Courses</h3></div>
        <div class="card-body">
          <h5 class="card-title text-center display-4 text-muted">35</h5>
          <p class="card-text text-center ">Courses</p>
          <div class="d-flex justify-content-end">
            <a href="#" class="btn btn-primary btn-sm ml-auto">Manage courses <i class="fa fa-caret-right"></i></a>
          </div>
        </div>
      </div>
    </div>


    <div class="col-md-6">
      <div class="card text-dark bg-light mb-3">
        <div class="card-header"><h3 class="h4">Curriculums</h3></div>
        <div class="card-body">
          <h5 class="card-title text-center display-4 text-muted">5</h5>
          <p class="card-text text-center ">Curriculums</p>
          <div class="d-flex justify-content-end">
            <a href="#" class="btn btn-primary btn-sm ml-auto">Manage curriculums <i class="fa fa-caret-right"></i></a>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection