<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/toastr.css') }}" />
        <link href="{{ asset('assets/css/material-icons.css') }}" rel="stylesheet">
        <title>PNHS IMS</title>
    </head>

    <body>
        <div class="container">
            <div class="form-container">
                <header class="header">
                    <img class="school-logo" src="{{ asset('assets/images/Logo.jpg') }}" alt="Logo">
                    <h1>PNHS IMS</h1>
                </header>  
                <form method="POST" action="{{ route('login.check') }}">
                    @csrf
                    <div class="form-group">
                        <div class="input-group">
                            <label for="email_username">Email | Username</label>
                            <input type="text" name="email_username" id="email_username" placeholder="Email | Username" class="{{ $errors->has('email_username') ? 'error' : '' }}">
                        </div>
                        @error('email_username')
                            <span class="error">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" placeholder="Password" class="{{ $errors->has('password') ? 'error' : '' }}">
                            <i class="material-icons toggle-password" id="togglePassword-signin">visibility</i>
                        </div>
                        @error('password')
                            <span class="error">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <button type="submit" >LOGIN</button>
                </form>
            </div>
        </div>
    </body>
    <script>
        document.getElementById('togglePassword-signin').addEventListener('click', function () {
            const passwordField = document.getElementById('password');
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);

            this.innerText = type === 'password' ? 'visibility' : 'visibility_off';
        });
    </script>
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/js/toastnotif.js') }}"></script>
    @if (session('message'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                showToast("{{ session('type') }}", "{{ session('message') }}");
            }); 
        </script>
    @endif
</html>