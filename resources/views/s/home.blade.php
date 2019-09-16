@extends('layout.app', ['active' => 'home-student'])


@section('title', 'Home')

@section('content')
    <div id="app">
        <h1 class="page-header mb-3">Home</h1>
        {{-- <div class="card">
            <div class="card-body pt-4">
                <h2 style="font-weight: 300; font-size: 20px">Hello, {{ ucwords(strtolower(auth()->user()->first_name)) }}. Welcome Back!</h2>
            </div>
        </div> --}}
        <div class="alert" style="color: #028c4a;background-color: #b8f3ba;border-color: #13f387;">
            {{-- <h2 style="font-weight: 300; font-size: 20px">Hello, {{ ucwords(strtolower(auth()->user()->first_name)) }}. Welcome Back!</h2> --}}
            <h5>Hello, {{ ucwords(strtolower(auth()->user()->first_name)) }}. Welcome Back!</h5>
        </div>
        
        <hr>
        <div class="mt-3 row">
            <div class="col-md-3 mb-4">
              <div class="card shadow dashboard-card d-flex flex-column" style="background: #8BC34A" v-on:click="changeLocation('/s/assessments')">
                <div class="card-body py-2">
                  <img class="w-100" height="80px" src="{{ asset('img/icon_svg/assessment.svg') }}" alt="assessment picture">

                  
                </div>
                <div class="dashboard-card-footer">
                    <div class="dashboard-count text-info mt-3">{{ $assessments->count() }}</div>
                    <h3 class="card-name">Assessments</h3>
                </div>
              </div>
            </div>
            <div class="col-md-3 mb-4 ">
              <div class="card shadow dashboard-card" style="background: #209ba7" v-on:click="changeLocation('/s/' + student.id + '/obe_curriculum')">
                <div class="card-body py-2">
                  <img class="w-100" height="80px" src="{{ asset('img/icon_svg/books.svg') }}" alt="assessment picture">

                  
                </div>
                <div class="dashboard-card-footer">
                    <div class="dashboard-count text-info">{{ count($student->curriculum->getDoneCurriculumCourses($student->id)) }} / {{ $student->curriculum->curriculumCourses->count() }}</div>


                    <div class="px-4">
                        <div class="progress">
                          <div class="progress-bar bg-success" role="progressbar" style="width: {{ count($student->curriculum->getDoneCurriculumCourses($student->id)) / ($student->curriculum->curriculumCourses->count() == 0 ? 1 : $student->curriculum->curriculumCourses->count()) * 100 }}%" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <h3 class="card-name">Courses</h3>
                </div>
              </div>
            </div>
            <div class="col-md-3 mb-4 ">
              <div class="card shadow dashboard-card" style="background: #ffe69c" v-on:click="changeLocation('/s/' + student.id + '/obe_curriculum')">
                <div class="card-body py-2">
                  <img class="w-100" height="80px" src="{{ asset('img/icon_svg/folder1.svg') }}" alt="assessment picture">

                  
                </div>
                <div class="dashboard-card-footer">
                    <div class="dashboard-count text-info">{{ $student->curriculum->getDoneUnits($student->id) }} / {{ $student->curriculum->totalUnits() }}</div>

                    <div class="px-4">
                        <div class="progress">
                          <div class="progress-bar bg-success" role="progressbar" style="width: {{ $student->curriculum->getDoneUnits($student->id) / ($student->curriculum->totalUnits() == 0 ? 1 : $student->curriculum->totalUnits())  * 100 }}%" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <h3 class="card-name">Units</h3>
                </div>
              </div>
            </div>
        </div>

        <div class="mt-3 card">
            <div class="card-body py-3">
                <h5><i class="fa fa-flag text-info"></i> Student Outcomes</h5>
            </div>
        </div>

        <ul class="list-group list-student-outcomes mt-3">
            @foreach($student->program->studentOutcomes as $student_outcome)
            <li class="list-group-item">
                <div class="d-flex align-items-center mr-3">
                    <div>
                      <span class="avatar-student-outcome mr-3" style="background:#86db67">{{ $student_outcome->so_code }}</span>
                    </div>
                    <span>{{ $student_outcome->description }}</span>

                </div>
                
            </li>
            @endforeach
        </ul>
{{-- 
#6c50bd
 --}}

    </div>
@endsection

@push('scripts')
    <script>
        var vm = new Vue({
            el: '#app',
            data: {
                student: @json($student)
            },
            methods: {
                changeLocation(url) {
                 window.location.href = myRootURL + url;
                }
            }
        });
    </script>
@endpush