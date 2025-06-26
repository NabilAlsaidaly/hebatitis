<?php

use App\Http\Controllers\Auth\PatientLoginController;
use App\Http\Controllers\DoctorLoginController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PatientDashboardController;
use App\ML\MLService;
use Barryvdh\DomPDF\Facade\Pdf;
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

Route::get('/preview-report/{filename}', function ($filename) {
    $path = storage_path("app/public/reports/{$filename}");

    if (!file_exists($path)) {
        abort(404);
    }

    return response()->file($path);
})->name('preview.report');



// ✅ تسجيل دخول وخروج المريض (خارج middleware لأن المستخدم لم يُصادق بعد)
Route::get('/patient/login', [\App\Http\Controllers\Auth\PatientLoginController::class, 'showLoginForm'])->name('patient.login');
Route::post('/patient/login', [\App\Http\Controllers\Auth\PatientLoginController::class, 'login'])->name('patient.login.submit');
Route::post('/patient/logout', [\App\Http\Controllers\Auth\PatientLoginController::class, 'logout'])->name('patient.logout');
Route::middleware(['auth'])->group(function () {
    Route::get('/patient/dashboard', [\App\Http\Controllers\PatientDashboardController::class, 'index'])->name('patient.dashboard');
    Route::get('/patient/records', [\App\Http\Controllers\PatientDashboardController::class, 'records'])->name('patient.records');
    Route::get('/patient/reports', [\App\Http\Controllers\PatientDashboardController::class, 'reports'])->name('patient.reports');
    Route::get('/patient/chart', [\App\Http\Controllers\PatientDashboardController::class, 'chart'])->name('patient.chart');
    Route::get('/patient/chart-data', [\App\Http\Controllers\PatientDashboardController::class, 'chartData'])->name('patient.chart.data');
    Route::get('/patient/info', [PatientDashboardController::class, 'info'])->name('patient.info');

});
