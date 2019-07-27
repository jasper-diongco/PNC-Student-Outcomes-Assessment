@extends('layout.app', ['active' => 'curriculum_mapping'])

@section('title', 'Curriculum Mapping Index')


@section('content')
    <div id="app" v-cloak>
        <div>
            <div>
                

                @if(count($curricula) > 0)
                  <div class="card">
                    <div class="card-body">
                      <h1 class="page-header"><i class="fa fa-map" style="color:#a1a1a1"></i> Curriculum Mapping</h1>
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
                      


                      <table class="table table-borderless">
                        <thead>
                          <tr>
                            <th>ID</th>
                            <th>Program</th>
                            <th>Name</th>
                            <th>Year</th>
                            <th>Revision No.</th>
                            <th>College</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                            <template v-if="curricula_show.length > 0">
                              <tr v-for="curriculum in curricula_show">
                                <td>@{{ curriculum.id }}</td>
                                <td>@{{ curriculum.program.program_code }}</td>
                                <td>@{{ curriculum.name }}</td>
                                <td>@{{ curriculum.year }}</td>
                                <td>@{{ curriculum.revision_no }}.0 
                                </td>
                                <td>@{{ curriculum.program.college.college_code }}</td>
                                <td><a :href="'curriculum_mapping/' + curriculum.id" class="btn btn-sm btn-info">View <i class="fa fa-search"></i></a></td>
                              </tr>
                            </template>
                            <template v-else>
                                <tr>
                                  <td colspan="7" align="center">No Record found in database.</td>
                                </tr>
                            </template>
                            
                        </tbody>
                      </table>
                    </div>
                  </div>
                  
                @else
                  <div class="text-center bg-white p-3">No Curriculum Found in Database.</div>
                @endif

            </div>
        </div>
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