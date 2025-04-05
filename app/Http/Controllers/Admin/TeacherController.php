<?php

namespace App\Http\Controllers\Admin;

use App\Models\Teacher;
use App\Models\TeacherSubject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TeacherController extends Controller
{
    public function index() 
    {
        return view('admin.teachers.teachers');
    }

    public function fetchTeachers(Request $request)
    {
        $search = $request->search;
        $pageSize = $request->input('pageSize', 10);
        $role = $request->role;
        $sex = $request->sex;
        $status = $request->status;
        $employment_type = $request->employment_type;
        $is_deleted = filter_var($request->input('is_deleted'), FILTER_VALIDATE_BOOLEAN);

        $teachers = Teacher::query()->with(['province', 'municipality', 'barangay', 'subjects'])->where('is_deleted', $is_deleted)
            ->when($search, function ($query, $search) {
                return $query->where(function ($querySearch) use ($search) {
                    $querySearch->where('first_name', 'like', "%{$search}%")
                    ->orWhere('middle_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
                });
            })
            ->when($role, function ($query, $role) {
                return $query->where('designation', $role);
            })
            ->when($sex, function ($query, $sex) {
                return $query->where('sex', $sex);
            })
            ->when($status, function ($query, $status) {
                return $query->where('employment_status', $status);
            })
            ->when($employment_type, function ($query, $employment_type) {
                return $query->where('employment_type', $employment_type);
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
        return view('admin.teachers.teacher_form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'middle_name' => 'nullable',
            'last_name' => 'required',
            'sex' => 'required|in:Male,Female',
            'date_of_birth' => 'required|date',
            'civil_status' => ['required', Rule::in(User::CIVIL_STATUSES)],
            'nationality' => 'required',
            'province' => 'required|exists:provinces,province_id',
            'municipality' => 'required|exists:municipalities,municipality_id',
            'barangay' => 'required|exists:barangays,barangay_id',
            'street_address' => 'nullable',
            'account_role' => 'required|in:Adviser,Teacher',
            'subject_handle' => 'required|array|min:1',
            'employment_type' => 'required|in:Full-Time,Part-Time',
            'date_hired' => 'required|date',
            'employment_status' => 'nullable',
            'username' => 'required|string|min:6',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);


        DB::beginTransaction();

        try {
            $user = User::create([
                'username' => $request->username,
                'name' => $request->first_name . ' ' . $request->middle_name . ' ' . $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'default_password' => $request->password,
                'role_id' => 2, //TEACHER
            ]);

            $teacher = Teacher::create([
                'user_id' => $user->user_id,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'sex' => $request->sex,
                'date_of_birth' => $request->date_of_birth,
                'civil_status' => $request->civil_status,
                'nationality' => $request->nationality,
                'province_id' => $request->province,
                'municipality_id' => $request->municipality,
                'barangay_id' => $request->barangay,
                'street_address' => $request->street_address,
                'designation' => $request->account_role,
                'employment_type' => $request->employment_type,
                'date_hired' => $request->date_hired,
                'employment_status' => $request->employment_status,
            ]);

            if (!empty($request->subject_handle)) {
                foreach ($request->subject_handle as $subject) {
                    TeacherSubject::create([
                        'teacher_id' => $teacher->teacher_id,
                        'subject_id' => $subject,
                    ]);
                }
            }            

            DB::commit();

            return redirect()->route('admin.create.teacher')->with([
                'type' => 'success',
                'message' => 'Teacher Created Successfully!',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('admin.create.teacher')->with([
                'type' => 'error',
                'message' => 'Unable to create teacher!',
            ]);
        }
    }

    public function edit(string $teacher_id)
    {
        $teacher = Teacher::find($teacher_id);
        $subjects = TeacherSubject::where('teacher_id', $teacher->teacher_id)->get();

        return view('admin.teachers.teacher_form', compact('teacher', 'subjects'));
    }

    public function update(Request $request, string $teacher_id)
    {
        $request->validate([
            'first_name' => 'required',
            'middle_name' => 'nullable',
            'last_name' => 'required',
            'sex' => 'required|in:Male,Female',
            'date_of_birth' => 'required|date',
            'civil_status' => ['required', Rule::in(User::CIVIL_STATUSES)],
            'nationality' => 'required',
            'province' => 'required|exists:provinces,province_id',
            'municipality' => 'required|exists:municipalities,municipality_id',
            'barangay' => 'required|exists:barangays,barangay_id',
            'street_address' => 'nullable',
            'account_role' => 'required|in:Adviser,Teacher',
            'subject_handle' => 'required|array|min:1',
            'employment_type' => 'required|in:Full-Time,Part-Time',
            'date_hired' => 'required|date',
            'employment_status' => 'nullable',
        ]);

        DB::beginTransaction();

        try {
            
            $teacher = Teacher::find($teacher_id);

            $teacher->update([
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'sex' => $request->sex,
                'date_of_birth' => $request->date_of_birth,
                'civil_status' => $request->civil_status,
                'nationality' => $request->nationality,
                'province_id' => $request->province,
                'municipality_id' => $request->municipality,
                'barangay_id' => $request->barangay,
                'street_address' => $request->street_address,
                'designation' => $request->account_role,
                'employment_type' => $request->employment_type,
                'date_hired' => $request->date_hired,
                'employment_status' => $request->employment_status,
            ]);

            TeacherSubject::where('teacher_id', $teacher_id)->delete();
            foreach ($request->subject_handle as $subject) {
                TeacherSubject::create([
                    'teacher_id' => $teacher_id,
                    'subject_id' => $subject,
                ]);
            }

            $user = User::find($teacher->user_id);

            $user->update([
                'name' => trim("{$request->first_name} {$request->middle_name} {$request->last_name}"),
            ]);

            DB::commit();

            return redirect()->route('admin.edit.teacher', $teacher_id)->with([
                'type' => 'success',
                'message' => 'Teacher Updated Successfully!',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('admin.edit.teacher', $teacher_id)->with([
                'type' => 'error',
                'message' => 'Unable to update teacher!',
            ]);
        }
    }

    public function delete(Request $request)
    {
        $teacher = Teacher::find($request->teacher_id);
        $teacher->is_deleted = true;

        if($teacher->save())
        {
            User::where('user_id', $teacher->user_id)->update(['is_deleted' => true]);
            return response()->json(['success' => true, 'message' => 'Selected record have been deleted.']);
        }

        return response()->json(['success' => false, 'message' => 'No record were selected for deletion.']);

    }

    public function destroy(Request $request)
    {
        $teacher = Teacher::find($request->teacher_id);

        if($teacher->delete())
        {
            return response()->json(['success' => true, 'message' => 'Selected teacher permanently deleted.']);
        }

        return response()->json(['success' => false, 'message' => 'No record were selected for deletion.']);
    }

    public function bulkDelete(Request $request)
    {
        $teacher_ids = $request->input('teacher_ids');

        if (empty($teacher_ids)) {
            return response()->json(['success' => false, 'message' => 'No records were selected for deletion.']);
        }

        $teachers = Teacher::whereIn('teacher_id', $teacher_ids)->with('user')->get();

        $softDeleteTeachers = [];
        $permanentDeleteTeachers = [];
        $softDeleteUsers = [];
        $permanentDeleteUsers = [];

        foreach ($teachers as $teacher) {
            if ($teacher->is_deleted == 1) {
                $permanentDeleteTeachers[] = $teacher->teacher_id;
                if ($teacher->user) {
                    $permanentDeleteUsers[] = $teacher->user_id;
                }
            } else {
                $softDeleteTeachers[] = $teacher->teacher_id;
                if ($teacher->user) {
                    $softDeleteUsers[] = $teacher->user_id;
                }
            }
        }

        Teacher::whereIn('teacher_id', $softDeleteTeachers)->update(['is_deleted' => true]);
        User::whereIn('user_id', $softDeleteUsers)->update(['is_deleted' => true]);

        Teacher::whereIn('teacher_id', $permanentDeleteTeachers)->delete();
        User::whereIn('user_id', $permanentDeleteUsers)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Selected records and associated users have been deleted permanently.',
        ]);
    }

    public function bulkRestore(Request $request)
    {
        $teacher_ids = $request->input('teacher_ids');

        if (empty($teacher_ids)) {
            return response()->json(['success' => false, 'message' => 'No records were selected for deletion.']);
        }

        $teachers = Teacher::whereIn('teacher_id', $teacher_ids)->with('user')->get();

        $restoreTeachers = [];
        $restoreUsers = [];

        foreach ($teachers as $teacher) {
            if ($teacher->is_deleted == 1) {
                $restoreTeachers[] = $teacher->teacher_id;
                if ($teacher->user) {
                    $restoreUsers[] = $teacher->user_id;
                }
            }
        }

        Teacher::whereIn('teacher_id', $restoreTeachers)->update(['is_deleted' =>false]);
        User::whereIn('user_id', $restoreUsers)->update(['is_deleted' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Selected records and associated users have been restore successfully.',
        ]);

    }

    public function restore(Request $request)
    {
        $teacher = Teacher::find($request->teacher_id);
        $user = User::find($teacher->user_id);
        $teacher->is_deleted = false;
        $user->is_deleted = false;


        if($teacher->save() && $user->save()) 
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