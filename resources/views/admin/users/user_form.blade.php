@extends('layouts.app')

@section('content')
    <section class="wrapper">
        <header class="header">
            <h2 class="title">{{ isset($user) ? 'Update User' : 'Create New Admin' }}</h2>
        </header>

        <div class="content-box">
            <div class="form-container">
                <form action="{{ isset($user) ? route('admin.update.user', ['user_id' => $user->user_id]) : route('admin.store.user') }}" method="POST">
                    @csrf
                    @isset($user)
                        @method('PUT')
                    @endisset

                    <section class="form-section">
                        <header class="header">
                            <strong>User Information</strong>
                            <span class="collapsible"><i class="material-icons icon">expand_less</i></span>
                        </header>
                        <div class="form-group-row">
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="name">Name<strong class="required">*</strong></label>
                                    <input value="{{ isset($user) ? $user->name : old('name') }}" type="text" name="name" id="name" placeholder="Name" class="{{ $errors->has('name') ? 'error' : '' }}">
                                </div>
                                @error('name')
                                    <span class="error">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="email">Email<strong class="required">*</strong></label>
                                    <input value="{{ isset($user) ? $user->email : old('email') }}" type="email" name="email" id="email" placeholder="Email" class="{{ $errors->has('email') ? 'error' : '' }}">
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
                                    <label for="username">Username<strong class="required">*</strong></label>
                                    <input value="{{ isset($user) ? $user->username : old('username') }}" type="text" name="username" id="username" placeholder="Username" class="{{ $errors->has('username') ? 'error' : '' }}">
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
                        <button type="submit"><i class="material-icons icon">{{ isset($user) ? 'update' : 'add_circle_outline' }}</i>{{ isset($user) ? 'UPDATE' : 'CREATE' }}</button>
                        <a href="{{ route('admin.users') }}"><i class="material-icons icon">cancel</i>CANCEL</a>
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

</script>
@endsection