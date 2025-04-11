<?php

namespace App\Http\Controllers\Student;

use Auth;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $user = User::find(Auth::user()->user_id);

        return view('student.profile', compact('user'));
    }

    public function update(Request $request, string $user_id)
    {
        $validated = $request->validate([
            'profile_image' => 'nullable|image',
            'first_name' => 'required',
            'middle_name' => 'nullable',
            'last_name' => 'required',
            'email' => 'required|unique:users,email,' . $user_id . ',user_id',
            'username' => 'required|unique:users,username,'  . $user_id . ',user_id',
            'password' => 'nullable|min:6|confirmed',
            'password_confirmation' => 'nullable'
        ]);
        
        if ($request->hasFile('profile_image')) {
            $filenameWithExtension = $request->file('profile_image')->getClientOriginalName();
            $filename = pathinfo($filenameWithExtension, PATHINFO_FILENAME);
            $extension = $request->file('profile_image')->getClientOriginalExtension();
            $filenameToStore = $filename . '_' . time() . '.' . $extension;
            $request->file('profile_image')->storeAs('profile_images', $filenameToStore);
            $validated['profile_image'] = $filenameToStore;
        }

        $user = User::find($user_id);
        
        $name = $validated['first_name'] . ' ' . $validated['middle_name'] . ' ' . $validated['last_name'];
        
        $updateData = [
            'profile_image' => $validated['profile_image'] ?? null,
            'name' => $name,
            'email' => $validated['email'],
            'username' => $validated['username'],
        ];
        
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($validated['password']);
        }
        
        if($user->update($updateData)) {

            $student = Student::where('user_id', $user_id)->first();
            if ($student) {
                $student->update([
                    'first_name' => $validated['first_name'],
                    'middle_name' => $validated['middle_name'],
                    'last_name' => $validated['last_name']
                ]);
            }

            return redirect()->route('student.profile')->with([
                'type' => 'success',
                'message' => 'User updated successfully!'
            ]);
        }

        return redirect()->route('student.profile')->withInput()->with([
            'type' => 'error',
            'message' => 'Unable to updated user!'
        ]);
    }
}