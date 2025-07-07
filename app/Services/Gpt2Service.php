<?php
// app/Services/Gpt2Service.php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Gpt2Service
{
    protected string $apiUrl;
    protected int $timeout;

    public function __construct()
    {
        $this->apiUrl = config('services.gpt2.api_url', 'http://127.0.0.1:5000');
        $this->timeout = config('services.gpt2.timeout', 30);
    }

    public function generateText(string $prompt, array $parameters = [])
    {
        try {
            $response = Http::timeout($this->timeout)
                ->post($this->apiUrl . '/generate', [
                    'prompt' => $prompt,
                    'max_length' => $parameters['max_length'] ?? 100,
                    'temperature' => $parameters['temperature'] ?? 0.7,
                    'top_k' => $parameters['top_k'] ?? 50,
                    'top_p' => $parameters['top_p'] ?? 0.9,
                    'num_return_sequences' => $parameters['num_return_sequences'] ?? 1,
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('GPT-2 API Error: ' . $response->body());
            return ['error' => 'API request failed', 'status' => $response->status()];

        } catch (\Exception $e) {
            Log::error('GPT-2 Service Exception: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    public function batchGenerate(array $prompts)
    {
        try {
            $response = Http::timeout($this->timeout * count($prompts))
                ->post($this->apiUrl . '/batch_generate', [
                    'prompts' => $prompts
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('GPT-2 Batch API Error: ' . $response->body());
            return ['error' => 'API request failed', 'status' => $response->status()];

        } catch (\Exception $e) {
            Log::error('GPT-2 Batch Service Exception: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    public function healthCheck()
    {
        try {
            $response = Http::timeout(5)->get($this->apiUrl . '/health');
            return $response->json();
        } catch (\Exception $e) {
            return ['status' => 'unhealthy', 'error' => $e->getMessage()];
        }
    }
}