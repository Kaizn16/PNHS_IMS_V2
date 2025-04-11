let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

const tableData = document.querySelector('.tableData');
const searchInput = document.getElementById('search');
const paginationContainer = document.querySelector('.paginations');
const paginationInfo = document.querySelector('.pagination-info');
const FETCH_SUBJECTS = route('admin.subjects.fetch');
const paginationSizeSelect = document.getElementById('paginationSize-select');


let currentPage = 1;
let pageSize = parseInt(paginationSizeSelect.value);
let debounceTimer;

const filterByStrand = document.getElementById('filterByStrand');
const filterByDeletedData = document.getElementById('deletedData');

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


async function fetchSubjects(search = '', page = 1, pageSize, strand = '', is_deleted) {
    try {
        const response = await fetch(`${FETCH_SUBJECTS}?search=${search}&page=${page}&pageSize=${pageSize}&strand=${strand}&is_deleted=${is_deleted}`);
        const data = await response.json();
        populateTable(data.data);
        updatePagination(data);
        updatePaginationInfo(data);
    } catch (error) {
        console.error('Error fetching rooms:', error);
    }
}

paginationSizeSelect.addEventListener('change', () => {
    pageSize = parseInt(paginationSizeSelect.value);
    currentPage = 1;
    fetchSubjects( 
        searchInput.value, 
        currentPage,
        pageSize,
        filterByStrand.value, 
        filterByDeletedData.checked
    );
});

function populateTable(subjects) {
    tableData.innerHTML = '';

    if (subjects.length === 0) {
        tableData.innerHTML = '<tr><td colspan="7" style="text-align:center">No results found</td></tr>';
        return;
    }

    subjects.forEach((subject, index) => {

        let showAction = subject.is_deleted == 1 ? 
        `
            <span data-subject_id='${subject.subject_id}' onclick="event.stopPropagation(); restoreAction(this);" class="restore" title="RESTORE"><i class="material-icons">restore</i></span>
            <span data-subject_id='${subject.subject_id}' onclick="event.stopPropagation(); deleteAction(this, true);" class="delete" title="PERMANENT DELETE"><i class="material-icons">delete</i></span>
        ` : 
        `
            <a href="${route('admin.edit.subject', { subject_id: subject.subject_id })}" class="edit" title="EDIT"><i class="material-icons">edit</i></a>
            <span data-subject_id='${subject.subject_id}' onclick="event.stopPropagation(); deleteAction(this);" class="delete" title="DELETE"><i class="material-icons">delete</i></span>
        `;

        const row = `
            <tr>
                <td class="checkboxTable">
                    <div class="checkbox-wrapper">
                        <input class="checkData checkbox" type="checkbox" data-subject_id="${subject.subject_id}" data-deleted="${subject.is_deleted == 1}" />
                    </div>
                </td>
                <td>${index + 1}</td>
                <td>${subject.strand ? subject.strand.strand_name : 'N/A'}</td>
                <td>${subject.subject_code}</td>
                <td>${subject.subject_title}</td>
                <td>${subject.subject_description || 'N/A'}</td>
                <td>
                    <div class="actions">
                        <span data-subjectData='${JSON.stringify(subject)}' onclick="event.stopPropagation(); viewAction(this);" class="view" title="VIEW"><i class="material-icons">visibility</i></span>
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
            fetchSubjects( 
                searchInput.value, 
                currentPage,
                pageSize,
                filterByStrand.value,
                filterByDeletedData.checked
            );
        });
    }

    if (nextButton) {
        nextButton.addEventListener('click', () => {
            currentPage++;
            fetchSubjects( 
                searchInput.value, 
                currentPage,
                pageSize,
                filterByStrand.value,
                filterByDeletedData.checked
            );
        });
    }

    pageButtons.forEach(button => {
        button.addEventListener('click', () => {
            currentPage = parseInt(button.textContent);
            fetchSubjects( 
                searchInput.value, 
                currentPage,
                pageSize,
                filterByStrand.value,
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
        fetchSubjects(searchValue, 
            currentPage,
            pageSize,
            filterByStrand.value,
            filterByDeletedData.checked
        );
    }, 300);
});

function handleFiltersChange() {
    pageSize = parseInt(paginationSizeSelect.value);
    fetchSubjects(searchInput.value, 
        currentPage,
        pageSize,
        filterByStrand.value,
        filterByDeletedData.checked
    );
}

filterByStrand.addEventListener('change', handleFiltersChange);
filterByDeletedData.addEventListener('change', handleFiltersChange);

fetchSubjects(search = '', page = 1, pageSize = 10, strand = '', is_deleted = 0); // Initial fetch

function viewAction(subjectData) {
    var subjectData = JSON.parse(subjectData.getAttribute('data-subjectData'));

    Swal.fire({
        html: `
            <h1>View Subject Info</h1>
            <hr>
            <section class="view-group-col">
                <h3>Subject Information</h3>
                <strong>Strand: <span>${subjectData.strand ? subjectData.strand.strand_name : 'N/A'}</span></strong>
                <strong>Subject: <span>${subjectData.subject_code}</span></strong>
                <strong>Titke: <span>${subjectData.subject_title}</span></strong>
                <strong>Description: <span>${subjectData.subject_description || 'N/A'}</span></strong>
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

function deleteAction(subject_id, destroy = false) {

    var subject_id = JSON.parse(subject_id.getAttribute('data-subject_id'));

    const DELETE_ROUTE = destroy ? route('admin.destroy.subject') : route('admin.softdelete.subject');
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
            fetch(`${DELETE_ROUTE}?subject_id=${subject_id}`, {
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
                    fetchSubjects(search = '', page = 1, pageSize = 10, strand = '', is_deleted = 0);
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
    const BULK_DELETE_ROUTE = route('admin.softbulkdelete.subject');
    const selectedIds = Array.from(document.querySelectorAll('.checkData:checked')).map(checkbox => checkbox.dataset.subject_id);

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
                body: JSON.stringify({ subject_ids: selectedIds })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('info', data.message);
                    fetchSubjects(search = '', page = 1, pageSize = 10, strand = '', is_deleted = 0);
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
    const BULK_RESTORE_ROUTE = route('admin.bulkrestore.subject');
    const selectedIds = Array.from(document.querySelectorAll('.checkData:checked')).map(checkbox => checkbox.dataset.subject_id);

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
                body: JSON.stringify({ subject_ids: selectedIds })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('info', data.message);
                    fetchSubjects(search = '', page = 1, pageSize = 10, strand = '', is_deleted = 0);
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

function restoreAction(subject_id) {

    var subject_id = JSON.parse(subject_id.getAttribute('data-subject_id'));

    const RESTORE_ROUTE = route('admin.restore.subject');

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
            fetch(`${RESTORE_ROUTE}?subject_id=${subject_id}`, {
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
                    fetchSubjects(search = '', page = 1, pageSize = 10, strand = '', is_deleted = 0);
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