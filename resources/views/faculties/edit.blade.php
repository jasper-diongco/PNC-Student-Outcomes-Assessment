@extends('layouts.sb_admin')

@section('title', 'Update Faculty')


@section('content')

<a href="{{  url('faculties/' . $faculty->id) }}" class="btn btn-success btn-sm"><i class="fa fa-arrow-left"></i> Back</a>

<div class="row" id="app">
  <div class="col-xs-12 col-md-8 mx-auto mb-5">
    <div class="card mt-3">
      <div class="card-header">
        <h3>Update Faculty</h3>
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
        <form action="{{  url('faculties/' . $faculty->id) . '?faculty_type=' . Auth::user()->user_type_id }}" method="post" autocomplete="off">
           {{ csrf_field() }}
           {{ method_field('PUT') }}

          <div class="form-group">
            <label>First Name *</label>
            <input 
              type="text" 
              placeholder="Enter First Name" 
              class="form-control" 
              name="first_name"
              v-uppercase
              v-model="first_name"
              {{ $name_readonly ? 'readonly' : '' }}
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
              v-model="middle_name"
              {{ $name_readonly ? 'readonly' : '' }}>
          </div>
          <div class="form-group">
            <label>Last Name *</label>
            <input 
              type="text" 
              placeholder="Enter Last Name" 
              class="form-control" 
              name="last_name"
              v-uppercase
              v-model="last_name"
              {{ $name_readonly ? 'readonly' : '' }}>
          </div>
          
          
          <div class="form-group">
            <label>Sex *</label>
            <div class="custom-control custom-radio">
              <input 
                value="M" 
                type="radio" 
                id="male" 
                name="sex" 
                class="custom-control-input"
                @if(isset($faculty) && empty(old('sex')))
                  {{ $faculty->user->sex == 'M' ? 'checked' : '' }}
                @else
                  {{ old('sex') && old('sex') == 'M' ? 'checked' : '' }}
                @endif 
                 >
              <label class="custom-control-label" for="male">Male</label>
            </div>
            <div class="custom-control custom-radio">
              <input 
                value="F" 
                type="radio" 
                id="female" 
                name="sex" 
                class="custom-control-input" 
                @if(isset($faculty) && empty(old('sex')))
                  {{ $faculty->user->sex == 'F' ? 'checked' : '' }}
                @else
                  {{ old('sex') && old('sex') == 'F' ? 'checked' : '' }}
                @endif >
              <label class="custom-control-label" for="female">Female</label>
            </div>
          </div>

          <div class="form-group">
            <label>Date Of Birth *</label>
            <datepicker 
              placeholder="Select Date" 
              name="date_of_birth" 
              value="{{ old('date_of_birth') ? old('date_of_birth')  : $faculty->user->date_of_birth }}"></datepicker>
          </div>

          <div class="form-group">
            <label>Contact Number (optional)</label>
            <input 
              name="contact_no" 
              type="tel" 
              class="form-control" 
              placeholder="Enter Contact Number"
              value="{{ old('contact_no') ? old('contact_no')  : $faculty->user->contact_no }}">
          </div>

          <div class="form-group">
            <label for="address">Address (optional)</label>
            <textarea 
              id="address" 
              name="address" 
              class="form-control" 
              placeholder="Enter Address"
              >{{ old('address') ? old('address')  : $faculty->user->address }}</textarea>
          </div>


          <div class="form-group">
            <label for="college_id">Select College *</label>
            <select name="college_id" id="college_id" class="form-control" {{ !Gate::check('isSAdmin') ? 'disabled' : '' }} {{ $faculty->user->user_type_id == 'dean' ? 'disabled' : '' }} v-model="college_id">
              <option value="" style="display: none">Select College</option>
              @foreach($colleges as $college)
                <option 
                  value="{{ $college->id }}"
                  @if(empty(old('college_id')) && $faculty->college->id == $college->id)
                    {{ 'selected' }}
                  @elseif (old('college_id') == $college->id)
                    {{ 'selected' }}
                  @endif
                  >{{ $college->name }}</option>
              @endforeach
            </select>
            <input type="hidden" name="college_id" :value="college_id" />
          </div>
{{--           <hr>
          <h4>Account Information</h4>
          <div class="form-group">
            <label>Email *</label>
            <input 
              type="email" 
              placeholder="Enter Email" 
              class="form-control" 
              name="email"
              value="{{ old('email') ? old('email')  : $faculty->user->email }}">
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
          </div> --}}
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
          first_name: '{{ old('first_name') ? old('first_name')  : $faculty->user->first_name  }}',
          middle_name: '{{ old('middle_name') ? old('middle_name')  : $faculty->user->middle_name }}',
          last_name: '{{ old('last_name') ? old('last_name')  : $faculty->user->last_name }}',
          showPass: false,
          college_id: '{{ $faculty->college_id }}'
        }
      }
  });
</script>
@endpush