<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\MedicalRecord;
use App\Models\Report;

class StatsController extends Controller
{
    public function overview()
    {
        try {
            $distribution = MedicalRecord::selectRaw('Prediction, COUNT(*) as total')
                ->groupBy('Prediction')
                ->pluck('total', 'Prediction');

            return response()->json([
                'patients' => Patient::count(),
                'records' => MedicalRecord::count(),
                'reports' => Report::count(),
                'predictions' => $distribution->sum(),
                'distribution' => $distribution,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => '❌ فشل في حساب الإحصاءات',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}

