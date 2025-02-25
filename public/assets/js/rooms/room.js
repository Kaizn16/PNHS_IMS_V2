let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

const tableData = document.querySelector('.tableData');
const searchInput = document.getElementById('search');
const paginationContainer = document.querySelector('.paginations');
const paginationInfo = document.querySelector('.pagination-info');
const FETCH_ROOMS = route('admin.rooms.fetch');
const paginationSizeSelect = document.getElementById('paginationSize-select');


let currentPage = 1;
let pageSize = parseInt(paginationSizeSelect.value);
let debounceTimer;

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


async function fetchRooms(search = '', page = 1, pageSize, is_deleted) {
    try {
        const response = await fetch(`${FETCH_ROOMS}?search=${search}&page=${page}&pageSize=${pageSize}&is_deleted=${is_deleted}`);
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
    fetchRooms( 
        searchInput.value, 
        currentPage,
        pageSize, 
        filterByDeletedData.checked
    );
});

function populateTable(rooms) {
    tableData.innerHTML = '';

    if (rooms.length === 0) {
        tableData.innerHTML = '<tr><td colspan="6" style="text-align:center">No results found</td></tr>';
        return;
    }

    rooms.forEach((room, index) => {

        let showAction = room.is_deleted == 1 ? 
        `
            <span data-room_id='${room.room_id}' onclick="event.stopPropagation(); restoreAction(this);" class="restore" title="RESTORE"><i class="material-icons">restore</i></span>
            <span data-room_id='${room.room_id}' onclick="event.stopPropagation(); deleteAction(this, true);" class="delete" title="PERMANENT DELETE"><i class="material-icons">delete</i></span>
        ` : 
        `
            <a href="${route('admin.edit.room', { room_id: room.room_id })}" class="edit" title="EDIT"><i class="material-icons">edit</i></a>
            <span data-room_id='${room.room_id}' onclick="event.stopPropagation(); deleteAction(this);" class="delete" title="DELETE"><i class="material-icons">delete</i></span>
        `;

        const row = `
            <tr>
                <td class="checkboxTable">
                    <div class="checkbox-wrapper">
                        <input class="checkData checkbox" type="checkbox" data-room_id="${room.room_id}" data-deleted="${room.is_deleted == 1}" />
                    </div>
                </td>
                <td>${index + 1}</td>
                <td>${room.building_name}</td>
                <td>${room.room_name}</td>
                <td>${room.max_seat}</td>
                <td>
                    <div class="actions">
                        <span data-roomData='${JSON.stringify(room)}' onclick="event.stopPropagation(); viewAction(this);" class="view" title="VIEW"><i class="material-icons">visibility</i></span>
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
            fetchRooms( 
                searchInput.value, 
                currentPage,
                pageSize,
                filterByDeletedData.checked
            );
        });
    }

    if (nextButton) {
        nextButton.addEventListener('click', () => {
            currentPage++;
            fetchRooms( 
                searchInput.value, 
                currentPage,
                pageSize,
                filterByDeletedData.checked
            );
        });
    }

    pageButtons.forEach(button => {
        button.addEventListener('click', () => {
            currentPage = parseInt(button.textContent);
            fetchRooms( 
                searchInput.value, 
                currentPage,
                pageSize,
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
        fetchRooms(searchValue, 
            currentPage,
            pageSize,
            filterByDeletedData.checked
        );
    }, 300);
});

function handleFiltersChange() {
    pageSize = parseInt(paginationSizeSelect.value);
    fetchRooms( searchInput.value, 
        currentPage,
        pageSize,
        filterByDeletedData.checked
    );
}

filterByDeletedData.addEventListener('change', handleFiltersChange);

fetchRooms(search = '', page = 1, pageSize = 10, is_deleted = 0); // Initial fetch

function viewAction(roomData) {
    var roomData = JSON.parse(roomData.getAttribute('data-roomData'));

    Swal.fire({
        html: `
            <h1>View Room Info</h1>
            <hr>
            <section class="view-group-col">
                <h3>Room Information</h3>
                <strong>Building Name: <span>${roomData.building_name}</span></strong>
                <strong>Room Name: <span>${roomData.room_name}</span></strong>
                <strong>Max Seat: <span>${roomData.max_seat}</span></strong>
                <strong>Building Description: <span>${roomData.building_description || 'N/A'}</span></strong>
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

function deleteAction(room_id, destroy = false) {

    var room_id = JSON.parse(room_id.getAttribute('data-room_id'));

    const DELETE_ROUTE = destroy ? route('admin.destroy.room') : route('admin.softdelete.room');
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
            fetch(`${DELETE_ROUTE}?room_id=${room_id}`, {
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
                    fetchRooms(search = '', page = 1, pageSize = 10, is_deleted = 0);
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
    const BULK_DELETE_ROUTE = route('admin.softbulkdelete.room');
    const selectedIds = Array.from(document.querySelectorAll('.checkData:checked')).map(checkbox => checkbox.dataset.room_id);

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
                body: JSON.stringify({ room_ids: selectedIds })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('info', data.message);
                    fetchRooms(search = '', page = 1, pageSize = 10, is_deleted = 0);
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
    const BULK_RESTORE_ROUTE = route('admin.bulkrestore.room');
    const selectedIds = Array.from(document.querySelectorAll('.checkData:checked')).map(checkbox => checkbox.dataset.room_id);

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
                body: JSON.stringify({ room_ids: selectedIds })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('info', data.message);
                    fetchRooms(search = '', page = 1, pageSize = 10, is_deleted = 0);
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

function restoreAction(room_id) {

    var room_id = JSON.parse(room_id.getAttribute('data-room_id'));

    const RESTORE_ROUTE = route('admin.restore.room');

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
            fetch(`${RESTORE_ROUTE}?room_id=${room_id}`, {
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
                    fetchRooms(search = '', page = 1, pageSize = 10, is_deleted = 0);
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