<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-role" content="{{ Auth::user() ? Auth::user()->role->role_type : '' }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/Logo.jpg') }}">
    <title>{{ config('app.name', 'PNHS IMS') }} - {{ Request::path() }}</title>

    <!----===== CSS ===== -->
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/material-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/icons/more-material-icons.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/toastr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}" />
    <!----===== CSS ===== -->
    @routes
</head>
<body>
    <main class="container" style="visibility: hidden;">

        @if (Auth::user()->role->role_type == 'admin')
            @include('layouts.admin_navigation')
        @elseif (Auth::user()->role->role_type == 'teacher')
            @include('layouts.teacher_navigation')
        @elseif (Auth::user()->role->role_type == 'student')
            @include('layouts.student_navigation')
        @else
            @include('layouts.404')
        @endif

        <section class="content">
            <x-loading-spinner/>
            @yield('content')
        </section>
    </main>
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/js/chart.js') }}"></script>
    <script src="{{ asset('assets/js/sweetalert2-11.js') }}"></script>
    <script src="{{ asset('assets/js/preloader.js') }}"></script>
    <script src="{{ asset('assets/js/toastnotif.js') }}"></script>
    <script src="{{ asset('assets/js/lodash.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    @yield('script')
</body>
</html>