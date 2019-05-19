@extends('layouts.sb_admin')

@section('title', 'Deactivated Courses')

@section('content')

<a href="{{ url('/curricula/' . request('curriculum_id')) }}" class="btn btn-success mb-3 btn-sm"><i class="fa fa-arrow-left"></i> Back</a>

<div id="app">
  <div class="d-flex justify-content-between mb-3">
    <div>
      <h1 class="h3 mb-4 text-gray-800">List of deactivated courses</h1>
    </div>
  </div>

  @if(count($curriculum_courses) > 0) 
      <table class="table">
        <thead class="thead-dark">
          <tr>
            <tr>
              <th><input type="checkbox" v-model="is_checked_all"></th>
              <th>Course Code</th>
              <th>Description</th>
              <th>Lec Unit</th>
              <th>Lab Unit</th>
              <th>Year/Sem</th>
              <th class="text-center">Action</th>
            </tr>
          </tr>
        </thead>
        <tbody class="bg-white">
          @foreach ($curriculum_courses as $curriculum_course)
            <tr>
              <td><input type="checkbox" v-model="checked_courses" value="{{ $curriculum_course->id }}"></td>
              <th>{{ $curriculum_course->course->course_code }}</th>
              <td>{{ $curriculum_course->course->description }}</td>
              <td>{{ $curriculum_course->course->lec_unit }}</td>
              <td>{{ $curriculum_course->course->lab_unit }}</td>
              <td>{{ $curriculum_course->year_level . ' / ' . $curriculum_course->semester  }}</td>
              <td class="text-center">
                <form v-on:submit.prevent="activateCourse" action="{{ url('/curriculum_courses/' . $curriculum_course->id . '/activate') }}" method="post">
                  @csrf
                  <button class="btn btn-success btn-sm">Activate <i class="fa fa-history"></i></button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
      <div v-show="checked_courses.length > 0">
        <form v-on:submit.prevent="activateSelected" action="{{ url('/curriculum_courses/activate_selected') }}" method="post">
          @csrf
          <input type="hidden" name="checked_courses" :value="checked_courses_json()">
          <button class="btn btn-primary btn-sm">Activate <i class="fa fa-history"></i></button>
        </form>
      </div>
    
  @else
    <div class="text-center bg-white p-3">No Deactivated Course Found in Database.</div>
  @endif

  <div class="my-3 d-flex justify-content-end">
    {{ $curriculum_courses->appends(request()->input())->links() }}
  </div>
</div>

  
@endsection

@push('scripts')
  <script>
    new Vue({
      el: '#app',
      data: {
        checked_courses: [],
        is_checked_all: false,
        curriculum_courses: @json($curriculum_courses)
      },
      watch: {
        is_checked_all() {
          if(this.is_checked_all) {
            this.checkAll();
          } else {
            this.checked_courses = [];
          }
        }
      },
      methods: {
        activateCourse(event) {
          swal.fire({
            title: 'Do you want to activate this course?',
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#1cc88a',
            cancelButtonColor: '#858796',
            confirmButtonText: 'Yes',
            width: '400px'
          }).then((result) => {
            if (result.value) {
              event.target.submit();
            }
          });
        },
        activateSelected(event) {
          swal.fire({
            title: 'Do you want to activate selected course?',
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#1cc88a',
            cancelButtonColor: '#858796',
            confirmButtonText: 'Yes',
            width: '400px'
          }).then((result) => {
            if (result.value) {
              event.target.submit();
            }
          });
        },
        checkAll() {
          this.checked_courses = [];
          for(let i = 0; i < this.curriculum_courses.data.length; i++) {
            this.checked_courses.push(this.curriculum_courses.data[i].id)
          }
        },
        checked_courses_json() {
          return JSON.stringify(this.checked_courses);
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