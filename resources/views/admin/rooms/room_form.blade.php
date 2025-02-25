@extends('layouts.app')

@section('content')
    <section class="wrapper">
        <header class="header">
            <h2 class="title">Manage Rooms</h2>
        </header>

        <div class="content-box">
            <div class="form-container">
                <form action="{{ isset($room) ? route('admin.update.room', ['room_id' => $room->room_id]) : route('admin.store.room') }}" method="POST">
                    @csrf
                    @isset($room)
                        @method('PUT')
                    @endisset

                    <section class="form-section">
                        <header class="header">
                            <strong>Room Information</strong>
                            <span class="collapsible"><i class="material-icons icon">expand_less</i></span>
                        </header>
                        <div class="form-group-row">
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="room_name">Room Name<strong class="required">*</strong></label>
                                    <input value="{{ isset($room) ? $room->room_name : old('room_name') }}" type="text" name="room_name" id="room_name" placeholder="Class Name" class="{{ $errors->has('room_name') ? 'error' : '' }}">
                                </div>
                                @error('room_name')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="max_seat">Max Seats<strong class="required">*</strong></label>
                                    <input value="{{ isset($room) ? $room->max_seat : old('max_seat') }}" type="number" name="max_seat" id="max_seat" placeholder="Max Seats" inputmode="numeric" pattern="^[1-9][0-9]*$" oninput="this.value = this.value.replace(/^0+/, '')" class="{{ $errors->has('max_seat') ? 'error' : '' }}">
                                </div>
                                @error('max_seat')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="building_name">Building Name<strong class="required">*</strong></label>
                                    <input value="{{ isset($room) ? $room->building_name : old('building_name') }}" type="text" name="building_name" id="building_name" placeholder="Building Name" class="{{ $errors->has('building_name') ? 'error' : '' }}">
                                </div>
                                @error('building_name')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group-row">
                            <div class="form-group full">
                                <div class="input-group">
                                    <label for="building_description">Building Description</label>
                                    <textarea name="building_description" placeholder="Building Description">{{ isset($room) ? $room->building_description : old('building_description') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </section>

                    <div class="form-actions">
                        <button type="submit"><i class="material-icons icon">{{ isset($room) ? 'update' : 'add_circle_outline' }}</i>{{ isset($room) ? 'UPDATE' : 'CREATE' }}</button>
                        <a href="{{ route('admin.rooms') }}"><i class="material-icons icon">cancel</i>CANCEL</a>
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
</script>
@endsection