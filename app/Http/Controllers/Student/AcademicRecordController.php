<?php

namespace App\Http\Controllers\Student;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\AcademicRecord;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AcademicRecordController extends Controller
{
    public function index()
    {
        $student = Student::find(Auth::user()->student->student_id);

        $academicRecords = AcademicRecord::query()
            ->where('academic_records.is_deleted', false)
            ->whereHas('classManage.students', function ($query) {
                $query->where('student_id', Auth::user()->student->student_id);
            })
            ->with([
                'classManage.subject',
                'classManage.teacher',
                'studentRecords' => function ($query) {
                    $query->where('student_id', Auth::user()->student->student_id);
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
            

        return view('student.academic_records.academic_records', compact('student', 'academicRecords'));
    }
}
