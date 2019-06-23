@extends('layouts.sb_admin')

@section('title', 'Deactivated Faculties')

@section('content')

<a href="{{ url('/faculties') }}" class="btn btn-success mb-3 btn-sm"><i class="fa fa-arrow-left"></i> Back</a>

<div id="app">
  <div class="d-flex justify-content-between mb-2">
    <div>
      <h1 class="h3 text-gray-800">List of deactivated faculties</h1>
    </div>
  </div>

  @if(count($faculties) > 0) 
      <table class="table">
        <thead class="bg-light">
          <tr>
            <tr>
              <th><input type="checkbox" v-model="is_checked_all" v-on:change="toggleCheckAll"></th>
              <th>ID</th>
              <th>FullName</th>
              <th>Email</th>
              <th>College</th>
              <th>User Type</th>
              <th class="text-center">Action</th>
            </tr>
          </tr>
        </thead>
        <tbody class="bg-white">
          @foreach ($faculties as $faculty)
            <tr>
              <td><input type="checkbox" v-model="checked_faculties" value="{{ $faculty->user->id }}"></td>
              <th>{{ $faculty->user->id }}</th>
              <td>{{ $faculty->user->getFullName() }}</td>
              <td>{{ $faculty->user->email }}</td>
              <td>{{ $faculty->college->college_code }}</td>
              <td>{{ $faculty->user->userType->description }}</td>
              <td class="text-center">
                <form v-on:submit.prevent="activateFaculty" action="{{ url('/users/' . $faculty->user->id . '/activate') }}" method="post">
                  @csrf
                  <input type="hidden" name="user_type" value="faculty">
                  <button class="btn btn-success btn-sm">Activate <i class="fa fa-history"></i></button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
      <div v-show="checked_faculties.length > 0">
        <form v-on:submit.prevent="activateSelected" action="{{ url('/users/activate_selected') }}" method="post">
          @csrf
          <input type="hidden" name="user_type" value="faculty">
          <input type="hidden" name="checked_users" :value="checked_users_json()">
          <button class="btn btn-primary btn-sm">Activate <i class="fa fa-history"></i></button>
        </form>
      </div>
    
  @else
    <div class="text-center bg-white p-3">No Deactivated Faculties Found in Database.</div>
  @endif

  <div class="my-3 d-flex justify-content-end">
    {{ $faculties->appends(request()->input())->links() }}
  </div>
</div>

  
@endsection

@push('scripts')
  <script>
    var vm = new Vue({
      el: '#app',
      data: {
        is_checked_all: false,
        checked_faculties: [],
        faculties: @json($faculties)
      },
      watch: {
        // is_checked_all() {
        //   if(this.is_checked_all) {
        //     this.checkAll();
        //   } else {
        //     this.checked_faculties = [];
        //   }
        // }
      },
      methods: {
        activateFaculty(event) {
          swal.fire({
              title: 'Do you want to activate this faculty?',
              text: "Please confirm",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#1cc88a',
              cancelButtonColor: '#858796',
              confirmButtonText: 'Yes',
              width: '350px'
            }).then((result) => {
              if (result.value) {
                event.target.submit();
              }
            });
        },
        activateSelected(event) {
          swal.fire({
              title: 'Do you want to activate the selected users?',
              text: "Please confirm",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#1cc88a',
              cancelButtonColor: '#858796',
              confirmButtonText: 'Yes',
              width: '350px'
            }).then((result) => {
              if (result.value) {
                event.target.submit();
              }
            });
        },
        checkAll() {
          this.checked_faculties = [];
          for(let i = 0; i < this.faculties.data.length; i++) {
            this.checked_faculties.push(this.faculties.data[i].user.id);
          }
        },
        toggleCheckAll() {
          
          if(this.is_checked_all) {
            this.checkAll();
          } else {
            this.checked_faculties = [];
          }
        },
        checked_users_json() {
          return JSON.stringify(this.checked_faculties);
        }
      }
    })
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