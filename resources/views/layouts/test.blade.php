<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Dashboard Template for Bootstrap</title>


    <!-- Bootstrap core CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <style>
      body {
        background: #f0f3f6;
        font-family: 'Roboto', sans-serif;
      }
      .side-nav {
        width: 18%;

      }
      .main-content {
        width: 82%;
      }



      .brand {
        text-align: center;
        padding: 10px;
      }
      .side-menu {
        width: 100%;
        background: #3a4651;
        height: 100vh;
        overflow-y: auto;
      }
      .h1-brand {
        font-size: 150%;
        color: #26de81;
        font-weight: 400;
      }
      .nav-item {
        padding: 5px;
        font-weight: 400;
      }
      .side-nav .nav-item:hover {
        background: #2d363f;
      }
      .nav-item a {
        color: #9da3a8;
      }
      .sub-menu {
        list-style: none;
        margin: 0;
        padding: 0;
      }




      .nav-link {
        color: #fff;
      }
      .user-icon {
        color: #4b6584;
      }
      .user-dropdown {
        margin-right: 20px;
      }
      .dropdown-icon {
        color: #45aaf2;
      }
      .fa {
        font-size: 23px;
      }
      #pnc-logo {
        width: 110px;
      }
      .my-navbar {
        background: #d7dde4 !important;
      }
      .nav-item.active {
        background: #85ce36;
        color: #fff !important;
      }
      .nav-item.active a {
        color: #fff;
      }
      .nav-item.active:hover {
        background: #85ce36;
      }
    </style>
  </head>

  <body>

    <div class="d-flex">
      <div class="side-nav">
        <div class="side-menu">
          

          <ul class="nav flex-column">
            <li class="nav-item active">
              <a class="nav-link" href="#">
                <i class="fa fa-home"></i> Dashboard
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">
                <i class="fa fa-university"></i> College
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Courses</a>
            </li>
          </ul>
        </div>
      </div>
      <div class="main-content">
        <div class="top-navbar">
          <nav class="navbar navbar-expand-lg navbar-light bg-light my-navbar">
            <div class="brand">
              <img id="pnc-logo" src="{{ asset('img/pnc_logo_name_small.png') }}" alt="">
            </div>
            <ul class="nav ml-auto">
              <li class="nav-item dropdown user-dropdown">
                <a
                  class="nav-link dropdown-toggle"
                  href="#"
                  role="button"
                >
                  <i class="fa fa-user"></i> Jasper
                  Diongco
                </a>
                <transition name="fade">
                  <div v-show="showDropDown" class="dropdown-menu">
                    <a class="dropdown-item" href="#">
                      <font-awesome-icon
                        class="dropdown-icon"
                        icon="id-badge"
                      />&nbsp;My Profile
                    </a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">
                      <font-awesome-icon
                        class="dropdown-icon"
                        icon="sign-out-alt"
                      />&nbsp;Logout
                    </a>
                  </div>
                </transition>
              </li>
            </ul>
          </nav>
        </div>
        <div class="container">
          <h1>Test</h1>
        </div>
      </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
  </body>
</html>
