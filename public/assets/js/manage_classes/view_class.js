let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const userRoleMeta = document.querySelector('meta[name="user-role"]');
const authUserRole = userRoleMeta ? userRoleMeta.getAttribute('content') : null;

const tableData = document.querySelector('.tableData');
const searchInput = document.getElementById('search');
const paginationContainer = document.querySelector('.paginations');
const paginationInfo = document.querySelector('.pagination-info');
const FETCH_STUDENTS = route('get.students.class');
const paginationSizeSelect = document.getElementById('paginationSize-select');

const classManagementId = 5;
const filterBySex = document.getElementById('filterBySex');

let currentPage = 1;
let pageSize = parseInt(paginationSizeSelect.value);
let debounceTimer;


async function fetchStudentsByClass(search = '', page = 1, pageSize = 10, class_management_id,  sex = '') {
    try {
        const response = await fetch(`${FETCH_STUDENTS}?search=${search}&page=${page}&pageSize=${pageSize}&class_management_id=${class_management_id}&sex=${sex}`);
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
    fetchStudentsByClass(searchInput.value, 
        currentPage,
        pageSize,
        classManagementId,
        filterBySex.value,
    );
});

function populateTable(manageClasses) {
    tableData.innerHTML = '';

    if (manageClasses.length === 0) {
        tableData.innerHTML = '<tr><td colspan="3" style="text-align:center">No results found</td></tr>';
        return;
    }

    manageClasses.forEach((manageClass, index) => {

        const row = `
            <tr>
                <td>${index + 1}</td>
                <td>${manageClass.student.last_name}, ${manageClass.student.first_name} ${manageClass.student.middle_name || ''}</td>
                <td>${manageClass.student.sex}</td>
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
            fetchStudentsByClass(searchInput.value, 
                currentPage,
                pageSize,
                classManagementId,
                filterBySex.value,
            );
        });
    }

    if (nextButton) {
        nextButton.addEventListener('click', () => {
            currentPage++;
            fetchStudentsByClass(searchInput.value, 
                currentPage,
                pageSize,
                classManagementId,
                filterBySex.value,
            );
        });
    }

    pageButtons.forEach(button => {
        button.addEventListener('click', () => {
            currentPage = parseInt(button.textContent);
            fetchStudentsByClass(searchInput.value, 
                currentPage,
                pageSize,
                classManagementId,
                filterBySex.value,
            );
        });
    });
}


searchInput.addEventListener('input', () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        const searchValue = searchInput.value;
        pageSize = parseInt(paginationSizeSelect.value);
        fetchStudentsByClass(searchValue, 
            currentPage,
            pageSize,
            classManagementId,
            filterBySex.value,
        );
    }, 300);
});

function handleFiltersChange() {
    pageSize = parseInt(paginationSizeSelect.value);
    fetchStudentsByClass(searchInput.value, 
        currentPage,
        pageSize,
        classManagementId,
        filterBySex.value,
    );
}

filterBySex.addEventListener('change', handleFiltersChange);

fetchStudentsByClass(search = '', page = 1, pageSize = 10, classManagementId, sex = ''); // Initial fetch

document.getElementById('printButton').addEventListener('click', function() {
    var tableInformation = document.querySelector('.table-information');
    var table = document.querySelector('.table');

    var contentToPrint = tableInformation.outerHTML + table.outerHTML;

    var printFrame = document.createElement('iframe');
    printFrame.style.display = 'none';
    document.body.appendChild(printFrame);
    var iframeDoc = printFrame.contentWindow.document;
    
    iframeDoc.open();
    iframeDoc.write('<html><head><title>Print</title>');
    iframeDoc.write('<link rel="stylesheet" type="text/css" href="' + CSS_ROUTE + '">');
    iframeDoc.write('</head><body>');
    iframeDoc.write(`<style>
        .logo-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 100%;
            gap: 6px;
            margin-bottom: 12px;
            padding: 12px;
        }
        img {
            clip-path: circle();
            width: 120px;
            height: 120px;
        }
        strong {
            color: white;
        }
        .table-information {
            padding: 12px;
        }
        </style>`);
    iframeDoc.write(`
        <div>
            <div class="logo-container">
                <img src="${SCHOOL_LOGO}" alt="Logo">
                <strong>Pontevedra National High School</strong>
            </div>
            ${contentToPrint}
        </div>`);
    iframeDoc.write('</body></html>');
    iframeDoc.close();

    setTimeout(function() {
        iframeDoc.defaultView.print();
        document.body.removeChild(printFrame);
    }, 500);
});