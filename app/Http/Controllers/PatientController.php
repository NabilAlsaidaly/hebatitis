<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function list()
    {
        return response()->json(Patient::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'dob' => 'nullable|date',
            'contact_info' => 'nullable|string',
        ]);

        $patient = Patient::create([
            'Name' => $request->name,
            'Date_Of_Birth' => $request->dob,
            'Contact_Info' => $request->contact_info,
            'Doctor_ID' => Auth::id(), // أو ثابت مؤقتًا للاختبار
        ]);

        return response()->json(['message' => '✅ تم إضافة المريض', 'patient' => $patient]);
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
}
