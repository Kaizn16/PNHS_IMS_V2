@extends('layouts.app')

@section('content')
    <section class="wrapper">
        <header class="header">
            <h2 class="title">Manage Classes</h2>
        </header>

        <div class="content-box">
            <div class="form-container">
                <form action="{{ isset($class) ? route('admin.update.class', ['class_management_id' => $class->class_management_id]) : route('admin.store.class') }}" method="POST">
                    @csrf
                    @isset($class)
                        @method('PUT')
                    @endisset

                    <section class="form-section">
                        <header class="header">
                            <strong>Class Information</strong>
                            <span class="collapsible"><i class="material-icons icon">expand_less</i></span>
                        </header>
                        <div class="form-group-row">
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="class_name">Class Name<strong class="required">*</strong></label>
                                    <input value="{{ isset($class) ? $class->class_name : old('class_name') }}" type="text" name="class_name" id="class_name" placeholder="Class Name" class="{{ $errors->has('class_name') ? 'error' : '' }}">
                                </div>
                                @error('class_name')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="room">Room<strong class="required">*</strong></label>
                                    <select name="room" id="room" class="{{ $errors->has('room') ? 'error' : '' }}"></select>
                                </div>
                                @error('room')
                                <span class="error">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            </div>
                        </div>
                        <div class="form-group-row">
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="teacher">Teacher<strong class="required">*</strong></label>
                                    <select name="teacher" id="teacher" class="{{ $errors->has('teacher') ? 'error' : '' }}"></select>
                                </div>
                                @error('teacher')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="subject">Subject<strong class="required">*</strong></label>
                                    <select name="subject" id="subject" class="{{ $errors->has('subject') ? 'error' : '' }}"></select>
                                </div>
                                @error('subject')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group-row">
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="year_level">Year Level<strong class="required">*</strong></label>
                                    <select name="year_level" id="year_level" class="{{ $errors->has('year_level') ? 'error' : '' }}">
                                        <option value="">-- Select Year Level --</option>
                                        <option value="Grade 11" {{ old('year_level', isset($class) ? $class->year_level : '') == 'Grade 11' ? 'selected' : '' }}>Grade 11</option>
                                        <option value="Grade 12" {{ old('year_level', isset($class) ? $class->year_level : '') == 'Grade 12' ? 'selected' : '' }}>Grade 12</option>
                                    </select>
                                </div>
                                @error('year_level')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="section">Section<strong class="required">*</strong></label>
                                    <select name="section" id="section" class="{{ $errors->has('section') ? 'error' : '' }}">
                                        <option value="">-- Select Section --</option>
                                        <option value="A" {{ old('section', isset($class) ? $class->section : '') == 'A' ? 'selected' : '' }}>A</option>
                                        <option value="B" {{ old('section', isset($class) ? $class->section : '') == 'B' ? 'selected' : '' }}>B</option>
                                        <option value="C" {{ old('section', isset($class) ? $class->section : '') == 'C' ? 'selected' : '' }}>C</option>
                                        <option value="D" {{ old('section', isset($class) ? $class->section : '') == 'D' ? 'selected' : '' }}>D</option>
                                        <option value="E" {{ old('section', isset($class) ? $class->section : '') == 'E' ? 'selected' : '' }}>E</option>
                                        <option value="F" {{ old('section', isset($class) ? $class->section : '') == 'F' ? 'selected' : '' }}>F</option>
                                    </select>
                                </div>
                                @error('section')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group-row">
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="semester">Semester<strong class="required">*</strong></label>
                                    <select name="semester" id="semester" class="{{ $errors->has('semester') ? 'error' : '' }}">
                                        <option value="">-- Select Semester --</option>
                                        <option value="1st Semester" {{ old('semester', isset($class) ? $class->semester : '') == '1st Semester' ? 'selected' : '' }}>1st Semester</option>
                                        <option value="2nd Semester" {{ old('semester', isset($class) ? $class->semester : '') == '2nd Semester' ? 'selected' : '' }}>2nd Semester</option>
                                    </select>
                                </div>
                                @error('semester')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="school_year">School Year<strong class="required">*</strong></label>
                                    <input value="{{ isset($class) ? $class->school_year : old('school_year' )}}" type="text" name="school_year" id="school_year" placeholder="School Year (2024-2025)" class="{{ $errors->has('school_year') ? 'error' : '' }}">
                                </div>
                                @error('school_year')
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
                        <div class="form-group-row">
                            <div class="form-group full">
                                <div class="input-group">
                                    <label for="students">Students<strong class="required">*</strong></label>
                                    <select name="students[]" id="students" class="{{ $errors->has('students') ? 'error' : '' }}" multiple></select>
                                </div>
                                @error('students')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </section>

                    <section class="form-section">
                        <header class="header">
                            <strong>Schedule Information</strong>
                            <span class="collapsible"><i class="material-icons icon">expand_less</i></span>
                        </header>
                        <div class="form-group-col">
                            <ul class="schedule-list" id="schedule-list">
                                @php
                                    $schedulesData = old('day') 
                                        ? array_map(null, old('day'), old('start_time'), old('end_time')) 
                                        : (isset($schedules) && count($schedules) ? $schedules : [null]); // <- Add [null] as a fallback
                                @endphp

                                @foreach($schedulesData as $index => $schedule)
                                    <li class="schedule">
                                        <span class="removeSchedule" title="Remove Schedule" onclick="removeSchedule(this)" style="{{ $loop->first ? 'display: none;' : 'display: flex;' }}">
                                            <i class="material-icons icon">close</i>
                                        </span>
                                        <div class="form-group full">
                                            <div class="input-group">
                                                <label for="day">Day<strong class="required">*</strong></label>
                                                <select name="day[]" class="{{ $errors->has("day.$index") ? 'error' : '' }}">
                                                    <option value="">-- Select Day --</option>
                                                    @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'] as $day)
                                                        <option value="{{ $day }}" {{ (old('day')[$index] ?? $schedule->day ?? '') == $day ? 'selected' : '' }}>{{ $day }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error("day.$index")
                                                <span class="error"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                        <div class="time">
                                            <div class="form-group half">
                                                <div class="input-group">
                                                    <label for="start_time">Start<strong class="required">*</strong></label>
                                                    <input type="time" name="start_time[]" value="{{ old('start_time')[$index] ?? $schedule->time_start ?? '' }}" class="{{ $errors->has("start_time.$index") ? 'error' : '' }}">
                                                </div>
                                                @error("start_time.$index")
                                                    <span class="error"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                            <div class="form-group half">
                                                <div class="input-group">
                                                    <label for="end_time">End<strong class="required">*</strong></label>
                                                    <input type="time" name="end_time[]" value="{{ old('end_time')[$index] ?? $schedule->time_end ?? '' }}" class="{{ $errors->has("end_time.$index") ? 'error' : '' }}">
                                                </div>
                                                @error("end_time.$index")
                                                    <span class="error"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                        </div>
                                    </li>
                                @endforeach

                            </ul>                            
                            <div class="schedule-action">
                                <button type="button" onclick="debouncedAddSchedule()">
                                    <i class="material-icons icon">add</i>Add New Schedule
                                </button>
                            </div>
                        </div>
                    </section>

                    <div class="form-actions">
                        <button type="submit"><i class="material-icons icon">{{ isset($class) ? 'update' : 'add_circle_outline' }}</i>{{ isset($class) ? 'UPDATE' : 'CREATE' }}</button>
                        <a href="{{ route('admin.manageclass') }}"><i class="material-icons icon">cancel</i>CANCEL</a>
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

    let addScheduleTimeout = false;

    function debouncedAddSchedule() {
        if (addScheduleTimeout) return;

        addSchedule();
        addScheduleTimeout = true;
        setTimeout(() => addScheduleTimeout = false, 1000);
    }

    function addSchedule() {
        const scheduleList = document.getElementById('schedule-list');
        const schedules = document.querySelectorAll('.schedule');
        const lastSchedule = schedules[schedules.length - 1];
        const newSchedule = lastSchedule.cloneNode(true);

        newSchedule.querySelector('select').value = '';
        newSchedule.querySelectorAll('input[type="time"]').forEach(input => input.value = '');

        newSchedule.querySelector('.removeSchedule').style.display = 'flex';
        scheduleList.appendChild(newSchedule);

        const updatedSchedules = document.querySelectorAll('.schedule');
        if (updatedSchedules.length > 1) {
            updatedSchedules[0].querySelector('.removeSchedule').style.display = 'flex';
        }
    }

    function removeSchedule(element) {
        const schedule = element.closest('.schedule');
        schedule.remove();

        const updatedSchedules = document.querySelectorAll('.schedule');
        if (updatedSchedules.length === 1) {
            updatedSchedules[0].querySelector('.removeSchedule').style.display = 'none';
        }
    }


    $('#room').select2({
        placeholder: "-- Select Room --",
        allowClear: true,
        ajax: {
            url: route('get.rooms'),
            delay: 400,
            data: function (params) {
                return {
                    search: params.term,
                    selected_room: selectedRoom,
                    page: params.page || 1,
                    per_page: 10
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;

                return {
                    results: data.data.map(room => ({
                        id: room.room_id,
                        text: `${room.room_name} - ${room.building_name}`
                    })),
                    pagination: {
                        more: data.more
                    }
                };
            }
        }
    });

    let selectedRoom = @json(isset($class) ? $class->room_id : old('room'));
    if (selectedRoom) {
        $.ajax({
            url: route("get.rooms"),
            dataType: 'json',
            data: { selected_room: selectedRoom },
            success: function (response) {
                let existingRoom = response.data.find(room => room.room_id == selectedRoom);

                if (existingRoom) {
                    let option = new Option(`${existingRoom.room_name} - ${existingRoom.building_name}`, existingRoom.room_id, true, true);
                    $('#room').append(option).trigger('change');
                } else {
                    console.warn("Province not found in API response:", selectedRoom);
                }
            }
        });
    }
    

    $('#teacher').select2({
        placeholder: "-- Select Teacher --",
        allowClear: true,
        ajax: {
            url: route('get.teachers'),
            delay: 400,
            data: function (params) {
                return {
                    search: params.term,
                    selected_teacher: selectedTeacher,
                    page: params.page || 1,
                    per_page: 10
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;

                return {
                    results: data.data.map(teacher => ({
                        id: teacher.teacher_id,
                        text: `${teacher.first_name} ${teacher.middle_name?.[0] ? teacher.middle_name[0] + "." : ''} ${teacher.last_name}`
                    })),
                    pagination: {
                        more: data.more
                    }
                };
            }
        }
    });

    let selectedTeacher = @json(isset($class) ? $class->teacher_id : old('teacher'));
    if (selectedTeacher) {
        $.ajax({
            url: route("get.teachers"),
            dataType: 'json',
            data: { selected_teacher: selectedTeacher },
            success: function (response) {
                let existingTeacher = response.data.find(teacher => teacher.teacher_id == selectedTeacher);

                if (existingTeacher) {
                    let option = new Option(`${existingTeacher.first_name} ${existingTeacher.middle_name?.[0] ? existingTeacher.middle_name[0] + "." : ''} ${existingTeacher.last_name}`, existingTeacher.teacher_id, true, true);
                    $('#teacher').append(option).trigger('change');
                } else {
                    console.warn("Province not found in API response:", selectedTeacher);
                }
            }
        });
    }

    $('#subject').select2({
        placeholder: "-- Select Subject --",
        allowClear: true,
        ajax: {
            url: route('get.teacher.subjects'),
            delay: 300,
            data: function (params) {
                return {
                    search: params.term,
                    teacher_id: $('#teacher').val(),
                    selected_subject: selectedSubject,
                    page: params.page || 1,
                    per_page: 10
                };
            },
            processResults: function (data, params) {
                return {
                    results: data.data.map(subject => ({
                        id: subject.subject.subject_id,
                        text: subject.subject.subject_code
                    })),
                    pagination: {
                        more: data.more
                    }
                };
            }
        }
    });

    let selectedSubject = @json(isset($class) ? $class->subject_id : old('subject'));
    if (selectedSubject) {
        $.ajax({
            url: route("get.teacher.subjects"),
            dataType: 'json',
            data: {
                teacher_id: $('#teacher').val(),
                selected_subject: selectedSubject
            },
            success: function (response) {
                let existingSubject = response.data.find(subject => subject.subject.subject_id == selectedSubject);
                if (existingSubject) {
                    let option = new Option(existingSubject.subject.subject_code, existingSubject.subject.subject_id, true, true);
                    $('#subject').append(option).trigger('change');
                }
            }
        });
    }

    $('#teacher').on('change', function () {
        $('#subject').val(null).trigger('change');
    });


    // Students
    $('#students').select2({
        placeholder: "-- Select Students --",
        allowClear: true,
        multiple: true,
        ajax: {
            url: route('get.students'),
            delay: 300,
            data: function (params) {
                return {
                    search: params.term,
                    year_level: $('#year_level').val(),
                    section: $('#section').val(),
                    selected_students: selectedStudents,
                    page: params.page || 1,
                    per_page: 10
                };
            },
            processResults: function (data, params) {
                return {
                    results: data.data.map(student => ({
                        id: student.student_id,
                        text: `${student.first_name} ${student.middle_name?.[0] ? student.middle_name[0] + "." : ''} ${student.last_name}`
                    })),
                    pagination: {
                        more: data.more
                    }
                };
            }
        }
    });

    let selectedStudents = @json(isset($students) ? $students->pluck('student_id'): old('students'));
    if (selectedStudents) {
        fetchAndSelectStudents();
    }

    $('#year_level').on('change', function () {
        fetchAndSelectStudents();
    });

    $('#section').on('change', function () {
        fetchAndSelectStudents();
    });

    function fetchAndSelectStudents() {
        $.ajax({
            url: route("get.students"),
            dataType: 'json',
            data: {
                year_level: $('#year_level').val(),
                section: $('#section').val()
            },
            success: function (response) {
                let students = response.data.map(student => {
                    return new Option(`${student.first_name} ${student.middle_name?.[0] ? student.middle_name[0] + "." : ''} ${student.last_name}`, student.student_id, true, true);
                });
                $('#students').empty().append(students).trigger('change');
                selectedStudents = response.data.map(student => student.student_id);
            }
        });
    }

</script>
@endsection