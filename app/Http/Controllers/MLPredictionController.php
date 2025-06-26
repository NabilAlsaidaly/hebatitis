<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ML\MLService;

class MLPredictionController extends Controller
{
    public function predictDisease(Request $request)
    {
        $service = new MLService();

        // نحصل على بيانات الطلب (يمكن تعديلها لاحقًا لجعلها Validate)
        $inputData = $request->all();

        $result = $service->predictDisease($inputData);

        return response()->json([
            'prediction_result' => $result
        ]);
    }


    public function predictTreatment(Request $request)
    {
        $service = new MLService();
        $inputData = $request->all();

        $result = $service->predictTreatment($inputData);

        return response()->json([
            'treatment_result' => $result
        ]);
    }


    public function predictLSTM(Request $request)
    {
        $service = new MLService();
        $inputData = $request->all();

        $result = $service->predictLSTM($inputData);

        return response()->json([
            'lstm_result' => $result
        ]);
    }


}
