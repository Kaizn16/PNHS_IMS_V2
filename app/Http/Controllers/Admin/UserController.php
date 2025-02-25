<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.users.users');
    }

    public function fetchUsers(Request $request)
    {
        $search = $request->search;
        $pageSize = $request->input('pageSize', 10);
        $role = $request->role;
        $is_deleted = filter_var($request->input('is_deleted'), FILTER_VALIDATE_BOOLEAN);

        $subjects = User::query()->with('role')->where('is_deleted', $is_deleted)
            ->when($search, function ($query, $search) {
                return $query->where(function ($querySearch) use ($search) {
                    $querySearch->where('username', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($role, function ($query, $role) {
                return $query->where('role_id', $role);
            })
            ->paginate($pageSize);

        return response()->json([
            'data' => $subjects->items(),
            'current_page' => $subjects->currentPage(),
            'last_page' => $subjects->lastPage(),
            'prev_page_url' => $subjects->previousPageUrl(),
            'next_page_url' => $subjects->nextPageUrl(),
            'total_pages' => $subjects->lastPage(),
            'per_page' => $subjects->perPage(),
        ]);
    }

    public function create()
    {
        return view('admin.users.user_form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => $request->password,
            'role_id' => 1, // Default Admin
        ]);

        if($user) {
            return redirect()->route('admin.users')->with([
                'type' => 'success',
                'message' => 'User successfully created!'
            ]);
        }

        return redirect()->route('admin.room.user')->withInput()->with([
            'type' => 'error',
            'message' => 'Unable to created user!'
        ]);
    }

    public function edit(string $user_id)
    {
        $user = User::find($user_id);

        return view('admin.users.user_form', compact('user'));
    }

    public function update(Request $request, string $user_id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $user_id . ',user_id',
            'username' => 'required|unique:users,username,'  . $user_id . ',user_id',
            'password' => 'nullable|min:6|confirmed',
            'password_confirmation' => 'nullable'
        ]);
        
        $user = User::find($user_id);
        
        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
        ];
        
        if ($request->filled('password')) {
            $updateData['password'] = $request->password;
        }
        
        if($user->update($updateData)) {
            return redirect()->route('admin.users')->with([
                'type' => 'success',
                'message' => 'User updated successfully!'
            ]);
        }

        return redirect()->route('admin.room.user')->withInput()->with([
            'type' => 'error',
            'message' => 'Unable to updated user!'
        ]);
    }

    public function delete(Request $request)
    {
        $user = User::find($request->user_id);
        $user->is_deleted = true;

        if($user->save())
        {
            return response()->json(['success' => true, 'message' => 'Selected record have been deleted.']);
        }

        return response()->json(['success' => false, 'message' => 'No record were selected for deletion.']);

    }

    public function destroy(Request $request)
    {
        $user = User::find($request->user_id);

        if($user->delete())
        {
            return response()->json(['success' => true, 'message' => 'Selected user permanently deleted.']);
        }

        return response()->json(['success' => false, 'message' => 'No record were selected for deletion.']);
    }

    public function bulkDelete(Request $request)
    {
        $user_ids = $request->input('user_ids');

        if (empty($user_ids)) {
            return response()->json(['success' => false, 'message' => 'No records were selected for deletion.']);
        }

        $users = User::whereIn('user_id', $user_ids)->get();

        $softDeleteUsers = [];
        $permanentDeleteUsers = [];

        foreach ($users as $user) {
            if ($user->is_deleted == 1) {
                $permanentDeleteUsers[] = $user->user_id;
            } else {
                $softDeleteUsers[] = $user->user_id;
            }
        }

        User::whereIn('user_id', $softDeleteUsers)->update(['is_deleted' => true]);
        User::whereIn('user_id', $permanentDeleteUsers)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Selected records have been deleted.',
        ]);
    }

    public function bulkRestore(Request $request)
    {
        $user_ids = $request->input('user_ids');

        if (empty($user_ids)) {
            return response()->json(['success' => false, 'message' => 'No records were selected for deletion.']);
        }

        $users = User::whereIn('user_id', $user_ids)->get();

        $restoreUsers = [];

        foreach ($users as $user) {
            if ($user->is_deleted == 1) {
                $restoreUsers[] = $user->user_id;
            }
        }

        User::whereIn('user_id', $restoreUsers)->update(['is_deleted' =>false]);

        return response()->json([
            'success' => true,
            'message' => 'Selected records have been restore successfully.',
        ]);

    }

    public function restore(Request $request)
    {
        $user = User::find($request->user_id);
        $user->is_deleted = false;

        if($user->save()) 
        {
            return response()->json([
                'success' => true,
                'message' => 'Selected records have been restore successfully.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Unable to restore selected records.',
        ]);

    }
}
