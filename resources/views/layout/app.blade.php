<?php
    $active = $active ?? '';
?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>PNC | SOA - @yield('title')</title>

    {{-- fonts --}}
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">

    <link rel="stylesheet" href="{{ asset('css/prism.css') }}">

    <script src="{{ asset('js/prism.js') }}"></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <header>
        <!-- navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-success">
          <div class="container">
            <a class="navbar-brand" href="#">PNC | SOA</a>
              
              
              {{-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button> --}}

              @auth
                <div class="dropdown">
                  <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-user"></i> 
                    {{ Auth::user()->first_name . ' ' . Auth::user()->last_name }}
                  </button>
                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    @if(Auth::user()->user_type_id == 'dean' || Auth::user()->user_type_id == 'prof')
                    <a class="dropdown-item" href="{{ url('/profile/faculty/' . Auth::user()->getFaculty()->id ) }}"><i class="fa fa-user-circle"></i> My Profile</a>
                    @endif
                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                             <i class="fa fa-sign-out-alt"></i>Logout</a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                  </div>
                </div>
            @endauth
          </div> 
          
        </nav>
        <!-- //navbar -->
        
        <!-- navigation -->
        <nav id="main-nav" class="navbar navbar-expand-lg navbar-dark bg-white">
          <div class="container">
              <ul class="nav">

                  <!-- Nav Item - Dashboard -->
                  @if(Gate::check('isDean') || Gate::check('isProf'))
                    <li class="nav-item {{ $active == 'dashboard' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('/colleges/' . Auth::user()->getFaculty()->college_id . '/dashboard') }}">
                            <div class="d-flex flex-column justify-content-center text-center">
                                <i class="fa fa-columns icon-nav"></i> 
                                <span>Dashboard</span>
                            </div>
                        </a>
                    </li>
                  @endif

                  @can('isSAdmin')
                    <!-- Nav Item - Colleges -->
                    <li class="nav-item {{ $active == 'colleges' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('/colleges') }}">
                            <div class="d-flex flex-column justify-content-center text-center">
                                <i class="fa fa-university icon-nav"></i> 
                                <span>Colleges</span>
                            </div>
                        </a>
                    </li>
                  @endcan

                    @if(Gate::check('isDean') || Gate::check('isSAdmin') || Gate::check('isProf'))

                        <!-- Nav Item - Test Question -->
                        <li class="nav-item {{ $active == 'test_questions' ? 'active' : '' }}">
                          <a class="nav-link" href="{{ url('/test_bank/list_programs?college_id='. Session::get('college_id')) }}">
                                <div class="d-flex flex-column justify-content-center text-center">
                                    <i class="fas fa-database icon-nav"></i>
                                    <span>Test Bank</span>
                                </div>
                            </a>   
                        </li>

                        <!-- Nav Item - Programs -->
                        <li class="nav-item {{ $active == 'programs' ? 'active' : '' }}">
                            <a class="nav-link " href="{{ url('/programs') }}">
                                <div class="d-flex flex-column justify-content-center text-center">
                                    <i class="fas fa-graduation-cap icon-nav"></i>
                                    <span>Programs</span>
                                </div>
                            </a>
                        </li>

                        <!-- Nav Item - Courses -->
                        <li class="nav-item {{ $active == 'courses' ? 'active' : '' }}">
                            <a class="nav-link" href="{{ url('/courses') }}">
                                <div class="d-flex flex-column justify-content-center text-center">
                                    <i class="fas fa-book icon-nav"></i>
                                    <span>Courses</span>
                                </div>
                            </a>
                        </li>

                        <!-- Nav Item - Curricula -->
                        <li class="nav-item {{ $active == 'curricula' ? 'active' : '' }}">
                          @if (Gate::check('isDean') || Gate::check('isProf'))
                            <a class="nav-link" href="{{ url('/curricula?college_id=' . Session::get('college_id')) }}">
                          @else
                            <a class="nav-link" href="{{ url('/curricula') }}">
                          @endif
                                <div class="d-flex flex-column justify-content-center text-center">
                                    <i class="fas fa-book-open icon-nav"></i>
                                    <span>Curricula</span>
                                </div>

                            </a>
                        </li>

                        <!-- Nav Item - Student Outcomes -->
                        <li class="nav-item {{ $active == 'student_outcomes' ? 'active' : '' }}">
                            <a class="nav-link" href="{{ url('/student_outcomes/list_program?college_id='. Session::get('college_id')) }}">
                                <div class="d-flex flex-column justify-content-center text-center">
                                    <i class="fas fa-flag"></i>
                                    <span>Student Outcomes</span>
                                </div>
                            </a>
                        </li>

                        <!-- Nav Item - Student Outcomes -->
                        <li class="nav-item {{ $active == 'curriculum_mapping' ? 'active' : '' }}">
                            <a class="nav-link " href="{{ url('/curriculum_mapping?college_id='. Session::get('college_id')) }}">
                                <div class="d-flex flex-column justify-content-center text-center">
                                    <i class="fas fa-map"></i>
                                    <span>Curriculum Mapping</span>
                                </div>
                            </a>
                        </li>

                        <!-- Nav Item - Users Collapse Menu -->
                        <li class="nav-item dropdown {{ $active == 'users' ? 'active' : '' }}">
                          <a class="nav-link" href="#" data-toggle="dropdown" >
                                <div class="d-flex flex-column justify-content-center text-center">
                                    <i class="fas fa-users"></i>
                                    <span>Users</span>
                                </div>
                            </a>
                          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="nav-link" href="{{ url('/faculties') }}" >
                                <div class="d-flex flex-column justify-content-center text-center">
                                    <i class="fas fa-user"></i>
                                    <span>Faculties</span>
                                </div>
                            </a>
                            <a class="nav-link" href="{{ url('/students') }}">
                                <div class="d-flex flex-column justify-content-center text-center">
                                    <i class="fas fa-user"></i>
                                    <span>Students</span>
                                </div>
                            </a>
                          </div>
                        </li>

                      @endif


            </ul>
          </div> 
          
        </nav>
        <!-- //navigation -->
    </header>
        
    <main id="page-content" class="pt-3">
        <!-- Begin Page Content -->
        <div class="container">

          <!-- Page Heading -->
          
          @yield('content')
          
        </div>
        <!-- /.container-fluid -->
    </main>
            
    <footer id="sticky-footer" class="py-2 mt-5">
        <div class="pl-3">Pamantasan ng Cabuyao &copy; 2019 | Student Outcomes Assessment</div>
    </footer>
</body>
</html>


<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>

@stack('scripts')

<script>
    $(function () {
      $('[data-toggle="tooltip"]').tooltip();
    });

    $(function () {
      $('[data-toggle="popover"]').popover();
    });
</script>