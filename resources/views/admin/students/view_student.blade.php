@extends('layouts.app')

@section('content')
    <section class="wrapper">
        <header class="header">
            <h2 class="title">Manage Students</h2>
            <strong class="subtitle">View</strong>
        </header>

        <div class="content-box">

            <section class="student-information">
                <a href="{{ route('admin.students') }}" class="link-button"><i class="material-icons">arrow_back</i> BACK</a>
                <strong class="row">Name: <p>{{ $student->user->name }}</p></strong>
                <strong class="row">Grade: <p>{{ $student->current_year_level }}</p></strong>
                <strong class="row">Section: <p>{{ $student->section }}</p></strong>
            </section>

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
@if (session('message'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            showToast("{{ session('type') }}", "{{ session('message') }}");
        }); 
    </script>
@endif
@endsection