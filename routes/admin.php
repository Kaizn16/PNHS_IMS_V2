<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ManageClassController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TeacherConctroller;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;


Route::controller(DashboardController::class)->group(function() {
   Route::get('Dashboard', 'index')->name('admin.dashboard');
});

Route::controller(TeacherConctroller::class)->prefix('Teachers')->group(function() {
   Route::get('/', 'index')->name('admin.teachers');
   Route::get('/Fetch/Teachers', 'fetchTeachers')->name('admin.teachers.fetch');
   Route::get('/Create', 'create')->name('admin.create.teacher');
   Route::post('/Create/Store', 'store')->name('admin.store.teacher');
   Route::get('/Edit/{teacher_id}', 'edit')->name('admin.edit.teacher');
   Route::put('/Edit/{teacher_id}/Update', 'update')->name('admin.update.teacher');
   Route::put('/SoftDelete/Teacher', 'delete')->name('admin.softdelete.teacher');
   Route::put('/SoftBulkDelete/Teachers', 'bulkDelete')->name('admin.softbulkdelete.teacher');
   Route::put('/Restore/Teacher', 'restore')->name('admin.restore.teacher');
   Route::put('/BulkRestore/Teachers', 'bulkRestore')->name('admin.bulkrestore.teacher');
   Route::delete('/PermentlyDelete/Teacher', 'destroy')->name('admin.destroy.teacher');
});

Route::controller(StudentController::class)->prefix('Students')->group(function() {
   Route::get('/', 'index')->name('admin.students');
   Route::get('/Fetch/Students', 'fetchStudents')->name('admin.students.fetch');
   Route::get('/Create', 'create')->name('admin.create.student');
   Route::post('/Create/Store', 'store')->name('admin.store.student');
   Route::get('/Edit/{student_id}', 'edit')->name('admin.edit.student');
   Route::put('/Edit/{student_id}/Update', 'update')->name('admin.update.student');
   Route::put('/SoftDelete/Student', 'delete')->name('admin.softdelete.student');
   Route::put('/SoftBulkDelete/Students', 'bulkDelete')->name('admin.softbulkdelete.student');
   Route::put('/Restore/Student', 'restore')->name('admin.restore.student');
   Route::put('/BulkRestore/Students', 'bulkRestore')->name('admin.bulkrestore.student');
   Route::delete('/PermentlyDelete/Student', 'destroy')->name('admin.destroy.student');
});

Route::controller(ManageClassController::class)->prefix('ManageClass')->group(function() {
   Route::get('/', 'index')->name('admin.manageclass');
   Route::get('/Fetch/Classes', 'fetchClasses')->name('admin.classes.fetch');
   Route::get('/Create', 'create')->name('admin.create.class');
   Route::post('/Create/Store', 'store')->name('admin.store.class');
   Route::get('/Edit/{class_management_id}', 'edit')->name('admin.edit.class');
   Route::put('/Edit/{class_management_id}/Updatee', 'update')->name('admin.update.class');
   Route::get('/View/{class_management_id}', 'view')->name('admin.view.class');
   Route::put('/SoftDelete/Class', 'delete')->name('admin.softdelete.class');
   Route::put('/SoftBulkDelete/Classes', 'bulkDelete')->name('admin.softbulkdelete.class');
   Route::put('/Restore/Class', 'restore')->name('admin.restore.class');
   Route::put('/BulkRestore/Classes', 'bulkRestore')->name('admin.bulkrestore.class');
   Route::delete('/PermentlyDelete/Class', 'destroy')->name('admin.destroy.class');
});

Route::controller(RoomController::class)->prefix('ManageRooms')->group(function() {
   Route::get('/', 'index')->name('admin.rooms');
   Route::get('/Fetch/Rooms', 'fetchRooms')->name('admin.rooms.fetch');
   Route::get('/Create', 'create')->name('admin.create.room');
   Route::post('/Create/Store', 'store')->name('admin.store.room');
   Route::get('/Edit/{room_id}', 'edit')->name('admin.edit.room');
   Route::put('/Edit/{room_id}/Update', 'update')->name('admin.update.room');
   Route::put('/SoftDelete/Room', 'delete')->name('admin.softdelete.room');
   Route::put('/SoftBulkDelete/Rooms', 'bulkDelete')->name('admin.softbulkdelete.room');
   Route::put('/Restore/Room', 'restore')->name('admin.restore.room');
   Route::put('/BulkRestore/Rooms', 'bulkRestore')->name('admin.bulkrestore.room');
   Route::delete('/PermentlyDelete/Room', 'destroy')->name('admin.destroy.room');
});

Route::controller(SubjectController::class)->prefix('Subjects')->group( function() {
   Route::get('/', 'index')->name('admin.subjects');
   Route::get('/Fetch/Subjects', 'fetchSubjects')->name('admin.subjects.fetch');
   Route::get('/Create', 'create')->name('admin.create.subject');
   Route::post('/Create/Store', 'store')->name('admin.store.subject');
   Route::get('/Edit/{subject_id}', 'edit')->name('admin.edit.subject');
   Route::put('/Edit/{subject_id}/Update', 'update')->name('admin.update.subject');
   Route::put('/SoftDelete/Subject', 'delete')->name('admin.softdelete.subject');
   Route::put('/SoftBulkDelete/Subjects', 'bulkDelete')->name('admin.softbulkdelete.subject');
   Route::put('/Restore/Subject', 'restore')->name('admin.restore.subject');
   Route::put('/BulkRestore/Subjects', 'bulkRestore')->name('admin.bulkrestore.subject');
   Route::delete('/PermentlyDelete/subject', 'destroy')->name('admin.destroy.subject');
});

Route::controller(UserController::class)->prefix('Users')->group(function() {
   Route::get('/', 'index')->name('admin.users');
   Route::get('/Fetch/Users', 'fetchUsers')->name('admin.users.fetch');
   Route::get('/Create', 'create')->name('admin.create.user');
   Route::post('/Create/Store', 'store')->name('admin.store.user');
   Route::get('/Edit/{user_id}', 'edit')->name('admin.edit.user');
   Route::put('/Edit/{user_id}/Update', 'update')->name('admin.update.user');
   Route::put('/SoftDelete/User', 'delete')->name('admin.softdelete.user');
   Route::put('/SoftBulkDelete/Users', 'bulkDelete')->name('admin.softbulkdelete.user');
   Route::put('/Restore/User', 'restore')->name('admin.restore.user');
   Route::put('/BulkRestore/Users', 'bulkRestore')->name('admin.bulkrestore.user');
   Route::delete('/PermentlyDelete/User', 'destroy')->name('admin.destroy.user');
});