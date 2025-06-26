<?php

namespace App\ML;

use Illuminate\Support\Facades\Http;

class MLService
{
    protected $baseUrl;

    public function __construct()
    {
        // في المستقبل يمكننا استخدام config file
        $this->baseUrl = "http://127.0.0.1:5000";
    }

    // 🔍 التنبؤ بالتصنيف (Category)
    public function predictDisease(array $data): ?int
    {
        try {
            $response = Http::post("{$this->baseUrl}/predict/disease", $data);
            if ($response->successful()) {
                return $response->json()['prediction'];
            }
        } catch (\Exception $e) {
        }

        return null;
    }

    // 💊 التنبؤ بالعلاج بناءً على category والتحاليل
    public function predictTreatment(array $data): ?string
    {
        try {
            $response = Http::post("{$this->baseUrl}/predict/treatment", $data);
            if ($response->successful()) {
                return $response->json()['treatment'];
            }
        } catch (\Exception $e) {
        }

        return null;
    }

    // 🔁 توقع تطور المرض باستخدام LSTM
    public function predictLSTM(array $timeSeries): ?array
    {
        try {
            $response = Http::post("{$this->baseUrl}/predict/lstm", $timeSeries);
            if ($response->successful()) {
                return $response->json(); // يحتوي على prediction و confidence
            }
        } catch (\Exception $e) {
        }

        return null;
    }

    // 📊 الحصول على احتمالات التشخيص فقط
    public function getProbabilities(array $data): ?array
    {
        try {
            $response = Http::post("{$this->baseUrl}/predict/disease", $data);
            if ($response->successful()) {
                return $response->json()['probabilities'] ?? [];
            }
        } catch (\Exception $e) {
        }

        return [];
    }

    // ✅ التنبؤ الكامل: تشخيص + احتمالات + علاج (مسار موحد)
    public function predictFull(array $data): ?array
    {
        try {
            $response = Http::post("{$this->baseUrl}/predict/full", $data);
            if ($response->successful()) {
                return $response->json(); // يحتوي على category, category_text, treatment, probabilities
            }
        } catch (\Exception $e) {
        }

        return null;
    }
}
