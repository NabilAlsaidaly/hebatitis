<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicalRecord;
use App\Models\Prediction;
use App\Models\Diagnosis;
use App\Models\Treatment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MedicalRecordController extends Controller
{
    // 🧾 تخزين تحليل جديد
    public function store(Request $request)
    {
        // ✅ التحقق من صحة البيانات الأساسية
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,Patients_ID',
            'Age' => 'required|numeric',
            'Sex' => 'required|numeric',
            'ALT' => 'required|numeric',
            'AST' => 'required|numeric',
            'ALB' => 'nullable|numeric',
            'ALP' => 'nullable|numeric',
            'BIL' => 'nullable|numeric',
            'CHE' => 'nullable|numeric',
            'CHOL' => 'nullable|numeric',
            'CREA' => 'nullable|numeric',
            'GGT' => 'nullable|numeric',
            'PROT' => 'nullable|numeric',
            'prediction' => 'required|integer',
            'probabilities' => 'required|array',
            'treatment' => 'required|string',
            'confidence' => 'nullable|numeric'
        ]);

        DB::beginTransaction(); // 🔐 نبدأ المعاملة (Transaction)
        try {
            // 1️⃣ حفظ السجل الأساسي في medical_records
            $record = MedicalRecord::create([
                'Patients_ID' => $validated['patient_id'],
                'Doctor_ID' => Auth::id(), // الطبيب الحالي
                'Age' => $validated['Age'],
                'Sex' => $validated['Sex'],
                'ALB' => $validated['ALB'] ?? null,
                'ALP' => $validated['ALP'] ?? null,
                'ALT' => $validated['ALT'],
                'AST' => $validated['AST'],
                'BIL' => $validated['BIL'] ?? null,
                'CHE' => $validated['CHE'] ?? null,
                'CHOL' => $validated['CHOL'] ?? null,
                'CREA' => $validated['CREA'] ?? null,
                'GGT' => $validated['GGT'] ?? null,
                'PROT' => $validated['PROT'] ?? null,
            ]);

            // 2️⃣ حفظ نتيجة التصنيف في جدول predictions
            $prediction = Prediction::create([
                'Record_ID' => $record->Record_ID,
                'result' => $validated['prediction'],
                'probabilities' => $validated['probabilities'],
                'notes' => null,
            ]);

            // 3️⃣ حفظ التشخيص (LSTM أو المرحلة المتوقعة لاحقًا)
            $diagnosis = Diagnosis::create([
                'Record_ID' => $record->Record_ID,
                'disease_stage' => $validated['prediction'], // حاليًا نفس النتيجة
                'confidence' => $validated['confidence'],
            ]);

            // 4️⃣ حفظ العلاج في جدول treatments
            Treatment::create([
                'Diagnosis_ID' => $diagnosis->Diagnosis_ID,
                'Treatment_Name' => $validated['treatment'],
                'description' => null,
            ]);

            DB::commit(); // ✅ تم كل شيء بنجاح
            return response()->json(['message' => '✅ تم حفظ جميع النتائج بنجاح']);
        } catch (\Exception $e) {
            DB::rollBack(); // ❌ إلغاء كل شيء عند حدوث خطأ
            return response()->json(['error' => 'فشل أثناء الحفظ: ' . $e->getMessage()], 500);
        }
    }

    // ⚙️ عرض آخر تحليل للمريض (للمعالجة المسبقة)
public function preprocessing($id)
{
    $record = MedicalRecord::where('Patients_ID', $id)
        ->latest()
        ->first();

    if (!$record) {
        return response()->json(null, 204); // لا يوجد محتوى
    }

    return response()->json([
        'Age' => $record->Age,
        'Sex' => $record->Sex,
        'ALB' => $record->ALB,
        'ALP' => $record->ALP,
        'ALT' => $record->ALT,
        'AST' => $record->AST,
        'BIL' => $record->BIL,
        'CHE' => $record->CHE,
        'CHOL' => $record->CHOL,
        'CREA' => $record->CREA,
        'GGT' => $record->GGT,
        'PROT' => $record->PROT,
    ]);
}

}
