<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::where('is_deleted', false)->count();
        $totalStudents = Student::where('is_deleted', false)->count();
        $totalTeachers = Teacher::where('is_deleted', false)->count();

        return view('admin.dashboard', compact('totalUsers', 'totalStudents', 'totalTeachers'));
    }

    public function studentsChart()
    {
        $studentCounts = Student::query()
            ->selectRaw('enrollment_status, COUNT(*) as total')
            ->groupBy('enrollment_status')
            ->pluck('total', 'enrollment_status');

        return response()->json([
            'Continuing' => $studentCounts['Continuing'] ?? 0,
            'Graduated' => $studentCounts['Graduated'] ?? 0,
            'Stopped' => $studentCounts['Stopped'] ?? 0
        ]);
    }
}
