NAVBAR -->
<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-light">
    <div class="container-fluid">
        
        @guest

        @else
            <div id="sidebarCollapse" class="mr-3">
                <i class="material-icons">menu</i>
            </div>
        @endguest
        
        <a href="#" class="navbar-brand"><img id="pnc-logo" src="{{ asset('img/pnc_logo_name_small.png') }}" alt="PNC Logo"></a>




        {{-- <ul class="nav navbar-nav ml-auto d-flex flex-row" id="navRight">
            <li class="nav-item mr-2">
                <a class="nav-link" href="#">Page</a>
            </li>
            <li class="nav-item mr-2">
                <a class="nav-link" href="#">Page</a>
            </li>
            <li class="nav-item mr-2">
                <a class="nav-link" href="#">Page</a>
            </li>
            <li class="nav-item mr-2">
                <a class="nav-link" href="#">Page</a>
            </li>
        </ul> --}}
        <!-- Right Side Of Navbar -->
        <ul class="navbar-nav ml-auto">
            <!-- Authentication Links -->
            @guest
                <li class="nav-item active">
                    <a class="nav-link" href="{{ route('login') }}">Login <i class="fa fa-sign-in-alt"></i></a>
                </li>
                {{-- @if (Route::has('register'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                    </li>
                @endif --}}
            @else
                <li class="nav-item dropdown active">
                    <a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-user"></i>
                        {{ Auth::user()->first_name . ' ' . Auth::user()->last_name }}
                        <i class="fa fa-caret-down"></i>
                    </a>

                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">

                        @if(Auth::user()->user_type_id == 'dean' || Auth::user()->user_type_id == 'prof')
                            <a class="dropdown-item" href="{{ url('/profile/faculty/' . Auth::user()->getFaculty()->id ) }}">
                               <i class="fa fa-user-circle"></i> My Profile 
                            </a>
                        @endif


                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                            <i class="fa fa-sign-out-alt"></i> Logout 
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        
                    </div>
                </li>
            @endguest
        </ul>

    </div>
</nav>
<!-- /NAVBAR