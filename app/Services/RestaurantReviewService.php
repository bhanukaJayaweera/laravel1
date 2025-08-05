<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class RestaurantReviewService
{
    protected Client $client;
    protected string $apiUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiUrl = env('REVIEW_API_URL', 'http://localhost:8001');
    }

    public function askQuestion(string $question, bool $includeReviews = false): array
    {
        try {
            $response = $this->client->post("{$this->apiUrl}/ask", [
                'json' => [
                    'question' => $question,
                    'show_reviews' => $includeReviews
                ],
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ]
            ]);

            return json_decode($response->getBody()->getContents(), true);

        } catch (GuzzleException $e) {
            Log::error("API request failed: " . $e->getMessage());
            return [
                'error' => 'Failed to get response from review service',
                'details' => $e->getMessage()
            ];
        }
    }

    public function checkHealth(): bool
    {
        try {
            $response = $this->client->get("{$this->apiUrl}/health");
            return $response->getStatusCode() === 200;
        } catch (GuzzleException $e) {
            return false;
        }
    }
}