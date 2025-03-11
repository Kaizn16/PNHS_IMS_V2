@extends('layouts.app')
@section('content')
    <section class="wrapper">
        <header class="header">
            <h2 class="title">Manage Academic Records</h2>
        </header>

        <div class="content-box">
            <div class="table-container">
                <header class="table-header">
                    <h2 class="title">
                        Academic Records
                    </h2>
                    <div class="table-header-actions">
                        <div class="table-information">
                            <h1>Students Grade</h1>
                            <strong>Subject: {{ $classRecord->subject->subject_code }}</strong>
                            <strong>Teacher: {{ $classRecord->teacher->first_name }} {{ $classRecord->teacher->last_name }}</strong>
                            <strong>Semester: {{ $classRecord->semester }}</strong>
                            <strong>School Year: {{ $classRecord->school_year }}</strong>
                        </div>
                        <div class="buttons">
                            <a href="{{ route('teacher.academic.records') }}"><i class="material-icons icon">arrow_back</i>BACK</a>
                            @if ($classRecord->teacher_id == $teacher->teacher_id)
                                <a href="{{ route('teacher.edit.academic.record', ['class_management_id' => $classRecord->class_management_id]) }}"><i class="material-icons icon">edit</i>EDIT RECORD</a>
                            @endif
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
                </header>
                <div class="table-wrapper">
                    <table class="table">
                        <thead>
                            <tr class="heading">
                                <th>#</th>
                                <th>Student</th>
                                <th colspan="2">Midterm Exam</th>
                                <th colspan="2">Final Exam</th>
                                <th>Average Grade</th>
                                <th>Remarks</th>
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
        const CLASS_RECORD = @json($classRecord->class_management_id);
        CSS_ROUTE = `{{ asset('assets/css/main.css') }}`;
        SCHOOL_LOGO = `{{ asset('assets/images/Logo.jpg') }}`;
    </script>
    <script src="{{ asset('assets/js/academic_records/teacher_academic_records.js') }}"></script>
@endsection