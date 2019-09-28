@extends('layout.app', ['active' => 'student_outcomes'])

@section('title', 'Student Outcomes - Program listing')

@section('content')

<div id="app" class="card mb-4">
  <div class="card-body pt-4">
    <div class="d-flex justify-content-between align-items-baseline">
      <div>
        <h1 class="page-header mb-0"><i class="fa fa-flag" style="color: #a1a1a1"></i> Student Outcomes</h1>
      </div>
      
      <div>
        @can('isSAdmin')
          
            <div class="d-flex mr-4 mb-2">
              <div class="mr-2"><label class="col-form-label">Filter By College: </label></div>
              <div>
                <form v-on:change="filterByCollege" ref="filterForm" :action="myRootURL + '/student_outcomes/list_program?college_id=' + college_id">
                  <select class="form-control" name="college_id"  v-model="college_id">
                    <option value="all">All</option>
                    @foreach ($colleges as $college)
                      <option value="{{ $college->id }}">{{ $college->college_code }}</option>
                    @endforeach
                  </select>
                </form>
              </div>
            </div>        
        @endcan
      </div>
    </div>
  </div>
</div>


@if($programs->count() > 0)
  <h5 class="text-dark mb-3">Select Program</h5>
  <div class="d-flex flex-wrap">
      @foreach($programs as $program)
        <div class="card shadow mb-4 mr-3 w-md-31">
            <div class="card-body pt-3">
                <div class="d-flex justify-content-between align-items-baseline">
                    <div class="d-flex">
                        <div class="mr-2">
                            <div class="avatar" style="background: #cbff90; color:#585858;"><i class="fa fa-graduation-cap"></i></div>
                        </div>
                        <div style="font-weight: 600">{{ $program->program_code }}</div>
                    </div>
                    <div class="ml-3">
                      
                    </div>
                </div>
                <div style="font-size: 13px" class="text-muted ml-2 mt-2">
                    {{ $program->college->name }}
                </div>
                <hr>
                <div class="text-muted">
                     <i class="fa fa-file-alt text-muted"></i> {{ $program->description }}
                </div>
                <div class="text-muted">
                     <i class="fa fa-flag text-muted"></i> {{ $program->studentOutcomes->count() }} Student Outcomes
                </div>

                
            </div>
            <div class="card-footer d-flex justify-content-end">
                <a class="btn btn-sm btn-info" href="{{ url('/student_outcomes?program_id=' . $program->id) }}"class="btn btn-sm">
                         View <i class="fa fa-chevron-right"></i>
                    </a>
              </div>
        </div>
      @endforeach
  </div>
@else
  <div class="p-3 bg-white text-muted">
    No Program found.
  </div>
@endif

<div class="my-3 d-flex justify-content-end">
  {{ $programs->appends(request()->input())->links() }}
</div>

  
@endsection


@push('scripts')

  <script>
    var vm = new Vue({
      el: '#app',
      data: {
        college_id: '{{ request('college_id') }}',
        myRootURL: ''
      },
      methods: {
        filterByCollege() {
          this.$refs.filterForm.submit();
        }
      },
      created() {
        this.myRootURL = myRootURL;
      }
    });
  </script>
@endpush