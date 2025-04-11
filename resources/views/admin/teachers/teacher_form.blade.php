@extends('layouts.app')

@section('content')
    <section class="wrapper">
        <header class="header">
            <h2 class="title">{{ isset($teacher) ? 'Update Teacher' : 'Create Teacher' }}</h2>
        </header>

        <div class="content-box">
            <div class="form-container">
                <form action="{{ isset($teacher) ? route('admin.update.teacher', ['teacher_id' => $teacher->teacher_id]) : route('admin.store.teacher') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @isset($teacher)
                        @method('PUT')
                    @endisset

                    <div class="form-section">
                        <header class="header">
                            <strong>Personal Information</strong>
                            <span class="collapsible"><i class="material-icons icon">expand_less</i></span>
                        </header>
                        <div class="form-group-row">
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="first_name">First name<strong class="required">*</strong></label>
                                    <input value="{{ isset($teacher) ? $teacher->first_name : old('first_name') }}" type="text" name="first_name" id="first_name" placeholder="First name" class="{{ $errors->has('first_name') ? 'error' : '' }}">
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
                                    <input value="{{ isset($teacher) ? $teacher->middle_name : old('middle_name') }}" type="text" name="middle_name" id="middle_name" placeholder="Middle name" class="{{ $errors->has('middle_name') ? 'error' : '' }}">
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
                                    <input value="{{ isset($teacher) ? $teacher->last_name : old('last_name') }}" type="text" name="last_name" id="last_name" placeholder="Last name" class="{{ $errors->has('last_name') ? 'error' : '' }}">
                                </div>
                                @error('last_name')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="date_of_birth">Date of Birth<strong class="required">*</strong></label>
                                    <input value="{{ isset($teacher) ? $teacher->date_of_birth : old('date_of_birth') }}" type="date" name="date_of_birth" id="date_of_birth" class="{{ $errors->has('date_of_birth') ? 'error' : '' }}">
                                </div>
                                @error('date_of_birth')
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
                                        <option value="Male" {{ isset($teacher) && $teacher->sex == 'Male' ? 'selected' : '' }} >Male</option>
                                        <option value="Female" {{ isset($teacher) && $teacher->sex == 'Female' ? 'selected' : '' }} >Female</option>
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
                                    <label for="civil_status">Civil Status<strong class="required">*</strong></label>
                                    <select name="civil_status" id="civil_status" class="{{ $errors->has('civil_status') ? 'error' : '' }}">
                                        <option value="">-- Select Civil Status --</option>
                                        <option value="Single" {{ isset($teacher) && $teacher->civil_status == 'Single' ? 'selected' : '' }} >Single</option>
                                        <option value="Married" {{ isset($teacher) && $teacher->civil_status == 'Married' ? 'selected' : '' }}>Married</option>
                                        <option value="Divorced" {{ isset($teacher) && $teacher->civil_status == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                        <option value="Widowed" {{ isset($teacher) && $teacher->civil_status == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                        <option value="Separated" {{ isset($teacher) && $teacher->civil_status == 'Separated' ? 'selected' : '' }}>Separated</option>
                                        <option value="In a Domestic Partnership" {{ isset($teacher) && $teacher->civil_status == 'In a Domestic Partnership' ? 'selected' : '' }}>In a Domestic Partnership</option>
                                        <option value="Engaged" {{ isset($teacher) && $teacher->civil_status == 'Engaged' ? 'selected' : '' }}>Engaged</option>
                                    </select>
                                </div>
                                @error('civil_status')
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
                    </div>

                    <div class="form-section">
                        <header class="header">
                            <strong>Address Information</strong>
                            <span class="collapsible"><i class="material-icons icon">expand_less</i></span>
                        </header>
                        <div class="form-group-row">
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="province">Province<strong class="required">*</strong></label>
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
                                    <input value="{{ isset($teacher) ? $teacher->street_address : old('street_address') }}" type="text" name="street_address" id="street_address" placeholder="Street Address" class="{{ $errors->has('street_address') ? 'error' : '' }}">
                                </div>
                                @error('street_address')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <header class="header">
                            <strong>Employment Information</strong>
                            <span class="collapsible"><i class="material-icons icon">expand_less</i></span>
                        </header>
                        <div class="form-group-row">
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="account_role">-- Account Role --<strong class="required">*</strong></label>
                                    <select name="account_role" id="account_role" class="{{ $errors->has('account_role') ? 'error' : '' }}">
                                        <option>Select Role</option>
                                        <option value="Adviser" {{ isset($teacher) && $teacher->designation == "Adviser" ? 'selected' : '' }}>Adviser</option>
                                        <option value="Teacher" {{ isset($teacher) && $teacher->designation == "Teacher" ? 'selected' : '' }}>Teacher</option>
                                    </select>
                                </div>
                                @error('account_role')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group half">
                                <div class="input-group">
                                    <label for="subject_handle">Subjects Handle(Ex. Math, English)<strong class="required">*</strong></label>
                                    <select name="subject_handle[]" id="subject_handle" class="{{ $errors->has('subject_handle') ? 'error' : '' }}" multiple></select>
                                </div>
                                @error('subject_handle')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group-row">
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="employment_type">-- Employment Type --<strong class="required">*</strong></label>
                                    <select name="employment_type" id="employment_type" class="{{ $errors->has('employment_type') ? 'error' : '' }}">
                                        <option>Select Employment Type</option>
                                        <option value="Part-Time" {{ isset($teacher) && $teacher->employment_type == "Part-Time" ? 'selected' : '' }}>Part-Time</option>
                                        <option value="Full-Time" {{ isset($teacher) && $teacher->employment_type == "Full-Time" ? 'selected' : '' }}>Full-Time</option>
                                    </select>
                                </div>
                                @error('employment_type')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="date_hired">Date Hired<strong class="required">*</strong></label>
                                    <input value="{{ isset($teacher) ? $teacher->date_hired : old('date_hired') }}"  type="date" name="date_hired" id="date_hired" class="{{ $errors->has('date_hired') ? 'error' : '' }}">
                                </div>
                                @error('date_hired')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="employment_status">-- Employment Status --</label>
                                    <select name="employment_status" id="employment_status" class="{{ $errors->has('employment_status') ? 'error' : '' }}">
                                        <option value="Active" {{ isset($teacher) && $teacher->employment_status == "Active" ? 'selected' : '' }}>Active</option>
                                        <option value="Inactive" {{ isset($teacher) && $teacher->employment_status == "Inactive" ? 'selected' : '' }} >Inactive</option>
                                    </select>
                                </div>
                                @error('employment_status')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-section" style="display: {{ isset($teacher) ? 'none' : '' }}">
                        <header class="header">
                            <strong>Account Information</strong>
                            <span class="collapsible"><i class="material-icons icon">expand_less</i></span>
                        </header>
                        <div class="form-group-row">
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="username">Username<strong class="required">*</strong></label>
                                    <input type="text" name="username" id="username" placeholder="Username" class="{{ $errors->has('username') ? 'error' : '' }}">
                                </div>
                                @error('username')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="email">Email<strong class="required">*</strong></label>
                                    <input type="email" name="email" id="email" placeholder="Email" class="{{ $errors->has('email') ? 'error' : '' }}">
                                </div>
                                @error('email')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group-row">
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
                    </div>

                    <div class="form-actions">
                        <button type="submit"><i class="material-icons icon">{{ isset($teacher) ? 'update' : 'add_circle_outline' }}</i>{{ isset($teacher) ? 'UPDATE' : 'CREATE' }}</button>
                        <a href="{{ route('admin.teachers') }}"><i class="material-icons icon">cancel</i>CANCEL</a>
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

    $('#nationality').select2({
        placeholder: 'Select a nationality',
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

    let selectedNationality = @json(isset($teacher) ? $teacher->nationality : '');
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
        placeholder: "Select Province",
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

    let selectedProvince = @json(isset($teacher) ? $teacher->province_id : '');
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
        placeholder: "Select Municipality",
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

    let selectedMunicipality = @json(isset($teacher) ? $teacher->municipality_id : '');
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

    // Barangay
    $('#barangay').select2({
        placeholder: "Select Barangay",
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

    let selectedBarangay = @json(isset($teacher) ? $teacher->barangay_id : '');
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


    $('#subject_handle').select2({
        placeholder: "Select Subjects",
        allowClear: true,
        multiple: true, 
        ajax: {
            url: route('get.subjects'),
            delay: 300,
            data: function (params) {
                return {
                    search: params.term,
                    selected_subjects: selectedSubjects,
                    page: params.page || 1,
                    per_page: 10
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;

                return {
                    results: data.data.map(subject => ({
                        id: subject.subject_id,
                        text: subject.subject_code
                    })),
                    pagination: {
                        more: data.more
                    }
                };
            }
        },
        templateResult: function (data) {
            return data.text || '';
        }
    });

    let selectedSubjects = @json(isset($subjects) ? $subjects->pluck('subject_id') : []);
    if (selectedSubjects.length > 0) {
        $.ajax({
            url: route("get.subjects"),
            dataType: 'json',
            data: {
                selected_subjects: selectedSubjects 
            },
            success: function (response) {
                response.data.forEach(subject => {
                    let option = new Option(subject.subject_code, subject.subject_id, true, true);
                    $('#subject_handle').append(option).trigger('change');
                });
            }
        });
    }

</script>
<script>
    function generatePassword(length = 8) {
        const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789@#$!";
        let password = "";
        for (let i = 0; i < length; i++) {
            password += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        return password;
    }

    window.addEventListener('DOMContentLoaded', () => {
        const password = generatePassword(10);
        document.getElementById('password').value = password;
        document.getElementById('password_confirmation').value = password;
    });
</script>
@endsection