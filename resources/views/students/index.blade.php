@extends('layouts.sb_admin')

@section('title', 'Students Index')

@section('content')
  <div class="d-flex justify-content-between mb-3">
    <div>
      <h1 class="h2">List of Students</h1>
    </div>
    <div>
      @if(Gate::check('isProf') || Gate::check('isDean') || Gate::check('isSAdmin'))
        <a href="{{ url('/students/create') }}" class="btn btn-success btn-round">Add New Student &nbsp;<i class="fa fa-plus"></i></a>
      @endif
    </div>
  </div>
  

  <div class="card shadow" id="app">
    <div class="card-body">
      <div class="row">
        <div class="col-md-4">
          <div class="input-group mb-3">
            
            <input v-on:input="searchStudent" v-model="search" type="search" class="form-control" placeholder="Search student...">
            <div class="input-group-append">
              <button class="input-group-text btn btn-primary"><i class="fa fa-search"></i></button>
            </div>
          </div>
        </div>
       {{--  @if ($deactivated_faculties_count > 0)
          <div class="col-md-4 offset-4">
            <div class="d-flex justify-content-end">
              <a href="{{ url('/faculties/deactivated') }}" class="btn btn-dark btn-sm">View Deactivated Faculties ({{ $deactivated_faculties_count }}) <i class="fa fa-users"></i></a>
            </div>
          </div>
        @endif --}}
        
      </div>
      
      <div class="table-responsive">
        <table id="students-table" class="table table-hover">
          <thead class="bg-dark text-white">
            <tr>
              <th scope="col">Student ID</th>
              <th scope="col">Full Name</th>
              <th scope="col">Email</th>
              <th scope="col">College</th>
              <th scope="col">Program</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
            <template v-if="tableLoading">
              <tr>
                <td colspan="6"><table-loading></table-loading></td>
              </tr>
            </template>
            <template v-else-if="students.length <= 0">
              <tr>
                <td class="text-center" colspan="6">No Record Found in Database.</td>
              </tr>
            </template>
            <template v-else>
              <tr v-for="student in students" >
                  <td>@{{ student.student_id }}</td>
                  <td>@{{ student.full_name }}</td>
                  <td>@{{ student.email }}</td>
                  <td>@{{ student.college_code }}</td>
                  <td>@{{ student.program_code }}</td>
                  <td>
                    <a title="View Details" class="btn btn-primary btn-sm" :href=" 'students/' + student.id">
                      <i class="fa fa-eye"></i>
                    </a>
                  </td>
              </tr>
            </template>
            
              
            
          </tbody>
        </table>
      </div>
      <nav v-show="search.trim() == ''">
        <ul class="pagination justify-content-end">
          <li class="page-item" :class="{ disabled: meta.current_page == 1 }">
            <a class="page-link" href="#" v-on:click.prevent="paginate(meta.current_page - 1)">Previous</a>
          </li>
          <template v-for="num in totalPagination">
            <li class="page-item" :class="{ active : num == meta.current_page }">
              <a class="page-link" href="#" v-on:click.prevent="paginate(num)">@{{ num }}</a>
            </li>
          </template>
          <li class="page-item" :class="{ disabled: meta.current_page == meta.last_page }">
            <a class="page-link" href="#" v-on:click.prevent="paginate(meta.current_page + 1)">Next</a>
          </li>
        </ul>
      </nav>
    </div>
  </div>

@endsection

@push('scripts')
  {{-- <script>
    $(document).ready(function() {
      $('#faculty-table').DataTable();
    })
  </script> --}}
  <script>
    var vm = new Vue({
      el: '#app',
      data: {
        students: [],
        search: '',
        meta: {
          total: 0,
          per_page: 0,
          current_page: 1
        },
        links: {},
        totalPagination: 0,
        tableLoading: true
      },
      methods: {
        getStudents(page=1) {
          this.tableLoading = true;
          ApiClient.get('/students?page=' + page + '&json=true')
          .then(response => {
            this.students = response.data.data;
            this.meta = response.data.meta;
            this.links = response.data.links;
            this.totalPagination = Math.ceil(this.meta.total / this.meta.per_page);
            this.tableLoading = false;
          });
        },
        searchStudent: _.debounce(() => {
            vm.tableLoading = true;
            if(vm.search.trim() == '') {
              return vm.getStudents();
            }
            ApiClient.get('/students?q=' + vm.search + '&json=true')
            .then(response => {
              vm.students = response.data.data;
              vm.tableLoading = false;
            })
          }, 500),
        paginate(page) {
          this.getStudents(page);
        }
      },
      created() {
        setTimeout(() => {
          this.getStudents(this.meta.current_page);
        }, 1000);
        
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