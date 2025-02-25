<?php

use App\Http\Controllers\Student\AcademicRecordController;
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\ManageClassController;
use Illuminate\Support\Facades\Route;

Route::controller(DashboardController::class)->prefix('Dashboard')->group(function() {
    Route::get('/', 'index')->name('student.dashboard');
});

Route::controller(ManageClassController::class)->prefix('ManageClass')->group(function() {
    Route::get('/', 'index')->name('student.manageclass');
    Route::get('/Fetch/Classes', 'fetchClasses')->name('student.classes.fetch');
});


Route::controller(AcademicRecordController::class)->prefix('AcademicRecords')->group( function() {
    Route::get('/', 'index')->name('student.academic.records');
});