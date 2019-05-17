@extends('layouts.sb_admin')

@section('title', 'Faculties Index')

@section('content')
  <div class="d-flex justify-content-between mb-3">
    <div>
      <h1 class="h2">List of Faculties</h1>
    </div>
    <div>
      <a href="{{ url('faculties/create') }}" class="btn btn-success valign-center">Add New Faculty &nbsp;<i class="material-icons">person_add</i></a>
    </div>
  </div>
  

  <div class="card" id="app">
    <div class="card-body">
      <div class="row">
        <div class="col-md-4">
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fa fa-search"></i></span>
            </div>
            <input v-on:input="searchFaculties" v-model="search" type="search" class="form-control" placeholder="Search faculty...">
          </div>
        </div>
        
      </div>
      

      <table id="faculty-table" class="table table-hover">
        <thead class="bg-light">
          <tr>
            <th scope="col">User ID</th>
            <th scope="col">Full Name</th>
            <th scope="col">Email</th>
            <th scope="col">College</th>
            <th scope="col">User Type</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
          <template v-if="tableLoading">
            <tr>
              <td colspan="6"><table-loading></table-loading></td>
            </tr>
          </template>
          <template v-else-if="faculties.length <= 0">
            <tr>
              <td class="text-center" colspan="6">No Record Found in Database.</td>
            </tr>
          </template>
          <template v-else>
            <tr v-for="faculty in faculties" >
                <td>@{{ faculty.user_id }}</td>
                <td>@{{ faculty.last_name + ', ' + faculty.first_name + ' ' + faculty.middle_name }}</td>
                <td>@{{ faculty.email }}</td>
                <td>@{{ faculty.college_code }}</td>
                <td>@{{ faculty.user_type }}</td>
                <td>
                  <a title="View Details" class="btn btn-primary btn-sm" :href=" 'faculties/' + faculty.id">
                    <i class="fa fa-eye"></i>
                  </a>
                  <a title="Edit Information" class="btn btn-success btn-sm" :href=" 'faculties/' + faculty.id + '/edit'">
                    <i class="fa fa-edit"></i>
                  </a>
                </td>
            </tr>
          </template>
          
            
          
        </tbody>
      </table>
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
    new Vue({
      el: '#app',
      data: {
        faculties: [],
        search: '',
        meta: {
          total: 0,
          per_page: 0,
          current_page: 1
        },
        links: {},
        totalPagination: 0,
        tableLoading: false
      },
      methods: {
        getFaculties(page) {
          this.tableLoading = true;
          Services.getFaculties(page)
          .then(response => {
            this.faculties = response.data.data;
            this.meta = response.data.meta;
            this.links = response.data.links;
            this.totalPagination = Math.ceil(this.meta.total / this.meta.per_page);
            this.tableLoading = false;
          });
        },
        searchFaculties() {
          this.tableLoading = true;
          if(this.search.trim() == '') {
            return this.getFaculties();
          }
          Services.searchFaculties(this.search)
          .then(response => {
            this.faculties = response.data;
            this.tableLoading = false;
          })
        },
        paginate(page) {
          this.getFaculties(page);
        }
      },
      created() {
        this.getFaculties(this.meta.current_page);
      }
    });
  </script>
@endpush