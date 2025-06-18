<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class DoctorLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.doctor-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('Email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->Password)) {
            if ($user->Role_ID == 1) {
                Auth::login($user);
                session()->regenerate(); // مهم لحفظ الجلسة
                return redirect()->intended('/doctor/dashboard');
            }
            return back()->with('error', '⚠️ هذا الحساب ليس لطبيب.');
        }

        return back()->with('error', '❌ البريد الإلكتروني أو كلمة المرور غير صحيحة');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('doctor.login.form');
    }
}
