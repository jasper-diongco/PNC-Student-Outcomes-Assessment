@extends('layouts.sb_admin')

@section('title', 'Curricula Index')


@section('content')
  <div id="app">
    @can('isSAdmin')
      <a href="{{ url('/curricula') }}" class="btn btn-success mb-3 btn-sm"><i class="fa fa-arrow-left"></i> Back</a>
    @endcan

    <div class="d-flex justify-content-between mb-3">
      <div>
        <h1 class="h3 mb-4 text-gray-800">Curricula</h1>
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
            <div class="card shadow" style="height: 100%;">
              <!-- Card Header - Dropdown -->
              <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-light">
                <h6 class="m-0 font-weight-bold text-primary">{{ $curriculum->program->program_code }}</h6>
                <div class="dropdown no-arrow">
                  <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(17px, 19px, 0px);">
                    <div class="dropdown-header">Actions:</div>
                    <a href="{{ url('/curricula/' . $curriculum->id) }}" class="dropdown-item"><i class="fa fa-eye"></i> View</a>
                    <a href="#" class="dropdown-item" data-toggle="modal" data-target="#modal{{ $curriculum->id }}"><i class="fa fa-history"></i> Past Version : {{ count($curriculum->getPastVersion()) }}</a>
                    <div class="dropdown-divider"></div>
                  </div>
                </div>
              </div>

              <div class="card-body">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item"> <i class="fa fa-book-open"></i> {{ $curriculum->name }}</li>
                  <li class="list-group-item"><i class="fa fa-file-alt"></i> {{ $curriculum->description}}</li>
                  <li class="list-group-item"><i class="fa fa-calendar-alt"></i> {{ $curriculum->year }}</li>
                  <li class="list-group-item"><i class="fa fa-copy"></i> v{{ $curriculum->revision_no }}.0</li>
                </ul>      
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
      el: '#app',
      data: {
            visible: false
        },
        methods: {
            show: function () {
                this.visible = true;
            }
        }
    });
  </script>
@endpush