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
}
