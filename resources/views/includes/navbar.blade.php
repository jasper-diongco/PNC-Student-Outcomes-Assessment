<nav
  id="main-nav"
  class="navbar navbar-expand-lg navbar-dark fixed-top navbar-dark"
  style="background: #3c4249"
>
  <div class="container-fluid">
    <a class="navbar-brand" href="#">
      <span style="color:#2ecc71">PNC</span> | SOA</a
    >

    <button
      class="navbar-toggler"
      type="button"
      data-toggle="collapse"
      data-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent"
      aria-expanded="false"
      aria-label="Toggle navigation"
    >
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
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
          <li class="nav-item dropdown">
            <a
              class="nav-link dropdown-toggle"
              href="#"
              id="navbarDropdown"
              role="button"
              data-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false"
            >
              <img
                src="{{ asset('img/user.svg') }}"
                alt="user icon"
                class="mr-2"
                style="width: 30px;"
              />{{ Auth::user()->first_name . ' ' . Auth::user()->last_name }}
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
        @endif
      </ul>
    </div>
  </div>
</nav>