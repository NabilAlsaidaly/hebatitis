<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class PatientController extends Controller
{
    public function list()
    {
        return response()->json(
            Patient::select('Patients_ID as id', 'Name', 'Date_Of_Birth', 'Contact_Info')->get()
        );
    }


    public function store(Request $request)
    {
        // 🔐 التحقق من صحة البيانات
        $request->validate([
            'name' => 'required|string|max:255',
            'dob' => 'nullable|date',
            'contact_info' => 'nullable|string',
            'email' => 'required|email|unique:users,Email',
            'password' => 'required|string|min:4',
        ]);

        // 🧑‍⚕️ الحصول على الطبيب الحالي
        $doctor = Auth::user();
        if (!$doctor || $doctor->Role_ID !== 1) {
            return response()->json(['error' => '⚠️ غير مصرح لك بإضافة المرضى'], 403);
        }

        // ✅ إنشاء المستخدم كمريض
        $user = User::create([
            'Name' => $request->name,
            'Email' => $request->email,
            'Password' => $request->password, // تُشفّر تلقائيًا في الـ Model
            'Role_ID' => 2, // 🩺 مريض
        ]);

        // ✅ ربط المستخدم الجديد بسجل المريض
        $patient = Patient::create([
            'Name' => $request->name,
            'Date_Of_Birth' => $request->dob,
            'Contact_Info' => $request->contact_info,
            'Doctor_ID' => $doctor->User_ID,
            'User_ID' => $user->getKey(),
        ]);

        return response()->json([
            'message' => '✅ تم إضافة المريض مع حسابه بنجاح',
            'patient' => $patient
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'dob' => 'nullable|date',
            'contact_info' => 'nullable|string',
        ]);

        $patient = Patient::findOrFail($id);
        $patient->update([
            'Name' => $request->name,
            'Date_Of_Birth' => $request->dob,
            'Contact_Info' => $request->contact_info,
        ]);

        return response()->json(['message' => '✅ تم تعديل المريض', 'patient' => $patient]);
    }

    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();

        return response()->json(['message' => '🗑️ تم حذف المريض']);
    }


    public function records($id)
    {
        $patient = Patient::findOrFail($id);

        $records = $patient->medicalRecords()
            ->with(['prediction', 'diagnosis']) // العلاقات المرتبطة
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'patient' => [
                'name' => $patient->Name,
                'dob' => $patient->Date_Of_Birth,
                'contact' => $patient->Contact_Info,
            ],
            'records' => $records
        ]);
    }
}
