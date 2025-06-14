<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $path = $request->file('file')->store('reports', 'public');

        $report = Report::create([
            'Patients_ID' => $request->patient_id,
            'File_Name' => $request->file('file')->getClientOriginalName(),
            'File_Path' => $path,
        ]);

        return response()->json([
            'message' => '✅ تم رفع التقرير',
            'report' => $report
        ]);
    }

    public function list($patient_id)
    {
        return response()->json(
            Report::where('Patients_ID', $patient_id)->get()
        );
    }
}
