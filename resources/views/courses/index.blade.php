@extends('layout.app', ['active' => 'courses'])

@section('title', 'Courses Index')


@section('content')
<div id="app">
  <div class="d-flex justify-content-between mb-3">
    <div>
      <h1 class="page-header">List of Courses</h1>
    </div>

    <div>
      @if(Gate::check('isDean') || Gate::check('isSAdmin'))
        <!-- COURSE MODAL -->
        <course-modal :college-id="college_id" :colleges='@json($colleges)'></course-modal>
        <!-- END MODAL -->
      @endif
    </div>
  </div>
  <div class="card">
    <div class="card-body">
      <div class="row d-flex justify-content-between mb-3">
        <div class="col-md-4">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fa fa-search"></i></span>
            </div>
            <input v-on:input="searchCourses" v-model="search" type="search" class="form-control" placeholder="Search course...">
          </div>
        </div>
        @can('isSAdmin')
          <div class="col-md-4 d-flex row align-items-center">
            <div class="col-6 text-right">
              <label class="col-form-label">Filter By College:</label>
            </div>
            <div class="col-6">
              <select v-on:change="getCourses" class="form-control" name="filter_by_college" id="filter_by_college" v-model="filter_by_college">
                <option value="">All</option>
                @foreach($colleges as $college)
                  <option value="{{ $college->id }}">{{ $college->college_code }}</option>
                @endforeach
              </select>
            </div>   
          </div>
        @endcan
        <div class="col-md-4 d-flex row">
          <div class="col-6 text-right">
            <label class="col-form-label">Filter By Privacy:</label>
          </div>
          <div class="col-6">
            <select v-on:change="getCourses" class="form-control" name="filter_by_privacy" id="filter_by_college" v-model="filter_by_privacy">
              <option value="">All</option>
              <option value="public">Public</option>
              <option value="private">Private</option>
            </select>
          </div>   
        </div>
        
      </div>
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">Course ID</th>
              <th scope="col">Course Code</th>
              <th scope="col">Description</th>
              <th scope="col">Units</th>
              <th scope="col">College</th>
              <th scope="col">Privacy</th>
              <th scope="col">Actions</th>
            </tr>
          </thead>
          <tbody>
            <template v-if="tableLoading">
              <tr>
                <td colspan="8"><table-loading></table-loading></td>
              </tr>
            </template>
            <template v-else-if="courses.length <= 0">
              <tr>
                <td class="text-center" colspan="8">No Record Found in Database.</td>
              </tr>
            </template>
            <template v-else>
              <tr v-for="course in courses" :key="course.id">
                <td>@{{ course.id }}</td>
                <td>@{{ course.course_code }}</td>
                <td>@{{ course.description }}</td>
                <td>@{{ course.lec_unit + course.lab_unit }}</td>
                <td>@{{ course.college_code}}</td>
                <td>
                  <span v-if="course.is_public">public <i class="fa fa-globe-americas"></i></span>
                  <span v-else>private <i class="fa fa-lock"></i></span></td>



                <td>
                  <a title="View Details" class="btn btn-success btn-sm" :href=" 'courses/' + course.id">
                    <i class="fa fa-search"></i>
                  </a>
                </td>
              </tr>
            </template>
          </tbody>
        </table>
      </div>
      <!-- Pagination -->
      <div>Showing @{{ courses.length }} of {{ $courses_count }} records</div>
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
       <!-- End Pagination -->
    </div>
  </div>
</div>
@endsection


@push('scripts')
<script>
  const vm = new Vue({
    el: '#app',
    data: {
      courses: [],
      tableLoading: true,
      search: '',
      meta: {
          total: 0,
          per_page: 0,
          current_page: 1
        },
      links: {},
      totalPagination: 0,
      filter_by_college: '',
      college_id: '{{ Session::get('college_id') }}',
      filter_by_privacy: ''
    },
    methods: {
      getCourses(page=1) {
        this.tableLoading = true;
        ApiClient.get('/courses/?page=' + page + '&filter_by_college=' + this.filter_by_college + '&filter_by_privacy=' + this.filter_by_privacy)
        .then(response => {
          this.courses = response.data.data;
          this.tableLoading = false;
          this.meta = response.data.meta;
          this.links = response.data.links;
          this.totalPagination = Math.ceil(this.meta.total / this.meta.per_page);
        }).
        catch(err => {
          console.log(err);
          serverError();
          this.tableLoading = false;
        })
      },
      searchCourses : _.debounce(() => {
        if(vm.search == '') {
          return vm.getCourses();
        }
        vm.filter_by_college = '';
        vm.filter_by_privacy = '';
        vm.tableLoading = true;
        ApiClient.get('/courses/?q=' + vm.search)
        .then(response => {
          vm.courses = response.data.data;
          vm.tableLoading = false;
        }).
        catch(err => {
          console.log(err);
          vm.tableLoading = false;
        })
      }, 400),
      paginate(page) {
          this.getCourses(page);
      }
    },
    created() {
      setTimeout(() => {
        this.getCourses();
      }, 
      1000);
      
    }
  });
</script>


@endpush