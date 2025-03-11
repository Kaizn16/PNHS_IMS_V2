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
                                        <th colspan="2">Midterm</th>
                                        <th colspan="2">Final</th>
                                        <th>Average Gradee</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>1st Quarter</td>
                                        <td>2nd Quarter</td>
                                        <td>3rd Quarter</td>
                                        <td>4th Quarter</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
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
                                                $first_quarter = optional($subject['records']->firstWhere(fn($record) => $record['exam_type'] === 'Midterm' && $record['quarter_type'] === '1st Quarter'))->grade ?? 0;
                                                $second_quarter = optional($subject['records']->firstWhere(fn($record) => $record['exam_type'] === 'Midterm' && $record['quarter_type'] === '2nd Quarter'))->grade ?? 0;

                                                $third_quarter = optional($subject['records']->firstWhere(fn($record) => $record['exam_type'] === 'Final' && $record['quarter_type'] === '3rd Quarter'))->grade ?? 0;
                                                $fourth_quater = optional($subject['records']->firstWhere(fn($record) => $record['exam_type'] === 'Final' && $record['quarter_type'] === '4th Quarter'))->grade ?? 0;

                                                $midterm = $first_quarter + $second_quarter;
                                                $final = $third_quarter + $fourth_quater;

                                                $finalGrade = ($midterm + $final) / 4;
                                            @endphp
                                            <td>{{ $first_quarter ?: '-' }}</td>
                                            <td>{{ $second_quarter ?: '-' }}</td>
                                            <td>{{ $third_quarter ?: '-' }}</td>
                                            <td>{{ $fourth_quater ?: '-' }}</td>
                                            <td>{{ number_format($finalGrade) }}</td>
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