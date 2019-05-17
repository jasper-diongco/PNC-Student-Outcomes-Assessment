@extends('layouts.sb_admin')

@section('title', $college->college_code)

@section('content')
  
  
  @if(Session::has('message'))
    @component('components.alert')
      {{ Session::get('message') }}
    @endcomponent
  @endif

  <h1 class="h3 mb-4 text-gray-800">{{ $college->name }}</h1>

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

    <!-- Programs -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Programs</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">2</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-graduation-cap fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Courses -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Courses</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">120</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-book fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Curricula -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Curricula</div>
              <div class="row no-gutters align-items-center">
                <div class="col-auto">
                  <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">8</div>
                </div>
                <div class="col">
                  <div class="progress progress-sm mr-2">
                    <div class="progress-bar bg-info" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-book-open fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Students -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Students</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">500</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-users fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection