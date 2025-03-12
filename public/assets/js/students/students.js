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

        let showAction = student.is_deleted == 1 ? 
        `
            <span data-student_id='${student.student_id}' onclick="event.stopPropagation(); restoreAction(this);" class="restore" title="RESTORE"><i class="material-icons">restore</i></span>
            <span data-student_id='${student.student_id}' onclick="event.stopPropagation(); deleteAction(this, true);" class="delete" title="PERMANENT DELETE"><i class="material-icons">delete</i></span>
        ` : 
        `
            <a href="${route(`${authUserRole}.edit.student`, { student_id: student.student_id })}" class="edit" title="EDIT"><i class="material-icons">edit</i></a>
            <span data-student_id='${student.student_id}' onclick="event.stopPropagation(); deleteAction(this);" class="delete" title="DELETE"><i class="material-icons">delete</i></span>
        `;

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
                        <span data-studentData='${JSON.stringify(student)}' onclick="event.stopPropagation(); viewAction(this);" class="view" title="VIEW"><i class="material-icons">visibility</i></span>
                        ${userDesignation !== 'Teacher' ? showAction : ''}
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

function viewAction(studentData) {
    var studentData = JSON.parse(studentData.getAttribute('data-studentData'));

    Swal.fire({
        html: `
            <h1>View Student Info</h1>
            <hr>
            <section class="view-group-col">
                <h3>Personal Information</h3>
                <strong>First name: <span>${studentData.first_name}</span></strong>
                <strong>Middle name: <span>${studentData.middle_name || ''}</span></strong>
                <strong>Last name: <span>${studentData.last_name}</span></strong>
                <strong>Sex: <span>${studentData.sex}</span></strong>
                <strong>Date Of Birth: <span>${formatDate(studentData.date_of_birth)}</span></strong>
                <strong>Nationality: <span>${studentData.nationality}</span></strong>
            </section>
            <hr>
            <section class="view-group-col">
                <h3>Address Information</h3>
                <strong>Province: <span>${studentData.province.province_name}</span></strong>
                <strong>Municipality: <span>${studentData.municipality.municipality_name}</span></strong>
                <strong>Barangay: <span>${studentData.barangay.barangay_name}</span></strong>
                <strong>Street Address: <span>${studentData.street_address}</span></strong>
            </section>
            <hr>
        `,
        showConfirmButton: false,
        showCancelButton: true,
        cancelButtonColor: "#d33",
        cancelButtonText: 'CLOSE',
        customClass: {
            popup: 'custom-swal-popup',
        },
    });
}

function deleteAction(student_id, destroy = false) {

    var student_id = JSON.parse(student_id.getAttribute('data-student_id'));

    const DELETE_ROUTE = destroy ? route(`${authUserRole}.destroy.student`) : route(`${authUserRole}.softdelete.student`);
    const method = destroy ? 'DELETE' : 'PUT';

    Swal.fire({
        title: 'Are you sure?',
        html: `<strong class="info">Are you sure you want to delete selected data?</strong>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'YES',
        confirmButtonColor: '#5296BE',
        cancelButtonColor: "#d33",
        cancelButtonText: 'CANCEL',
        reverseButtons: true,
        customClass: {
            popup: 'custom-swal-popup',
            title: 'custom-swal-title',
            content: 'custom-swal-text',
        },
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`${DELETE_ROUTE}?student_id=${student_id}`, {
                method: method,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('info', data.message);
                    fetchStudents(search = '', page = 1, pageSize = 10, sex = '', year_level = '', section = '', strand = '', adviser = '', school_year = '', enrollment_status = '', is_deleted = 0);
                } else {
                    showToast('error', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('error', 'An error occurred while deleting the data.');
            });
        }
    });
}


document.querySelector('.bulkActionDelete').addEventListener('click', bulkActionDelete);

function bulkActionDelete() {
    const BULK_DELETE_ROUTE = route(`${authUserRole}.softbulkdelete.student`);
    const selectedIds = Array.from(document.querySelectorAll('.checkData:checked')).map(checkbox => checkbox.dataset.student_id);

    if (selectedIds.length === 0) {
        showToast('warning', 'No Selection, Please select at least one record to delete.');
        return;
    }

    Swal.fire({
        title: 'Are you sure?',
        html: `<strong class="info">Are you sure you want to delete selected data?</strong>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'YES',
        confirmButtonColor: '#5296BE',
        cancelButtonColor: "#d33",
        cancelButtonText: 'CANCEL',
        reverseButtons: true,
        customClass: {
            popup: 'custom-swal-popup',
            title: 'custom-swal-title',
            content: 'custom-swal-text',
        },
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`${BULK_DELETE_ROUTE}`, {
                method: 'PUT',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ student_ids: selectedIds })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('info', data.message);
                    fetchStudents(search = '', page = 1, pageSize = 10, sex = '', year_level = '', section = '', strand = '', adviser = '', school_year = '', enrollment_status = '', is_deleted = 0);
                } else {
                    showToast('error', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('error', 'An error occurred while deleting the data.');
            });
        }
    });
}

document.querySelector('.bulkActionRestore').addEventListener('click', bulkActionRestore);

function bulkActionRestore() {
    const BULK_RESTORE_ROUTE = route(`${authUserRole}.bulkrestore.student`);
    const selectedIds = Array.from(document.querySelectorAll('.checkData:checked')).map(checkbox => checkbox.dataset.student_id);

    if (selectedIds.length === 0) {
        showToast('warning', 'No Selection, Please select at least one record to restore.');
        return;
    }

    Swal.fire({
        title: 'Are you sure?',
        html: `<strong class="info">Are you sure you want to restore selected data?</strong>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'YES',
        confirmButtonColor: '#5296BE',
        cancelButtonColor: "#d33",
        cancelButtonText: 'CANCEL',
        reverseButtons: true,
        customClass: {
            popup: 'custom-swal-popup',
            title: 'custom-swal-title',
            content: 'custom-swal-text',
        },
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`${BULK_RESTORE_ROUTE}`, {
                method: 'PUT',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ student_ids: selectedIds })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('info', data.message);
                    fetchStudents(search = '', page = 1, pageSize = 10, sex = '', year_level = '', section = '', strand = '', adviser = '', school_year = '', enrollment_status = '', is_deleted = 0);
                } else {
                    showToast('error', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('error', 'An error occurred while restoring the data.');
            });
        }
    });
}

function restoreAction(student_id) {

    var student_id = JSON.parse(student_id.getAttribute('data-student_id'));

    const RESTORE_ROUTE = route(`${authUserRole}.restore.student`);

    Swal.fire({
        title: 'Are you sure?',
        html: `<strong class="info">Are you sure you want to restore selected data?</strong>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'YES',
        confirmButtonColor: '#5296BE',
        cancelButtonColor: "#d33",
        cancelButtonText: 'CANCEL',
        reverseButtons: true,
        customClass: {
            popup: 'custom-swal-popup',
            title: 'custom-swal-title',
            content: 'custom-swal-text',
        },
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`${RESTORE_ROUTE}?student_id=${student_id}`, {
                method: 'PUT',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('info', data.message);
                    fetchStudents(search = '', page = 1, pageSize = 10, sex = '', year_level = '', section = '', strand = '', adviser = '', school_year = '', enrollment_status = '', is_deleted = 0);
                } else {
                    showToast('error', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('error', 'An error occurred while restoring the data.');
            });
        }
    });
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}