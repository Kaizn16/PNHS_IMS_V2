<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ClassStudent;
use Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $myTotalClasses = ClassStudent::where('student_id', Auth::user()->student->student_id)->count();

        return view('student.dashboard', compact('myTotalClasses'));
    }
}
