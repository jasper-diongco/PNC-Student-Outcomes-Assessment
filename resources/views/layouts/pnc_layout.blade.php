<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>PNC SOA &mdash; @yield('title')</title>
    <!-- CSRF TOKEN -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- Material icon -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
  rel="stylesheet">
    <link
      rel="stylesheet"
      href="https://use.fontawesome.com/releases/v5.8.2/css/all.css"
      integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay"
      crossorigin="anonymous"
    />
    <link
      href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700"
      rel="stylesheet"
    />
    <style>
      body {
        padding: 0;
        margin: 0;
        font-family: "Roboto", sans-serif;
      }
      #main {
        padding: 20px 35px;
        padding-bottom: 50px;
        min-width: 900px;

      }
      #sidenav {
        background: #3c4249;
        height: 100vh;
        position: fixed;
        width: 70px;
        cursor: pointer;
        padding-top: 62px;
        overflow-x: hidden;
        z-index: 10;
        box-shadow: 1px 0 7px rgba(0,0,0,0.10);
      }
      #main-nav {
        z-index: 11;

      }
      #main-content {
        background: #fff;
        width: 100%;
        margin-left: 70px;
        padding-top: 70px;
        height: 100vh;
        background: #f8fafc;

      }
      #sidenav .fa {
        font-size: 18px;
      }
      #sidenav .nav .nav-item a {
        color: #565656;
        transition: all 0.2s;
      }
      #sidenav .nav .nav-item {
        padding: 3px 0;
        padding-left: 12px;
        transition: all 0.2s;
      }
      #sidenav .nav .nav-item:hover {
        background: #bdc3c7;
      }
      #sidenav .nav .nav-item a:hover {
        color: #27ae60;
      }
      .active-nav {
        background: #27ae60;
      }
      .active-nav:hover {
        background: #2ecc71;
      }
      #pnc-logo-img {
        width: 100px;
      }
      .nav-text {
        display: none;
      }
      .flex-wrapper {
        display: flex;
        min-height: 100vh;
        flex-direction: column;
        justify-content: space-between;
      }
    </style>
  </head>
  <body>
    <div class="wrapper d-flex">
      <!-- SIDENAV -->
        @include('includes.sidebar')
      <!-- END SIDE NAV -->
        @include('includes.navbar')
      <!-- MAIN CONTENT -->
      <div id="main-content">
        <!-- NAVBAR -->
          
        <!-- END NAVBAR -->

        <!-- CONTENT GOES HERE -->
        <div class="flex-wrapper">
          <div class="container-fluid">
            <div id="main">
              @yield('content')
            </div>
          </div>
          <!-- END CONTENT -->
          <footer
            class="main-footer d-none d-sm-block"
            style="background: #bcc2c8;"
          >
            <small class="ml-4"
              >Copyright &copy; 2019 Pamantasan ng Cabuyao | Student Outcomes
              Assessment System. All rights reserved.
            </small>
          </footer>
        </div>
      </div>
      <!-- END MAIN CONTENT -->
    </div>

    <!-- <script
      src="https://code.jquery.com/jquery-3.4.1.min.js"
      integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
      integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
      crossorigin="anonymous"
    ></script>
    <script
      src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
      integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
      crossorigin="anonymous"
    ></script> -->
    <script src="{{ asset('js/app.js') }}"></script>

    <script>
      $(document).ready(function() {
        $("#sidenav").mouseenter(function() {
          $(this).animate({ width: "250px" }, 300);
          setTimeout(function() {
            $(".nav-text").show();
          }, 150);
          $(".nav-indicator")
            .find("i")
            .removeClass("fa-chevron-right")
            .addClass("fa-chevron-left");
        });

        $("#sidenav").mouseleave(function() {
          $(".nav-text").hide();
          $(this).animate({ width: "70px" }, 300);
          $(".nav-indicator")
            .find("i")
            .removeClass("fa-chevron-left")
            .addClass("fa-chevron-right");
          $('#userSubmenu').collapse('hide');
        });


      });
    </script>

    @stack('scripts')
  </body>
</html>
