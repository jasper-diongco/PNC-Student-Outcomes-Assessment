<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow" id="app-nav">

  <!-- Sidebar Toggle (Topbar) -->
  <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
    <i class="fa fa-bars"></i>
  </button>
  <img src="{{ asset('img/pnc_logo_name_small.png') }}" alt="" style="width: 100px">
  <!-- Topbar Search -->
  <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
    <div class="input-group">
      <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
      <div class="input-group-append">
        <button class="btn btn-primary" type="button">
          <i class="fas fa-search fa-sm"></i>
        </button>
      </div>
    </div>
  </form>

  <!-- Topbar Navbar -->
  <ul class="navbar-nav ml-auto">

    <!-- Nav Item - Search Dropdown (Visible Only XS) -->
    <li class="nav-item dropdown no-arrow d-sm-none">
      <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-search fa-fw"></i>
      </a>
      <!-- Dropdown - Messages -->
      <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
        <form class="form-inline mr-auto w-100 navbar-search">
          <div class="input-group">
            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
            <div class="input-group-append">
              <button class="btn btn-primary" type="button">
                <i class="fas fa-search fa-sm"></i>
              </button>
            </div>
          </div>
        </form>
      </div>
    </li>

    <div class="topbar-divider d-none d-sm-block"></div>


    @auth

      <!-- Nav Item - User Information -->
      <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->first_name . ' ' . Auth::user()->last_name }}</span>
          <img class="img-profile rounded-circle" src="{{ asset('img/user.svg') }}">
        </a>
        <!-- Dropdown - User Information -->
        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
          @if(Auth::user()->user_type_id == 'dean' || Auth::user()->user_type_id == 'prof')
              <a class="dropdown-item" href="{{ url('/profile/faculty/' . Auth::user()->getFaculty()->id ) }}">
                 <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> My Profile 
              </a>
          @endif
          {{-- <a class="dropdown-item" href="#">
            <i class="fas fa-lock fa-sm fa-fw mr-2 text-gray-400"></i>
            Change Password
          </a> --}}
          {{-- <account-modal></account-modal> --}}
          {{-- <a
            class="dropdown-item"
            data-toggle="modal"
            data-target="#accountModal"
            href="#"
          >
            <i class="fas fa-lock fa-sm fa-fw mr-2 text-gray-400"></i>
            Change Password
          </a> --}}

          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
            Logout
          </a>
        </div>
      </li>

    @endauth

  </ul>

</nav>
<!-- End of Topbar -->
