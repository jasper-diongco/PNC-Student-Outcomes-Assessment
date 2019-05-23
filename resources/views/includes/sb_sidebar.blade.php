<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion {{ Session::get('toggle_sb') == '1' ? '' : 'toggled' }}" id="accordionSidebar">

  <!-- Sidebar - Brand -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html"> 
    <div class="sidebar-brand-icon rotate-n-15">
      <i class="fas fa-bell text-white"></i>
    </div>
    <div class="sidebar-brand-text mx-3"><span>PNC | SOA</div>
  </a>

  <!-- Divider -->
  <hr class="sidebar-divider my-0">

  <!-- Nav Item - Dashboard -->
  @if(Gate::check('isDean') || Gate::check('isProf'))
    <li class="nav-item">
      <a class="nav-link" href="{{ url('/colleges/' . Auth::user()->getFaculty()->college_id . '/dashboard') }}">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Dashboard</span></a>
    </li>
  @endif

  <!-- Divider -->
  <hr class="sidebar-divider">

  <!-- Heading -->
  <div class="sidebar-heading">
    Navigation
  </div>

  @can('isSAdmin')
    <!-- Nav Item - Colleges -->
    <li class="nav-item">
      <a class="nav-link" href="{{ url('/colleges') }}">
        <i class="fas fa-fw fa-university"></i>
        <span>Colleges</span></a>
    </li>
  @endcan
  
  @if(Gate::check('isDean') || Gate::check('isSAdmin') || Gate::check('isProf'))

    <!-- Nav Item - Programs -->
    <li class="nav-item">
      <a class="nav-link" href="{{ url('/programs') }}">
        <i class="fas fa-fw fa-graduation-cap"></i>
        <span>Programs</span></a>
    </li>

    <!-- Nav Item - Courses -->
    <li class="nav-item">
      <a class="nav-link" href="{{ url('/courses') }}">
        <i class="fas fa-fw fa-book"></i>
        <span>Courses</span></a>
    </li>

    <!-- Nav Item - Curricula -->
    <li class="nav-item">
      @if (Gate::check('isDean') || Gate::check('isProf'))
        <a class="nav-link" href="{{ url('/curricula?college_id=' . Session::get('college_id')) }}">
      @else
        <a class="nav-link" href="{{ url('/curricula') }}">
      @endif
        <i class="fas fa-fw fa-book-open"></i>
        <span>Curricula</span></a>
    </li>

    <!-- Nav Item - Student Outcomes -->
    <li class="nav-item">
      <a class="nav-link" href="{{ url('/student_outcomes/list_program?college_id='. Session::get('college_id')) }}">
        <i class="fas fa-fw fa-flag"></i>
        <span>Student Outcomes</span></a>
    </li>

    <!-- Nav Item - Student Outcomes -->
    <li class="nav-item">
      <a class="nav-link" href="{{ url('/curriculum_mapping?college_id='. Session::get('college_id')) }}">
        <i class="fas fa-fw fa-map"></i>
        <span>Curriculum Mapping</span></a>
    </li>

    <!-- Nav Item - Users Collapse Menu -->
    <li class="nav-item">
      <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUser" aria-expanded="true" aria-controls="collapseTwo">
        <i class="fas fa-fw fa-users"></i>
        <span>Users</span>
      </a>
      <div id="collapseUser" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
          <h6 class="collapse-header">Users :</h6>
          <a class="collapse-item" href="{{ url('/faculties') }}">Faculties</a>
          <a class="collapse-item" href="{{ url('/students') }}">Students</a>
        </div>
      </div>
    </li>

  @endif


  <!-- Divider -->
  <hr class="sidebar-divider">


  <!-- Sidebar Toggler (Sidebar) -->
  <div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
  </div>

</ul>
<!-- End of Sidebar -->