let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

const tableData = document.querySelector('.tableData');
const searchInput = document.getElementById('search');
const paginationContainer = document.querySelector('.paginations');
const paginationInfo = document.querySelector('.pagination-info');
const FETCH_TEACHERS = route('admin.teachers.fetch');
const paginationSizeSelect = document.getElementById('paginationSize-select');

const filterByRole = document.getElementById('filterByRole');
const filterBySex = document.getElementById('filterBySex');
const filterByStatus = document.getElementById('filterByStatus');
const filterByEmyploymentType = document.getElementById('filterByEmyploymentType');
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


async function fetchTeachers(search = '', page = 1, pageSize, role, sex, employment_status, employment_type, is_deleted) {
    try {
        const response = await fetch(`${FETCH_TEACHERS}?search=${search}&page=${page}&pageSize=${pageSize}&role=${role}&sex=${sex}&status=${employment_status}&employment_type=${employment_type}&is_deleted=${is_deleted}`);
        const data = await response.json();
        populateTable(data.data);
        updatePagination(data);
        updatePaginationInfo(data);
    } catch (error) {
        console.error('Error fetching teachers:', error);
    }
}

paginationSizeSelect.addEventListener('change', () => {
    pageSize = parseInt(paginationSizeSelect.value);
    currentPage = 1;
    fetchTeachers( 
        searchInput.value, 
        currentPage,
        pageSize, 
        filterByRole.value, 
        filterBySex.value, 
        filterByStatus.value, 
        filterByEmyploymentType.value,
        filterByDeletedData.checked
    );
});

function populateTable(teachers) {
    tableData.innerHTML = '';

    if (teachers.length === 0) {
        tableData.innerHTML = '<tr><td colspan="12" style="text-align:center">No results found</td></tr>';
        return;
    }

    teachers.forEach((teacher, index) => {

        let showAction = teacher.is_deleted == 1 ? 
        `
            <span data-teacher_id='${teacher.teacher_id}' onclick="event.stopPropagation(); restoreAction(this);" class="restore" title="RESTORE"><i class="material-icons">restore</i></span>
            <span data-teacher_id='${teacher.teacher_id}' onclick="event.stopPropagation(); deleteAction(this, true);" class="delete" title="PERMANENT DELETE"><i class="material-icons">delete</i></span>
        ` : 
        `
            <a href="${route('admin.edit.teacher', { teacher_id: teacher.teacher_id })}" class="edit" title="EDIT"><i class="material-icons">edit</i></a>
            <span data-teacher_id='${teacher.teacher_id}' onclick="event.stopPropagation(); deleteAction(this);" class="delete" title="DELETE"><i class="material-icons">delete</i></span>
        `;

        const row = `
            <tr>
                <td class="checkboxTable">
                    <div class="checkbox-wrapper">
                        <input class="checkData checkbox" type="checkbox" data-teacher_id="${teacher.teacher_id}" data-deleted="${teacher.is_deleted == 1}" />
                    </div>
                </td>
                <td>${index + 1}</td>
                <td>${teacher.first_name} ${teacher.middle_name ? `${teacher.middle_name[0]}.` : ''} ${teacher.last_name}</td>
                <td>${teacher.sex}</td>
                <td>${formatDate(teacher.date_of_birth)}</td>
                <td>${teacher.civil_status}</td>
                <td>${teacher.nationality}</td>
                <td>${teacher.designation}</td>
                <td>${formatDate(teacher.date_hired)}</td>
                <td>${teacher.employment_type}</td>
                <td>
                    <div class="${teacher.employment_status}">
                        <p>${teacher.employment_status}</p>
                    </div>
                </td>
                <td>
                    <div class="actions">
                        <span data-teacherData='${JSON.stringify(teacher)}' onclick="event.stopPropagation(); viewAction(this);" class="view" title="VIEW"><i class="material-icons">visibility</i></span>
                        ${showAction}
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
            fetchTeachers( 
                searchInput.value, 
                currentPage,
                pageSize,
                filterByRole.value, 
                filterBySex.value, 
                filterByStatus.value, 
                filterByEmyploymentType.value,
                filterByDeletedData.checked
            );
        });
    }

    if (nextButton) {
        nextButton.addEventListener('click', () => {
            currentPage++;
            fetchTeachers( 
                searchInput.value, 
                currentPage,
                pageSize,
                filterByRole.value, 
                filterBySex.value, 
                filterByStatus.value, 
                filterByEmyploymentType.value,
                filterByDeletedData.checked
            );
        });
    }

    pageButtons.forEach(button => {
        button.addEventListener('click', () => {
            currentPage = parseInt(button.textContent);
            fetchTeachers( 
                searchInput.value, 
                currentPage,
                pageSize,
                filterByRole.value, 
                filterBySex.value, 
                filterByStatus.value, 
                filterByEmyploymentType.value,
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
        fetchTeachers(searchValue, 
            currentPage,
            pageSize,
            filterByRole.value,
            filterBySex.value, 
            filterByStatus.value, 
            filterByEmyploymentType.value,
            filterByDeletedData.checked
        );
    }, 300);
});

function handleFiltersChange() {
    pageSize = parseInt(paginationSizeSelect.value);
    fetchTeachers( searchInput.value, 
        currentPage,
        pageSize,
        filterByRole.value, 
        filterBySex.value, 
        filterByStatus.value, 
        filterByEmyploymentType.value,
        filterByDeletedData.checked
    );
}

filterByRole.addEventListener('change', handleFiltersChange);
filterBySex.addEventListener('change', handleFiltersChange);
filterByStatus.addEventListener('change', handleFiltersChange);
filterByEmyploymentType.addEventListener('change', handleFiltersChange);
filterByDeletedData.addEventListener('change', handleFiltersChange);

fetchTeachers(search = '', page = 1, pageSize = 10, role = '', sex = '', employment_status = '', employment_type = '', is_deleted = 0); // Initial fetch

function viewAction(teacherData) {
    var teacherData = JSON.parse(teacherData.getAttribute('data-teacherData'));

    Swal.fire({
        html: `
            <h1>View Teacher Info</h1>
            <hr>
            <section class="view-group-col">
                <h3>Personal Information</h3>
                <strong>First name: <span>${teacherData.first_name}</span></strong>
                <strong>Middle name: <span>${teacherData.middle_name || ''}</span></strong>
                <strong>Last name: <span>${teacherData.last_name}</span></strong>
                <strong>Sex: <span>${teacherData.sex}</span></strong>
                <strong>Date Of Birth: <span>${formatDate(teacherData.date_of_birth)}</span></strong>
                <strong>Civil Status: <span>${teacherData.civil_status}</span></strong>
                <strong>Nationality: <span>${teacherData.nationality}</span></strong>
            </section>
            <hr>
            <section class="view-group-col">
                <h3>Address Information</h3>
                <strong>Province: <span>${teacherData.province.province_name}</span></strong>
                <strong>Municipality: <span>${teacherData.municipality.municipality_name}</span></strong>
                <strong>Barangay: <span>${teacherData.barangay.barangay_name}</span></strong>
                <strong>Street Address: <span>${teacherData.street_address}</span></strong>
            </section>
            <hr>
            <section class="view-group-col">
                <h3>Employment Information</h3> 
                <strong>Account Role: <span>${teacherData.designation}</span></strong>
                <ul class="list">
                    <strong>Subjects Handle:</strong>
                    ${teacherData.subjects.map(subject => `<li><strong>${subject.subject_code}</strong> - ${subject.subject_title}</li>`).join('')}
                </ul>
                <strong>Employment Type: <span>${teacherData.employment_type}</span></strong>
                <strong>Date Hired: <span>${formatDate(teacherData.date_hired)}</span></strong>
                <strong>Emyploment Status: <span>${teacherData.employment_status}</span></strong>
            </section>
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

function deleteAction(teacher_id, destroy = false) {

    var teacher_id = JSON.parse(teacher_id.getAttribute('data-teacher_id'));

    const DELETE_ROUTE = destroy ? route('admin.destroy.teacher') : route('admin.softdelete.teacher');
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
            fetch(`${DELETE_ROUTE}?teacher_id=${teacher_id}`, {
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
                    fetchTeachers(search = '', page = 1, pageSize = 10, role = '', sex = '', employment_status = '', employment_type = '', is_deleted = 0);
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
    const BULK_DELETE_ROUTE = route('admin.softbulkdelete.teacher');
    const selectedIds = Array.from(document.querySelectorAll('.checkData:checked')).map(checkbox => checkbox.dataset.teacher_id);

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
                body: JSON.stringify({ teacher_ids: selectedIds })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('info', data.message);
                    fetchTeachers(search = '', page = 1, pageSize = 10, role = '', sex = '', employment_status = '', employment_type = '', is_deleted = 0);
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
    const BULK_RESTORE_ROUTE = route('admin.bulkrestore.teacher');
    const selectedIds = Array.from(document.querySelectorAll('.checkData:checked')).map(checkbox => checkbox.dataset.teacher_id);

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
                body: JSON.stringify({ teacher_ids: selectedIds })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('info', data.message);
                    fetchTeachers(search = '', page = 1, pageSize = 10, role = '', sex = '', employment_status = '', employment_type = '', is_deleted = 0);
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

function restoreAction(teacher_id) {

    var teacher_id = JSON.parse(teacher_id.getAttribute('data-teacher_id'));

    const RESTORE_ROUTE = route('admin.restore.teacher');

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
            fetch(`${RESTORE_ROUTE}?teacher_id=${teacher_id}`, {
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
                    fetchTeachers(search = '', page = 1, pageSize = 10, role = '', sex = '', employment_status = '', employment_type = '', is_deleted = 0);
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


document.getElementById('printButton').addEventListener('click', function() {
    var table = document.querySelector('.table-wrapper');

    var contentToPrint = table.outerHTML;

    var printFrame = document.createElement('iframe');
    printFrame.style.display = 'none';
    document.body.appendChild(printFrame);
    var iframeDoc = printFrame.contentWindow.document;
    
    iframeDoc.open();
    iframeDoc.write('<html><head><title>Print</title>');
    iframeDoc.write('<link rel="stylesheet" type="text/css" href="' + CSS_ROUTE + '">');
    iframeDoc.write('</head><body>');
    iframeDoc.write(`<style>
        img {
            width: 120px;
            height: 120px;
        }
        </style>`);
    iframeDoc.write(contentToPrint);
    iframeDoc.write('</body></html>');
    iframeDoc.close();

    setTimeout(function() {
        iframeDoc.defaultView.print();
        document.body.removeChild(printFrame);
    }, 500);
});