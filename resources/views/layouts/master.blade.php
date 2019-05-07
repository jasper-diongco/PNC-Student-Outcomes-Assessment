<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>PNC SOA &mdash; @yield('title')</title>

    <!-- Material icon -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
  rel="stylesheet">

    <!-- Font Awesome JS -->
    <script
        defer
        src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js"
        integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ"
        crossorigin="anonymous"
    ></script>
    <script
        defer
        src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js"
        integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY"
        crossorigin="anonymous"
    ></script>
    {{-- ion icon --}}
    <link href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
  
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>
<body>
  @include('includes/navbar');
  <div class="wrapper">
  @include('includes/sidebar');
    <div id="content" class="mt-5 pb-5 mb-5 {{ !Auth::check() ? 'active' : '' }}">
           @yield('content')
    </div>

  </div>

  <footer class="fixed-bottom" style="background: #e8e8e8;">
    <small class="d-block text-sm-right mr-3 p-1 text-muted">PNC &copy; 2019 &mdash; Student Outcomes Assessments</small>
  </footer>


    
  <script src="{{ asset('js/app.js') }}"></script>

  <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
  <script type="text/javascript">
        $(document).ready(function () {

            $('#sidebarCollapse').on('click', function () {
                $('#sidebar, #content').toggleClass('active');
                $('.collapse.in').toggleClass('in');
                $('a[aria-expanded=true]').attr('aria-expanded', 'false');

                $('.dropdown-toggle')
            });

            $('.dropdown-toggle').on('click', function() {
                var li = $(this).closest('li');
                if(li.hasClass('submenu')) {
                    li.removeClass('submenu');
                } else {
                    li.addClass('submenu');
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>