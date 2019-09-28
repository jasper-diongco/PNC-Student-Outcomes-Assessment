@extends('layout.app', ['active' => 'faculties'])

@section('title', 'Faculties Index')

@section('content')
<div id="app">
  <faculty-modal is-dean="{{ Gate::check('isDean') ? 'true': 'false' }}" college-id="{{ Session::get('college_id') }}" :colleges='@json($colleges)' v-on:refresh-faculties="getFaculties"></faculty-modal>

  <faculty-modal is-dean="{{ Gate::check('isDean') ? 'true' : 'false' }}" college-id="{{ Session::get('college_id') }}" :colleges='@json($colleges)' v-on:refresh-faculties="getFaculties" is-update="true" :faculty-id="faculty_id"></faculty-modal>

  

  <div class="card">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-0">
        <div>
          <h1 class="page-header">List of Faculties</h1>
        </div>
        <div>
          @if(Gate::check('isDean') || Gate::check('isSAdmin'))
            <a href="#" data-toggle="modal" data-target="#facultyModal" class="btn btn-success-b">Add New Faculty</a>
          @endif
        </div>
      </div>
      <div class="d-flex justify-content-between">
        <div>
          <div class="input-group mb-3" id="search-input">
            <input v-on:input="searchFaculties" v-model="search" type="search" class="form-control" placeholder="Search faculty...">
            <div class="input-group-append">
              <span class="input-group-text"><i class="fa fa-search"></i></span>
            </div>
          </div>
        </div>
        <div>
        @if ($deactivated_faculties_count > 0)
            <div>
              <a href="{{ url('/faculties/deactivated') }}" class="btn btn-light btn-sm">View Deactivated Faculties ({{ $deactivated_faculties_count }}) <i class="fa fa-users"></i></a>
            </div>
        @endif
        @can('isSAdmin')
          <div class="d-flex align-items-baseline">
            <div>
              <label class="text-dark mr-2">Filter By College</label>
            </div>
            <div>
              <select class="form-control" v-model="filter_college_id" v-on:change="filterByCollege">
                <option value="">All</option>
                @foreach($colleges as $college)
                  <option value="{{ $college->id }}">{{ $college->college_code }}</option>
                @endforeach
            </select>
            </div>
            
          </div>
        @endcan
        </div>
      </div>
      
      <div class="table-responsive">
        <table id="faculty-table" class="table table-borderless">
          <thead>
            <tr>
              <th scope="col">User ID</th>
              <th scope="col">Full Name</th>
              <th scope="col">Email</th>
              <th scope="col">College</th>
              <th scope="col">User Type</th>
              @if(Gate::check('isSAdmin') || Gate::check('isDean'))
              <th scope="col" class="text-center">Actions</th>
              @endif
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
                  <td>@{{ faculty.last_name + ', ' + faculty.first_name + ' ' + (faculty.middle_name ? faculty.middle_name  : '') }}</td>
                  <td>@{{ faculty.email }}</td>
                  <td>@{{ faculty.college_code }}</td>
                  <td>@{{ faculty.user_type }}</td>
                  @if(Gate::check('isSAdmin') || Gate::check('isDean'))
                  <td align="right">
                    
                      <a title="View Details" class="btn btn-info btn-sm" :href=" 'faculties/' + faculty.id">
                        View <i class="fa fa-search"></i>
                      </a>
                      <button title="Edit Information" v-on:click="openUpdateModal(faculty.id)" class="btn btn-success btn-sm">
                        Update <i class="fa fa-edit"></i>
                      </button>
                    
                  </td>
                  @endif
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
        faculties: [],
        search: '',
        meta: {
          total: 0,
          per_page: 0,
          current_page: 1
        },
        links: {},
        totalPagination: 0,
        tableLoading: true,
        faculty_id: '',
        filter_college_id: ''
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
        filterByCollege() {
          this.tableLoading = true;
          ApiClient.get("/faculties?json=true&filter_by_college=" + this.filter_college_id)
            .then(response => {
              this.faculties = response.data.data;
              this.meta = response.data.meta;
              this.links = response.data.links;
              this.totalPagination = Math.ceil(this.meta.total / this.meta.per_page);
              this.tableLoading = false;
            });
        },
        searchFaculties: _.debounce(() => {
            vm.tableLoading = true;
            if(vm.search.trim() == '') {
              return vm.getFaculties();
            }
            Services.searchFaculties(vm.search)
            .then(response => {
              vm.faculties = response.data.data;
              vm.tableLoading = false;
            })
          }, 300),
        paginate(page) {
          this.getFaculties(page);
        },
        openUpdateModal(faculty_id) {
          this.faculty_id = faculty_id;
          $('#facultyModalUpdate').modal('show');

        }
      },
      created() {
        setTimeout(() => {
          this.getFaculties(this.meta.current_page);
        }, 300);
        
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