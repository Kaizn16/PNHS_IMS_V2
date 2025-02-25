<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticationController;

Route::controller(AuthenticationController::class)->group(function() {
    Route::get('/Login', 'index')->name('login');
    Route::post('/Check/Auth', 'store')->name('login.check');
    Route::post('/', 'logout')->name('auth.logout');
});