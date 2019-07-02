@extends('layout.app', ['active' => 'curricula'])

@section('title', 'Curricula Index')


@section('content')
  <div id="app">
    @can('isSAdmin')
      <a href="{{ url('/curricula') }}" class="text-success"><i class="fa fa-arrow-left"></i> Back</a>
    @endcan

    <div class="d-flex justify-content-between mb-3 mt-3">
      <div>
        <h1 class="page-header">Curricula</h1>
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
      {{-- <div class="row">
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
                    <div class="dropdown-divider"></div>
                  </div>
                </div>
              </div>

              <div class="card-body">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item"> <i class="fa fa-book-open text-secondary"></i> {{ $curriculum->name }}</li>
                  <li class="list-group-item"><i class="fa fa-file-alt text-primary"></i> {{ $curriculum->description}}</li>
                  <li class="list-group-item"><i class="fa fa-calendar-alt text-secondary"></i> {{ $curriculum->year }}</li>
                  <li class="list-group-item"><i class="fa fa-book text-success"></i> {{ count($curriculum->curriculumCourses) }} courses</li>
                </ul>      
              </div>
              <div class="card-footer d-flex justify-content-end">
                <a href="{{ url('/curricula/' . $curriculum->id) }}">View <i class="fa fa-chevron-right"></i></a>
              </div>
            </div>
          </div>
        @endforeach
      </div> --}}
      <div class="card">
        <div class="card-body">
          <h5 class="mb-3"><i class="text-info fa fa-book-open"></i> {{ $college->college_code }} List of curriculum</h5>
          <table class="table table-borderless">
            <thead>
              <tr>
                <th scope="col">ID</th>
                <th scope="col">Name</th>
                <th scope="col">Revision No</th>
                <th scope="col">Program</th>
                <th scope="col">Year</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($curricula as $curriculum)
              <tr>
                <th>{{ $curriculum->id }}</th>
                <td>{{ $curriculum->name }}</td>
                <td>{{ $curriculum->revision_no }}.0</td>
                <td>{{ $curriculum->program->description }}</td>
                <td>{{ $curriculum->year }}</td>
                <td><a href="{{ url('/curricula/' . $curriculum->id . '?college_id=' . request('college_id')) }}" class="btn btn-secondary btn-sm"><i class="fa fa-search "></i></a></td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
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