@extends('layouts.app')
@section('content')
    <section class="wrapper">
        <header class="header">
            <h2 class="title">Academic Records</h2>
        </header>

        <div class="content-box">
            @foreach ($academicRecords as $schoolYear => $semesters)
                <div class="table-container">
                    <header class="table-header">
                        <h2 class="title">School Year: {{ $schoolYear }}</h2>
                        <button class="print" id="printButton" title="PRINT"><i class="material-icons">print</i></button>
                    </header>

                    @foreach ($semesters as $semester => $subjects)
                        <div class="semester-header">
                            <h3>Semester: {{ $semester }}</h3>
                        </div>
                        <div class="table-wrapper">
                            <table class="table">
                                <thead>
                                    <tr class="heading">
                                        <th>Subject</th>
                                        <th>Teacher</th>
                                        <th>Year Level</th>
                                        <th>Midterm</th>
                                        <th>Final</th>
                                        <th>Final Grade</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($subjects as $subject)
                                        <tr>
                                            <td>{{ $subject['subject']->subject_code ?? 'N/A' }}</td>
                                            <td>
                                                {{ strtoupper($subject['teacher']->last_name ?? 'N/A') }},
                                                {{ strtoupper($subject['teacher']->first_name ?? '') }}
                                                {{ isset($subject['teacher']->middle_name) 
                                                    ? strtoupper(substr($subject['teacher']->middle_name, 0, 1)) . '.' 
                                                    : '' }}
                                            </td>                                                
                                            <td>{{ $subject['year_level'] ?? 'N/A' }}</td>
                                            @php
                                                $midterm = optional($subject['records']->firstWhere('exam_type', 'Midterm'))->grade ?? 0;
                                                $final = optional($subject['records']->firstWhere('exam_type', 'Final'))->grade ?? 0;
                                                $finalGrade = ($midterm + $final) / 2;
                                            @endphp
                                            <td>{{ $midterm ?: 'N/A' }}</td>
                                            <td>{{ $final ?: 'N/A' }}</td>
                                            <td>{{ number_format($finalGrade, 2) }}</td>
                                            <td>
                                                <div class="{{ $finalGrade >= 75 ? 'Passed' : 'Failed' }}">
                                                    <p>{{ $finalGrade >= 75 ? 'Passed' : 'Failed' }}</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </section>
@endsection
@section('script')
<script>
    CSS_ROUTE = `{{ asset('assets/css/main.css') }}`;
    SCHOOL_LOGO = `{{ asset('assets/images/Logo.jpg') }}`;

    document.getElementById('printButton').addEventListener('click', function() {
        var table = document.querySelector('.content-box');

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
            .student-information {
                display: flex;
                flex-direction: column;
                gap: 8px;
                padding-left: 2rem;
            }
            strong {
                color: white;
            }
            .print {
                display: none;
            }
            </style>`);
        iframeDoc.write(`
            <div>
                <div class="logo-container">
                    <img src="${SCHOOL_LOGO}" alt="Logo">
                    <strong>Pontevedra National High School</strong>
                </div>
                <div class="student-information">
                    <strong>Student Name: {{ $student->first_name }} {{ $student->middle_name ?? '' }} {{ $student->last_name }}</strong>
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
</script>
@endsection