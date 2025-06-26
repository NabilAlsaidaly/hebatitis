<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use Illuminate\Support\Facades\Auth;
use App\Models\Patient;
use App\Models\Report;
use Illuminate\Http\JsonResponse;

class PatientDashboardController extends Controller
{
    // 🧍‍♂️ صفحة بيانات المريض الشخصية
    public function info()
    {
        $user = Auth::user();
        $patient = Patient::where('User_ID', $user->User_ID)->first();

        return view('patient.sections.patient-info', compact('patient'));
    }

    // 🏠 لوحة التحكم الرئيسية
    public function index()
    {
        $user = Auth::user();
        $patient = Patient::where('User_ID', $user->User_ID)->first();

        return view('patient.dashboard', compact('patient'));
    }

    // 📋 تحاليل المريض
    public function records()
    {
        $user = Auth::user();
        $patient = Patient::where('User_ID', $user->User_ID)->first();

        if (!$patient) {
            abort(403, 'Unauthorized');
        }

        $records = MedicalRecord::where('Patients_ID', $patient->Patients_ID)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('patient.sections.records', compact('records'));
    }

    // 📄 تقارير المريض
    public function reports()
    {
        $user = Auth::user();
        $patient = Patient::where('User_ID', $user->User_ID)->first();

        if (!$patient) {
            abort(403, 'Unauthorized');
        }

        $reports = Report::where('Patients_ID', $patient->Patients_ID)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('patient.sections.reports', compact('reports'));
    }

    // 📈 عرض صفحة المخطط
    public function chart()
    {
        return view('patient.sections.chart');
    }

    // 📡 تزويد بيانات المخطط بصيغة JSON
    public function chartData(): JsonResponse
    {
        $user = Auth::user();
        $patient = Patient::where('User_ID', $user->User_ID)->firstOrFail();

        $records = MedicalRecord::where('Patients_ID', $patient->Patients_ID)
            ->orderBy('created_at')
            ->get(['created_at', 'ALT', 'AST', 'BIL']);

        $data = [
            'labels' => [],
            'ALT' => [],
            'AST' => [],
            'BIL' => [],
        ];

        foreach ($records as $record) {
            $data['labels'][] = $record->created_at->format('Y-m-d');
            $data['ALT'][] = $record->ALT;
            $data['AST'][] = $record->AST;
            $data['BIL'][] = $record->BIL;
        }

        return response()->json($data);
    }
}
