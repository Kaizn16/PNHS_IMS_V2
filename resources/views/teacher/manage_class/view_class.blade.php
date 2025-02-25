@extends('layouts.app')
@section('content')
    <section class="wrapper">
        <header class="header">
            <h2 class="title">Manage Classes</h2>
        </header>

        <div class="content-box">
            <div class="table-container">
                <heder class="table-header">
                    <h2 class="title">
                        Students
                    </h2>
                    <div class="table-header-actions">
                        <div class="table-information">
                            <h2>Class Information</h2>
                            <strong>Class Name: {{ $manageClass->class_name }}</strong>
                            <strong>Subject: {{ $manageClass->subject->subject_code }}</strong>
                            <strong>Room: {{ $manageClass->room->room_name }}</strong>
                            <strong>Teacher: {{ $manageClass->teacher->last_name }}, {{ $manageClass->teacher->first_name }} {{ $manageClass->teacher->middle_name ?? ''}}</strong>
                            <strong>Total Students: {{ $totalStudents ?? 0 }}</strong>
                            <strong> Schedules:
                                @if($schedules->isNotEmpty())
                                    @foreach($schedules as $schedule)
                                        <strong>
                                            {{ $schedule->day }} - 
                                            {{ \Carbon\Carbon::createFromFormat('H:i:s', $schedule->time_start)->format('h:i A') }} - 
                                            {{ \Carbon\Carbon::createFromFormat('H:i:s', $schedule->time_end)->format('h:i A') }},
                                        </strong>
                                    @endforeach
                                @else
                                    <p>No schedule available</p>
                                @endif
                            </strong>
                        </div>
                        <div class="buttons">
                            <a href="{{ route('teacher.manageclass') }}"><i class="material-icons icon">arrow_back</i>BACK</a>
                        </div>
                        <div class="filters">
                            <div class="search_wrap search_wrap_1">
                                <div class="search_box">
                                    <input type="text" class="input" id="search" name="search" placeholder="Search....">
                                    <div class="btn btn_common">
                                        <i class="search-icon material-icons">search</i>
                                    </div>
                                </div>
                            </div>
                            <div class="selectionFilter">
                                <select name="pageSize" id="paginationSize-select">
                                    <option value="10">Show 10</option>
                                    <option value="25">Show 25</option>
                                    <option value="50">Show 50</option>
                                    <option value="100">Show 100</option>
                                    <option value="500">Show 500</option>
                                    <option value="1000">Show 1000</option>
                                </select>
                                <button class="print" id="printButton" title="PRINT"><i class="material-icons">print</i></button>
                            </div>                        
                        </div>
                    </div>
                </heder>
                <div class="table-wrapper">
                    <header class="header">
                        <select name="filterBySex" id="filterBySex">
                            <option value="">Filter By Sex</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </header>
                    <table class="table">
                        <thead>
                            <tr class="heading">
                                <th>#</th>
                                <th>Last name | First name | Middle name</th>
                                <th>Sex</th>
                            </tr>
                        </thead>
                        <tbody class="tableData">
                            
                        </tbody>
                    </table>
                </div>
                <div class="pagination-container">
                    <div class="pagination-info">
                        
                    </div>
                    <ul class="paginations">
                
                    </ul>
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
    CSS_ROUTE = `{{ asset('assets/css/main.css') }}`;
    SCHOOL_LOGO = `{{ asset('assets/images/Logo.jpg') }}`;
</script>
<script src="{{ asset('assets/js/manage_classes/view_class.js') }}"></script>
@endsection