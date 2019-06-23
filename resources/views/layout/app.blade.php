<?php
    $active_link = $active_link ?? '';
?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>PNC | SOA - @yield('title')</title>

    {{-- fonts --}}

    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">

    <link rel="stylesheet" href="{{ asset('css/prism.css') }}">

    <script src="{{ asset('js/prism.js') }}"></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="main" class="d-flex flex-column">
        

        <nav class="navbar navbar-expand-lg navbar-light bg-white">
            <a class="navbar-brand" href="#">
            <img src="{{ asset('img/pnc_logo_name_small.png') }}" height="35" alt="PNC Logo">
            </a>
            <span class="soa text-success">Student Outcomes Assessment</span>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
          </nav>

          <ul class="main-nav nav">
            <li class="nav-item">
              <a class="nav-link " href="#"><i class="fa fa-home"></i> Home</a>
            </li>
            <li class="nav-item {{ $active_link == 'colleges' ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('/colleges') }}"><i class="fa fa-university"></i> Colleges</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#"><i class="fa fa-graduation-cap"></i> Programs</a>
            </li>
          </ul>

          <div class="main-header d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                @if(isset($back_link))
                  <a href="{{url($back_link)}}" class="mr-3 btn btn-sm btn-success"><i class="fa fa-arrow-left "></i> Back</a>
                @endif
                <h1 class="mr-3">{{ $header ?? '' }} <i class="fa fa-cog text-info"></i></h1>
            </div>
            <div>
                @component('components.breadcrumb', ['link' => $link ?? 'home'])
                @endcomponent
            </div>
          </div>

        <main id="page-content" class="py-4">
                <div class="container-fluid">
                    @yield('content')

                </div>
            
        </main>

        <footer id="sticky-footer" class="py-2 bg-white">
            <div class="container-fluid">
                <small>Pamantasan ng Cabuyao | SOA &copy; 2019</small>
            </div>
        </footer>

    </div>
</body>
</html>


<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>

@stack('scripts')
