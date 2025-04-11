<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Models\ClassManagement;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ManageClassController extends Controller
{
    public function index()
    {
        $schoolYears = ClassManagement::select('school_year')->distinct()->get();

        return view('student.manage_class.manage_class', compact('schoolYears'));   
    }

    public function fetchClasses(Request $request)
    {
        $search = $request->search;
        $pageSize = $request->input('pageSize', 10);
        $semester = $request->semester;
        $school_year = $request->school_year;

        $studentId = Auth::user()->student->student_id;

        $classes = ClassManagement::query()->with(['room', 'teacher', 'subject', 'schedules', 'students'])
            ->where('is_deleted', false)
            ->whereHas('students', function ($query) use ($studentId) {
                $query->where('student_id', $studentId);
            })
            ->when($search, function ($query, $search) {
                return $query->where(function ($querySearch) use ($search) {
                    $querySearch->where('class_name', 'like', "%{$search}%");
                });
            })
            ->when($semester, function ($query, $semester) {
                return $query->where('semester', $semester);
            })
            ->when($school_year, function ($query, $school_year) {
                return $query->where('school_year', $school_year);
            })
            ->orderBy('year_level')
            ->orderBy('section')
            ->orderBy('semester')
            ->orderBy('school_year')
            ->paginate($pageSize);

        return response()->json([
            'data' => $classes->items(),
            'current_page' => $classes->currentPage(),
            'last_page' => $classes->lastPage(),
            'prev_page_url' => $classes->previousPageUrl(),
            'next_page_url' => $classes->nextPageUrl(),
            'total_pages' => $classes->lastPage(),
            'per_page' => $classes->perPage(),
        ]);
    }
}
