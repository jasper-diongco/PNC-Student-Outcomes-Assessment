<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="PNC SOA">
  <meta name="author" content="Jasper Diongco">

  <!-- CSRF TOKEN -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>PNC | SOA - @yield('title')</title>

  <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">

  <link rel="stylesheet" href="{{ asset('fonts/nunito.css') }}">

  <link rel="stylesheet" href="{{ asset('css/app.css') }}">

  <link rel="stylesheet" href="{{ asset('css/prism.css') }}">
  <script src="{{ asset('js/prism.js') }}"></script>



  

  <style type="text/css">
    .fade-enter-active,
    .fade-leave-active {
      transition: opacity .5s;
    }

    .fade-enter,
    .fade-leave-to {
      opacity: 0;
    }
  </style> 

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    @include('includes.sb_sidebar')

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

      @include('includes.sb_navbar')

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          
          @yield('content')
          
        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      @include('includes.sb_footer')

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Go Top-->
  @include('includes.sb_gotop')
  
  <!-- Logout modal -->
  @include('includes.sb_logout')

  <script src="{{ asset('js/app.js') }}"></script>

  

  <script>
            
  </script>

  @stack('scripts')

  <script>
    window.onload = function() {
      let toggleStatus = localStorage.getItem('toggleStatus');
      let accordionSidebar = document.getElementById('accordionSidebar');

      if(toggleStatus == '1') {
        accordionSidebar.classList.remove('toggled');
      } else if (toggleStatus == '0') {
        accordionSidebar.classList.add('toggled');
      }

      setTimeout(() => {
        toggleAccordion();
      },2000);
      


      document.getElementById('sidebarToggle').onclick = function() {
        toggleAccordion();
      }


      function toggleAccordion() {
        if(toggleStatus == null) {
          localStorage.setItem('toggleStatus', '1');
        } else if(accordionSidebar.classList.contains('toggled')) {
          localStorage.setItem('toggleStatus', '0');
        } else if(!accordionSidebar.classList.contains('toggled')) {
          localStorage.setItem('toggleStatus', '1');
        }

        ApiClient.get('/toggle_sb/' + localStorage.getItem('toggleStatus'));
      }


    }

    $(function () {
      $('[data-toggle="tooltip"]').tooltip();
    });

    $(function () {
      $('[data-toggle="popover"]').popover();
    });
  </script>

  

</body>

</html>
