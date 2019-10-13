<?php
    $active = $active ?? '';
    $container_fluid = $container_fluid ?? false;
    $hide_navigation = $hide_navigation ?? false;
    $hide_header = $hide_header ?? false;
    $hide_footer = $hide_footer ?? false;
    $dark_bg = $dark_bg ?? false;
    $brand = $brand ?? 'PNC | SOA';
    $assessment = $assessment ?? false;
    $fixed_top = $fixed_top ?? false;
    $shadow = $shadow ?? false;
    $custom_layout = $custom_layout ?? false;
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
    {{-- <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&display=swap" rel="stylesheet"> --}}
    <link rel="stylesheet" href="{{ asset('fonts/open_sans.css') }}">

    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">

    <link rel="stylesheet" href="{{ asset('css/prism.css') }}">

    <script src="{{ asset('js/prism.js') }}"></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/print.min.css') }}">
</head>
<body>
@if(!$custom_layout)
<div class="d-flex flex-column sticky-footer-wrapper">
    <header class="sticky-top">
        <!-- navbar -->
        @if(!$hide_header)
        <nav class="navbar navbar-expand-lg navbar-dark bg-success {{ $fixed_top ? 'fixed-top' : '' }} {{ $shadow ? 'shadow' : '' }}">
          @if($container_fluid)
                <div class="container-fluid">
            @else
                <div class="container">
            @endif
            <a class="navbar-brand" href="#">{!! $brand !!} </a>
            {{--< div>
                <img src="{{ asset('img/pnc_logo_name_small.png') }}"  style="width: 100px;">
            </div>  --}}
              
              
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
                        {{-- <a class="dropdown-item" href="{{ url('/profile/faculty/' . Auth::user()->getFaculty()->id ) }}"><i class="fa fa-user-circle"></i> My Profile</a> --}}
                        <a class="dropdown-item" href="{{ url('/faculties/' . Auth::user()->id . '/profile' ) }}"><i class="fa fa-user-circle"></i> My Profile</a>
                        
                        @elseif (Auth::user()->user_type_id == 'stud')
                            <a class="dropdown-item" href="{{ url('/s/my_profile/' . Auth::user()->id ) }}"><i class="fa fa-user-circle"></i> My Profile</a>
                        @elseif (Auth::user()->user_type_id == 's_admin')
                            <a class="dropdown-item" href="{{ url('users/' . Auth::user()->id . '/super_admin_profile' ) }}"><i class="fa fa-user-circle"></i> My Profile</a>
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
        @endif
        
        @if (!$hide_navigation)
        <!-- navigation -->
        <nav id="main-nav" class="navbar navbar-expand-lg navbar-dark bg-white">
            @if($container_fluid)
                <div class="container-fluid">
            @else
                <div class="container">
            @endif
              <ul id="nav-scroll-h" class="nav flex-row d-flex flex-nowrap" style="overflow-x: auto;">

                @can('isStud')
                    <li class="nav-item {{ $active == 'home-student' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('/s/home') }}">
                            <div class="d-flex flex-column justify-content-center text-center">
                                <i class="fa fa-home icon-nav"></i> 
                                <span>Home</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item {{ $active == 'assessments' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('/s/assessments') }}">
                            <div class="d-flex flex-column justify-content-center text-center">
                                <i class="fas fa-edit icon-nav"></i> 
                                <span>Assessments</span>
                            </div>
                        </a>
                    </li>
                    @if(Gate::check('isStud'))
                    <li class="nav-item {{ $active == 'obe-curriculum' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('s/' . Auth::user()->getStudent()->id . '/obe_curriculum') }}">
                            <div class="d-flex flex-column justify-content-center text-center">
                                <i class="fa fa-file-alt icon-nav"></i> 
                                <span>OBE Curriculum</span>
                            </div>
                        </a>
                    </li>
                    @endif
                @endcan

                  <!-- Nav Item - Dashboard -->
                  @if(Gate::check('isDean') || Gate::check('isProf'))
                    <li class="nav-item {{ $active == 'dashboard' ? 'active' : '' }}">
                        <a class="nav-link" @if(Gate::check('isDean')) href="{{ url('/colleges/' . Auth::user()->getFaculty()->college_id . '/dashboard') }}" @elseif(Gate::check('isProf')) href="{{ url('/faculties/dashboard') }}" @endif>
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
                        
                        <!-- Nav Item - Assessments -->
                        <li class="nav-item {{ $active == 'assessments' ? 'active' : '' }}">
                          <a class="nav-link" href="{{ url('/assessment_results?college_id=' . Session::get('college_id')) }}">
                                <div class="d-flex flex-column justify-content-center text-center">
                                    <i class="fas fa-poll icon-nav"></i>
                                    <span>Assessments</span>
                                </div>
                            </a>   
                        </li>

                        <!-- Nav Item - Test Question -->
                        <li class="nav-item {{ $active == 'test_questions' ? 'active' : '' }}">
                          <a class="nav-link" href="{{ url('/test_bank?program_id=' . Session::get('program_id')) }}">
                                <div class="d-flex flex-column justify-content-center text-center">
                                    <i class="fas fa-database icon-nav"></i>
                                    <span style="white-space: nowrap">Test Bank</span>
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
                                    <span style="white-space: nowrap">Student Outcomes</span>
                                </div>
                            </a>
                        </li>

                        <!-- Nav Item - Student Outcomes -->
                        <li class="nav-item {{ $active == 'curriculum_mapping' ? 'active' : '' }}">
                            <a class="nav-link " href="{{ url('/curriculum_mapping?college_id='. Session::get('college_id')) }}">
                                <div class="d-flex flex-column justify-content-center text-center">
                                    <i class="fas fa-map"></i>
                                    <span style="white-space: nowrap">Curriculum Mapping</span>
                                </div>
                            </a>
                        </li>

                        <!-- Nav Item - Students -->
                        <li class="nav-item {{ $active == 'students' ? 'active' : '' }}">
                            <a class="nav-link " href="{{ url('/students') }}">
                                <div class="d-flex flex-column justify-content-center text-center">
                                    <i class="fas fa-users"></i>
                                    <span style="white-space: nowrap">Students</span>
                                </div>
                            </a>
                        </li>

                        <!-- Nav Item - Students -->
                        <li class="nav-item {{ $active == 'faculties' ? 'active' : '' }}">
                            <a class="nav-link " href="{{ url('/faculties') }}">
                                <div class="d-flex flex-column justify-content-center text-center">
                                    <i class="fas fa-users"></i>
                                    <span style="white-space: nowrap">Faculty Members</span>
                                </div>
                            </a>
                        </li>

                        <!-- Nav Item - Users Collapse Menu -->
                        {{-- <li class="nav-item dropdown {{ $active == 'users' ? 'active' : '' }}">
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
                        </li> --}}

                    @endif

                    @if(Gate::check('isSAdmin')) 

                        <!--Application Settings -->
                        <li class="nav-item {{ $active == 'application_settings' ? 'active' : '' }}">
                            <a class="nav-link " href="{{ url('/application_settings') }}">
                                <div class="d-flex flex-column justify-content-center text-center">
                                    <i class="fas fa-cogs"></i>
                                    <span>Settings</span>
                                </div>
                            </a>
                        </li>
                        <!--Application Settings -->
                        <li class="nav-item {{ $active == 'maintenance' ? 'active' : '' }}">
                            <a class="nav-link " href="{{ url('/maintenance') }}">
                                <div class="d-flex flex-column justify-content-center text-center">
                                    <i class="fas fa-cog"></i>
                                    <span>Maintenance</span>
                                </div>
                            </a>
                        </li>
                    @endif


            </ul>
          </div> 
          
        </nav>
        <!-- //navigation -->

        @endif
    </header>
        
    <main id="page-content" class="pt-3 flex-fill {{ $fixed_top ? 'pt-5 mt-3' : '' }}">
        <!-- Begin Page Content -->
        @if($container_fluid)
            <div class="container-fluid">
        @else
            <div class="container">
        @endif
        

          <!-- Page Heading -->
          
          @yield('content')
          
        </div>
        <!-- /.container-fluid -->
    </main>
    @if(!$hide_footer) 
    <footer id="sticky-footer" class="py-2 mt-5">
        <div class="pl-3">Pamantasan ng Cabuyao &copy; 2019 | Student Outcomes Assessment</div>
    </footer>
    @endif
</div>
@else
    @yield('content')
@endif
</body>
</html>


<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/double-scroll.js') }}"></script>
<script src="{{ asset('js/print.min.js') }}"></script>


@stack('scripts')

<script>
    $(function () {
      $('[data-toggle="tooltip"]').tooltip();
    });

    $(function () {
      $('[data-toggle="popover"]').popover();
    });
</script>
