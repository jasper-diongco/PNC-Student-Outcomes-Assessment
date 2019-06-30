@extends('layout.app', ['active' => 'colleges'])

@if(isset($college))
  @section('title', 'Update College')
@else
  @section('title', 'Add College')
@endif


@section('content')

  <a href="{{ isset($college) ? url('colleges/' . $college->id)  : url('colleges') }}" class="text-success"><i class="fa fa-arrow-left"></i> Back</a>

<div class="row" id="app" v-cloak>
  <div class="col-sm-8 mx-auto mt-4">
    <div class="card">
      <div class="card-header">
        @if (isset($college))
          <h2 class="page-header">Update College</h2>
        @else
          <h2 class="page-header">Add New College</h2>
        @endif
        

      </div>
      <div class="card-body">
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
            <label>College Code</label>
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
            <label>College Name</label>
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
            <label>Dean</label>
            
            {{-- <div v-if="!showSelect">
            @if(isset($college))
              <p ><b>Current Dean:</b> {{ $college->faculty->user->getFullName() }} <button v-on:click="changeDean" type="button" class="btn btn-sm btn-primary">Change <i class="fa fa-edit"></i></button></p>
            @endif
            </div> --}}

            {{-- <vue-select v-else v-model="faculty_id" :reduce="faculty => faculty.id" placeholder="Search Dean" :filterable="false" :options="options" v-on:search="onSearch">
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
            </vue-select> --}}
            <div v-if="showSearch">
              <input type="search" v-model="searchText" v-on:input="searchFaculty" placeholder="Search faculty..." class="form-control">

              <ul class="list-group">
                <li v-for="f in faculties" class="list-group-item d-flex justify-content-between">
                  <div>
                    <i class="fa fa-user"></i> 
                    @{{ f.full_name }} - @{{ f.user_type }} 
                  </div>
                  <div>
                    <button v-on:click="selectFaculty(f.id)" type="button" class="btn btn-sm btn-success">Select</button>
                  </div>
                </li>
              </ul>
              <ul v-if="faculties.length == 0 && searchText != ''" class="list-group">
                <li class="list-group-item">
                  No record found.
                </li>
              </ul>
            </div>

            <div v-else>
              <label>Selected:</label> @{{ faculty.full_name }}
              <button v-on:click="changeDean" type="button" class="btn btn-sm btn-primary">Change <i class="fa fa-edit"></i></button>
            </div>

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
      showSelect: {{ isset($college) ? 'false' : 'true' }},
      faculties: [],
      searchText: '',
      showSearch: '{{ old('faculty_id') || isset($college) ? false : true }}'
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
          console.log(response.data);
          //this.options = response.data;
        })
      },
      getFaculty() {
        ApiClient.get('/faculties/' + this.faculty_id).
        then(response => {
          this.faculty = response.data.data;
        })
      },
      searchFaculty() {
        if(this.searchText.trim() == '') {
          return this.faculties = [];
        }

        ApiClient.get('/faculties?q=' + this.searchText).
        then(response => {
          //loading(false);
          //console.log(response.data);
          this.faculties = response.data.data;
        })
        
      },
      changeDean() {
        this.showSearch = true;
        this.showSelect = true;
        this.faculty_id = '';
      },
      selectFaculty(faculty_id) {
        this.faculties = [];
        this.faculty_id = faculty_id;
        this.getFaculty();
        this.showSearch = false;
      }
    },
    created() {
      this.getFaculty();
    }
  });
</script>
@endpush