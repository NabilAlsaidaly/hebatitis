<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MLPredictionController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StatsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| These routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
*/

// ✅ التحقق من المستخدم المسجّل
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// ✅ واجهات الذكاء الاصطناعي
Route::post('/predict/disease', [MLPredictionController::class, 'predictDisease']);
Route::post('/predict/treatment', [MLPredictionController::class, 'predictTreatment']);
Route::post('/predict/lstm', [MLPredictionController::class, 'predictLSTM']);



Route::get('/patients', [PatientController::class, 'list']);
Route::post('/patients', [PatientController::class, 'store']);

Route::put('/patients/{id}', [PatientController::class, 'update']);
Route::delete('/patients/{id}', [PatientController::class, 'destroy']);

Route::post('/records', [MedicalRecordController::class, 'store']);
Route::get('/preprocessing/{id}', [MedicalRecordController::class, 'preprocessing']);

Route::get('/stats', [StatsController::class, 'overview']);
Route::post('/reports', [ReportController::class, 'store']);
Route::get('/reports/{patient_id}', [ReportController::class, 'list']);
