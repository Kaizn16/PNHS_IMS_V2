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
    async function fetchStudentData() {
        const STUDENTS_CHART_ROUTE = route('admin.students.chart');
        try {
            const response = await fetch(`${STUDENTS_CHART_ROUTE}`);
            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Error fetching student data:', error);
            return { Continuing: 0, Graduated: 0, Stopped: 0 };
        }
    }

    async function renderStudentChart() {
        const studentData = await fetchStudentData();

        const ctx = document.getElementById('studentsChart').getContext('2d');
        
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Continuing', 'Graduated', 'Stopped'],
                datasets: [{
                    label: 'Total Students',
                    data: [studentData.Continuing, studentData.Graduated, studentData.Stopped],
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