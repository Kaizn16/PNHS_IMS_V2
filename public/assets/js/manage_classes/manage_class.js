let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const userRoleMeta = document.querySelector('meta[name="user-role"]');
const authUserRole = userRoleMeta ? userRoleMeta.getAttribute('content') : null;

const tableData = document.querySelector('.tableData');
const searchInput = document.getElementById('search');
const paginationContainer = document.querySelector('.paginations');
const paginationInfo = document.querySelector('.pagination-info');
const FETCH_STUDENTS = route(`${authUserRole}.classes.fetch`);
const paginationSizeSelect = document.getElementById('paginationSize-select');

const filterByYearLevel = document.getElementById('filterByYearLevel');
const filterBySection = document.getElementById('filterBySection');
const filterBySemester = document.getElementById('filterBySemester');
const filterBySchoolYear = document.getElementById('filterBySchoolYear');
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


async function fetchClasses(search = '', page = 1, pageSize = 10, year_level = '', section = '', semester  = '', school_year = '', is_deleted = false) {
    try {
        const response = await fetch(`${FETCH_STUDENTS}?search=${search}&page=${page}&pageSize=${pageSize}
            &year_level=${year_level}&section=${section}&semester=${semester}&school_year=${school_year}&is_deleted=${is_deleted}`);
        const data = await response.json();
        populateTable(data.data);
        updatePagination(data);
        updatePaginationInfo(data);
    } catch (error) {
        console.error('Error fetching classes:', error);
    }
}

paginationSizeSelect.addEventListener('change', () => {
    pageSize = parseInt(paginationSizeSelect.value);
    currentPage = 1;
    fetchClasses(searchInput.value, 
        currentPage,
        pageSize,
        filterByYearLevel.value,
        filterBySection.value,
        filterBySemester.value,
        filterBySchoolYear.value,
        filterByDeletedData.checked
    );
});

function populateTable(manageClasses) {
    tableData.innerHTML = '';

    if (manageClasses.length === 0) {
        tableData.innerHTML = '<tr><td colspan="12" style="text-align:center">No results found</td></tr>';
        return;
    }

    manageClasses.forEach((manageClass, index) => {

        let showAction = manageClass.is_deleted == 1 ? 
        `
            <span data-class_management_id='${manageClass.class_management_id}' onclick="event.stopPropagation(); restoreAction(this);" class="restore" title="RESTORE"><i class="material-icons">restore</i></span>
            <span data-class_management_id='${manageClass.class_management_id}' onclick="event.stopPropagation(); deleteAction(this, true);" class="delete" title="PERMANENT DELETE"><i class="material-icons">delete</i></span>
        ` : 
        `
            <a href="${route(`${authUserRole}.edit.class`, { class_management_id: manageClass.class_management_id })}" class="edit" title="EDIT"><i class="material-icons">edit</i></a>
            <span data-class_management_id='${manageClass.class_management_id}' onclick="event.stopPropagation(); deleteAction(this);" class="delete" title="DELETE"><i class="material-icons">delete</i></span>
        `;

        const row = `
            <tr>
                <td class="checkboxTable">
                    <div class="checkbox-wrapper">
                        <input class="checkData checkbox" type="checkbox" data-class_management_id="${manageClass.class_management_id}" data-deleted="${manageClass.is_deleted == 1}" />
                    </div>
                </td>
                <td>${index + 1}</td>
                <td>${manageClass.class_name}</td>
                <td>${manageClass.subject.subject_code}</td>
                <td>${manageClass.room.room_name}</td>
                <td>${manageClass.teacher.first_name} ${manageClass.teacher.middle_name ? `${manageClass.teacher.middle_name[0]}.` : ''} ${manageClass.teacher.last_name}</td>
                <td>${manageClass.schedules.map(schedule => `${schedule.day.substring(0, 3)} ${formatTime(schedule.time_start)} - ${formatTime(schedule.time_end)}`).join(', ')}</td>
                <td>${manageClass.year_level}</td>
                <td>${manageClass.section}</td>
                <td>${manageClass.semester}</td>
                <td>${manageClass.school_year}</td>
                <td>
                    <div class="actions">
                        <a href="${route(`${authUserRole}.view.class`, { class_management_id: (manageClass.class_management_id) }) }" class="view" title="VIEW">
                            <i class="material-icons">visibility</i>
                        </a>
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
            fetchClasses(searchInput.value, 
                currentPage,
                pageSize,
                filterByYearLevel.value,
                filterBySection.value,
                filterBySemester.value,
                filterBySchoolYear.value,
                filterByDeletedData.checked
            );
        });
    }

    if (nextButton) {
        nextButton.addEventListener('click', () => {
            currentPage++;
            fetchClasses(searchInput.value, 
                currentPage,
                pageSize,
                filterByYearLevel.value,
                filterBySection.value,
                filterBySemester.value,
                filterBySchoolYear.value,
                filterByDeletedData.checked
            );
        });
    }

    pageButtons.forEach(button => {
        button.addEventListener('click', () => {
            currentPage = parseInt(button.textContent);
            fetchClasses(searchInput.value, 
                currentPage,
                pageSize,
                filterByYearLevel.value,
                filterBySection.value,
                filterBySemester.value,
                filterBySchoolYear.value,
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
        fetchClasses(searchInput.value, 
            currentPage,
            pageSize,
            filterByYearLevel.value,
            filterBySection.value,
            filterBySemester.value,
            filterBySchoolYear.value,
            filterByDeletedData.checked
        );
    }, 300);
});

function handleFiltersChange() {
    pageSize = parseInt(paginationSizeSelect.value);
    fetchClasses(searchInput.value, 
        currentPage,
        pageSize,
        filterByYearLevel.value,
        filterBySection.value,
        filterBySemester.value,
        filterBySchoolYear.value,
        filterByDeletedData.checked
    );
}

filterByYearLevel.addEventListener('change', handleFiltersChange);
filterBySection.addEventListener('change', handleFiltersChange);
filterBySemester.addEventListener('change', handleFiltersChange);
filterBySchoolYear.addEventListener('change', handleFiltersChange);
filterByDeletedData.addEventListener('change', handleFiltersChange);

fetchClasses(search = '', page = 1, pageSize = 10, year_level = '', section = '', semester = '', school_year = '', is_deleted = 0); // Initial fetch

function deleteAction(class_management_id, destroy = false) {

    var class_management_id = JSON.parse(class_management_id.getAttribute('data-class_management_id'));

    const DELETE_ROUTE = destroy ? route(`${authUserRole}.destroy.class`) : route(`${authUserRole}.softdelete.class`);
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
            fetch(`${DELETE_ROUTE}?class_management_id=${class_management_id}`, {
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
                    fetchClasses(search = '', page = 1, pageSize = 10, year_level = '', section = '', semester = '', school_year = '', is_deleted = 0);
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
    const BULK_DELETE_ROUTE = route(`${authUserRole}.softbulkdelete.class`);
    const selectedIds = Array.from(document.querySelectorAll('.checkData:checked')).map(checkbox => checkbox.dataset.class_management_id);

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
                body: JSON.stringify({ class_management_ids: selectedIds })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('info', data.message);
                    fetchClasses(search = '', page = 1, pageSize = 10, year_level = '', section = '', semester = '', school_year = '', is_deleted = 0);
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
    const BULK_RESTORE_ROUTE = route(`${authUserRole}.bulkrestore.class`);
    const selectedIds = Array.from(document.querySelectorAll('.checkData:checked')).map(checkbox => checkbox.dataset.class_management_id);

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
                body: JSON.stringify({ class_management_ids: selectedIds })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('info', data.message);
                    fetchClasses(search = '', page = 1, pageSize = 10, year_level = '', section = '', semester = '', school_year = '', is_deleted = 0);
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

function restoreAction(class_management_id) {

    var class_management_id = JSON.parse(class_management_id.getAttribute('data-class_management_id'));

    const RESTORE_ROUTE = route(`${authUserRole}.restore.class`);

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
            fetch(`${RESTORE_ROUTE}?class_management_id=${class_management_id}`, {
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
                    fetchClasses(search = '', page = 1, pageSize = 10, year_level = '', section = '', semester = '', school_year = '', is_deleted = 0);
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

const formatTime = (time) => {
    const [hour, minute] = time.split(':');
    const h = parseInt(hour, 10);
    const period = h >= 12 ? 'PM' : 'AM';
    const formattedHour = h % 12 === 0 ? 12 : h % 12;
    return `${formattedHour}:${minute} ${period}`;
  };