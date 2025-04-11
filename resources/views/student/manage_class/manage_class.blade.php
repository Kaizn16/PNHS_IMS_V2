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
                        All Classes
                    </h2>
                    <div class="table-header-actions">
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
                            </div>                        
                        </div>
                    </div>
                </heder>
                <div class="table-wrapper">
                    <header class="header">
                        <select name="filterBySchoolYear" id="filterBySchoolYear">
                            <option value="">Filter By School Year</option>
                            @foreach ($schoolYears as $school_year)
                                <option value="{{ $school_year->school_year }}">{{ $school_year->school_year }}</option>
                            @endforeach
                        </select>
                        <select name="filterBySemester" id="filterBySemester">
                            <option value="">Filter By Semester</option>
                            <option value="1st Semester">1st Semester</option>
                            <option value="2nd Semester">2nd Semester</option>
                        </select>
                        <select name="filterByYearLevel" id="filterByYearLevel">
                            <option value="">Filter By Year Level</option>
                            <option value="Grade 11">Grade 11</option>
                            <option value="Grade 12">Grade 12</option>
                        </select>
                        <select name="filterBySection" id="filterBySection">
                            <option value="">Filter By Section</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                            <option value="E">E</option>
                            <option value="F">F</option>
                        </select>
                    </header>
                    <table class="table">
                        <thead>
                            <tr class="heading">
                                <th>#</th>
                                <th>Class Name</th>
                                <th>Subject</th>
                                <th>Room</th>
                                <th>Teacher</th>
                                <th>Schedule</th>
                                <th>Year Level</th>
                                <th>Section</th>
                                <th>Semester</th>
                                <th>School Year</th>
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
<script src="{{ asset('assets/js/manage_classes/student.classes.js') }}"></script>
@endsection