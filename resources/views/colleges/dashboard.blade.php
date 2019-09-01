@extends('layout.app', ['active' => 'dashboard'])

@section('title', $college->college_code)

@section('content')
  
  
  @if(Session::has('message'))
    @component('components.alert')
      {{ Session::get('message') }}
    @endcomponent
  @endif
  <div id="app">
    @if (!$password_changed)
      <account-modal email="{{ Auth::user()->email }}" user_id="{{ Auth::user()->id }}"></account-modal>
    @endif

    <h1 class="page-header">{{ $college->name }}</h1>

    <div class="w-25"><bar-chart :data="sampleData"></bar-chart></div>

    {{-- <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Details</h4>
        <a href="{{ url('colleges/' . $college->id . '/edit') }}" class="btn btn-light">Edit <i class="fa fa-edit"></i></a>
      </div>
      <div class="card-body">
        <ul class="list-group list-group-flush">
          <li class="list-group-item"><b>Dean:</b>
            <a href="{{ url('faculties/' . $college->faculty->id) }}">{{ $college->faculty->user->getFullName() }}</a>
            
          </li>
          <li class="list-group-item"><b>College Code:</b> {{ $college->college_code }}</li>
          <li class="list-group-item"><b>College Name:</b> {{ $college->name }}</li>
        </ul>
      </div>
    </div> --}}
    
      {{-- <div class="row">

        <!-- Programs -->
        <div class="col-xl-3 col-md-6 mb-4">
          <div class="card h-100 py-2">
            <div class="card-body">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Programs</div>
                  <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $program_count }}</div>
                </div>
                <div class="col-auto">
                  <i class="fas fa-graduation-cap fa-2x text-gray-300"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Courses -->
        <div class="col-xl-3 col-md-6 mb-4">
          <div class="card h-100 py-2">
            <div class="card-body">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Courses</div>
                  <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $courses_count }}</div>
                </div>
                <div class="col-auto">
                  <i class="fas fa-book fa-2x text-gray-300"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Curricula -->
        <div class="col-xl-3 col-md-6 mb-4">
          <div class="card h-100 py-2">
            <div class="card-body">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Curricula</div>
                  <div class="row no-gutters align-items-center">
                    <div class="col-auto">
                      <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $curriculum_count }}</div>
                    </div>
                  </div>
                </div>
                <div class="col-auto">
                  <i class="fas fa-book-open fa-2x text-gray-300"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Students -->
        <div class="col-xl-3 col-md-6 mb-4">
          <div class="card h-100 py-2">
            <div class="card-body">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Students</div>
                  <div class="h5 mb-0 font-weight-bold">{{ $student_count }}</div>
                </div>
                <div class="col-auto">
                  <i class="fas fa-users fa-2x"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div> --}}

      

      <div class="row">

        {{-- test questions --}}
        <div class="col-md-3">

            <div class="card shadow dashboard-card" v-on:click="changeLocation('/test_bank?program_id=')">
              <div class="card-body py-2">
                <img class="w-100" height="80px" src="{{ asset('img/icon_svg/questions.svg') }}" alt="assessment picture">

                
              </div>
              <div class="dashboard-card-footer">
                  <div class="dashboard-count text-info">{{ $test_question_count }}</div>
                  <h3 class="card-name">Test Questions</h3>
              </div>
            </div>
        </div>

        {{-- exams --}}
        <div class="col-md-3">
          <div class="card shadow dashboard-card" v-on:click="changeLocation('/test_bank?program_id=')">
            <div class="card-body py-2">
              <img class="w-100" height="80px" src="{{ asset('img/icon_svg/document.svg') }}" alt="assessment picture">

              
            </div>
            <div class="dashboard-card-footer">
                <div class="dashboard-count text-info">{{ $exams_count }}</div>
                <h3 class="card-name">Exams</h3>
            </div>
          </div>
        </div>

        


        {{-- students --}}
        <div class="col-md-3">
          <div class="card shadow dashboard-card" v-on:click="changeLocation('/students')">
            <div class="card-body py-2">
              <img class="w-100" height="80px" src="{{ asset('img/icon_svg/student.svg') }}" alt="student picture">

              
            </div>
            <div class="dashboard-card-footer">
                <div class="dashboard-count text-info">{{ $student_count }}</div>
                <h3 class="card-name">Students</h3>
            </div>
          </div>
        </div>

        {{-- programs --}}
        <div class="col-md-3">
          <div class="card shadow dashboard-card" v-on:click="changeLocation('/programs')">
            <div class="card-body py-2">
              <img class="w-100" height="80px" src="{{ asset('img/icon_svg/graduation.svg') }}" alt="programs picture">

              
            </div>
            <div class="dashboard-card-footer">
                <div class="dashboard-count text-info">{{ $program_count }}</div>
                <h3 class="card-name">Programs</h3>
            </div>
          </div>
        </div>

        {{-- curricula --}}
        <div class="col-md-3 mt-4">
          <div class="card shadow dashboard-card" v-on:click="changeLocation('/curricula')">
            <div class="card-body py-2">
              <img class="w-100" height="80px" src="{{ asset('img/icon_svg/folder.svg') }}" alt="curricula picture">

              
            </div>
            <div class="dashboard-card-footer">
                <div class="dashboard-count text-info">{{ $curriculum_count }}</div>
                <h3 class="card-name">Curricula</h3>
            </div>
          </div>
        </div>

        {{-- courses --}}
        <div class="col-md-3 mt-4">
          <div class="card shadow dashboard-card" v-on:click="changeLocation('/courses')">
            <div class="card-body py-2">
              <img class="w-100" height="80px" src="{{ asset('img/icon_svg/books.svg') }}" alt="courses picture">

              
            </div>
            <div class="dashboard-card-footer">
                <div class="dashboard-count text-info">{{ $courses_count }}</div>
                <h3 class="card-name">Courses</h3>
            </div>
          </div>
        </div>

        {{-- faculties --}}
        <div class="col-md-3 mt-4">
          <div class="card shadow dashboard-card" v-on:click="changeLocation('/faculties')">
            <div class="card-body py-2">
              <img class="w-100" height="80px" src="{{ asset('img/icon_svg/group.svg') }}" alt="faculties picture">

              
            </div>
            <div class="dashboard-card-footer">
                <div class="dashboard-count text-info">{{ $faculties_count }}</div>
                <h3 class="card-name">Faculties</h3>
            </div>
          </div>
        </div>

        {{-- assessments --}}
        <div class="col-md-3 mt-4">
          <div class="card shadow dashboard-card" v-on:click="changeLocation('/assessment_results?college_id=' + college_id)">
            <div class="card-body py-2">
              <img class="w-100" height="80px" src="{{ asset('img/icon_svg/assessment.svg') }}" alt="assessment picture">

              
            </div>
            <div class="dashboard-card-footer">
                <div class="dashboard-count text-info">{{ $assessment_count }}</div>
                <h3 class="card-name">Assessments</h3>
            </div>
          </div>
        </div>

        
      </div>
  </div>
@endsection

@push('scripts')
  <script>
    new Vue({
      el: '#app',
      data: {
        college_id: '{{ $college->id }}',
        sampleData: {
                labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
                datasets: [
                    {
                        label: "Score per courses",
                        data: [12, 19, 3, 5, 2, 3],
                        backgroundColor: [
                            "rgba(255, 99, 132, 0.2)",
                            "rgba(54, 162, 235, 0.2)",
                            "rgba(255, 206, 86, 0.2)",
                            "rgba(75, 192, 192, 0.2)",
                            "rgba(153, 102, 255, 0.2)",
                            "rgba(255, 159, 64, 0.2)"
                        ],
                        borderColor: [
                            "rgba(255, 99, 132, 1)",
                            "rgba(54, 162, 235, 1)",
                            "rgba(255, 206, 86, 1)",
                            "rgba(75, 192, 192, 1)",
                            "rgba(153, 102, 255, 1)",
                            "rgba(255, 159, 64, 1)"
                        ],
                        borderWidth: 1
                    }
                ]
            }
      },
      methods: {
        changeLocation(url) {
         window.location.href = myRootURL + url;
        }
      }
    });
  </script>
  
  @if (!$password_changed)
    <script>
      $(document).ready(function() {
        $('#accountModal').modal('show');
      });
    </script>
  @endif
@endpush