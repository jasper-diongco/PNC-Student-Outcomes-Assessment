@extends('layouts.pnc_layout')

@section('title', $college->college_code)

@section('content')
  <a href="{{ url('colleges') }}" class="valign-center btn btn-success btn-sm"><i class="material-icons">arrow_back</i> Back</a>
  
  @if(Session::has('message'))
    @component('components.alert')
      {{ Session::get('message') }}
    @endcomponent
  @endif

  <h1 class="h3 mt-4 mb-3">{{ $college->name }}</h1>

  <div class="card">
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
  </div>
@endsection