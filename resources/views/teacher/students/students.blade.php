@extends('layouts.app')

@section('content')
    <section class="wrapper">
        <header class="header">
            <h2 class="title">Manage Students</h2>
        </header>

        <div class="content-box">
            <div class="table-container">
                <heder class="table-header">
                    <h2 class="title">
                        All Students
                    </h2>
                    <div class="table-header-actions">
                        @if ($teacher->designation == 'Adviser')
                            <div class="buttons">
                                <a href="{{ route('teacher.create.student') }}"><i class="material-icons icon">add</i>NEW STUDENT</a>
                            </div>
                        @endif
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
                        <button class="bulkAction bulkActionDelete" type="button" title="BULK ACTION"><i class="material-icons icon">delete</i>BULK DELETE</button>
                        <button class="bulkAction bulkActionRestore" type="button" title="BULK ACTION"><i class="material-icons icon">restore</i>BULK RESTORE</button>
                        <select name="filterBySex" id="filterBySex">
                            <option value="">Filter By Sex</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
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
                        <select name="filterByStrand" id="filterByStrand">
                            <option value="">Filter By Strand</option>
                            @foreach ($strands as $strand)
                                <option value="{{ $strand->strand_id }}">{{ $strand->strand_name }}</option>
                            @endforeach
                        </select>
                        <select name="filterBySchoolYear" id="filterBySchoolYear">
                            <option value="">Filter By School Year</option>
                            @foreach ($schoolYears as $schoolYear)
                                <option value="{{ $schoolYear->school_year }}">{{ $schoolYear->school_year }}</option>
                            @endforeach
                        </select>
                        <select name="filterByStatus" id="filterByStatus">
                            <option value="">Filter By Status</option>
                            <option value="Continuing">Continuing</option>
                            <option value="Graduate">Graduate</option>
                            <option value="Stopped">Stopped</option>
                        </select>
                        <span class="table-more-filters" id="table-more-filters" title="More Actions">
                            <i class="material-icons icon">filter_alt</i>
                            <div class="dropdown-wrapper">
                                <div class="checkbox-wrapper">
                                    <input class="checkbox" id="deletedData" type="checkbox"/>
                                    <label for="deletedData">Show Deleted</label>
                                </div>
                            </div>
                        </span>
                    </header>
                    <table class="table">
                        <thead>
                            <tr class="heading">
                                <th>
                                    <div class="checkbox-wrapper">
                                        <input id="checkAll" class="checkbox" type="checkbox"/>
                                    </div>                                  
                                </th>
                                <th>#</th>
                                <th>Name</th>
                                <th>Sex</th>
                                <th>Date of Birth</th>
                                <th>Year Level</th>
                                <th>Section</th>
                                <th>Strand</th>
                                <th>Adviser</th>
                                <th>School Year</th>
                                <th>Status</th>
                                <th>
                                    <i class="material-icons icon">settings</i>
                                </th>
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
    const userDesignation = @json($teacher->designation);
</script> 
<script src="{{ asset('assets/js/students/students.js') }}"></script>
@endsection