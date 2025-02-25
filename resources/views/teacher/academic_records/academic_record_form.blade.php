@extends('layouts.app')

@section('content')
    <section class="wrapper">
        <header class="header">
            <h2 class="title">{{ isset($academicRecords) ? "Update Academic Record" : "New Academic Record" }}</h2>
        </header>
        <div class="content-box">
            <div class="form-container">
                <form action="{{ isset($academicRecords) ? route('teacher.update.academic.record', ['class_management_id' => $academicRecords->first()->class_management_id]) : route('teacher.store.academic.record') }}" method="POST">
                    @csrf
                    @isset ($academicRecords)
                        @method('PUT')
                    @endisset
                    <section class="form-section">
                        <header class="header">
                            <strong>Academic Record Information</strong>
                            <span class="collapsible"><i class="material-icons icon">expand_less</i></span>
                        </header>
                        <div class="form-group-row">
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="class_name">Class<strong class="required">*</strong></label>
                                    <select name="class_name" id="class_name" class="{{ $errors->has('class_name') ? 'error' : '' }}">
                                        <option value="">-- Select Class --</option>
                                        @foreach ($myClasses as $myClass)
                                            <option value="{{ $myClass->class_management_id }}"
                                                data-subject="{{ $myClass->subject->subject_code }}"
                                                data-teacher="{{ $myClass->teacher->last_name }}, {{ $myClass->teacher->first_name }} {{ $myClass->teacher->middle_name || ''}}"
                                                data-semester="{{ $myClass->semester }}"
                                                data-school-year="{{ $myClass->school_year }}"
                                                data-students='@json($myClass->students)'
                                                {{ old('class_name', isset($academicRecords) ? $academicRecords->first()->class_management_id : '') == $myClass->class_management_id ? 'selected' : '' }}>
                                                {{ $myClass->class_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('class_name')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="subject">Subject</label>
                                    <input type="text" name="subject" id="subject" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="teacher">Teacher</label>
                                    <input type="text" name="teacher" id="teacher" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group-row">
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="semester">Semester</label>
                                    <input type="text" name="semester" id="semester" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="school_year">School Year</label>
                                    <input type="text" name="school_year" id="school_year" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="exam_type">Exam Type<strong class="required">*</strong></label>
                                    <select name="exam_type" id="exam_type" class="{{ $errors->has('exam_type') ? 'error' : '' }}">
                                        <option value="">-- Select Exam Type --</option>
                                        <option value="Midterm" {{ old('exam_type') == 'Midterm' ? 'selected' : '' }}>Midterm</option>
                                        <option value="Final" {{ old('exam_type') == 'Final' ? 'selected' : '' }}>Final</option>
                                    </select>
                                </div>
                                @error('exam_type')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </section>

                    <section class="form-section">
                        <header class="header">
                            <strong>Students</strong>
                            <span class="collapsible"><i class="material-icons icon">expand_less</i></span>
                        </header>
                        <div id="students-container" class="form-group-col"></div>
                    </section>

                    <div class="form-actions">
                        <button type="submit"><i class="material-icons icon">{{ isset($academicRecords) ? 'update' : 'add_circle_outline' }}</i>{{ isset($academicRecords) ? 'SAVE' : 'CREATE' }}</button>
                        <a href="{{ isset($academicRecords) ? route('teacher.view.academic.records', ['class_record' => $academicRecords->first()->class_management_id]) : route('teacher.academic.records') }}"><i class="material-icons icon">cancel</i>CANCEL</a>
                    </div>

                </form>
            </div>
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
<script>
    const oldGrades = @json(old('grades', []));
    const gradeErrors = @json($errors->getBag('default')->messages());
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const collapsibleHeaders = document.querySelectorAll('.form-section .header');

        collapsibleHeaders.forEach(header => {
            header.addEventListener('click', function () {
                const section = this.closest('.form-section');
                section.classList.toggle('collapsed');
                const icon = this.querySelector('.icon');
                icon.textContent = section.classList.contains('collapsed') ? 'expand_more' : 'expand_less';
            });
        });
    });

    document.getElementById('class_name').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        document.getElementById('subject').value = selectedOption.getAttribute('data-subject') || '';
        document.getElementById('teacher').value = selectedOption.getAttribute('data-teacher') || '';
        document.getElementById('semester').value = selectedOption.getAttribute('data-semester') || '';
        document.getElementById('school_year').value = selectedOption.getAttribute('data-school-year') || '';

        const students = JSON.parse(selectedOption.getAttribute('data-students') || '[]');
        const studentsContainer = document.getElementById('students-container');
        studentsContainer.innerHTML = '';

        students.forEach((student, index) => {
            const studentRow = document.createElement('div');
            studentRow.className = 'form-group-row';
            const gradeError = gradeErrors[`grades.${index}`] ? `<span class="error"><strong>${gradeErrors[`grades.${index}`][0]}</strong></span>` : '';


            studentRow.innerHTML = `
                <div class="form-group">
                    <div class="input-group">
                        <label>${index + 1}.</label>
                        <input type="hidden" name="students[]" value="${student.student.student_id}">
                        <input type="text" name="student_name[]" value="${student.student.first_name} ${student.student.middle_name || ''} ${student.student.last_name}" readonly tabindex="-1">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <label>Grade<strong class="required">*</strong></label>
                        <input 
                            type="number" 
                            name="grades[]" 
                            value="${oldGrades[index] || ''}" 
                            placeholder="Grade" 
                            step="0.01" 
                            min="0" 
                            max="100"
                            pattern="^[0-9]+(\\.[0-9]{1,2})?$" 
                            oninput="if(parseFloat(this.value) > 100) this.value = '100'; else if(!/^[0-9]*\\.?[0-9]{0,2}$/.test(this.value)) this.value = this.value.slice(0, -1);" 
                            class="${gradeErrors[index] ? 'error' : ''}">
                    </div>
                    ${gradeError}
                </div>
            `;
            studentsContainer.appendChild(studentRow);
        });
    }); 

    const studentRecords = @json($academicRecords->flatMap->studentRecords ?? []);


    document.getElementById('exam_type').addEventListener('change', function() {
        const selectedExamType = this.value;
        const gradeInputs = document.querySelectorAll('[name="grades[]"]');
        const studentInputs = document.querySelectorAll('[name="students[]"]');

        studentInputs.forEach((studentInput, index) => {
            const studentId = studentInput.value;
            const record = studentRecords.find(record => record.student_id == studentId && record.exam_type === selectedExamType);

            const gradeInput = gradeInputs[index];
            if (gradeInput) {
                gradeInput.value = record ? record.grade : '';
            } else {
                showToast('warning', `No input found for Student ${studentId}`);
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const classSelect = document.getElementById('class_name');
        if (classSelect.value) {
            classSelect.dispatchEvent(new Event('change'));
        }

        const examTypeSelect = document.getElementById('exam_type');
        if (examTypeSelect.value) {
            examTypeSelect.dispatchEvent(new Event('change'));
        }
    });
</script>
@endsection