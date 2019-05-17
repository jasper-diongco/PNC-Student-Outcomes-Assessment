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

<body class="bg-gradient-success">

  <div class="container">
    @yield('content')
  </div>

  <script src="{{ asset('js/app.js') }}"></script>

  @stack('scripts')

</body>

</html>
