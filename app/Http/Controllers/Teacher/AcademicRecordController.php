<?php

namespace App\Http\Controllers\Teacher;

use Auth;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use App\Models\AcademicRecord;
use App\Models\ClassManagement;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AcademicRecordController extends Controller
{
    public function index()
    {
        $schoolYears = ClassManagement::select('school_year')->distinct()->get();

        return view('teacher.academic_records.academic_records', compact('schoolYears'));
    }

    public function fetchClasses(Request $request)
    {
        $search = $request->search;
        $pageSize = $request->input('pageSize', 10);
        $year_level = $request->year_level;
        $section = $request->section;
        $semester = $request->semester;
        $school_year = $request->school_year;

        $classes = ClassManagement::query()->with(['room', 'teacher', 'subject', 'schedules', 'students'])
            ->where('is_deleted', false)
            ->when($search, function ($query, $search) {
                return $query->where(function ($querySearch) use ($search) {
                    $querySearch->where('class_name', 'like', "%{$search}%")
                                ->orWhereHas('subject', function ($subjectQuery) use ($search) {
                                    $subjectQuery->where('subject_code', 'like', "%{$search}%");
                                })
                                ->orWhereHas('teacher', function ($teacherQuery) use ($search) {
                                    $teacherQuery->where('first_name', 'like', "%{$search}%")
                                    ->orWhere('middle_name', 'like', "%{$search}%")
                                    ->orWhere('last_name', 'like', "%{$search}%");
                                })
                                ->orWhereHas('room', function ($roomQuery) use ($search) {
                                    $roomQuery->where('room_name', 'like', "%{$search}%");
                                });
                });
            })
            ->when($year_level, function ($query, $year_level) {
                return $query->where('year_level', $year_level);
            })
            ->when($section, function ($query, $section) {
                return $query->where('section', $section);
            })
            ->when($semester, function ($query, $semester) {
                return $query->where('semester', $semester);
            })
            ->when($school_year, function ($query, $school_year) {
                return $query->where('school_year', $school_year);
            })
            ->when(Auth::user()->teacher->designation === 'Teacher', function ($query) {
                $query->where('teacher_id', Auth::user()->teacher->teacher_id);
            })
            ->when(Auth::user()->teacher->designation === 'Adviser', function ($query) {
                $teacherId = Auth::user()->teacher->teacher_id;
                $query->where(function ($q) use ($teacherId) {
                    $q->where('teacher_id', $teacherId)
                    ->orWhereHas('students.student', function ($studentQuery) use ($teacherId) {
                        $studentQuery->where('teacher_id', $teacherId);
                    });
                });
            })
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


    public function create()
    {
        $myClasses = ClassManagement::where('teacher_id', Auth::user()->teacher->teacher_id)->get();

        return view('teacher.academic_records.academic_record_form', compact('myClasses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_name' => 'required',
            'exam_type' => ['required', Rule::in(['Midterm', 'Final'])],
            'quarter_type' => [
                'required',
                Rule::when($request->exam_type === 'Midterm', Rule::in(['1st Quarter', '2nd Quarter'])),
                Rule::when($request->exam_type === 'Final', Rule::in(['3rd Quarter', '4th Quarter'])),
            ],
            'grades' => 'required|array',
            'grades.*' => 'required|numeric|min:0|max:100',
        ], [
            'grades' => 'Grade field is required.',
            'grades.*' => 'Grade field is required.',
        ]);

        $existingRecord = AcademicRecord::where('class_management_id', $request->class_name)
            ->where('semester', $request->semester)
            ->where('school_year', $request->school_year)
            ->whereHas('studentRecords', function($query) use ($request) {
                $query->where('exam_type', $request->exam_type)
                    ->where('quarter_type', $request->quarter_type);
            })
            ->exists();

        if ($existingRecord) {
            return redirect()->route('teacher.create.academic.record')->withInput()->with([
                'type' => 'error',
                'message' => 'Academic record already exists for this class, exam type, quarter, semester, and school year!'
            ]);
        }

        DB::beginTransaction();

        try {
            $academicRecord = AcademicRecord::create([
                'class_management_id' => $request->class_name,
                'semester' => $request->semester,
                'school_year' => $request->school_year,
            ]);

            $students = $request->input('students');
            $grades = $request->input('grades');

            foreach ($grades as $index => $grade) {
                StudentRecord::create([
                    'academic_record_id' => $academicRecord->academic_record_id,
                    'student_id' => $students[$index],
                    'exam_type' => $request->exam_type,
                    'quarter_type' => $request->quarter_type,
                    'grade' => $grade
                ]);
            }

            DB::commit();

            return redirect()->route('teacher.academic.records')->with([
                'type' => 'success',
                'message' => 'Academic Record successfully created!'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('teacher.create.academic.record')->withInput()->with([
                'type' => 'error',
                'message' => 'Unable to create academic record! '
            ]);
        }
    }

    public function edit(string $class_management_id)
    {
        $academicRecords = AcademicRecord::with(['studentRecords' => function($query) {
            $query->whereIn('exam_type', ['Midterm', 'Final']);
        }])
        ->where('class_management_id', $class_management_id)
        ->get();

        if($academicRecords->isEmpty()) 
        {
            return redirect()->route('teacher.view.academic.records', ['class_record' => $class_management_id])->with([
                'type' => 'warning',
                'message' => 'Record not found! '
            ]);
        }

        $myClasses = ClassManagement::where('teacher_id', Auth::user()->teacher->teacher_id)->get();

        return view('teacher.academic_records.academic_record_form', compact('myClasses', 'academicRecords'));
    }

    public function update(Request $request, string $class_management_id)
    {
        $request->validate([
            'class_name' => 'required',
            'exam_type' => ['required', Rule::in(['Midterm', 'Final'])],
            'quarter_type' => [
                'required',
                Rule::when($request->exam_type === 'Midterm', Rule::in(['1st Quarter', '2nd Quarter'])),
                Rule::when($request->exam_type === 'Final', Rule::in(['3rd Quarter', '4th Quarter'])),
            ],
            'students' => 'required|array',
            'grades' => 'required|array',
            'grades.*' => 'required|numeric|min:0|max:100',
        ], [
            'grades.*.required' => 'Grade field is required.',
            'grades.*.numeric' => 'Grade must be a number.',
            'grades.*.min' => 'Grade cannot be less than 0.',
            'grades.*.max' => 'Grade cannot be more than 100.',
        ]);
    
        DB::beginTransaction();
    
        try {
            $academicRecords = AcademicRecord::where('class_management_id', $class_management_id)
                ->pluck('academic_record_id')
                ->toArray();
    
            foreach ($request->students as $index => $student_id) {
                $grade = $request->grades[$index] ?? null;
    
                if ($grade === null || empty($academicRecords)) {
                    continue;
                }
    
                $studentRecord = StudentRecord::whereIn('academic_record_id', $academicRecords)
                    ->where('student_id', $student_id)
                    ->where('exam_type', $request->exam_type)
                    ->where('quarter_type', $request->quarter_type)
                    ->first();
    
                if ($studentRecord) {
                    $studentRecord->update(['grade' => $grade]);
                } else {
                    StudentRecord::create([
                        'academic_record_id' => reset($academicRecords),
                        'student_id' => $student_id,
                        'exam_type' => $request->exam_type,
                        'quarter_type' => $request->quarter_type,
                        'grade' => $grade,
                    ]);
                }
            }
    
            DB::commit();
    
            return redirect()->route('teacher.view.academic.records', ['class_record' => $class_management_id])->with([
                'type' => 'success',
                'message' => 'Academic records saved successfully!',
            ]);
    
        } catch (\Exception $e) {
            DB::rollBack();
    
            return redirect()->route('teacher.view.academic.records', ['class_record' => $class_management_id])->with([
                'type' => 'error',
                'message' => 'Unable to save academic records! Error: ',
            ]);
        }
    }
    

    public function view(string $class_record)
    {
        $classRecord = ClassManagement::find($class_record);

        if(!$classRecord) {
            return redirect()->route('teacher.academic.records')->with([
                'type' => 'warning',
                'message' => 'Class Record Not Found!'
            ]);
        }

        $schoolYears = ClassManagement::select('school_year')->distinct()->get();
        $teacher = Teacher::where('user_id', Auth::user()->user_id)->first();

        return view('teacher.academic_records.academic_record_view', compact('schoolYears', 'classRecord', 'teacher'));
    }

    public function fetchAcademicRecords(Request $request)
    {
        $search = $request->search;
        $pageSize = $request->input('pageSize', 10);
        $classManagementId = $request->class_record;
        
        $academicRecords = AcademicRecord::query()
            ->where('academic_records.is_deleted', false)
            ->when($classManagementId, function ($query, $classManagementId) {
                $query->where('academic_records.class_management_id', $classManagementId);
            })
            ->when($search, function ($query) use ($search) {
                $query->whereHas('studentRecords.student', function ($studentQuery) use ($search) {
                    $studentQuery->where(function ($nameQuery) use ($search) {
                        $nameQuery->where(DB::raw("CONCAT(students.first_name, ' ', students.last_name)"), 'like', "%{$search}%")
                                  ->orWhere('students.first_name', 'like', "%{$search}%")
                                  ->orWhere('students.last_name', 'like', "%{$search}%");
                    });
                });
            })
            ->with([
                'classManage',
                'studentRecords' => function ($query) use ($search) {
                    $query->with(['student:student_id,first_name,last_name,middle_name']);
                    if ($search) {
                        $query->whereHas('student', function ($studentQuery) use ($search) {
                            $studentQuery->where(function ($nameQuery) use ($search) {
                                $nameQuery->where(DB::raw("CONCAT(students.first_name, ' ', students.last_name)"), 'like', "%{$search}%")
                                          ->orWhere('students.first_name', 'like', "%{$search}%")
                                          ->orWhere('students.last_name', 'like', "%{$search}%");
                            });
                        });
                    }
                }
            ])
            ->paginate($pageSize);
        
        $academicRecords->each(function ($record) {
            $record->studentRecords = $record->studentRecords->groupBy('student_id')->map(function ($records) {
                return $records->map(function ($studentRecord) {
                    return $studentRecord;
                });
            });
        });


        return response()->json([
            'data' => $academicRecords->items(),
            'current_page' => $academicRecords->currentPage(),
            'last_page' => $academicRecords->lastPage(),
            'prev_page_url' => $academicRecords->previousPageUrl(),
            'next_page_url' => $academicRecords->nextPageUrl(),
            'total_pages' => $academicRecords->lastPage(),
            'per_page' => $academicRecords->perPage(),
        ]);
    }
}
