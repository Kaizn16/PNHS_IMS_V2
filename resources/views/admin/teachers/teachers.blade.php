@extends('layouts.app')

@section('content')
    <section class="wrapper">
        <header class="header">
            <h2 class="title">Manage Teachers</h2>
        </header>

        <div class="content-box">
            <div class="table-container">
                <heder class="table-header">
                    <h2 class="title">
                        All Teachers
                    </h2>
                    <div class="table-header-actions">
                        <div class="buttons">
                            <a href="{{ route('admin.create.teacher') }}"><i class="material-icons icon">add</i>NEW TEACHER</a>
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
                            </div>                        
                        </div>
                    </div>
                </heder>
                <div class="table-wrapper">
                    <header class="header">
                        <button class="bulkAction bulkActionDelete" type="button" title="BULK ACTION"><i class="material-icons icon">delete</i>BULK DELETE</button>
                        <button class="bulkAction bulkActionRestore" type="button" title="BULK ACTION"><i class="material-icons icon">restore</i>BULK RESTORE</button>
                        <select name="filterByRole" id="filterByRole">
                            <option value="">Filter By Role</option>
                            <option value="Adviser">Adviser</option>
                            <option value="Teacher">Teacher</option>
                        </select>
                        <select name="filterBySex" id="filterBySex">
                            <option value="">Filter By Sex</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                        <select name="filterByStatus" id="filterByStatus">
                            <option value="">Filter By Status</option>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                        <select name="filterByEmyploymentType" id="filterByEmyploymentType">
                            <option value="">Filter By Employment Type</option>
                            <option value="Full-Time">Full-Time</option>
                            <option value="Part-Time">Part-Time</option>
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
                                <th>Civil Status</th>
                                <th>Nationality</th>
                                <th>Role</th>
                                <th>Date Hired</th>
                                <th>Employment Type</th>
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
<script src="{{ asset('assets/js/teachers/teachers.js') }}"></script>
@endsection