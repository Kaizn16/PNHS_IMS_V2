@extends('layouts.app')
@section('content')
    <section class="wrapper">
        <header class="header">
            <h2 class="title">Manage Subjects</h2>
        </header>

        <div class="content-box">
            <div class="table-container">
                <heder class="table-header">
                    <h2 class="title">
                        All Subjects
                    </h2>
                    <div class="table-header-actions">
                        <div class="buttons">
                            <a href="{{ route('admin.create.subject') }}"><i class="material-icons icon">add</i>NEW SUBJECT</a>
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
                        <select name="filterByStrand" id="filterByStrand">
                            <option value="">Filter By Strand</option>
                            @foreach ($strands as $strand)
                                <option value="{{ $strand->strand_id }}">{{ $strand->strand_name }}</option>
                            @endforeach
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
                                <th>Strand</th>
                                <th>Subject Code</th>
                                <th>Title</th>
                                <th>Description</th>
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
    <script src="{{ asset('assets/js/subjects/subject.js') }}"></script>
@endsection