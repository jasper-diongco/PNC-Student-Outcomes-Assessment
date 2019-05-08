@extends('layouts.master')

@section('title', 'Courses Index')


@section('content')
<div id="app">
  <div class="d-flex justify-content-between mb-3">
    <div>
      <h1 class="h2">List of Courses</h1>
    </div>

    <div>
      <course-modal :colleges='@json($colleges)'></course-modal>
    </div>
  </div>


  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-md-4">
          <input class="form-control mb-3" type="search" placeholder="Search course...">
        </div>
      </div>
      <table class="table">
        <thead class="bg-light">
          <tr>
            <th scope="col">#</th>
            <th scope="col">Course ID</th>
            <th scope="col">Course Code</th>
            <th scope="col">Description</th>
            <th scope="col">Units</th>
            <th scope="col">College</th>
            <th scope="col">Privacy</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="course in courses" :key="course.id">
            <th><div class="avatar-course" :style="{ 'background': course.color }">@{{ course.course_code }}</div></th>
            <td>@{{ course.id }}</td>
            <td>@{{ course.course_code }}</td>
            <td>@{{ course.description }}</td>
            <td>@{{ course.lec_unit + course.lab_unit }}</td>
            <td>@{{ course.college_code}}</td>
            <td>
              <span v-if="course.is_public" class="badge badge-success">public <i class="fa fa-globe-americas"></i></span>
              <span v-else class="badge badge-secondary">private <i class="fa fa-lock"></i></span></td>



            <td>
              <a title="View Details" class="btn btn-primary btn-sm" :href=" 'courses/' + course.id">
                <i class="fa fa-eye"></i>
              </a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection


@push('scripts')
<script>
  new Vue({
    el: '#app',
    data: {
      courses: []
    },
    methods: {
      getCourses() {
        ApiClient.get('/courses')
        .then(response => {
          this.courses = response.data.data;
        }).
        catch(err => {
          console.log(err);
          serverError();
        })
      }
    },
    created() {
      setTimeout(() => {
        this.getCourses();
      }, 300);
      
    }
  });
</script>


@endpush