@extends('layout.app', ['active' => 'colleges'])

@section('title', 'Colleges Index')

@section('content')


<div class="d-flex justify-content-between mb-3">
  <div>
    <h1 class="page-header">List of Colleges</h1>
  </div>
  <div>
    <a href="{{ url('colleges/create') }}" class="btn btn-success-b">Add New College</a>
  </div>
</div>

@if(count($colleges) > 0) 
  <div class="list-group">
    
    @foreach($colleges as $college)
      <a href="{{ url('colleges/' . $college->id) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
      {{ $college->name }}
        <i class="fa fa-chevron-right"></i>
      </a>
    @endforeach  
  </div>
@else
  <div class="text-center bg-white p-3">No Colleges Found in Database.</div>
@endif
  
@endsection