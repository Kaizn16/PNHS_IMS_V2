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

let currentPage = 1;
let pageSize = parseInt(paginationSizeSelect.value);
let debounceTimer;


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
        filterBySchoolYear.value
    );
});

function populateTable(manageClasses) {
    tableData.innerHTML = '';

    if (manageClasses.length === 0) {
        tableData.innerHTML = '<tr><td colspan="12" style="text-align:center">No results found</td></tr>';
        return;
    }

    manageClasses.forEach((manageClass, index) => {

        const row = `
            <tr>
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
            </tr>
        `;
        tableData.innerHTML += row;
    });
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
                filterBySchoolYear.value
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
                filterBySchoolYear.value
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
                filterBySchoolYear.value
            );
        });
    });
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
            filterBySchoolYear.value
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
        filterBySchoolYear.value
    );
}

filterByYearLevel.addEventListener('change', handleFiltersChange);
filterBySection.addEventListener('change', handleFiltersChange);
filterBySemester.addEventListener('change', handleFiltersChange);
filterBySchoolYear.addEventListener('change', handleFiltersChange);

fetchClasses(search = '', page = 1, pageSize = 10, year_level = '', section = '', semester = '', school_year = ''); // Initial fetch

const formatTime = (time) => {
    const [hour, minute] = time.split(':');
    const h = parseInt(hour, 10);
    const period = h >= 12 ? 'PM' : 'AM';
    const formattedHour = h % 12 === 0 ? 12 : h % 12;
    return `${formattedHour}:${minute} ${period}`;
};