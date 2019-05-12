@extends('layouts.pnc_layout')

@section('title', 'Add Faculty')


@section('content')

<a href="{{  url('faculties') }}" class="valign-center btn btn-success btn-sm"><i class="material-icons">arrow_back</i> Back</a>

<div class="row" id="app">
  <div class="col-xs-12 col-md-8 mx-auto">
    <div class="card mt-3">
      <div class="card-header">
        <h3>Add Faculty</h3>
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
        <form action="{{  url('faculties') }}" method="post" autocomplete="off">
           {{ csrf_field() }}

          <div class="form-group">
            <label>First Name *</label>
            <input 
              type="text" 
              placeholder="Enter First Name" 
              class="form-control" 
              name="first_name"
              v-uppercase
              v-model="first_name"
              >
          </div>
          <div class="form-group">
            <label>Middle Name</label>
            <input 
              type="text" 
              placeholder="Enter Middle Name" 
              class="form-control" 
              name="middle_name"
              v-uppercase
              v-model="middle_name">
          </div>
          <div class="form-group">
            <label>Last Name *</label>
            <input 
              type="text" 
              placeholder="Enter Last Name" 
              class="form-control" 
              name="last_name"
              v-uppercase
              v-model="last_name">
          </div>
          
          
          <div class="form-group">
            <label>Sex *</label>
            <div class="custom-control custom-radio">
              <input value="M" type="radio" id="male" name="sex" class="custom-control-input" {{ old('sex') && old('sex') == 'M' ? 'checked' : ''   }} >
              <label class="custom-control-label" for="male">Male</label>
            </div>
            <div class="custom-control custom-radio">
              <input value="F" type="radio" id="female" name="sex" class="custom-control-input" {{ old('sex') && old('sex') == 'F' ? 'checked' : ''   }}>
              <label class="custom-control-label" for="female">Female</label>
            </div>
          </div>

          <div class="form-group">
            <label>Date Of Birth *</label>
            <datepicker 
              placeholder="Select Date" 
              name="date_of_birth" 
              value="{{ old('date_of_birth') }}"></datepicker>
          </div>

          <div class="form-group">
            <label>Contact Number (optional)</label>
            <input 
              name="contact_no" 
              type="tel" 
              class="form-control" 
              placeholder="Enter Contact Number"
              value="{{ old('contact_no') }}">
          </div>

          <div class="form-group">
            <label for="address">Address (optional)</label>
            <textarea 
              id="address" 
              name="address" 
              class="form-control" 
              placeholder="Enter Address"
              >{{ old('address') }}</textarea>
          </div>


          <div class="form-group">
            <label for="college_id">Select College *</label>
            <select name="college_id" id="college_id" class="form-control">
              <option value="" style="display: none">Select College</option>
              @foreach($colleges as $college)
                <option 
                  value="{{ $college->id }}"
                  @if (old('college_id') == $college->id)
                    {{ 'selected' }}
                  @endif
                  >{{ $college->name }}</option>
              @endforeach
            </select>
          </div>
          <hr>
          <h4>Account Information</h4>
          <div class="form-group">
            <label>Email *</label>
            <input 
              type="email" 
              placeholder="Enter Email" 
              class="form-control" 
              name="email"
              value="{{ old('email') }}">
          </div>
          <div class="form-group">
            <label>Password *</label>
            <input 
              :type="showPass ? 'text' : 'password'" 
              placeholder="Enter Password" 
              class="form-control" 
              name="password"
              value="DefaultPass123"
              readonly>
            <input class="mt-2" type="checkbox" id="show-pass" v-model="showPass"> <label for="show-pass">Show</label>
          </div>
          <div class="d-flex justify-content-end">
          <a class="btn btn-light mr-2" href="{{ url('/colleges') }}">Cancel</a>
          <button class="btn btn-success">{{ isset($faculty) ? 'Update from database' :  'Add to Database' }}</button>
        </form>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
  const app = new Vue({
      el: '#app',
      data() {
        return {
          first_name: '{{ old('first_name') }}',
          middle_name: '{{ old('middle_name') }}',
          last_name: '{{ old('last_name') }}',
          showPass: false
        }
      }
  });
</script>
@endpush