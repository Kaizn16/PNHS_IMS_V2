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
                <div class="chart">
                    <strong class="text"><i class="material-icons icon">analytics</i>Students</strong>
                    <canvas id="studentsChart" height="200"></canvas>
                </div>
                <div class="chart">
                    <strong class="text"><i class="material-icons icon">analytics</i>Teachers</strong>
                    <canvas id="teachersChart" height="200"></canvas>
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

        const CONTINUING_STUDENTS = @json($continuingStudents);
        const GRADUATED_STUDENTS = @json($graduatedStudents);
        const STOPPED_STUDENTS = @json($stoppedStudents);

        const ctxStudentsData = document.getElementById('studentsChart').getContext('2d');
        
        new Chart(ctxStudentsData, {
            type: 'pie',
            data: {
                labels: ['Continuing', 'Graduated', 'Stopped'],
                datasets: [{
                    label: 'Total Students',
                    data: [CONTINUING_STUDENTS, GRADUATED_STUDENTS, STOPPED_STUDENTS],
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

    async function renderTeachersChart() {

        const FULL_TIME_TEACHERS = @json($fullTimeTeachers);
        const PART_TIME_TEACHERS = @json($partTimeTeachers);
        const ACTIVE_TEACHERS = @json($activeTeachers);
        const INACTIVE_TEACHERS = @json($inactiveTeachers);

        const ctxTechersData = document.getElementById('teachersChart').getContext('2d');

        new Chart(ctxTechersData, {
            type: 'pie',
            data: {
                labels: ['Full-Time', 'Part-Time', 'Active', 'Inactive'],
                datasets: [{
                    label: 'Total Teachers',
                    data: [FULL_TIME_TEACHERS, PART_TIME_TEACHERS, ACTIVE_TEACHERS, INACTIVE_TEACHERS],
                    backgroundColor: ['#36a2eb', '#4caf50', '#ff6384', '#4caf50'],
                    borderColor: ['#258cd1', '#388e3c', '#d32f2f', '#4caf50'],
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
    document.addEventListener('DOMContentLoaded', renderTeachersChart);
</script>
@endsection