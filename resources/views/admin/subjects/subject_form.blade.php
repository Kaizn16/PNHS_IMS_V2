@extends('layouts.app')

@section('content')
    <section class="wrapper">
        <header class="header">
            <h2 class="title">Manage Subjects</h2>
        </header>

        <div class="content-box">
            <div class="form-container">
                <form action="{{ isset($subject) ? route('admin.update.subject', ['subject_id' => $subject->subject_id]) : route('admin.store.subject') }}" method="POST">
                    @csrf
                    @isset($subject)
                        @method('PUT')
                    @endisset

                    <section class="form-section">
                        <header class="header">
                            <strong>Subject Information</strong>
                            <span class="collapsible"><i class="material-icons icon">expand_less</i></span>
                        </header>
                        <div class="form-group-row">
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="strand">Strand</label>
                                    <select name="strand" id="strand"></select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="subject_code">Subject Code<strong class="required">*</strong></label>
                                    <input value="{{ isset($subject) ? $subject->subject_code : old('subject_code') }}" type="text" name="subject_code" id="subject_code" placeholder="Subject Code" class="{{ $errors->has('subject_code') ? 'error' : '' }}">
                                </div>
                                @error('subject_code')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="subject_title">Subject Title<strong class="required">*</strong></label>
                                    <input value="{{ isset($subject) ? $subject->subject_title : old('subject_title') }}" type="text" name="subject_title" id="subject_title" placeholder="Subject Title" class="{{ $errors->has('subject_title') ? 'error' : '' }}">
                                </div>
                                @error('subject_title')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group-row">
                            <div class="form-group full">
                                <div class="input-group">
                                    <label for="subject_description">Subject Description</label>
                                    <textarea name="subject_description" placeholder="Subject Description">{{ isset($subject) ? $subject->subject_description : old('subject_description') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </section>

                    <div class="form-actions">
                        <button type="submit"><i class="material-icons icon">{{ isset($subject) ? 'update' : 'add_circle_outline' }}</i>{{ isset($subject) ? 'UPDATE' : 'CREATE' }}</button>
                        <a href="{{ route('admin.subjects') }}"><i class="material-icons icon">cancel</i>CANCEL</a>
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