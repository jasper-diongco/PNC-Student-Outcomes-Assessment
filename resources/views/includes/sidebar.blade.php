<!-- Sidebar  -->
@guest
@else
    {{-- <nav id="sidebar" class="{{ !Auth::check() ? 'active' : '' }}"> --}}
    <nav id="sidebar" class="active">
        <ul class="list-unstyled components">
            {{-- DEAN DASHBOARD --}}
            @if(Gate::check('isDean') || Gate::check('isProf'))
                <li>
                    <a href="{{ url('/colleges/' . Auth::user()->getFaculty()->college_id . '/dashboard') }}"><i class="fa fa-home"></i> Dashboard</a>
                </li>
                
            @endif

            @if(Gate::check('isDean') || Gate::check('isSAdmin') || Gate::check('isProf'))
                <li>
                    <a href="{{ url('/programs') }}"><i class="fa fa-graduation-cap"></i> Programs</a>
                </li>
                <li>
                    <a href="{{ url('/courses') }}"><i class="fa fa-book"></i> Courses</a>
                </li>
                <li>
                    <a href="{{ url('/curricula') }}"><i class="fa fa-book-open"></i> Curricula</a>
                </li>
            @endif
            
            @can('isSAdmin')
                <li>
                    <a href="{{ url('/colleges') }}"><i class="fa fa-university"></i> Colleges</a>
                </li>
            @endcan
            <li>
                <a href="#userSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="fa fa-users"></i> Users</a>
                <ul class="collapse list-unstyled" id="userSubmenu">
                    <li>
                        <a href="{{ url('/faculties') }}">Faculties</a>
                    </li>
                    <li>
                        <a href="#">Students</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-question"></i> About</a>
            </li>
            <li>
                <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="fa fa-user"></i> Pages</a>
                <ul class="collapse list-unstyled" id="pageSubmenu">
                    <li>
                        <a href="#">Page 1</a>
                    </li>
                    <li>
                        <a href="#">Page 2</a>
                    </li>
                    <li>
                        <a href="#">Page 3</a>
                    </li>
                </ul>
            </li>
            @can('isSAdmin')
            <li>
                <a href="#">Portfolio</a>
            </li>
            <li>
                <a href="#">Contact</a>
            </li>
            @endcan
        </ul>
    </nav>
@endguest