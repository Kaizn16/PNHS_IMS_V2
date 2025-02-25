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
    <!----===== CSS ===== -->
</head>
<body>
    <main class="container" style="visibility: hidden;">
        <section class="content">
            <x-loading-spinner/>
            <h1>PAGE NOT FOUND!</h1>
        </section>
    </main>
</body>
</html>