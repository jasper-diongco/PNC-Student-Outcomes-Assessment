@extends('layout.app', ['active' => 'curricula'])

@section('title', 'Curricula Index')


@section('content')
  <div id="app" v-cloak>

    <div class="d-flex justify-content-between mb-3">
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

          <div class="d-flex justify-content-between">
            <div>
              <h5 class="mb-3 text-info">List of Curriculum</h5>
            </div>
            <div class="d-flex align-items-baseline">
              <div class="mr-2">

                <label class="text-dark"><i class="fa fa-graduation-cap text-success"></i> Filter By Program</label>
              </div>
              <div>
                <select class="form-control" v-model="program_id" v-on:change="filterByProgram">
                  <option value="">All</option>
                  @foreach($programs as $program)
                    <option value="{{ $program->id }}">{{ $program->program_code }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          


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
              <template v-if="curricula_show.length > 0">
                <tr v-for="curriculum in curricula_show" :key="curriculum.id">
                  <th>@{{ curriculum.id }}</th>
                  <td>@{{ curriculum.name }}</td>
                  <td>@{{ curriculum.revision_no }}.0</td>
                  <td>@{{ curriculum.program.description }}</td>
                  <td>@{{ curriculum.year }}</td>
                  <td><a :href="'curricula/' + curriculum.id" class="btn btn-success btn-sm"><i class="fa fa-search "></i></a></td>
                </tr>
              </template>
              <template v-else>
                <tr>
                  <td colspan="6" align="center">
                    No Record found in database.
                  </td>
                </tr>
              </template>
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
            visible: false,
            program_id: '',
            curricula: @json($curricula),
            curricula_show: []
        },
        methods: {
            show: function () {
                this.visible = true;
            },
            filterByProgram() {
              if(this.program_id == '') {
                return this.curricula_show = this.curricula;
              }

              this.curricula_show = this.curricula.filter(curriculum => {
                return curriculum.program_id == this.program_id;
              });
            }
        },
        created() {
          this.filterByProgram();
        }
    });
  </script>
@endpush