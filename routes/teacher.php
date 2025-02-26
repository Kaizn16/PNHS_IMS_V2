<?php

use App\Http\Controllers\Teacher\AcademicRecordController;
use App\Http\Controllers\Teacher\DashboardController;
use App\Http\Controllers\Teacher\ManageClassController;
use App\Http\Controllers\Teacher\ReportController;
use App\Http\Controllers\Teacher\StudentController;
use Illuminate\Support\Facades\Route;

Route::controller(DashboardController::class)->prefix('Dashboard')->group(function() {
    Route::get('/', 'index')->name('teacher.dashboard');
});

Route::controller(StudentController::class)->prefix('Students')->group(function() {
    Route::get('/', 'index')->name('teacher.students');
    Route::get('/Fetch/Students', 'fetchStudents')->name('teacher.students.fetch');
    Route::get('/Create', 'create')->name('teacher.create.student');
    Route::post('/Create/Store', 'store')->name('teacher.store.student');
    Route::get('/Edit/{student_id}', 'edit')->name('teacher.edit.student');
    Route::put('/Edit/{student_id}/Update', 'update')->name('teacher.update.student');
    Route::put('/SoftDelete/Student', 'delete')->name('teacher.softdelete.student');
    Route::put('/SoftBulkDelete/Students', 'bulkDelete')->name('teacher.softbulkdelete.student');
    Route::put('/Restore/Student', 'restore')->name('teacher.restore.student');
    Route::put('/BulkRestore/Students', 'bulkRestore')->name('teacher.bulkrestore.student');
    Route::delete('/PermentlyDelete/Student', 'destroy')->name('teacher.destroy.student');
});

Route::controller(ManageClassController::class)->prefix('ManageClass')->group(function() {
    Route::get('/', 'index')->name('teacher.manageclass');
    Route::get('/Fetch/Classes', 'fetchClasses')->name('teacher.classes.fetch');
    Route::get('/Create', 'create')->name('teacher.create.class');
    Route::post('/Create/Store', 'store')->name('teacher.store.class');
    Route::get('/Edit/{class_management_id}', 'edit')->name('teacher.edit.class');
    Route::put('/Edit/{class_management_id}/Updatee', 'update')->name('teacher.update.class');
    Route::get('/View/{class_management_id}', 'view')->name('teacher.view.class');
    Route::put('/SoftDelete/Class', 'delete')->name('teacher.softdelete.class');
    Route::put('/SoftBulkDelete/Classes', 'bulkDelete')->name('teacher.softbulkdelete.class');
    Route::put('/Restore/Class', 'restore')->name('teacher.restore.class');
    Route::put('/BulkRestore/Classes', 'bulkRestore')->name('teacher.bulkrestore.class');
    Route::delete('/PermentlyDelete/Class', 'destroy')->name('teacher.destroy.class');
});

Route::controller(AcademicRecordController::class)->prefix('AcademicRecords')->group( function() {
    Route::get('/', 'index')->name('teacher.academic.records');
    Route::get('/Fetch/Classes', 'fetchClasses')->name('teacher.academic.record.classes.fetch');
    Route::get('/Fetch/AcademicRecords', 'fetchAcademicRecords')->name('teacher.academic.record.fetch');
    Route::get('/Create', 'create')->name('teacher.create.academic.record');
    Route::post('/Create/Store', 'store')->name('teacher.store.academic.record');
    Route::get('/Edit/{class_management_id}', 'edit')->name('teacher.edit.academic.record');
    Route::put('/Edit/{class_management_id}/Update', 'update')->name('teacher.update.academic.record');
    Route::get('/View/{class_record}', 'view')->name('teacher.view.academic.records');
});

Route::controller(ReportController::class)->prefix('Reports')->group( function() {
    Route::get('/', 'index')->name('teacher.reports');
});