<?php

use App\Http\Controllers\DiagnosisController;
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
Route::post('/records/lstm', [DiagnosisController::class, 'storeLSTM']);



Route::get('/patients', [PatientController::class, 'list']);

Route::put('/patients/{id}', [PatientController::class, 'update']);
Route::delete('/patients/{id}', [PatientController::class, 'destroy']);


Route::get('/preprocessing/{id}', [MedicalRecordController::class, 'preprocessing']);

Route::get('/stats', [StatsController::class, 'summary']);
Route::post('/reports', [ReportController::class, 'store']);
Route::get('/reports/{patient_id}', [ReportController::class, 'list']);
Route::get('/patients/{id}/records', [PatientController::class, 'records']);


// 📄 توليد تقرير PDF تلقائي لمريض محدد
Route::post('/reports/generate/{patientId}', [ReportController::class, 'generateReport']);

// 📂 جلب كل التقارير لمريض محدد
Route::get('/reports/list/{patientId}', [ReportController::class, 'list']);
Route::delete('/reports/{id}', [ReportController::class, 'destroy']);

Route::get('/preprocessing/{patientId}', [MedicalRecordController::class, 'latestForPatient']);
