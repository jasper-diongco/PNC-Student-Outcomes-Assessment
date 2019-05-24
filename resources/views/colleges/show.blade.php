@extends('layouts.sb_admin')

@section('title', $college->college_code)

@section('content')
  <a href="{{ url('colleges') }}" class="text-success"><i class="fa fa-arrow-left"></i> Back</a>
  
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


  <h4 class="mb-3 mt-5">List of Programs <i class="fa fa-graduation-cap text-primary"></i></h4>

    @if(count($college->programs) > 0) 
    <div class="list-group">
      
      @foreach($college->programs as $program)
        <a href="{{ url('/programs/' . $program->id) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
          <div class="d-flex justify-content-between align-items-center mr-3">
            <div>
              <div class="avatar mr-2" style="background: {{ $program->color  }}">
                {{ substr($program->program_code, 0 , 2) == 'BS' ? substr($program->program_code, 2) :  $program->program_code }}
              </div>
            </div>
            <span>{{ $program->description }}</span></div>
          <div>
            <i class="fa fa-chevron-right"></i>
          </div>
        </a>
      @endforeach  
    </div>
  @else
    <div class="text-center bg-white p-3">No Program Found in Database.</div>
  @endif
@endsection