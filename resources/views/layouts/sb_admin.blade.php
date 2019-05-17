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

  <!-- Custom fonts for this template-->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">

  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('css/app.css') }}">

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

  @stack('scripts')

</body>

</html>
