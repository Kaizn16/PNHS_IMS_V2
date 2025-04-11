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
    let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const userRoleMeta = document.querySelector('meta[name="user-role"]');
    const authUserRole = userRoleMeta ? userRoleMeta.getAttribute('content') : null;

    const tableData = document.querySelector('.tableData');
    const searchInput = document.getElementById('search');
    const paginationContainer = document.querySelector('.paginations');
    const paginationInfo = document.querySelector('.pagination-info');
    const FETCH_STUDENTS = route(`${authUserRole}.students.fetch`);
    const paginationSizeSelect = document.getElementById('paginationSize-select');

    const filterBySex = document.getElementById('filterBySex');
    const filterByYearLevel = document.getElementById('filterByYearLevel');
    const filterBySection = document.getElementById('filterBySection');
    const filterByStrand = document.getElementById('filterByStrand');
    const filterByAdviser = document.getElementById('filterByAdviser');
    const filterBySchoolYear = document.getElementById('filterBySchoolYear');
    const filterByStatus = document.getElementById('filterByStatus');
    const filterByDeletedData = document.getElementById('deletedData');

    let currentPage = 1;
    let pageSize = parseInt(paginationSizeSelect.value);
    let debounceTimer;


    const tableFilterButton = document.getElementById('table-more-filters');
    const tableDropdownMenu = document.querySelector('#table-more-filters .dropdown-wrapper');

    function toggleDropdown(event) {
        tableDropdownMenu.classList.toggle('show');
        event.stopPropagation();
    }

    function closeDropdown(event) {
        if (!tableFilterButton.contains(event.target) && !tableDropdownMenu.contains(event.target)) {
            tableDropdownMenu.classList.remove('show');
        }
    }

    tableFilterButton.addEventListener('click', toggleDropdown);
    document.addEventListener('click', closeDropdown);


    async function fetchStudents(search = '', page = 1, pageSize = 10, sex = '', year_level = '', section = '', strand = '', adviser = '', school_year = '', enrollment_status = '', is_deleted = false) {
        try {
            const response = await fetch(`${FETCH_STUDENTS}?search=${search}&page=${page}&pageSize=${pageSize}
                &sex=${sex}&year_level=${year_level}&section=${section}&strand=${strand}&adviser=${adviser}&school_year=${school_year}
                &enrollment_status=${enrollment_status}&is_deleted=${is_deleted}`);
            const data = await response.json();
            populateTable(data.data);
            updatePagination(data);
            updatePaginationInfo(data);
        } catch (error) {
            console.error('Error fetching students:', error);
        }
    }

    paginationSizeSelect.addEventListener('change', () => {
        pageSize = parseInt(paginationSizeSelect.value);
        currentPage = 1;
        fetchStudents(searchInput.value, 
            currentPage,
            pageSize,
            filterBySex.value,
            filterByYearLevel.value,
            filterBySection.value,
            filterByStrand.value,
            filterByAdviser ? filterByAdviser.value : null,
            filterBySchoolYear.value,
            filterByStatus.value,
            filterByDeletedData.checked
        );
    });

    function populateTable(students) {
        tableData.innerHTML = '';

        if (students.length === 0) {
            tableData.innerHTML = '<tr><td colspan="12" style="text-align:center">No results found</td></tr>';
            return;
        }

        students.forEach((student, index) => {

            const row = `
                <tr>
                    <td class="checkboxTable">
                        <div class="checkbox-wrapper">
                            <input class="checkData checkbox" type="checkbox" data-student_id="${student.student_id}" data-deleted="${student.is_deleted == 1}" />
                        </div>
                    </td>
                    <td>${index + 1}</td>
                    <td>${student.first_name} ${student.middle_name ? `${student.middle_name[0]}.` : ''} ${student.last_name}</td>
                    <td>${student.sex}</td>
                    <td>${formatDate(student.date_of_birth)}</td>
                    <td>${student.current_year_level}</td>
                    <td>${student.section}</td>
                    <td>${student.strand.strand_name}</td>
                    <td>${student.teacher.first_name} ${student.teacher.middle_name ? `${student.teacher.middle_name[0]}.` : ''} ${student.teacher.last_name}</td>
                    <td>${student.school_year}</td>
                    <td>
                        <div class="${student.enrollment_status}">
                            <p>${student.enrollment_status}</p>
                        </div>
                    </td>
                    <td>
                        <div class="actions">
                            <a href="${route(`${authUserRole}.view.student`, { student_id: student.student_id})}" class="view" title="VIEW"><i class="material-icons">visibility</i></a>
                        </div>
                    </td>
                </tr>
            `;
            tableData.innerHTML += row;
        });

        initializeCheckboxEvents();
    }

    function updatePagination(data) {
        paginationContainer.innerHTML = '';

        if (data.prev_page_url) {
            paginationContainer.innerHTML += `<li class="previous">PREVIOUS</li>`;
        }

        for (let i = 1; i <= data.last_page; i++) {
            paginationContainer.innerHTML += `<li class="${i === data.current_page ? 'active' : ''}">${i}</li>`;
        }

        if (data.next_page_url) {
            paginationContainer.innerHTML += `<li class="next">NEXT</li>`;
        }

        handlePaginationEvents(data);
    }

    function updatePaginationInfo(data) {
        paginationInfo.innerHTML = `<strong>${data.current_page} | ${data.total_pages}</strong>`;
    }

    function handlePaginationEvents(data) {
        const prevButton = document.querySelector('.previous');
        const nextButton = document.querySelector('.next');
        const pageButtons = document.querySelectorAll('.paginations li:not(.previous, .next)');
        pageSize = parseInt(paginationSizeSelect.value);

        if (prevButton) {
            prevButton.addEventListener('click', () => {
                currentPage--;
                fetchStudents(searchInput.value, 
                    currentPage,
                    pageSize,
                    filterBySex.value,
                    filterByYearLevel.value,
                    filterBySection.value,
                    filterByStrand.value,
                    filterByAdviser ? filterByAdviser.value : null,
                    filterBySchoolYear.value,
                    filterByStatus.value,
                    filterByDeletedData.checked
                );
            });
        }

        if (nextButton) {
            nextButton.addEventListener('click', () => {
                currentPage++;
                fetchStudents(searchInput.value, 
                    currentPage,
                    pageSize,
                    filterBySex.value,
                    filterByYearLevel.value,
                    filterBySection.value,
                    filterByStrand.value,
                    filterByAdviser ? filterByAdviser.value : null,
                    filterBySchoolYear.value,
                    filterByStatus.value,
                    filterByDeletedData.checked
                );
            });
        }

        pageButtons.forEach(button => {
            button.addEventListener('click', () => {
                currentPage = parseInt(button.textContent);
                fetchStudents(searchInput.value, 
                    currentPage,
                    pageSize,
                    filterBySex.value,
                    filterByYearLevel.value,
                    filterBySection.value,
                    filterByStrand.value,
                    filterByAdviser ? filterByAdviser.value : null,
                    filterBySchoolYear.value,
                    filterByStatus.value,
                    filterByDeletedData.checked
                );
            });
        });
    }

    function initializeCheckboxEvents() {
        const checkDataBoxes = document.querySelectorAll('.checkData');
        const checkAll = document.querySelector('#checkAll');
        const bulkActionDelete = document.querySelector('.bulkActionDelete');
        const bulkActionRestore = document.querySelector('.bulkActionRestore');

        toggleBulkAction();

        checkAll.addEventListener('change', () => {
            checkDataBoxes.forEach(box => {
                box.checked = checkAll.checked;
            });
            toggleBulkAction();
        });

        checkDataBoxes.forEach(box => {
            box.addEventListener('change', () => {
                checkAll.checked = Array.from(checkDataBoxes).every(checkbox => checkbox.checked);
                toggleBulkAction();
            });
        });

        function toggleBulkAction() {
            const checkedBoxes = Array.from(checkDataBoxes).filter(checkbox => checkbox.checked);
            const anyChecked = checkedBoxes.length > 0;
            const anyDeleted = checkedBoxes.some(checkbox => checkbox.dataset.deleted === "true");

            bulkActionDelete.classList.toggle('show', anyChecked);
            bulkActionRestore.classList.toggle('show', anyChecked && anyDeleted);
        }
    }

    searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            const searchValue = searchInput.value;
            pageSize = parseInt(paginationSizeSelect.value);
            fetchStudents(searchValue, 
                currentPage,
                pageSize,
                filterBySex.value,
                filterByYearLevel.value,
                filterBySection.value,
                filterByStrand.value,
                filterByAdviser ? filterByAdviser.value : null,
                filterBySchoolYear.value,
                filterByStatus.value,
                filterByDeletedData.checked
            );
        }, 300);
    });

    function handleFiltersChange() {
        pageSize = parseInt(paginationSizeSelect.value);
        fetchStudents(searchInput.value, 
            currentPage,
            pageSize,
            filterBySex.value,
            filterByYearLevel.value,
            filterBySection.value,
            filterByStrand.value,
            filterByAdviser ? filterByAdviser.value : null,
            filterBySchoolYear.value,
            filterByStatus.value,
            filterByDeletedData.checked
        );
    }

    filterBySex.addEventListener('change', handleFiltersChange);
    filterByYearLevel.addEventListener('change', handleFiltersChange);
    filterBySection.addEventListener('change', handleFiltersChange);
    filterByStrand.addEventListener('change', handleFiltersChange);

    if (filterByAdviser) {
        filterByAdviser.addEventListener('change', handleFiltersChange);
    }

    filterBySchoolYear.addEventListener('change', handleFiltersChange);
    filterByStatus.addEventListener('change', handleFiltersChange);
    filterByDeletedData.addEventListener('change', handleFiltersChange);

    fetchStudents(search = '', page = 1, pageSize = 10, sex = '', year_level = '', section = '', strand = '', adviser = '', school_year = '', enrollment_status = '', is_deleted = 0); // Initial fetch

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }
</script>
@endsection