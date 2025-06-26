<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Services\ReportService;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * 📄 توليد تقرير PDF تلقائي وتخزينه في النظام
     */
    public function generateReport($patientId)
    {
        try {
            $filePath = $this->reportService->generatePatientReport($patientId);

            $report = Report::create([
                'Patients_ID' => $patientId,
                'file_path' => $filePath,
            ]);

            return response()->json([
                'message' => '✅ تم إنشاء التقرير وحفظه بنجاح.',
                'report' => $report,
                'file' => asset('storage/' . $filePath),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => '❌ حدث خطأ أثناء إنشاء التقرير.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 📂 عرض كل التقارير الخاصة بمريض محدد
     */
    public function list($patient_id)
    {
        $reports = Report::where('Patients_ID', $patient_id)
            ->select('Report_ID as id', 'file_path', 'created_at')
            ->orderByDesc('created_at')
            ->get();

        return response()->json($reports);
    }

    /**
     * 🗑️ حذف تقرير محدد مع حذف الملف من التخزين
     */
    public function destroy($id)
    {
        $report = Report::findOrFail($id);

        if ($report->file_path && Storage::disk('public')->exists($report->file_path)) {
            Storage::disk('public')->delete($report->file_path);
        }

        $report->delete();

        return response()->json(['message' => '✅ تم حذف التقرير بنجاح.']);
    }
}
