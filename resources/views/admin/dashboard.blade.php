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
                        <h1>Users</h1>
                        <a href="{{ route('admin.users') }}" title="USERS">
                            <i class="material-icons icon">north_east</i>
                        </a>
                    </header>
                    <div class="box-info">
                        <i class="material-icons icon">people</i>
                        <strong>{{ $totalUsers ?? 0 }}</strong>
                    </div>
                </div>
                <div class="box">
                    <header class="header">
                        <h1>Students</h1>
                        <a href="{{ route('admin.students') }}" title="STUDENTS">
                            <i class="material-icons icon">north_east</i>
                        </a>
                    </header>
                    <div class="box-info">
                        <i class="material-icons icon">groups</i>
                        <strong>{{ $totalStudents ?? 0 }}</strong>
                    </div>
                </div>
                <div class="box">
                    <header class="header">
                        <h1>Teachers</h1>
                        <a href="{{ route('admin.teachers') }}" title="TEACHERS">
                            <i class="material-icons icon">north_east</i>
                        </a>
                    </header>
                    <div class="box-info">
                        <i class="material-icons icon">badge</i>
                        <strong>{{ $totalTeachers ?? 0 }}</strong>
                    </div>
                </div>
            </div>

            <div class="charts">
                <div class="chart chart1">
                    <strong class="text"><i class="material-icons icon">analytics</i>Students</strong>
                    <canvas id="studentsChart" height="200"></canvas>
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
<script>
    async function renderStudentChart() {

        const CONTINUING = @json($Continuing);
        const GRADUATED = @json($Graduated);
        const STOPPED = @json($Stopped);

        const ctx = document.getElementById('studentsChart').getContext('2d');
        
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Continuing', 'Graduated', 'Stopped'],
                datasets: [{
                    label: 'Total Students',
                    data: [CONTINUING, GRADUATED, STOPPED],
                    backgroundColor: ['#36a2eb', '#4caf50', '#ff6384'],
                    borderColor: ['#258cd1', '#388e3c', '#d32f2f'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', renderStudentChart);
</script>
@endsection