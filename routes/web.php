<?php

use App\Http\Controllers\DoctorLoginController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\PatientController;
use App\ML\MLService;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__ . '/auth.php';


// Route::get('/doctor/predict', function () {
//     return view('doctor.predict');
// });

Route::get('/doctor/login', [DoctorLoginController::class, 'showLoginForm'])->name('doctor.login.form');

// ✅ تنفيذ تسجيل الدخول (POST)
Route::post('/doctor/login', [DoctorLoginController::class, 'login'])->name('doctor.login');

// ✅ تسجيل الخروج
Route::post('/doctor/logout', [DoctorLoginController::class, 'logout'])->name('doctor.logout');

// ✅ لوحة التحكم للطبيب (محمية بـ auth)
Route::middleware('auth')->group(function () {
    Route::get('/doctor/dashboard', function () {
        return view('doctor.dashboard'); // أو أي view مخصص
    })->name('doctor.dashboard');
    Route::post('/doctor/patients', [PatientController::class, 'store']);
Route::post('/records', [MedicalRecordController::class, 'store']);
});




