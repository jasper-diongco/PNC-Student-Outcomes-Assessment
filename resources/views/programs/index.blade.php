@extends('layout.app', ['active' => 'programs'])

@section('title', 'Program Index')

@section('content')
<div id="app">
  

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
  
    <div class="card mb-3">
      <div class="card-body">
        <div class="d-flex justify-content-between mb-0">
          <div>
            <h1 class="page-header"><i class="fa fa-graduation-cap" style="color: #a1a1a1"></i> Programs</h1>
          </div>

          <div>
            @if(Gate::check('isDean') || Gate::check('isSAdmin'))
              <button v-on:click="getRandColor" type="button" class="btn btn-success-b" data-toggle="modal" data-target="#programModal">
                Add New Program
              </button>
            @endif
          </div>
        </div>
        <div class="d-flex justify-content-between">
        @can('isSAdmin')
          
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
        @endcan
        </div>

      </div>
    </div>
    
    @if($programs->count() > 0)
      <div class="d-flex flex-wrap">
          @foreach($programs as $program)
            <div style="width: 31%" class="card shadow mb-4 mr-3">
                <div class="card-body pt-3">
                    <div class="d-flex justify-content-between align-items-baseline">
                        <div class="d-flex">
                            <div class="mr-2">
                                <div class="avatar" style="background: #cbff90; color:#585858;"><i class="fa fa-graduation-cap"></i></div>
                            </div>
                            <div style="font-weight: 600">{{ $program->program_code }}</div>
                        </div>
                        
                    </div>
                    <div style="font-size: 13px" class="text-muted ml-2 mt-2">
                        {{ $program->college->name }}
                    </div>
                    <hr>
                    <div class="text-muted">
                       <i class="fa fa-file-alt"></i> {{ $program->description }}
                    </div>
                    <div class="mt-2 text-muted">
                      <i class="fa fa-flag"></i> {{ $program->studentOutcomes->count() }} student outcomes
                    </div>
                </div>

                <div class="card-footer">
                  <div class="d-flex justify-content-end">
                      <a class="btn btn-sm btn-info" href="{{ url('/programs/' . $program->id) }}" class="btn btn-sm">
                          View <i class="fa fa-angle-right"></i>
                      </a>
                    </div>
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