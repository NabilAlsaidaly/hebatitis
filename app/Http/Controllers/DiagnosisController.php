<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Diagnosis;
use App\Models\MedicalRecord;

class DiagnosisController extends Controller
{
    public function storeLSTM(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|integer|exists:patients,Patients_ID',
            'stage' => 'required|integer|min:0|max:4',
            'confidence' => 'nullable|numeric',
        ]);

        $latestRecord = MedicalRecord::where('Patients_ID', $request->patient_id)
            ->orderByDesc('created_at')
            ->first();

        if (!$latestRecord) {
            return response()->json(['error' => 'لا يوجد سجل تحاليل لهذا المريض.'], 404);
        }

        $diagnosis = Diagnosis::create([
            'Record_ID' => $latestRecord->Record_ID,
            'disease_stage' => $request->stage,
            'confidence' => $request->confidence,
        ]);

        return response()->json([
            'message' => '✅ تم حفظ نتيجة تحليل تطور المرض بنجاح.',
            'diagnosis' => $diagnosis
        ]);
    }
}

