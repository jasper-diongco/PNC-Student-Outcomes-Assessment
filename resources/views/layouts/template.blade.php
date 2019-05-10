<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- CSRF TOKEN -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta charset="utf-8" />
        <link rel="icon" type="image/png" href="assets/img/favicon.ico" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

        <title>PNC SOA &mdash; @yield('title')</title>

        <meta
            content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"
            name="viewport"
        />
        <meta name="viewport" content="width=device-width" />

        <!-- Main style -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <!-- Bootstrap core CSS     -->
        {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"> --}}

        <!-- Animation library for notifications   -->
        <link href="assets/css/animate.min.css" rel="stylesheet" />

        <!--  Light Bootstrap Table core CSS    -->
        <link
            href="assets/css/light-bootstrap-dashboard.css?v=1.4.0"
            rel="stylesheet"
        />

        <!--     Fonts and icons     -->
        <link
            href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css"
            rel="stylesheet"
        />
        <link
            href="http://fonts.googleapis.com/css?family=Roboto:400,700,300"
            rel="stylesheet"
            type="text/css"
        />
        <link href="assets/css/pe-icon-7-stroke.css" rel="stylesheet" />

        <link
            rel="stylesheet"
            href="https://use.fontawesome.com/releases/v5.8.2/css/all.css"
            integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay"
            crossorigin="anonymous"
        />
        <style>
            .sidebar-wrapper .fa {
                font-size: 17px !important;
            }
        </style>
        
    </head>
    <body>
        <div class="wrapper">
            <div class="sidebar" data-color="grey">
                <!--

        Tip 1: you can change the color of the sidebar using: data-color="blue | azure | green | orange | red | purple"
        Tip 2: you can also add an image using data-image tag

    -->

                <div class="sidebar-wrapper">
                    <div class="logo">
                        <a
                            href="http://www.creative-tim.com"
                            class="simple-text"
                        >
                            <img
                                style="width: 150px;"
                                src="assets/img/pnc_logo_name_small.png"
                                alt="pnc-log"
                            />
                        </a>
                    </div>

                    <ul class="nav">
                        <li class="active">
                            <a href="dashboard.html">
                                <i class="fa fa-edit"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li>
                            <a href="dashboard.html">
                                <i class="fa fa-university"></i>
                                <p>College</p>
                            </a>
                        </li>
                        <li>
                            <a href="dashboard.html">
                                <i class="fa fa-book"></i>
                                <p>Courses</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="main-panel">
                <nav class="navbar navbar-default navbar-fixed">
                    <div class="container-fluid">
                        <div class="navbar-header">
                            <button
                                type="button"
                                class="navbar-toggle"
                                data-toggle="collapse"
                            >
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <a class="navbar-brand" href="#">Dashboard</a>
                        </div>
                        <div class="collapse navbar-collapse">
                            <ul class="nav navbar-nav navbar-left">
                                <li>
                                    <a
                                        href="#"
                                        class="dropdown-toggle"
                                        data-toggle="dropdown"
                                    >
                                        <i class="fa fa-dashboard"></i>
                                    </a>
                                </li>
                            </ul>

                            <ul class="nav navbar-nav navbar-right">
                                <li>
                                    <a href="">
                                        Account
                                    </a>
                                </li>
                                <li class="dropdown">
                                    <a
                                        href="#"
                                        class="dropdown-toggle"
                                        data-toggle="dropdown"
                                    >
                                        <i class="fa fa-globe"></i>
                                        <b
                                            class="caret hidden-sm hidden-xs"
                                        ></b>
                                        <span
                                            class="notification hidden-sm hidden-xs"
                                            >5</span
                                        >
                                        <p class="hidden-lg hidden-md">
                                            5 Notifications
                                            <b class="caret"></b>
                                        </p>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a href="#">Notification 1</a></li>
                                        <li><a href="#">Notification 2</a></li>
                                        <li><a href="#">Notification 3</a></li>
                                        <li><a href="#">Notification 4</a></li>
                                        <li>
                                            <a href="#">Another notification</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>

                <div class="content">
                    <div class="container-fluid">
                        @yield('content')
                    </div>
                </div>

                <footer class="footer">
                    <div class="container-fluid">
                        <nav class="pull-left">
                            <ul>
                                <li>
                                    <a href="#">
                                        Home
                                    </a>
                                </li>
                            </ul>
                        </nav>
                        <p class="copyright pull-right">
                            &copy;
                            <script>
                                document.write(new Date().getFullYear());
                            </script>
                            <a href="http://www.creative-tim.com"
                                >Creative Tim</a
                            >, made with love for a better web
                        </p>
                    </div>
                </footer>
            </div>
        </div>
    </body>

    <!--   Core JS Files   -->
    {{-- <script src="assets/js/jquery.3.2.1.min.js" type="text/javascript"></script>
    <script src="assets/js/bootstrap.min.js" type="text/javascript"></script> --}}
    <script src="{{ asset('js/app.js') }}"></script>
    <!-- Light Bootstrap Table Core javascript and methods for Demo purpose -->
    <script src="assets/js/light-bootstrap-dashboard.js?v=1.4.0"></script>

    @stack('scripts')
</html>
