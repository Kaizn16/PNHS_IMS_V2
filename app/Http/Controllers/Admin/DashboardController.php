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

        $studentCounts = Student::query()
            ->selectRaw('enrollment_status, COUNT(*) as total')
            ->groupBy('enrollment_status')
            ->pluck('total', 'enrollment_status');

        $continuingStudents = $studentCounts['Continuing'] ?? 0;
        $graduatedStudents = $studentCounts['Graduated'] ?? 0;
        $stoppedStudents = $studentCounts['Stopped'] ?? 0;

        $teacherStatusCounts = Teacher::query()
            ->selectRaw('employment_status, COUNT(*) as total')
            ->groupBy('employment_status') 
            ->pluck('total', 'employment_status');
        
        $teacherEmploymentTypeCounts = Teacher::query()
            ->selectRaw('employment_type, COUNT(*) as total')
            ->groupBy('employment_type') 
            ->pluck('total', 'employment_type');

        $activeTeachers = $teacherStatusCounts['Active'] ?? 0;
        $inactiveTeachers = $teacherStatusCounts['Inactive'] ?? 0;

        $fullTimeTeachers = $teacherEmploymentTypeCounts['Full-Time'] ?? 0;
        $partTimeTeachers = $teacherEmploymentTypeCounts['Part-Time'] ?? 0;

        return view('admin.dashboard', 
        compact('totalUsers','totalStudents', 'totalTeachers', 'continuingStudents', 'graduatedStudents', 'stoppedStudents',
            'activeTeachers', 'inactiveTeachers', 'fullTimeTeachers', 'partTimeTeachers'));
    }
}
