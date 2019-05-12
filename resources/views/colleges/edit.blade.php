@extends('layouts.pnc_layout')

@if(isset($college))
  @section('title', 'Update College')
@else
  @section('title', 'Add College')
@endif


@section('content')

  <a href="{{ isset($college) ? url('colleges/' . $college->id)  : url('colleges') }}" class="valign-center btn btn-success btn-sm"><i class="material-icons">arrow_back</i> Back</a>

<div class="row" id="app">
  <div class="col-sm-8 mx-auto mt-4">
    <div class="card">
      <div class="card-header">
        @if (isset($college))
          <h2 class="h3">Update College</h2>
        @else
          <h2 class="h3">Add New College</h2>
        @endif
        

      </div>
      <div class="card-body">
        <p class="text-warning">All Fields with * are required</p>
        @if($errors->any())
        <div class="alert alert-danger">
          <strong>Please fix the following: </strong>
          <ul>
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif
        <form action="{{ isset($college) ? url('colleges/' . $college->id) : url('colleges') }}" method="post" autocomplete="off">
           {{ csrf_field() }}

           @if(isset($college)) 
              {{ method_field('PUT') }}
           @endif

          <div class="form-group">
            <label>College Code *</label>
            <input 
              type="text" 
              placeholder="Enter College Code" 
              class="form-control" 
              name="college_code"
              v-model="college_code"
              v-uppercase>
            <small class="text-info">College Code must be unique</small>
          </div>
          <div class="form-group">
            <label>College Name *</label>
            <input 
              type="text" 
              placeholder="Enter College Description" 
              class="form-control" 
              name="name"
              v-model="college_name" 
              v-uppercase
              >
          </div>
          <div class="form-group">
            <label>Select Dean *</label>
            
            <div v-if="!showSelect">
            @if(isset($college))
              <p ><b>Current Dean:</b> {{ $college->faculty->user->getFullName() }} <button v-on:click="changeDean" type="button" class="btn btn-sm btn-primary">Change <i class="fa fa-edit"></i></button></p>
            @endif
            </div>

            <vue-select v-else v-model="faculty_id" :reduce="faculty => faculty.id" placeholder="Search Dean" :filterable="false" :options="options" v-on:search="onSearch">
              <template slot="no-options">
                Type to search Users...
              </template>
              <template slot="option" slot-scope="option">
                <div class="d-center">
                  @{{ option.user_id + ' - ' + option.last_name + ' ' + option.first_name + ', ' + option.middle_name  }}
                </div>
                <small>@{{ option.college }}</small>
              </template>
              <template slot="selected-option" slot-scope="option">
                <div class="selected d-center">
                 @{{ option.last_name + ' ' + option.first_name + ', ' + option.middle_name  }}
                </div>
              </template>
            </vue-select>
            <input type="hidden" :value="faculty_id" name="faculty_id">
          </div>
          <div class="d-flex justify-content-end">
          <a class="btn btn-light mr-2" href="{{ url('/colleges') }}">Cancel</a>
          <button type="submit" class="btn btn-success">{{ isset($college) ? 'Update from database' :  'Add to Database' }}</button>
        </form>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
  new Vue({
    el: '#app',
    data: {
      options: [],
      faculty_id: '{{ old('faculty_id') ? old('faculty_id') : $college->faculty_id ?? '' }}',
      college_code: '{{ old('college_code') ? old('college_code') : $college->college_code ?? '' }}',
      college_name: '{{ old('name') ? old('name') : $college->name ?? '' }}',
      faculty: {},
      showSelect: {{ isset($college) ? 'false' : 'true' }}
    },
    methods: {
      onSearch(search, loading) {
        if(search == '') {
          this.options = [];
          return false;
        }
        loading(true);
        ApiClient.get('/faculties?q=' + escape(search)).
        then(response => {
          loading(false);
          this.options = response.data;
        })
      },
      getFaculty() {
        ApiClient.get('/faculties/' + this.faculty_id).
        then(response => {
          this.faculty = response.data.data;
        })
      },
      changeDean() {
        this.showSelect = true;
        this.faculty_id = '';
      }
    },
    created() {
      this.getFaculty();
    }
  });
</script>
@endpush