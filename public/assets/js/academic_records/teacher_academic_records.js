let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

const tableData = document.querySelector('.tableData');
const searchInput = document.getElementById('search');
const paginationContainer = document.querySelector('.paginations');
const paginationInfo = document.querySelector('.pagination-info');
const FETCH_ACADEMIC_RECORDS = route('teacher.academic.record.fetch');
const paginationSizeSelect = document.getElementById('paginationSize-select');


let currentPage = 1;
let pageSize = parseInt(paginationSizeSelect.value);
let debounceTimer;


async function fetchAcademicRecords(class_record, search = '', page = 1, pageSize) {
    try {
        const response = await fetch(`${FETCH_ACADEMIC_RECORDS}?class_record=${class_record}&search=${search}&page=${page}
            &pageSize=${pageSize}`);
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
    fetchAcademicRecords(CLASS_RECORD,
        searchInput.value, 
        currentPage,
        pageSize,
    );
});

function populateTable(academicRecords) {
    const tableData = document.querySelector('.tableData');
    tableData.innerHTML = '';

    if (academicRecords.length === 0) {
        tableData.innerHTML = '<tr><td colspan="8" style="text-align:center">No results found</td></tr>';
        return;
    }

    const groupedRecords = {};

    academicRecords.forEach(record => {
        record.student_records.forEach(studentRecord => {
            const key = `${studentRecord.student_id}-${record.class_management_id}-${record.semester}-${record.school_year}`;

            if (!groupedRecords[key]) {
                groupedRecords[key] = {
                    student: `${studentRecord.student.last_name}, ${studentRecord.student.first_name} ${studentRecord.student.middle_name || ''}`,
                    midtermGrade: '-',
                    finalGrade: '-'
                };
            }

            if (studentRecord.exam_type === 'Midterm') {
                groupedRecords[key].midtermGrade = studentRecord.grade;
            } else if (studentRecord.exam_type === 'Final') {
                groupedRecords[key].finalGrade = studentRecord.grade;
            }
        });
    });

    const sortedRecords = Object.values(groupedRecords).map(student => {
        const averageGrade = Math.round(((parseFloat(student.midtermGrade) || 0) + (parseFloat(student.finalGrade) || 0)) / 2);
        const remarks = averageGrade >= 75 ? 'Passed' : 'Failed';

        return {
            ...student,
            averageGrade,
            remarks
        };
    }).sort((a, b) => b.averageGrade - a.averageGrade);

    let rowIndex = 1;
    sortedRecords.forEach(student => {
        const row = `
            <tr>
                <td>${rowIndex++}</td>
                <td>${student.student}</td>
                <td>${student.midtermGrade}</td>
                <td>${student.finalGrade}</td>
                <td>${student.averageGrade}</td>
                <td>
                    <div class="${student.remarks}">
                        <p>${student.remarks}</p>
                    </div>
                </td>
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
            fetchAcademicRecords(CLASS_RECORD,
                searchInput.value, 
                currentPage,
                pageSize,
            );
        });
    }

    if (nextButton) {
        nextButton.addEventListener('click', () => {
            currentPage++;
            fetchAcademicRecords(CLASS_RECORD,
                searchInput.value, 
                currentPage,
                pageSize,
            );
        });
    }

    pageButtons.forEach(button => {
        button.addEventListener('click', () => {
            currentPage = parseInt(button.textContent);
            fetchAcademicRecords(CLASS_RECORD, 
                searchInput.value, 
                currentPage,
                pageSize,
            );
        });
    });
}

searchInput.addEventListener('input', () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        const searchValue = searchInput.value;
        pageSize = parseInt(paginationSizeSelect.value);
        fetchAcademicRecords(CLASS_RECORD, 
            searchValue, 
            currentPage,
            pageSize,
        );
    }, 300);
});

fetchAcademicRecords(CLASS_RECORD, search = '', page = 1, pageSize = 10); // Initial fetch


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