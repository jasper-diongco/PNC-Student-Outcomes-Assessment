@extends('layout.app', ['active' => 'faculties'])

@section('title', $faculty->user->getFullName())

@section('content')
  <a href="{{ url('faculties') }}" class="text-success"><i class="fa fa-arrow-left"></i> Back</a>
  
  {{-- @if(Session::has('message'))
    @component('components.alert')
      {{ Session::get('message') }}
    @endcomponent
  @endif --}}

  
  
<div id="app" v-cloak>
    <faculty-modal is-dean="{{ Gate::check('isDean') ? 'true' : 'false' }}" college-id="{{ Session::get('college_id') }}" :colleges='@json($colleges)' is-update="true" :faculty-id="{{ $faculty->id }}" :refresh-update="true"></faculty-modal>
        <div class="card mt-4" >

        <div class="card-body pt-4">
        {{-- <h1 class="page-header text-info">Faculty Information</h1> --}}
        <div class="d-flex justify-content-between">
          <div>
            <h4><i class="fa fa-user text-info"></i> {{ $faculty->user->getFullName()}}</h4>
          </div>
          @if(Gate::check('isDean') || Gate::check('isSAdmin'))
            <div class="d-flex">
              @if (!(Gate::check('isDean') && $faculty->user->user_type_id == 'dean'))
                <div>
                  <form v-on:submit.prevent="deactivateUser" action="{{ url('/users/'. $faculty->user->id .'/deactivate') }}" method="post">
                    @csrf
                    <input type="hidden" name="user_type" value="faculty">
                    <button class="btn btn-secondary btn-sm mr-2">Deactivate <i class="fa fa-user-slash"></i></button>
                  </form>
                </div>
              @endif
              <div>
                {{-- <a href="{{ url('faculties/' . $faculty->id . '/edit') }}" class="btn btn-success btn-sm">Update Information <i class="fa fa-edit"></i></a> --}}
                <button data-toggle="modal" data-target="#facultyModalUpdate" class="btn btn-success btn-sm">Update Information <i class="fa fa-edit"></i></button>
              </div>
            </div>
          @endif
        </div>
        
        <label class="text-info mt-3">Details</label>
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><label>Full Name:</label> {{ $faculty->user->getFullName()}} </li>
            <li class="list-group-item"><label>Last Name:</label> {{ $faculty->user->last_name}} </li>
            <li class="list-group-item"><label>First Name:</label> {{ $faculty->user->first_name}} </li>
            <li class="list-group-item"><label>Middle Name:</label> {{ $faculty->user->middle_name}} </li>
            <li class="list-group-item"><label>Sex:</label> {{ $faculty->user->sex == 'M' ? 'Male' : 'Female' }}</li>
            <li class="list-group-item"><label>Date of Birth:</label> @{{ dateOfBirth() }}</li>
            <li class="list-group-item"><label>College:</label> {{ $faculty->college->name }}</li>
            <li class="list-group-item"><label>Email:</label> {{ $faculty->user->email }} </li>
            <li class="list-group-item"><label>User Type:</label> {{ $faculty->user->userType->description }}</li>
        </ul>
    </div>
</div>
{{--     <div class="card mt-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h4>Account Information</h4>
  </div>
  <div class="card-body">
    <ul class="list-group list-group-flush">
      <li class="list-group-item"><b>Email:</b> {{ $faculty->user->email }} </li>
      <li class="list-group-item"><b>User Type:</b> {{ $faculty->user->userType->description }}</li>
    </ul>
  </div>
</div> --}}

<div class="card mt-4">
    <div class="card-body py-4">
        <div class="d-flex justify-content-between align-items-baseline">
            <div>
                <h5><i class="fa fa-book text-info"></i>  List of courses</h5>
            </div>
            <div><button class="btn btn-info" v-on:click="is_show_add = true">Add course</button></div>
            
            
            
        </div>

        <div v-if="is_show_add" class="mt-3">
            <div class="input-group mb-3" id="search-input">
                <input v-on:input="searchCourse" v-model="search"  type="search" class="form-control" placeholder="Search courses...">
                <div class="input-group-append">
                  <span class="input-group-text"><i class="fa fa-search"></i></span>
                </div>

            
            </div>

            <div class="mt-3">
                <div v-if="search_loading">
                    <table-loading></table-loading>
                </div>
                <div v-else>
                    <div v-if="searched_courses.length > 0">
                        <ul class="list-group">
                            <li v-for="searched_course in searched_courses" :key="searched_course.id" class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex justify-content-between">
                                        <div class="d-flex align-items-baseline">
                                            <div class="avatar mr-2"><i class="fa fa-book text-success"></i></div>
                                            <div style="font-size:18px"><strong>@{{ searched_course.course_code }}</strong> - @{{ searched_course.description }}</div>
                                        </div>
                                    </div>
                                    <button v-on:click="addCourseLoad(searched_course.id)" class="btn btn-sm btn-success">Add</button>
                                </div>
                                <div class="text-muted ml-4 pl-3">@{{ searched_course.lec_unit + searched_course.lab_unit  }} units</div>
                            </li>
                        </ul>
                    </div>
                    <div class="text-center p-3 bg-light" v-else>
                        No result.
                    </div>
                </div>
            </div>
        </div>


        
    </div>
</div>

<div v-if="course_load_loading">
    <table-loading></table-loading>
</div>
<div v-else>
    <div v-if="course_loads.length > 0">
        <ul class="list-group mt-3">
            <li v-for="course_load in course_loads" class="list-group-item">
                <div class="d-flex justify-content-between">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex align-items-baseline">
                            <div class="avatar mr-2"><i class="fa fa-book" :class="{ 'text-success' : course_load.is_active, 'text-dark': !course_load.is_active }"></i></div>
                            <div style="font-size:18px"><strong>@{{ course_load.course.course_code }}</strong> - @{{ course_load.course.description }}</div>
                        </div>
                    </div>
                    <div>
                        <button v-on:click="deactivateCourseLoad(course_load.id)" v-if="course_load.is_active" class="btn btn-sm btn-dark">Remove</button>
                        <button v-on:click="activateCourseLoad(course_load.id)" v-else class="btn btn-sm btn-success">Activate</button>
                    </div>
                </div>
                <div class="text-muted ml-4 pl-3">@{{ course_load.course.lec_unit + course_load.course.lab_unit  }} units</div>
            </li>
        </ul>
    </div>
    <div class="text-center p-3 bg-light" v-else>
        No course assigned.
    </div>
</div>
</div>
@endsection

@push('scripts')
    <script>
        var vm = new Vue({
            el: '#app',
            data: {
                faculty_id: {{ $faculty->id }},
                is_show_add: false,
                search: '',
                searched_courses: [],
                search_loading: false,
                course_load_loading: false,
                course_loads: []
            },
            methods: {
                searchCourse: _.debounce(() => {
                    if(!vm.search) {
                        return vm.searched_courses = [];
                    }
                    vm.search_loading = true;
                    ApiClient.get('/courses?q=' + vm.search)
                    .then(response => {
                        vm.search_loading = false;
                        vm.searched_courses = response.data.data;
                    })
                }),
                addCourseLoad(course_id) {
                    ApiClient.post('/faculty_courses', {
                        course_id: course_id,
                        faculty_id: this.faculty_id
                    })
                    .then(response => {
                        this.searched_courses = [];
                        this.search = '';
                        this.getCourseLoad();
                    })
                },
                getCourseLoad() {
                    this.course_load_loading = true;
                    ApiClient.get('/faculty_courses?faculty_id=' + this.faculty_id)
                    .then(response => {
                        this.course_loads = response.data;
                        this.course_load_loading = false;
                    })
                },
                activateCourseLoad(faculty_course_id) {
                    ApiClient.post('/faculty_courses/' + faculty_course_id + '/activate')
                    .then(response => {
                        this.getCourseLoad();
                    })
                },
                deactivateCourseLoad(faculty_course_id) {
                    ApiClient.post('/faculty_courses/' + faculty_course_id + '/deactivate')
                    .then(response => {
                        this.getCourseLoad();
                    })
                },
                deactivateUser(event) {
                  swal.fire({
                    title: 'Do you want to deactivate this user?',
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
                parseDate(date) {
                  return moment(date).format("MMM D, YYYY");  
                },
                dateOfBirth() {
                  return this.parseDate('{{ $faculty->user->date_of_birth  }}');
                }
            },
            created() {
                this.getCourseLoad();
            }
        });
    </script>
@endpush