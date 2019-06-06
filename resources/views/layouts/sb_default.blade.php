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
  <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">

  <link rel="stylesheet" href="{{ asset('fonts/nunito.css') }}">

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
