<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthenticationController extends Controller
{
    public function index()
    {
        return view("auth.login");
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email_username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (!Auth::guard('web')->attempt(['username' => $validated['email_username'], 'password' => $validated['password']])) {
            return back()->withErrors(['login' => 'The provided credentials are incorrect.']);
        }

        $user = Auth::user();

        if($user->is_deleted == true)
        {
            return back()->with([
                'type' => 'error', 
                'message' => 'User not found!'
            ]);
        }

        return $this->handleRoleRedirect($user);
    }

    /**
     * Handle user redirect based on their role
     *
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    private function handleRoleRedirect($user)
    {
        switch ($user->role->role_type) {
            case 'admin':
                return redirect()->route('admin.dashboard')->with(['type' => 'success', 'message' => 'Login successfully!']);
            case 'teacher':
                return redirect()->route('teacher.dashboard')->with(['type' => 'success', 'message' => 'Login successfully!']);
            case 'student':
                return redirect()->route('student.dashboard')->with(['type' => 'success', 'message' => 'Login successfully!']);
            default:
                return redirect()->route('auth.logout');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
