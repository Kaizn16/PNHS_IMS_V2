<?php

namespace App\Http\Controllers\Teacher;

use App\Models\ClassStudent;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\ClassManagement;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $manageClass = ClassManagement::where('teacher_id', Auth::user()->teacher->teacher_id)->get();
        $totalStudents = ClassStudent::whereIn('class_management_id', $manageClass->pluck('class_management_id'))->count();

        $totalClass = ClassManagement::where('teacher_id', Auth::user()->teacher->teacher_id)->count();
        
        return view('teacher.dashboard', compact('totalStudents', 'totalClass'));
    }
}
