@extends('layout.app', ['active' => 'curricula'])

@section('title', 'Curricula - College listing')

@section('content')


<div class="d-flex justify-content-between mb-3">
  <div>
    <h1 class="page-header">Curricula &mdash; Select college</h1>
  </div>
</div>

@if(count($colleges) > 0) 
  <div class="list-group">
    
    @foreach($colleges as $college)
      <a href="{{ url('/curricula?college_id=' . $college->id) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
      {{ $college->name }}
        <i class="fa fa-chevron-right"></i>
      </a>
    @endforeach  
  </div>
@else
  <div class="text-center bg-white p-3">No Colleges Found in Database.</div>
@endif
  
@endsection