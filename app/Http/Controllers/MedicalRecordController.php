<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicalRecord;

class MedicalRecordController extends Controller
{
    // 🧾 تخزين تحليل جديد
    public function store(Request $request)
    {
        $record = MedicalRecord::create([
            'Patients_ID' => $request->patient_id,
            'Age' => $request->Age,
            'Sex' => $request->Sex,
            'ALB' => $request->ALB,
            'ALP' => $request->ALP,
            'ALT' => $request->ALT,
            'AST' => $request->AST,
            'BIL' => $request->BIL,
            'CHE' => $request->CHE,
            'CHOL' => $request->CHOL,
            'CREA' => $request->CREA,
            'GGT' => $request->GGT,
            'PROT' => $request->PROT,
            'Prediction' => $request->prediction,
            'Confidence' => $request->confidence,
            'Treatment' => $request->treatment,
        ]);

        return response()->json(['message' => '✅ تم حفظ السجل الطبي بنجاح', 'data' => $record]);
    }

    // ⚙️ عرض آخر تحليل للمريض (للمعالجة المسبقة)
    public function preprocessing($id)
    {
        $record = MedicalRecord::where('Patients_ID', $id)
            ->latest()
            ->first();

        if (!$record) {
            return response()->json(null, 204); // No Content
        }

        return response()->json([
            'ALT' => $record->ALT,
            'AST' => $record->AST,
            'ALP' => $record->ALP,
            'BIL' => $record->BIL,
            'CHE' => $record->CHE,
            'ALB' => $record->ALB,
            'CHOL' => $record->CHOL,
            'CREA' => $record->CREA,
            'GGT' => $record->GGT,
            'PROT' => $record->PROT,
        ]);
    }
}

