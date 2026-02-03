<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required',
        ]);

        // Check if login is email or NIK
        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'nik';

        $employee = Employee::where($loginType, $request->login)->first();

        if (!$employee || !Hash::check($request->password, $employee->password)) {
            return back()->withErrors([
                'login' => 'Kombinasi email/NIK dan password tidak sesuai.',
            ])->withInput($request->only('login'));
        }

        if ($employee->status !== 'active') {
            return back()->withErrors([
                'login' => 'Akun Anda sedang tidak aktif.',
            ])->withInput($request->only('login'));
        }

        // Login employee
        Auth::login($employee, $request->filled('remember'));

        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
