<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PatientLoginController extends Controller
{
    // ✅ عرض صفحة تسجيل الدخول
    public function showLoginForm()
    {
        return view('patient.auth.login'); // ← تغيير مسار الواجهة ليكون ضمن مجلد المريض
    }

    // ✅ تنفيذ عملية تسجيل الدخول
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->Role_ID == 2) {
                return redirect()->route('patient.dashboard');
            }

            Auth::logout();
            throw ValidationException::withMessages([
                'email' => 'هذا الحساب ليس مخصصًا للمريض.',
            ]);
        }

        throw ValidationException::withMessages([
            'email' => 'بيانات الدخول غير صحيحة.',
        ]);
    }

    // ✅ تسجيل الخروج
    public function logout()
    {
        Auth::logout();
        return redirect()->route('patient.login');
    }
}
