<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});


Route::controller(ApiController::class)->group(function() {
    Route::get('api/nationalities', 'getNationalities')->name('get.nationalities');
    Route::get('api/provinces', 'getProvinces')->name('get.provinces');
    Route::get('api/municipalities', 'getMunicipalities')->name('get.municipalities');
    Route::get('api/barangays', 'getBarangays')->name('get.barangays');
    Route::get('api/subjects', 'getSubjects')->name('get.subjects');
    Route::get('api/suffixes', 'getSuffixes')->name('get.suffixes');
    Route::get('api/relationships', 'getRelationshipTypes')->name('get.relationshipTypes');
    Route::get('api/adviers', 'getAdvisers')->name('get.advisers');
    Route::get('api/strands', 'getStrands')->name('get.strands');
    Route::get('api/rooms', 'getRooms')->name('get.rooms');
    Route::get('api/teachers', 'getTeachers')->name('get.teachers');
    Route::get('api/teacher/subjects', 'getSubjectByTeacher')->name('get.teacher.subjects');
    Route::get('api/students', 'getStudents')->name('get.students');
    Route::get('api/class/students', 'getStudentsByClass')->name('get.students.class');
});