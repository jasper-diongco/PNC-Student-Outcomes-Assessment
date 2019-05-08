@extends('layouts.master')

@section('title', $program->program_code)

@section('content')
  <a href="{{ url('/programs') }}" class="valign-center btn btn-success btn-sm"><i class="material-icons">arrow_back</i> Back</a>

  <div id="app">
    <h1 class="h3 mt-4 mb-3">{{ $program->description }}</h1>
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center justify-content-start mb-2">
            <div class="avatar mr-2" style="background: {{ $program->color  }}">
              {{ substr($program->program_code, 0 , 2) == 'BS' ? substr($program->program_code, 2) :  $program->program_code }}
            </div>
            <div>
              <h4 class="card-title my-0">{{ $program->program_code }}</h4>
            </div>
          </div>
          @if(Gate::check('isDean') || Gate::check('isSAdmin'))
            <button v-on:click="getRandColor" type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#programModal">
              Update <i class="fa fa-edit"></i>
            </button>
          @endif
        </div>
        <small>created at {{ $program->created_at }}</small>
        <p class="card-text mt-3">{{ $program->description }}</p>      
        
      </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="programModal" tabindex="-1" role="dialog"  aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form v-on:submit.prevent="submitForm" action="{{ url('/programs/' . $program->id)  }}" method="post" autocomplete="off">
            <div class="modal-header">
              <h5 class="modal-title">Update Program</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              
                @csrf
                {{ method_field('PUT') }}
                {{-- Field for program code --}}
                <div class="form-group row">
                    <label for="program_code" class="col-md-3 col-form-label text-md-right"><b>Program Code</b></label>

                    <div class="col-md-9">
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
                <div class="form-group row">
                    <label for="description" class="col-md-3 col-form-label text-md-right"><b>Description</b></label>

                    <div class="col-md-9">
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
              <div class="form-group row">
                  <label for="description" class="col-md-3 col-form-label text-md-right"><b>Select College</b></label>

                  <div class="col-md-9">
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
                class="btn btn-primary"
                :disabled="btnLoading">
                Update from database
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
      program_id: '{{ $program->id }}',
      program_code: '{{ $program->program_code }}',
      description: '{{ $program->description }}',
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
      btnLoading: false
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
          id: this.program_id,
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
        
      }
    },
    created() {
      this.getRandColor();
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