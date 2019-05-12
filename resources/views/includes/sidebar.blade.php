<!-- Sidebar  -->
@auth
    
    <div id="sidenav" style="background: #ecf0f1;">
        <ul class="nav flex-column">
            {{-- DEAN DASHBOARD --}}
            @if(Gate::check('isDean') || Gate::check('isProf'))
                <li class="nav-item">
                    <a href="{{ url('/colleges/' . Auth::user()->getFaculty()->college_id . '/dashboard') }}" class="nav-link"><i class="fa fa-home mr-3"></i> <span class="nav-text">Home</span></a>
                </li>
            @endif
          
          @if(Gate::check('isDean') || Gate::check('isSAdmin') || Gate::check('isProf'))
                <li class="nav-item nav-item-active">
                    <a href="{{ url('/programs') }}" class="nav-link"><i class="fa fa-graduation-cap mr-3"></i> <span class="nav-text">Programs</span></a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/courses') }}" class="nav-link"><i class="fa fa-book mr-3"></i> <span class="nav-text">Courses </span></a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/curricula') }}" class="nav-link"><i class="fa fa-book-open mr-3"></i> <span class="nav-text">Curricula</span></a>
                </li>
            @endif
            
            @can('isSAdmin')
                <li class="nav-item">
                    <a href="{{ url('/colleges') }}" class="nav-link"><i class="fa fa-university mr-3"></i> <span class="nav-text">Colleges</span></a>
                </li>
            @endcan
            <li class="nav-item">
                <a href="#userSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link"><i class="fa fa-users mr-3"></i> <span class="nav-text">Users</span></a>
                <ul class="collapse list-unstyled" id="userSubmenu">
                    <li>
                        <a href="{{ url('/faculties') }}" class="nav-link">Faculties</a>
                    </li>
                    <li>
                        <a href="#" class="nav-link">Students</a>
                    </li>
                </ul>
            </li>

          <li class="nav-item active-nav nav-indicator">
            <a class="nav-link disabled" href="#"
              ><i class="fa fa-chevron-right mr-3"></i>
              <span class="nav-text">Navigation</span></a
            >
          </li>
        </ul>
    </div>
@endauth
