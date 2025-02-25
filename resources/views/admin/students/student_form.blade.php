@extends('layouts.app')

@section('content')
    <section class="wrapper">
        <header class="header">
            <h2 class="title">{{ isset($student) ? 'Update Student' : 'Create Student' }}</h2>
        </header>

        <div class="content-box">
            <div class="form-container">
                <form action="{{ isset($student) ? route('admin.update.student', ['student_id' => $student->student_id]) : route('admin.store.student') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    @isset($student)
                        @method('PUT')
                    @endisset

                    <section class="form-section">
                        <header class="header">
                            <strong>Personal Information</strong>
                            <span class="collapsible"><i class="material-icons icon">expand_less</i></span>
                        </header>
                        <div class="form-group-row">
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="lrn">Learning Reference Number (LRN)<strong class="required">*</strong></label>
                                    <input value="{{ isset($student) ? $student->lrn : old('lrn') }}" type="text" name="lrn" id="lrn" placeholder="Learning Reference Number" class="{{ $errors->has('lrn') ? 'error' : '' }}">
                                </div>
                                @error('lrn')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="first_name">First name<strong class="required">*</strong></label>
                                    <input value="{{ isset($student) ? $student->first_name : old('first_name') }}" type="text" name="first_name" id="first_name" placeholder="First name" class="{{ $errors->has('first_name') ? 'error' : '' }}">
                                </div>
                                @error('first_name')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="middle_name">Middle name</label>
                                    <input value="{{ isset($student) ? $student->middle_name : old('middle_name') }}" type="text" name="middle_name" id="middle_name" placeholder="Middle name" class="{{ $errors->has('middle_name') ? 'error' : '' }}">
                                </div>
                                @error('middle_name')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="last_name">Last name<strong class="required">*</strong></label>
                                    <input value="{{ isset($student) ? $student->last_name : old('last_name') }}" type="text" name="last_name" id="last_name" placeholder="Last name" class="{{ $errors->has('last_name') ? 'error' : '' }}">
                                </div>
                                @error('last_name')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="suffix">Suffix</label>
                                    <select name="suffix" id="suffix" class="{{ $errors->has('suffix') ? 'error' : '' }}"></select>
                                </div>
                                @error('suffix')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="sex">Sex<strong class="required">*</strong></label>
                                    <select name="sex" id="sex" class="{{ $errors->has('sex') ? 'error' : '' }}">
                                        <option>-- Select Sex --</option>
                                        <option value="Male" {{ old('sex', isset($student) ? $student->sex : '') == 'Male' ? 'selected' : '' }} >Male</option>
                                        <option value="Female" {{ old('sex', isset($student) ? $student->sex : '') == 'Female' ? 'selected' : '' }} >Female</option>
                                    </select>
                                </div>
                                @error('sex')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="date_of_birth">Date of Birth<strong class="required">*</strong></label>
                                    <input value="{{ isset($student) ? $student->date_of_birth : old('date_of_birth') }}" type="date" name="date_of_birth" id="date_of_birth" class="{{ $errors->has('date_of_birth') ? 'error' : '' }}">
                                </div>
                                @error('date_of_birth')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>                  
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="place_of_birth">Place of Birth<strong class="required">*</strong></label>
                                    <input value="{{ isset($student) ? $student->place_of_birth : old('place_of_birth') }}" type="text" name="place_of_birth" id="place_of_birth" placeholder="Place of Birth" class="{{ $errors->has('place_of_birth') ? 'error' : '' }}">
                                </div>
                                @error('place_of_birth')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>    
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="nationality">Nationality<strong class="required">*</strong></label>
                                    <select name="nationality" id="nationality" class="{{ $errors->has('nationality') ? 'error' : '' }}"></select>
                                </div>
                                @error('nationality')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </section>

                    <section class="form-section">
                        <header class="header">
                            <strong>Address Information</strong>
                            <span class="collapsible"><i class="material-icons icon">expand_less</i></span>
                        </header>
                        <div class="form-group-row">
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="province">Provice<strong class="required">*</strong></label>
                                    <select name="province" id="province" class="{{ $errors->has('province') ? 'error' : '' }}"></select>
                                </div>
                                @error('province')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="municipality">Municipality<strong class="required">*</strong></label>
                                    <select name="municipality" id="municipality" class="{{ $errors->has('municipality') ? 'error' : '' }}"></select>
                                </div>
                                @error('municipality')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="barangay">Barangay<strong class="required">*</strong></label>
                                    <select name="barangay" id="barangay" class="{{ $errors->has('barangay') ? 'error' : '' }}"></select>
                                </div>
                                @error('barangay')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group-row">
                            <div class="form-group full">
                                <div class="input-group">
                                    <label for="street_address">Street Address</label>
                                    <input value="{{ isset($student) ? $student->street_address : old('street_address') }}" type="text" name="street_address" id="street_address" placeholder="Street Address" class="{{ $errors->has('street_address') ? 'error' : '' }}">
                                </div>
                                @error('street_address')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </section>

                    <section class="form-section">
                        <header class="header">
                            <strong>Contact Information</strong>
                            <span class="collapsible"><i class="material-icons icon">expand_less</i></span>
                        </header>
                        <div class="form-group-row">
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="contact_no">Contact No.<strong class="required">*</strong></label>
                                    <input value="{{ isset($student) ? $student->contact_no : old('contact_no', '+63') }}" type="tel" maxlength="13" name="contact_no" id="contact_no" placeholder="Contact No." class="{{ $errors->has('contact_no') ? 'error' : '' }}">
                                </div>
                                @error('contact_no')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="email">Email<strong class="required">*</strong></label>
                                    <input value="{{ isset($student) ? $student->email : old('email') }}" type="email" name="email" id="email" placeholder="Email" class="{{ $errors->has('email') ? 'error' : '' }}">
                                </div>
                                @error('email')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </section>

                    <section class="form-section">
                        <header class="header">
                            <strong>Parents Information</strong>
                            <span class="collapsible"><i class="material-icons icon">expand_less</i></span>
                        </header>

                        <div class="form-group-col">
                            <section class="form-section">
                                <header class="header">
                                    <strong>Father Information</strong>
                                    <span class="collapsible"><i class="material-icons icon">expand_less</i></span>
                                </header>
                                <div class="form-group-row">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <label for="father_first_name">First name</label>
                                            <input value="{{ isset($student) ? $student->father_first_name : old('father_first_name') }}" type="text" name="father_first_name" id="father_first_name" placeholder="First name">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <label for="father_middle_name">Middle name</label>
                                            <input value="{{ isset($student) ? $student->father_middle_name : old('father_middle_name') }}" type="text" name="father_middle_name" id="father_middle_name" placeholder="Middle name">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <label for="father_last_name">Last name</label>
                                            <input value="{{ isset($student) ? $student->father_last_name : old('father_last_name') }}" type="text" name="father_last_name" id="father_last_name" placeholder="Last name">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <label for="father_occupation">Occupation</label>
                                            <input value="{{ isset($student) ? $student->father_occupation : old('father_occupation') }}" type="text" name="father_occupation" id="father_occupation" placeholder="Occupation">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <label for="father_contact_no">Contact No.</label>
                                            <input value="{{ isset($student) ? $student->father_contact_no : old('father_contact_no') }}" type="text" name="father_contact_no" id="father_contact_no" placeholder="Contact No.">
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <section class="form-section">
                                <header class="header">
                                    <strong>Mother Information</strong>
                                    <span class="collapsible"><i class="material-icons icon">expand_less</i></span>
                                </header>
                                <div class="form-group-row">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <label for="mother_first_name">First name</label>
                                            <input value="{{ isset($student) ? $student->mother_first_name : old('mother_first_name') }}" type="text" name="mother_first_name" id="mother_first_name" placeholder="First name">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <label for="mother_middle_name">Middle name</label>
                                            <input value="{{ isset($student) ? $student->mother_middle_name : old('mother_middle_name') }}" type="text" name="mother_middle_name" id="mother_middle_name" placeholder="Middle name">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <label for="mother_last_name">Last name</label>
                                            <input value="{{ isset($student) ? $student->mother_last_name : old('mother_last_name') }}" type="text" name="mother_last_name" id="mother_last_name" placeholder="Last name">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <label for="mother_occupation">Occupation</label>
                                            <input value="{{ isset($student) ? $student->mother_occupation : old('mother_occupation') }}" type="text" name="mother_occupation" id="mother_occupation" placeholder="Occupation">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <label for="mother_contact_no">Contact No.</label>
                                            <input value="{{ isset($student) ? $student->mother_contact_no : old('mother_contact_no') }}" type="text" name="mother_contact_no" id="mother_contact_no" placeholder="Contact No.">
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </section>

                    <section class="form-section">
                        <header class="header">
                            <strong>Guardian Information</strong>
                            <span class="collapsible"><i class="material-icons icon">expand_less</i></span>
                        </header>
                        <div class="form-group-row">
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="guardian_first_name">First name</label>
                                    <input value="{{ isset($student) ? $student->guardian_first_name : old('guardian_first_name') }}" type="text" name="guardian_first_name" id="guardian_first_name" placeholder="First name">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="guardian_middle_name">Middle name</label>
                                    <input value="{{ isset($student) ? $student->guardian_middle_name : old('guardian_middle_name') }}" type="text" name="guardian_middle_name" id="guardian_middle_name" placeholder="Middle name">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="guardian_last_name">Last name</label>
                                    <input value="{{ isset($student) ? $student->guardian_last_name : old('guardian_last_name') }}" type="text" name="guardian_last_name" id="guardian_last_name" placeholder="Last name">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="guardian_relation">Relationship</label>
                                    <select name="guardian_relation" id="guardian_relation"></select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="guardian_occupation">Occupation</label>
                                    <input value="{{ isset($student) ? $student->guardian_occupation : old('guardian_occupation') }}" type="text" name="guardian_occupation" id="guardian_occupation" placeholder="Occupation">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="guardian_contact_no">Contact No.</label>
                                    <input value="{{ isset($student) ? $student->guardian_contact_no : old('guardian_contact_no') }}" type="text" name="guardian_contact_no" id="guardian_contact_no" placeholder="Contact No">
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="form-section">
                        <header class="header">
                            <strong>Academic Information</strong>
                            <span class="collapsible"><i class="material-icons icon">expand_less</i></span>
                        </header>
                        <div class="form-group-row">
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="previous_school_name">Previous School Name<strong class="required">*</strong></label>
                                    <input value="{{ isset($student) ? $student->previous_school_name : old('previous_school_name') }}" class="{{ $errors->has('previous_school_name') ? 'error' : '' }}" type="text" name="previous_school_name" id="previous_school_name" placeholder="Previous School Name">
                                </div>
                                @error('previous_school_name')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="birth_certificate">Birth Certificate<strong class="required">*</strong></label>
                                    <input type="file" name="birth_certificate" id="birth_certificate" class="{{ $errors->has('birth_certificate') ? 'error' : '' }}">
                                </div>
                                @error('birth_certificate')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="report_card">Report Card<strong class="required">*</strong></label>
                                    <input type="file" name="report_card" id="report_card" class="{{ $errors->has('report_card') ? 'error' : '' }}">
                                </div>
                                @error('report_card')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="teacher">Adviser<strong class="required">*</strong></label>
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
                                    <label for="strand">Strand<strong class="required">*</strong></label>
                                    <select name="strand" id="strand" class="{{ $errors->has('strand') ? 'error' : '' }}"></select>
                                </div>
                                @error('strand')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="current_year_level">Year Level<strong class="required">*</strong></label>
                                    <select name="current_year_level" id="current_year_level" class="{{ $errors->has('current_year_level') ? 'error' : '' }}">
                                        <option value="">-- Select Year Level --</option>
                                        <option value="Grade 11" {{ old('current_year_level', isset($student) ? $student->current_year_level : '') == 'Grade 11' ? 'selected' : '' }}>Grade 11</option>
                                        <option value="Grade 12" {{ old('current_year_level', isset($student) ? $student->current_year_level : '') == 'Grade 12' ? 'selected' : '' }}>Grade 12</option>
                                    </select>
                                </div>
                                @error('current_year_level')
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
                                        <option value="A" {{ old('section', isset($student) ? $student->section : '') == 'A' ? 'selected' : '' }} >A</option>
                                        <option value="B" {{ old('section', isset($student) ? $student->section : '') == 'B' ? 'selected' : '' }}>B</option>
                                        <option value="C" {{ old('section', isset($student) ? $student->section : '') == 'C' ? 'selected' : '' }}>C</option>
                                        <option value="D" {{ old('section', isset($student) ? $student->section : '') == 'D' ? 'selected' : '' }}>D</option>
                                        <option value="E" {{ old('section', isset($student) ? $student->section : '') == 'E' ? 'selected' : '' }}>E</option>
                                        <option value="F" {{ old('section', isset($student) ? $student->section : '') == 'F' ? 'selected' : '' }}>F</option>
                                    </select>
                                </div>
                                @error('section')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="school_year">School Year<strong class="required">*</strong></label>
                                    <input value="{{ isset($student) ? $student->school_year : old('school_year') }}" name="school_year" id="school_year" placeholder="School Year (2024-2025)" class="{{ $errors->has('school_year') ? 'error' : '' }}">
                                </div>
                                @error('school_year')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="enrollment_status">Status<strong class="required">*</strong></label>
                                    <select name="enrollment_status" id="enrollment_status" class="{{ $errors->has('enrollment_status') ? 'error' : '' }}">
                                        <option value="">-- Select Status --</option>
                                        <option value="Continuing" {{ old('enrollment_status', isset($student) ? $student->enrollment_status : '') == 'Continuing' ? 'selected' : '' }}>Continuing</option>
                                        <option value="Graduated" {{ old('enrollment_status', isset($student) ? $student->enrollment_status : '') == 'Graduated' ? 'selected' : '' }}>Graduated</option>
                                        <option value="Stopped" {{ old('enrollment_status', isset($student) ? $student->enrollment_status : '') == 'Stopped' ? 'selected' : '' }}>Stopped</option>
                                    </select>
                                </div>
                                @error('enrollment_status')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </section>

                    <section class="form-section" style="display: {{ isset($student) ? 'none' : '' }}">
                        <header class="header">
                            <strong>Account Information</strong>
                            <span class="collapsible"><i class="material-icons icon">expand_less</i></span>
                        </header>
                        <div class="form-group-row">
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="username">Username<strong class="required">*</strong></label>
                                    <input value="{{ isset($student) ? $student->username : old('username') }}" type="text" name="username" id="username" placeholder="Username" class="{{ $errors->has('username') ? 'error' : '' }}">
                                </div>
                                @error('username')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="password">Password<strong class="required">*</strong></label>
                                    <input type="password" name="password" id="password" placeholder="Password" class="{{ $errors->has('password') ? 'error' : '' }}">
                                    <i class="material-icons toggle-password" data-target="password">visibility</i>
                                </div>
                                @error('password')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="password_confirmation">Confirm Password<strong class="required">*</strong></label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password" class="{{ $errors->has('password_confirmation') ? 'error' : '' }}">
                                    <i class="material-icons toggle-password" data-target="password_confirmation">visibility</i>
                                </div>
                                @error('password_confirmation')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </section>

                    <div class="form-actions">
                        <button type="submit"><i class="material-icons icon">{{ isset($student) ? 'update' : 'add_circle_outline' }}</i>{{ isset($student) ? 'UPDATE' : 'CREATE' }}</button>
                        <a href="{{ route('admin.students') }}"><i class="material-icons icon">cancel</i>CANCEL</a>
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

    document.querySelectorAll('.toggle-password').forEach(toggle => {
        toggle.addEventListener('click', function () {
            const passwordField = document.getElementById(this.dataset.target);
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            this.innerText = type === 'password' ? 'visibility' : 'visibility_off';
        });
    });

    $('#suffix').select2({
        placeholder: '-- Select Suffix --',
        allowClear: true,
        ajax: {
            url: route("get.suffixes"),
            dataType: 'json',
            delay: 300,
            data: function (params) {
                return {
                    search: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (value, key) {
                        return {
                            id: key,
                            text: value
                        };
                    })
                };
            }
        }
    });

    let selectedSuffixes = @json(isset($student) ? $student->suffix : old('suffix'));
    if (selectedSuffixes) {
        $.ajax({
            url: route("get.suffixes"),
            dataType: 'json',
            data: { search: selectedSuffixes },
            success: function (data) {
                let exstingSuffix = Object.entries(data).map(([key, value]) => ({
                    id: key,
                    text: value
                })).find(item => item.id == selectedNationality);

                if (exstingSuffix) {
                    let option = new Option(exstingSuffix.text, exstingSuffix.id, true, true);
                    $('#suffix').append(option).trigger('change');
                }
            }
        });
    }


    $('#nationality').select2({
        placeholder: '-- Select a nationality --',
        allowClear: true,
        ajax: {
            url: route("get.nationalities"),
            dataType: 'json',
            delay: 300,
            data: function (params) {
                return {
                    search: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (value, key) {
                        return {
                            id: key,
                            text: value
                        };
                    })
                };
            }
        }
    });

    let selectedNationality = @json(isset($student) ? $student->nationality : old('nationality'));
    if (selectedNationality) {
        $.ajax({
            url: route("get.nationalities"),
            dataType: 'json',
            data: { search: selectedNationality },
            success: function (data) {
                let existingNationality = Object.entries(data).map(([key, value]) => ({
                    id: key,
                    text: value
                })).find(item => item.id == selectedNationality);

                if (existingNationality) {
                    let option = new Option(existingNationality.text, existingNationality.id, true, true);
                    $('#nationality').append(option).trigger('change');
                }
            }
        });
    }

    $('#province').select2({
        placeholder: "-- Select Province --",
        allowClear: true,
        ajax: {
            url: route('get.provinces'),
            delay: 400,
            data: function (params) {
                return {
                    search: params.term,
                    selected_province: selectedProvince,
                    page: params.page || 1,
                    per_page: 10
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;

                return {
                    results: data.data.map(province => ({
                        id: province.province_id,
                        text: province.province_name
                    })),
                    pagination: {
                        more: data.more
                    }
                };
            }
        }
    });

    let selectedProvince = @json(isset($student) ? $student->province_id : old('province') );
    if (selectedProvince) {
        $.ajax({
            url: route("get.provinces"),
            dataType: 'json',
            data: { selected_province: selectedProvince },
            success: function (response) {
                let existingProvince = response.data.find(province => province.province_id == selectedProvince);

                if (existingProvince) {
                    let option = new Option(existingProvince.province_name, existingProvince.province_id, true, true);
                    $('#province').append(option).trigger('change');
                } else {
                    console.warn("Province not found in API response:", selectedProvince);
                }
            }
        });
    }

    $('#municipality').select2({
        placeholder: "-- Select Municipality --",
        allowClear: true,
        ajax: {
            url: route('get.municipalities'),
            delay: 300,
            data: function (params) {
                return {
                    search: params.term,
                    province_id: $('#province').val(),
                    selected_municipality: selectedMunicipality,
                    page: params.page || 1,
                    per_page: 10
                };
            },
            processResults: function (data, params) {
                return {
                    results: data.data.map(municipality => ({
                        id: municipality.municipality_id,
                        text: municipality.municipality_name
                    })),
                    pagination: {
                        more: data.more
                    }
                };
            }
        }
    });

    let selectedMunicipality = @json(isset($student) ? $student->municipality_id : old('municipality'));
    if (selectedMunicipality) {
        $.ajax({
            url: route("get.municipalities"),
            dataType: 'json',
            data: {
                province_id: $('#province').val(),
                selected_municipality: selectedMunicipality
            },
            success: function (response) {
                let existingMunicipality = response.data.find(municipality => municipality.municipality_id == selectedMunicipality);
                if (existingMunicipality) {
                    let option = new Option(existingMunicipality.municipality_name, existingMunicipality.municipality_id, true, true);
                    $('#municipality').append(option).trigger('change');
                }
            }
        });
    }

    $('#province').on('change', function () {
        $('#municipality').val(null).trigger('change');
    });


    $('#barangay').select2({
        placeholder: "-- Select Barangay --",
        allowClear: true,
        ajax: {
            url: route('get.barangays'),
            delay: 300,
            data: function (params) {
                return {
                    search: params.term,
                    municipality_id: $('#municipality').val(),
                    selected_barangay: selectedBarangay,
                    page: params.page || 1,
                    per_page: 10
                };
            },
            processResults: function (data, params) {
                return {
                    results: data.data.map(barangay => ({
                        id: barangay.barangay_id,
                        text: barangay.barangay_name
                    })),
                    pagination: {
                        more: data.more
                    }
                };
            }
        }
    });

    let selectedBarangay = @json(isset($student) ? $student->barangay_id : old('barangay'));
    if (selectedBarangay) {
        $.ajax({
            url: route("get.barangays"),
            dataType: 'json',
            data: {
                municipality_id: $('#municipality').val(),
                selected_barangay: selectedBarangay
            },
            success: function (response) {
                let existingBarangay = response.data.find(barangay => barangay.barangay_id == selectedBarangay);
                if (existingBarangay) {
                    let option = new Option(existingBarangay.barangay_name, existingBarangay.barangay_id, true, true);
                    $('#barangay').append(option).trigger('change');
                }
            }
        });
    }

    $('#municipality').on('change', function () {
        $('#barangay').val(null).trigger('change');
    });

    $('#guardian_relation').select2({
        placeholder: '-- Select Relationship --',
        allowClear: true,
        ajax: {
            url: route("get.relationshipTypes"),
            dataType: 'json',
            delay: 300,
            data: function (params) {
                return {
                    search: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (value, key) {
                        return {
                            id: key,
                            text: value
                        };
                    })
                };
            }
        }
    });

    let selectionRelationshipType = @json(isset($student) ? $student->guardian_relation : old('guardian_relation'));
    if (selectionRelationshipType) {
        $.ajax({
            url: route("get.relationshipTypes"),
            dataType: 'json',
            data: { search: selectionRelationshipType },
            success: function (data) {
                let existingRelationshipType = Object.entries(data).map(([key, value]) => ({
                    id: key,
                    text: value
                })).find(item => item.id == selectionRelationshipType);

                if (existingRelationshipType) {
                    let option = new Option(existingRelationshipType.text, existingRelationshipType.id, true, true);
                    $('#guardia_relation').append(option).trigger('change');
                }
            }
        });
    }

    $('#teacher').select2({
        placeholder: "-- Select Adviser --",
        allowClear: true,
        ajax: {
        url: route('get.advisers'),
            delay: 400,
            data: function (params) {
                return {
                    search: params.term,
                    selected_adviser: selectedAdviser,
                    page: params.page || 1,
                    per_page: 10
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;

                return {
                    results: data.data.map(adviser => ({
                        id: adviser.teacher_id,
                        text: `${adviser.first_name} ${adviser.middle_name?.[0] ? adviser.middle_name[0] + "." : ''} ${adviser.last_name}`
                    })),
                    pagination: {
                        more: data.more
                    }
                };
            }
        }
    });

    let selectedAdviser = @json(isset($student) ? $student->teacher_id : old('teacher'));
    if (selectedAdviser) {
        $.ajax({
            url: route("get.advisers"),
            dataType: 'json',
            data: { selected_adviser: selectedAdviser },
            success: function (response) {
                let existingAdviser = response.data.find(adviser => adviser.teacher_id == selectedAdviser);

                if (existingAdviser) {
                    let option = new Option(`${existingAdviser.first_name} ${existingAdviser.middle_name?.[0] ? existingAdviser.middle_name[0] + "." : ''} ${existingAdviser.last_name}`, existingAdviser.teacher_id, true, true);
                    $('#teacher').append(option).trigger('change');
                } else {
                    console.warn("Province not found in API response:", selectedAdviser);
                }
            }
        });
    }

    $('#strand').select2({
        placeholder: "-- Select Strand --",
        allowClear: true,
        ajax: {
        url: route('get.strands'),
            delay: 400,
            data: function (params) {
                return {
                    search: params.term,
                    selected_strand: selectedStrand,
                    page: params.page || 1,
                    per_page: 10
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;

                return {
                    results: data.data.map(strand => ({
                        id: strand.strand_id,
                        text: `${strand.strand_name} (${strand.strand_type})`
                    })),
                    pagination: {
                        more: data.more
                    }
                };
            }
        }
    });

    let selectedStrand = @json(isset($student) ? $student->strand_id : old('strand'));
    if (selectedStrand) {
        $.ajax({
            url: route("get.strands"),
            dataType: 'json',
            data: { selected_strand: selectedStrand },
            success: function (response) {
                let existingStrand = response.data.find(strand => strand.strand_id == selectedStrand);

                if (existingStrand) {
                    let option = new Option(`${existingStrand.strand_name} (${existingStrand.strand_type})`, existingStrand.strand_id, true, true);
                    $('#strand').append(option).trigger('change');
                } else {
                    console.warn("Province not found in API response:", selectedStrand);
                }
            }
        });
    }

</script>
@endsection