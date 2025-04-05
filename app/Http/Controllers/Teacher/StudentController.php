<?php

namespace App\Http\Controllers\Teacher;

use App\Models\User;
use App\Models\Strand;

//Models
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Models\AcademicRecord;
use App\Models\ClassManagement;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index()
    {
        $teacher = Teacher::where('user_id',Auth::user()->user_id)->first();
        $strands = Strand::all();
        $schoolYears = Student::select('school_year')->distinct()->get();

        return view('teacher.students.students', compact('teacher', 'strands', 'schoolYears'));
    }

    public function fetchStudents(Request $request)
    {
        $search = $request->search;
        $pageSize = $request->input('pageSize', 10);
        $sex = $request->sex;
        $year_level = $request->year_level;
        $section = $request->section;
        $strand = $request->strand;
        $school_year = $request->school_year;
        $enrollment_status = $request->enrollment_status;
        $is_deleted = filter_var($request->input('is_deleted'), FILTER_VALIDATE_BOOLEAN);

        $teachers = Student::query()->with(['province', 'municipality', 'barangay', 'strand', 'teacher'])
            ->where('is_deleted', $is_deleted)
            ->when($search, function ($query, $search) {
                return $query->where(function ($querySearch) use ($search) {
                    $querySearch->where('first_name', 'like', "%{$search}%")
                        ->orWhere('middle_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                });
            })
            ->when($sex, fn($query) => $query->where('sex', $sex))
            ->when($year_level, fn($query) => $query->where('current_year_level', $year_level))
            ->when($section, fn($query) => $query->where('section', $section))
            ->when($strand, fn($query) => $query->where('strand_id', $strand))
            ->when($school_year, fn($query) => $query->where('school_year', $school_year))
            ->when($enrollment_status, fn($query) => $query->where('enrollment_status', $enrollment_status))
            ->when(Auth::user()->teacher->designation === 'Adviser', function ($query) {
                $query->where('teacher_id', Auth::user()->teacher->teacher_id);
            })
            ->when(Auth::user()->teacher->designation === 'Teacher', function ($query) {
                $query->whereHas('classStudents', function ($q) {
                    $q->whereIn('class_management_id', ClassManagement::where('teacher_id', Auth::user()->teacher->teacher_id)
                        ->pluck('class_management_id'));
                });
            })
            ->paginate($pageSize);


        return response()->json([
            'data' => $teachers->items(),
            'current_page' => $teachers->currentPage(),
            'last_page' => $teachers->lastPage(),
            'prev_page_url' => $teachers->previousPageUrl(),
            'next_page_url' => $teachers->nextPageUrl(),
            'total_pages' => $teachers->lastPage(),
            'per_page' => $teachers->perPage(),
        ]);
    }

    public function show(string $student_id)
    {
        $student = Student::find($student_id);

        $academicRecords = AcademicRecord::query()
            ->where('academic_records.is_deleted', false)
            ->whereHas('classManage.students', function ($query) use ($student) {
                $query->where('student_id', $student->student_id);
            })
            ->with([
                'classManage.subject',
                'classManage.teacher',
                'studentRecords' => function ($query) use ($student) {
                    $query->where('student_id', $student->student_id);
                }
            ])
            ->orderByDesc('school_year')
            ->orderBy('semester')
            ->get()
            ->groupBy('school_year')
            ->map(function ($records) {
                return $records->groupBy('semester')->map(function ($semesterRecords) {
                    return $semesterRecords->groupBy('classManage.subject.subject_id')->map(function ($subjectRecords) {
                        return [
                            'subject' => $subjectRecords->first()->classManage->subject,
                            'teacher' => $subjectRecords->first()->classManage->teacher,
                            'year_level' => $subjectRecords->first()->classManage->year_level,
                            'records' => $subjectRecords->flatMap->studentRecords
                        ];
                    });
                });
            });

        if($student) {
            return view('teacher.students.view_student', compact('student', 'academicRecords'));
        }

        return redirect()->route('teacher.students')->with([
            'type' => 'warning',
            'message' => 'Student not found!',
        ]); 
    }
}