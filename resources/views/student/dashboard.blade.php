@extends('layouts.app')

@section('content')
    <section class="wrapper">
        <header class="header">
            <h2 class="title">Dashboard</h2>
        </header>

        <div class="content-box" style="background: transparent; border: none;">
            <div class="boxes">
                <div class="box">
                    <header class="header">
                        <h1>My Classes</h1>
                        <a href="{{ route('student.manageclass') }}" title="MY CLASSES">
                            <i class="material-icons icon">north_east</i>
                        </a>
                    </header>
                    <div class="box-info">
                        <i class="material-icons icon">class</i>
                        <strong>{{ $myTotalClasses ?? 0 }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
@if (session('message'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            showToast("{{ session('type') }}", "{{ session('message') }}");
        }); 
    </script>
@endif
@endsection