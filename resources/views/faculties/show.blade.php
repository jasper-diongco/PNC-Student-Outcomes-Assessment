@extends('layouts.pnc_layout')

@section('title', $faculty->user->getFullName())

@section('content')
  <a href="{{ url('faculties') }}" class="valign-center btn btn-success btn-sm"><i class="material-icons">arrow_back</i> Back</a>
  
  @if(Session::has('message'))
    @component('components.alert')
      {{ Session::get('message') }}
    @endcomponent
  @endif


  <div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4>Faculty Information</h4>
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
      <a href="{{ url('faculties/' . $faculty->id . '/edit') }}" class="btn btn-primary">Update Account <i class="fa fa-edit"></i></a>
    </div>
    <div class="card-body">
      <ul class="list-group list-group-flush">
        <li class="list-group-item"><b>Email:</b> {{ $faculty->user->email }} </li>
        <li class="list-group-item"><b>User Type:</b> {{ $faculty->user->userType->description }}</li>
      </ul>
    </div>
  </div>
@endsection