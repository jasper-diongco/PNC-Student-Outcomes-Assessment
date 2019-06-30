@extends('layout.app', ['active' => 'programs'])

@section('title', 'Program Index')

@section('content')
<div id="app">
  <div class="d-flex justify-content-between mb-3">
    <div>
      <h1 class="page-header">List of Programs</h1>
    </div>

    <div>
      @if(Gate::check('isDean') || Gate::check('isSAdmin'))
        <button v-on:click="getRandColor" type="button" class="btn btn-success-b" data-toggle="modal" data-target="#programModal">
          Add New Program
        </button>
      @endif
    </div>
  </div>

  @if ($errors->any())
    <div class="alert alert-danger">
      <strong>ERROR!</strong>
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif
  
    {{-- @if(count($programs) > 0)
      @foreach ($programs as $program)
        <div class="col-md-4 mb-3">
          <div class="card shadow" style="height: 100%">
            <div class="card-body">
              <div class="d-flex align-items-center justify-content-start mb-2">
                <div class="avatar mr-2" style="background: {{ $program->color  }}">
                  {{ substr($program->program_code, 0 , 2) == 'BS' ? substr($program->program_code, 2) :  $program->program_code }}
                </div>
                <div>
                  <h4 class="card-title my-0">{{ $program->program_code }} </h4>
                </div>
              </div>
              <small class="text-muted">{{ $program->college->name }}</small>
              <p class="card-text mt-2">{{ $program->description }}</p>
              
            </div>
            <div class="card-footer bg-white">
              <a href="{{ url('programs/' . $program->id) }}" class="btn btn-primary btn-sm card-link">View <i class="fa fa-chevron-right"></i></a>
            </div>
          </div>
        </div>
      @endforeach
    @else
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <h3 class="text-center">No Program Found In Database.</h3>
          </div>
        </div>
      </div>
    @endif --}}
    <div class="card">
      <div class="card-body">

        @can('isSAdmin')
          <div class="row">
            <div class="col-md-12">
              <div class="d-flex justify-content-end">
                <div class="d-flex mr-4 mb-2">
                  <div class="mr-2"><label class="col-form-label">Filter By College: </label></div>
                  <div>
                    <form v-on:change="filterByCollege" ref="filterForm" :action="myRootURL + '/programs/?college_id=' + filter_by_college_id">
                      <select class="form-control" name="college_id" :value=" filter_by_college_id"  v-model="filter_by_college_id">
                        <option value="">All</option>
                        @foreach ($colleges as $college)
                          <option value="{{ $college->id }}">{{ $college->college_code }}</option>
                        @endforeach
                      </select>
                    </form>
                  </div>
                </div>
              </div>
            </div>
            
          </div>
        @endcan
        
        <div class="table-responsive">
          <table id="students-table" class="table table-borderless">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Program Code</th>
                <th scope="col">Description</th>
                <th scope="col">College</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody>
              @if ($programs->count() <= 0)
                <tr>
                  <td class="text-center" colspan="6">No Record Found in Database.</td>
                </tr>
              @else
                @foreach ($programs as $program)
                <tr>
                    <td>{{ $program->id }}</td>
                    <td>{{ $program->program_code }}</td>
                    <td>{{ $program->description }}</td>
                    <td>{{ $program->college->college_code }}</td>
                    <td>
                      <a title="View Details" class="btn btn-success btn-sm" href="{{ url('/programs/' . $program->id) }}">
                        <i class="fa fa-search"></i>
                      </a>
                    </td>
                </tr>
                @endforeach
              @endif
                                       
            </tbody>
          </table>

          <div class="my-3 d-flex justify-content-end">
            {{ $programs->appends(request()->input())->links() }}
          </div>
        </div>
      </div>
    </div>

  <!-- Modal -->
  <div class="modal fade" id="programModal" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form v-on:submit.prevent="submitForm" action="{{ url('/programs')  }}" method="post" autocomplete="off">
          <div class="modal-header">
            <h5 class="modal-title">Add New Program</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            
              @csrf
              {{-- Field for program code --}}
              <div class="form-group">
                  <label for="program_code">Program Code</label>

                  <div>
                      <input 
                        id="program_code" 
                        type="text" 
                        class="form-control"
                        name="program_code" 
                        v-model="program_code"
                        v-validate="'required|max:10|alpha'"
                        :class="{ 'is-invalid': errors.has('program_code') || !isUniqueCode }" 
                        autofocus
                        v-uppercase
                        >
                        <div class="invalid-feedback">@{{ !isUniqueCode ? 'Program Code has already been taken.' : errors.first('program_code') }}</div>
                  </div>

              </div>
              {{-- /end Field for program code --}}

              {{-- Field for program description --}}
              <div class="form-group">
                  <label for="description">Description</label>

                  <div>
                      <input 
                        id="description" 
                        type="text" 
                        class="form-control"  
                        autofocus
                        name="description" 
                        v-model="description"
                        v-validate="'required|max:255|alpha_spaces'"
                        :class="{ 'is-invalid': errors.has('description')  }" 
                        autofocus
                        v-uppercase>
                        <div class="invalid-feedback">@{{ errors.first('description') }}</div>
                  </div>
              </div>
              {{-- /end Field for program description --}}


              {{-- Field for college_id --}}
              <div class="form-group">
                  <label for="description">Select College</label>

                  <div>
                      <select
                        id="college_id" 
                        type="text" 
                        class="form-control"  
                        autofocus
                        name="college_id" 
                        v-model="college_id"
                        v-validate="'required'"
                        :class="{ 'is-invalid': errors.has('college_id')  }"
                        @can('isDean')
                          disabled
                        @endcan 
                        >
                        <option value="" selected style="display: none">Select College</option>
                        @foreach ($colleges as $college)
                          <option value="{{ $college->id }}">{{ $college->name }}</option>
                        @endforeach
                      </select>
                        <div class="invalid-feedback">@{{ errors.first('college_id') }}</div>
                  </div>
              </div>
              {{-- /end Field for college_id --}}



              {{-- hidden fields --}}
              <input type="hidden" name="color" :value="color">
              <input type="hidden" name="college_id" :value="college_id">
            
          </div>
          <div class="modal-footer">
            <button 
              type="button" 
              class="btn btn-secondary" 
              data-dismiss="modal"
              :disabled="btnLoading">Close</button>
            <button 
              type="submit" 
              class="btn btn-success"
              :disabled="btnLoading">
              Add to database
              <div v-show="btnLoading" class="spinner-border text-light spinner-border-sm" role="status">
                <span class="sr-only">Loading...</span>
              </div>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- END MODAL -->
</div>  
@endsection

@push('scripts')
<script>
   const dictionary = {
     en: {
       attributes: {
         program_code: 'Program Code',
         description: 'Description'
       }
     }
   };

  VeeValidate.Validator.localize(dictionary);

  new Vue({
    el: '#app',
    data: {
      program_code: '',
      description: '',
      colors: [
        '#E53935',
        '#D81B60',
        '#8E24AA',
        '#5E35B1',
        '#3949AB',
        '#1E88E5',
        '#039BE5',
        '#00ACC1',
        '#00897B',
        '#43A047',
        '#7CB342',
        '#C0CA33',
        '#FDD835',
        '#F4511E',
        '#6D4C41'

      ],
      college_id: '{{ Auth::user()->user_type_id == 'dean' ? Auth::user()->getFaculty()->college_id : '' }}',
      color: '',
      isUniqueCode: true,
      btnLoading: false,
      filter_by_college_id: '{{ request('college_id') }}',
      myRootURL: myRootURL
    },
    methods: {
      getRandColor() {
        this.color = this.colors[Math.floor(Math.random() * this.colors.length)];
      },
      submitForm(event) {
        this.btnLoading = true;
        this.checkCode().
        then(() => {
          this.btnLoading = false;
          this.$validator.validateAll()
          .then(isValid => {
            if(isValid && this.isUniqueCode) {
              this.btnLoading = true;
              event.target.submit();
            } else {
              toast.fire({
                type: 'error',
                title: 'Please Enter a valid data!'
              });
            }
          });
        })
        .catch(err => {
          this.btnLoading = false;
          toast.fire({
            type: 'error',
            title: 'Please Enter a valid data!'
          });
        })
        
      },
      checkCode() {
        return new Promise((resolve, reject) => {
          ApiClient.post('/programs/check_code', {
          id: 0,
          program_code: this.program_code
            }).
            then(response => {
              if(response.statusText == 'OK') {
                this.isUniqueCode = true;
              }
              resolve(response);
            }).
            catch(err => {
              this.isUniqueCode = false;
              reject(err);
            });
          });
        
      },
      filterByCollege(event) {
        //event.target.submit();
        //alert("test");
        //console.log(event);
        //console.log();
        this.$refs.filterForm.submit();

      }
    },
    created() {
      this.getRandColor();
    }
  });
</script>
@endpush