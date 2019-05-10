@extends('layouts.master')

@section('title', 'Curricula Index')


@section('content')
  <div id="app">
    <div class="d-flex justify-content-between mb-3">
      <div>
        <h1 class="h2">Curricula</h1>
      </div>

      <div>
        @if(Gate::check('isDean') || Gate::check('isSAdmin'))
          <!-- COURSE MODAL -->
          <curriculum-modal :programs='@json($programs)'></curriculum-modal>
          <!-- END MODAL -->
        @endif
      </div>
    </div>


    <div class="row mb-3">
      <div class="col-md-4 d-flex row">
          <div class="col-3 text-right">
            <label class="col-form-label "><b><i class="fa fa-calendar-alt"></i> Year : </b></label>
          </div>
          <div class="col-4">
            <select class="form-control">
              <option value="" selected>2019</option>
              <option value="public">2018</option>
              <option value="private">2017</option>
            </select>
          </div>   
        </div>
    </div>

    @if(count($curricula) > 0)
      <div class="row">
        @foreach($curricula as $curriculum)
          <div class="col-md-4">
            <div class="card">
              <div class="card-body">

                <h3>{{ $curriculum->program->program_code }}</h3>
                <div class="cirriculum-name"> <i class="fa fa-book-open"></i> {{ $curriculum->name }}</div>

                <ul class="list-group list-group-flush mt-2">
                  <li class="list-group-item"><i class="fa fa-file-alt"></i> {{ $curriculum->description}}</li>
                  <li class="list-group-item"><i class="fa fa-calendar-alt"></i> {{ $curriculum->year }}</li>
                </ul>


                <a href="{{ url('/curricula/' . $curriculum->id) }}" class="btn btn-sm btn-primary mt-3">View <i class="fa fa-chevron-right"></i></a>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @else
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <h3 class="text-center">No Curriculum Found In Database.</h3>
          </div>
        </div>
      </div>
    @endif
  </div>
@endsection

@push('scripts')
  <script>
    new Vue({
      el: '#app'
    });
  </script>
@endpush