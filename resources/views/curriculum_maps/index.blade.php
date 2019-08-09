@extends('layout.app', ['active' => 'curriculum_mapping'])

@section('title', 'Curriculum Mapping Index')


@section('content')
    <div id="app" v-cloak>        
        <div class="card mb-4">
          <div class="card-body pt-4">
            <h1 class="page-header mb-1"><i class="fa fa-map" style="color:#a1a1a1"></i> Curriculum Mapping</h1>
            <div class="d-flex justify-content-end">
              @can('isSAdmin')
                <div class="d-flex align-items-center">
                  <div class="mr-2">
                    <i class="fa fa-graduation-cap text-success"></i>
                    <label class="text-dark">Filter By Program</label>
                  </div>
                  <div>
                    <select v-on:change="filterByProgram" class="form-control" v-model="program_id">
                      <option value="">All</option>
                      @foreach($programs as $program)
                        <option value="{{ $program->id }}">{{ $program->program_code }}</option>
                      @endforeach
                    </select>
                  </div> 
                    
                </div>
              @endcan
            </div>
            
          </div>
        </div>


      @if($curricula->count() > 0)
      <div class="d-flex flex-wrap">
          @foreach($curricula as $curriculum)
            <div style="width: 31%" class="card shadow mb-4 mr-3">
                <div class="card-body pt-3">
                    <div class="d-flex justify-content-between align-items-baseline">
                        <div class="d-flex">
                            <div class="mr-2">
                                <div class="avatar" style="background: #cbff90; color:#585858;"><i class="fa fa-map"></i></div>
                            </div>
                            <div>
                              <div style="font-weight: 600">{{ $curriculum->name }} <i class="fa fa-check-circle {{ $curriculum->checkIfLatestVersion() ? 'text-success': 'text-dark' }}"></i></div>
                              <div class="text-info">revision no. {{ $curriculum->revision_no }}.0</div>
                            </div>
                        </div>
                        
                    </div>
                    
                    <div class="ml-2" style="font-weight: 600">{{ $curriculum->year }}</div>
                    <div style="font-size: 13px" class="text-muted ml-2 mt-2">
                        {{ $curriculum->program->college->name }} &mdash; {{ $curriculum->program->program_code}}
                    </div>
                    <hr>          
                    <div class="text-muted">
                        <i class="fa fa-file-alt"></i> {{ $curriculum->description ?? 'No description' }}
                    </div>
                    <div class="text-muted">
                        <i class="fa fa-book"></i> {{ $curriculum->curriculumCourses->count() }} courses
                    </div>
                    <div class="text-muted">
                        <i class="fa fa-flag"></i> {{ $curriculum->program->studentOutcomes->count() }} Student Outcomes
                    </div>


                </div>
                <div class="card-footer">
                  <div class="d-flex justify-content-end align-items-end">
                      <a class="btn btn-sm btn-info" href="{{ url('/curriculum_mapping/' . $curriculum->id) }}" class="btn btn-sm">
                           View <i class="fa fa-angle-right"></i>
                      </a>
                    </div>
                </div>
            </div>
          @endforeach
      </div>
    @else
      <div class="p-3 bg-white text-muted">
        No Curriculum found.
      </div>
    @endif    
      
    </div>
@endsection

@push('scripts')
  <script>
      new Vue({
        el: '#app',
        data: {
          curricula: @json($curricula),
          curricula_show: [],
          program_id: ''
        },
        methods: {
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

  @if(Session::has('message'))
    <script>
      toast.fire({
        type: 'success',
        title: '{{ Session::get('message') }}'
      })
    </script>
  @endif
@endpush