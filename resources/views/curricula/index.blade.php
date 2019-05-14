@extends('layouts.pnc_layout')

@section('title', 'Curricula Index')


@section('content')
  <div id="app">
    <div class="d-flex justify-content-between mb-3">
      <div>
        <h1 class="h2">Curricula</h1>
      </div>

      <div>
        @if(Gate::check('isDean') || Gate::check('isSAdmin'))
          <!-- CURRICULUM MODAL -->
          <curriculum-modal :programs='@json($programs)' :curricula='@json($curricula)'></curriculum-modal>
          <!-- END MODAL -->
        @endif
      </div>
    </div>


    {{-- <div class="row mb-3">
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
    </div> --}}

    @if(count($curricula) > 0)
      <div class="row">
        @foreach($curricula as $curriculum)
          <div class="col-md-4 mb-3">
            <div class="card" style="height: 100%;">
              <div class="card-body">

                <h3>{{ $curriculum->program->program_code }}</h3>
                <div class="cirriculum-name"> <i class="fa fa-book-open"></i> {{ $curriculum->name }}</div>

                <ul class="list-group list-group-flush mt-2">
                  <li class="list-group-item"><i class="fa fa-file-alt"></i> {{ $curriculum->description}}</li>
                  <li class="list-group-item"><i class="fa fa-calendar-alt"></i> {{ $curriculum->year }}</li>
                  <li class="list-group-item"><i class="fa fa-copy"></i> v{{ $curriculum->revision_no }}.0</li>
                </ul>


                
              </div>
              <div class='card-footer'>
                <button data-toggle="modal" data-target="#modal{{ $curriculum->id }}" class="btn btn-light btn-sm mr-2"><i class="fa fa-history"></i> Past Version : {{ count($curriculum->getPastVersion()) }}</button>
                <a href="{{ url('/curricula/' . $curriculum->id) }}" class="btn btn-sm btn-primary">View <i class="fa fa-chevron-right"></i></a>
              </div>
            </div>
          </div>

          <!-- Modal -->
          <div class="modal fade" id="modal{{ $curriculum->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Past Version</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <ul class="list-group">
                    @if(count($curriculum->getPastVersion()))
                      @foreach ($curriculum->getPastVersion() as $past_version)
                      <li class="list-group-item">
                        <div><i class="fa fa-history"></i> v{{ $past_version->revision_no }}.0</div>
                        <hr>
                        <div><i class="fa fa-file-alt"></i> {{ $past_version->description }}</div>
                        <hr>
                        <div><i class="fa fa-calendar-alt"></i> {{ $past_version->year }}</div>
                        <hr>
                        <div class="d-flex justify-content-end">
                          <a href="{{ url('/curricula/' . $past_version->id) }}" class="btn btn-success btn-sm">View <i class="fa fa-chevron-right"></i></a>
                        </div>
                        
                      </li>
                      @endforeach
                    @else
                      <li class="list-group-item">No past version</li>
                    @endif
                  </ul>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
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