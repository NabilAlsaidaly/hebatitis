<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\MedicalRecord;
use App\Models\Report;
use App\Models\Prediction;

class StatsController extends Controller
{
    public function summary()
    {
        // ✅ مؤشرات عامة
        $patientsCount = Patient::count();
        $recordsCount = MedicalRecord::count();
        $reportsCount = Report::count();
        $predictionsCount = Prediction::count();

        // ✅ توزيع الفئات التشخيصية
        $distributionRaw = Prediction::select('result')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('result')
            ->get();

        $labels = [
            0 => "🟢 سليم",
            1 => "🟡 مشتبه",
            2 => "🟠 التهاب",
            3 => "🔴 تليف",
            4 => "🚨 تشمع",
        ];

        $distribution = [];
        foreach ($distributionRaw as $item) {
            $label = $labels[$item->result] ?? "غير معروف";
            $distribution[$label] = $item->count;
        }

        return response()->json([
            'patients' => $patientsCount,
            'records' => $recordsCount,
            'reports' => $reportsCount,
            'predictions' => $predictionsCount,
            'distribution' => $distribution
        ]);
    }
}
