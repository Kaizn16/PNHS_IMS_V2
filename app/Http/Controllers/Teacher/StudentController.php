<?php

namespace App\Http\Controllers\Teacher;

use App\Models\User;
use App\Models\Strand;

//Models
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
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

    public function create()
    {
        $teacher = Teacher::where('user_id',Auth::user()->user_id)->first();

        return view('teacher.students.student_form', compact('teacher'));
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            // Personal Information
            'lrn' => 'required|unique:students,lrn',
            'first_name' => 'required',
            'middle_name' => 'nullable',
            'last_name' => 'required',
            'suffix' => 'nullable',
            'sex' => 'required|in:Male,Female',
            'date_of_birth' => 'required|date',
            'place_of_birth' => 'required', 
            'nationality' => 'required',
            // Address Information
            'province' => 'required|exists:provinces,province_id',
            'municipality' => 'required|exists:municipalities,municipality_id',
            'barangay' => 'required|exists:barangays,barangay_id',
            'street_address' => 'nullable',
            // Contact Information
            'contact_no' => 'required|phone:PH',
            'email' => 'required|email|unique:users,email',
            // Father Information
            'father_first_name' => 'nullable',
            'father_middle_name' => 'nullable',
            'father_last_name' => 'nullable',
            'father_occupation' => 'nullable',
            'father_contact_no' => 'nullable',
            // Mother Information
            'mother_first_name' => 'nullable',
            'mother_middle_name' => 'nullable',
            'mother_last_name' => 'nullable',
            'mother_occupation' => 'nullable',
            'mother_contact_no' => 'nullable',
            // Guardian Information
            'guardian_first_name' => 'nullable',
            'guardian_middle_name' => 'nullable',
            'guardian_last_name' => 'nullable',
            'guardian_relation' => 'nullable',
            'guardian_occupation' => 'nullable',
            'guardian_contact_no' => 'nullable',
            // Academic Information
            'previous_school_name' => 'required',
            'birth_certificate' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5012',
            'report_card' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5012',
            'teacher' => 'required|exists:teachers,teacher_id',
            'strand' => 'required',
            'current_year_level' => 'required',
            'section' => 'required',
            'school_year' => 'required',
            'enrollment_status' => 'required|in:Continuing,Graduate,Stopped',
            // Account Information
            'username' => 'required|string|min:6',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
        ],[
            'contact_no' => 'Invalid Contact Number',
        ]);

        
        $uploadedFiles = [];

        try {
            if ($request->hasFile('birth_certificate')) {
                $file = $request->file('birth_certificate');
                $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $filenameToStore = $filename . '_' . time() . '.' . $extension;
                $file->storeAs('BirthCertificate', $filenameToStore);
                $uploadedFiles[] = storage_path("app/public/BirthCertificate/" . $filenameToStore);
                $validated['birth_certificate'] = $filenameToStore;
            }

            if ($request->hasFile('report_card')) {
                $file = $request->file('report_card');
                $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $filenameToStore = $filename . '_' . time() . '.' . $extension;
                $file->storeAs('ReportCard', $filenameToStore);
                $uploadedFiles[] = storage_path("app/public/ReportCard/" . $filenameToStore);
                $validated['report_card'] = $filenameToStore;
            }

            DB::beginTransaction();

            $user = User::create([
                'username' => $request->username,
                'name' => trim("$request->first_name $request->middle_name $request->last_name"),
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => 3, // Student
            ]);

            Student::create([
                'user_id' => $user->user_id,
                'lrn' => $request->lrn,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'sex' => $request->sex,
                'date_of_birth' => $request->date_of_birth,
                'place_of_birth' => $request->place_of_birth,
                'nationality' => $request->nationality,
                'province_id' => $request->province,
                'municipality_id' => $request->municipality,
                'barangay_id' => $request->barangay,
                'street_address' => $request->street_address,
                'contact_no' => $request->contact_no,
                'email' => $request->email,
                'father_first_name' => $request->father_first_name ?? null,
                'father_middle_name' => $request->father_middle_name ?? null,
                'father_last_name' => $request->father_last_name ?? null,
                'father_occupation' => $request->father_occupation ?? null,
                'father_contact_no' => $request->father_contact_no ?? null,
                'mother_first_name' => $request->mother_first_name ?? null,
                'mother_middle_name' => $request->mother_middle_name ?? null,
                'mother_last_name' => $request->mother_last_name ?? null,
                'mother_occupation' => $request->mother_occupation ?? null,
                'mother_contact_no' => $request->mother_contact_no ?? null,
                'guardian_first_name' => $request->guardian_first_name ?? null,
                'guardian_middle_name' => $request->guardian_middle_name ?? null,
                'guardian_last_name' => $request->guardian_last_name ?? null,
                'guardian_relation' => $request->guardian_relation ?? null,
                'guardian_occupation' => $request->guardian_occupation ?? null,
                'guardian_contact_no' => $request->guardian_contact_no ?? null,
                'previous_school_name' => $request->previous_school_name,
                'birth_certificate' => $validated['birth_certificate'],
                'report_card' => $validated['report_card'],
                'teacher_id' => $request->teacher,
                'strand_id' => $request->strand,
                'current_year_level' => $request->current_year_level,
                'section' => $request->section,
                'school_year' => $request->school_year,
                'enrollment_status' => $request->enrollment_status,
            ]);

            DB::commit();

            return redirect()->route('teacher.create.student')->with([
                'type' => 'success',
                'message' => 'Student Created Successfully!',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            foreach ($uploadedFiles as $filePath) {
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            return redirect()->route('teacher.create.student')->withInput()->with([
                'type' => 'error',
                'message' => 'Unable to create student!',
            ]);
        }
    }

public function edit(string $student_id)
    {
        $teacher = Teacher::where('user_id',Auth::user()->user_id)->first();
        $student = Student::find($student_id)->first();

        return view('teacher.students.student_form', compact('teacher','student'));
    }

    public function update(Request $request, string $student_id)
    {

        $student = Student::find($student_id);

        $validated = $request->validate([
            // Personal Information
            'lrn' => 'required|unique:students,lrn,' . $student_id . ',student_id',
            'first_name' => 'required',
            'middle_name' => 'nullable',
            'last_name' => 'required',
            'suffix' => 'nullable',
            'sex' => 'required|in:Male,Female',
            'date_of_birth' => 'required|date',
            'place_of_birth' => 'required', 
            'nationality' => 'required',
            // Address Information
            'province' => 'required|exists:provinces,province_id',
            'municipality' => 'required|exists:municipalities,municipality_id',
            'barangay' => 'required|exists:barangays,barangay_id',
            'street_address' => 'nullable',
            // Contact Information
            'contact_no' => 'required|phone:PH',
            'email' => 'required|email|unique:students,email,' . $student_id . ',student_id',
            // Father Information
            'father_first_name' => 'nullable',
            'father_middle_name' => 'nullable',
            'father_last_name' => 'nullable',
            'father_occupation' => 'nullable',
            'father_contact_no' => 'nullable',
            // Mother Information
            'mother_first_name' => 'nullable',
            'mother_middle_name' => 'nullable',
            'mother_last_name' => 'nullable',
            'mother_occupation' => 'nullable',
            'mother_contact_no' => 'nullable',
            // Guardian Information
            'guardian_first_name' => 'nullable',
            'guardian_middle_name' => 'nullable',
            'guardian_last_name' => 'nullable',
            'guardian_relation' => 'nullable',
            'guardian_occupation' => 'nullable',
            'guardian_contact_no' => 'nullable',
            // Academic Information
            'previous_school_name' => 'required',
            'birth_certificate' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5012',
            'report_card' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5012',
            'teacher' => 'required|exists:teachers,teacher_id',
            'strand' => 'required',
            'current_year_level' => 'required',
            'section' => 'required',
            'school_year' => 'required',
            'enrollment_status' => 'required|in:Continuing,Graduate,Stopped',
        ],[
            'contact_no' => 'Invalid Contact Number',
        ]);

        
        $uploadedFiles = [];

        try {
            if ($request->hasFile('birth_certificate')) {
                if ($student->birth_certificate) {
                    Storage::disk('public')->delete($student->birth_certificate);
                }
                $validated['birth_certificate'] = $request->file('birth_certificate')->store('BirthCertificate', 'public');
            } else {
                $validated['birth_certificate'] = $student->birth_certificate;
            }
    
            if ($request->hasFile('report_card')) {
                if ($student->report_card) {
                    Storage::disk('public')->delete($student->report_card);
                }
                $validated['report_card'] = $request->file('report_card')->store('ReportCard', 'public');
            } else {
                $validated['report_card'] = $student->report_card;
            }
            

            DB::beginTransaction();

            $student->update([
                'lrn' => $validated['lrn'],
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'],
                'last_name' => $validated['last_name'],
                'suffix' => $validated['suffix'] ?? null,
                'sex' => $validated['sex'],
                'date_of_birth' => $validated['date_of_birth'],
                'place_of_birth' => $validated['place_of_birth'],
                'nationality' => $validated['nationality'],
                'province_id' => $validated['province'],
                'municipality_id' => $validated['municipality'],
                'barangay_id' => $validated['barangay'],
                'street_address' => $validated['street_address'],
                'contact_no' => $validated['contact_no'],
                'email' => $validated['email'],
                'father_first_name' => $validated['father_first_name'],
                'father_middle_name' => $validated['father_middle_name'],
                'father_last_name' => $validated['father_last_name'],
                'father_occupation' => $validated['father_occupation'],
                'father_contact_no' => $validated['father_contact_no'],
                'mother_first_name' => $validated['mother_first_name'],
                'mother_middle_name' => $validated['mother_middle_name'],
                'mother_last_name' => $validated['mother_last_name'],
                'mother_occupation' => $validated['mother_occupation'],
                'mother_contact_no' => $validated['mother_contact_no'],
                'guardian_first_name' => $validated['guardian_first_name'],
                'guardian_middle_name' => $validated['guardian_middle_name'],
                'guardian_last_name' => $validated['guardian_last_name'],
                'guardian_relation' => $validated['guardian_relation'] ?? null,
                'guardian_occupation' => $validated['guardian_occupation'],
                'guardian_contact_no' => $validated['guardian_contact_no'],
                'previous_school_name' => $validated['previous_school_name'],
                'birth_certificate' => $validated['birth_certificate'],
                'report_card' => $validated['report_card'],
                'teacher_id' => $validated['teacher'],
                'strand_id' => $validated['strand'],
                'current_year_level' => $validated['current_year_level'],
                'section' => $validated['section'],
                'school_year' => $validated['school_year'],
                'enrollment_status' => $validated['enrollment_status'],
            ]);

            $user = User::find($student->user_id);

            $user->update([
                'name' => trim("{$validated['first_name']} {$validated['middle_name']} {$validated['last_name']}"),
                'email' => $validated['email']
            ]);


            DB::commit();

            return redirect()->route('teacher.students')->with([
                'type' => 'success',
                'message' => 'Student Updated Successfully!',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            foreach ($uploadedFiles as $filePath) {
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            return redirect()->route('teacher.edit.student', compact('student_id'))->withInput()->with([
                'type' => 'error',
                'message' => 'Unable to updated student!' . $e->getMessage(),
            ]);
        }
    }

    public function delete(Request $request)
    {
        $student = Student::find($request->student_id)->first();
        $student->is_deleted = true;

        if($student->save())
        {
            User::where('user_id', $student->user_id)->update(['is_deleted' => true]);
            return response()->json(['success' => true, 'message' => 'Selected record have been deleted.']);
        }

        return response()->json(['success' => false, 'message' => 'No record were selected for deletion.']);

    }

    public function destroy(Request $request)
    {
        $student = Student::find($request->student_id);

        if($student->delete())
        {
            return response()->json(['success' => true, 'message' => 'Selected student permanently deleted.']);
        }

        return response()->json(['success' => false, 'message' => 'No record were selected for deletion.']);
    }

    public function bulkDelete(Request $request)
    {
        $student_ids = $request->input('student_ids');

        if (empty($student_ids)) {
            return response()->json(['success' => false, 'message' => 'No records were selected for deletion.']);
        }

        $students = Student::whereIn('student_id', $student_ids)->with('user')->get();

        $softDeleteStudents = [];
        $permanentDeleteStudents = [];
        $softDeleteUsers = [];
        $permanentDeleteUsers = [];

        foreach ($students as $student) {
            if ($student->is_deleted == 1) {
                $permanentDeleteStudents[] = $student->student_id;
                if ($student->user) {
                    $permanentDeleteUsers[] = $student->user_id;
                }
            } else {
                $softDeleteStudents[] = $student->student_id;
                if ($student->user) {
                    $softDeleteUsers[] = $student->user_id;
                }
            }
        }

        Student::whereIn('student_id', $softDeleteStudents)->update(['is_deleted' => true]);
        User::whereIn('user_id', $softDeleteUsers)->update(['is_deleted' => true]);

        Student::whereIn('student_id', $permanentDeleteStudents)->delete();
        User::whereIn('user_id', $permanentDeleteUsers)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Selected records and associated users have been deleted permanently.',
        ]);
    }

    public function bulkRestore(Request $request)
    {
        $student_ids = $request->input('student_ids');

        if (empty($student_ids)) {
            return response()->json(['success' => false, 'message' => 'No records were selected for deletion.']);
        }

        $students = Student::whereIn('student_id', $student_ids)->with('user')->get();

        $restoreStudents = [];
        $restoreUsers = [];

        foreach ($students as $student) {
            if ($student->is_deleted == 1) {
                $restoreStudents[] = $student->student_id;
                if ($student->user) {
                    $restoreUsers[] = $student->user_id;
                }
            }
        }

        Student::whereIn('student_id', $restoreStudents)->update(['is_deleted' =>false]);
        User::whereIn('user_id', $restoreUsers)->update(['is_deleted' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Selected records and associated users have been restore successfully.',
        ]);

    }

    public function restore(Request $request)
    {
        $student = Student::find($request->student_id);
        $user = User::find($student->user_id);
        $student->is_deleted = false;
        $user->is_deleted = false;


        if($student->save() && $user->save()) 
        {
            return response()->json([
                'success' => true,
                'message' => 'Selected records and associated user have been restore successfully.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Unable to restore selected records and its associated user.',
        ]);

    }
}