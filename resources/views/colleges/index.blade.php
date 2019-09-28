@extends('layout.app', ['active' => 'colleges'])

@section('title', 'Colleges Index')

@section('content')

<div id="app" v-cloak>
  <college-modal v-on:refresh-colleges="getColleges" is-update="true" :college-id="college_id"></college-modal>


  <div class="card mb-3">
    <div class="card-body py-4">
      <div class="d-flex justify-content-between">
        <div>
          <h1 class="page-header"><i class="fa fa-university text-info"></i> Colleges</h1>
        </div>
        <div>
          {{-- <a href="{{ url('colleges/create') }}" class="btn btn-success-b">Add New College</a> --}}
          <college-modal v-on:refresh-colleges="getColleges"></college-modal>

        </div>
      </div>
    </div>
  </div>
  

  @if(count($colleges) > 0) 
    {{-- <div class="list-group">
      
      @foreach($colleges as $college)
        <a href="{{ url('colleges/' . $college->id) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
        {{ $college->name }}
          <i class="fa fa-chevron-right"></i>
        </a>
      @endforeach  
    </div> --}}

    <div class="card">
      <div class="card-body">
        <table class="table table-borderless">
          <thead>
            <tr>
              {{-- <th>ID</th> --}}
              <th>College Code</th>
              <th width="35%">Name</th>
              <th>Dean</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <template v-if="tableLoading">
              <tr>
                <td colspan="5">
                  <table-loading></table-loading>
                </td>
              </tr>
            </template>
            <template v-else>           
              <tr v-for="college in colleges" :key="college.id">
                {{-- <td>@{{ college.id }}</td> --}}
                <td>@{{ college.college_code }}</td>
                <td>@{{ college.name }}</td>
                <td>@{{ college.faculty.user.first_name + ' ' + college.faculty.user.last_name }}</td>
                <td>
                  <button v-on:click="selectCollege(college.id)" class="btn btn-sm btn-success">Update <i class="fa fa-edit"></i></button>
                  <a :href="'colleges/' + college.id" class="btn btn-sm btn-info">View <i class="fa fa-search"></i></a>
                  
                </td>
              </tr>
            </template>
          </tbody>
        </table>
      </div>
    </div>
  @else
    <div class="text-center bg-white p-3">No Colleges Found in Database.</div>
  @endif
</div>
@endsection

@push('scripts')
  <script>
    var vm = new Vue({
      el: '#app',
      data: {
        colleges: @json($colleges),
        college_id: '',
        tableLoading: false
      },
      methods: {
        getColleges() {
          this.tableLoading = true;
          ApiClient.get('/colleges?json=true')
          .then(response => {
            this.colleges = response.data;
            this.tableLoading = false;
          });
        },
        selectCollege(college_id) {
          this.college_id = college_id;
          $('#collegeModalUpdate').modal('show');
        }
      }
    });
  </script>
@endpush