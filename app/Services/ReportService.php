<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Models\Patient;
use App\Models\MedicalRecord;

class ReportService
{
    public function generatePatientReport($patientId, $forDate = null)
    {
        $patient = Patient::findOrFail($patientId);

        $records = MedicalRecord::where('Patients_ID', $patientId)
    ->when($forDate, function ($query, $forDate) {
        $query->whereDate('created_at', $forDate);
    })
    ->with('prediction') // 👈 لجلب التنبؤ
    ->get();


        $pdf = Pdf::loadView('doctor.sections.reports_pdf', [
            'patient' => $patient,
            'records' => $records,
        ]);


        $fileName = 'report_patient_' . $patientId . '_' . now()->format('Ymd_His') . '.pdf';
        $path = 'reports/' . $fileName;

        Storage::disk('public')->put($path, $pdf->output());

        return $path;
    }
}
