<?php

namespace App\Http\Controllers;

use App\Services\Gpt2Service;
use App\Services\RestaurantReviewService;
use Illuminate\Http\Request;

class Gpt2Controller extends Controller
{
 
    protected RestaurantReviewService $reviewService;

    //local AI agent
    public function __construct(RestaurantReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }
    public function submitQuery()
    {
        return view('gpt2.review');
    }

    public function ask(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:500'
        ]);

        $includeReviews = $request->boolean('include_reviews', false);

        $response = $this->reviewService->askQuestion(
            $request->input('question'),
            $includeReviews
        );

        if (isset($response['error'])) {
            return response()->json($response, 502);
        }

        return response()->json($response);
    }

    public function healthCheck()
    {
        $isHealthy = $this->reviewService->checkHealth();
        
        return response()->json([
            'status' => $isHealthy ? 'connected' : 'disconnected',
            'api_url' => env('REVIEW_API_URL')
        ]);
    }

    //GPT2 solution
    protected Gpt2Service $gpt2Service;
    // public function __construct(Gpt2Service $gpt2Service)
    // {
    //     $this->gpt2Service = $gpt2Service;
    // }

    public function showForm()
    {
        return view('gpt2.form');
    }
    
    public function generate(Request $request)
    {
        $validated = $request->validate([
            'prompt' => 'required|string|max:1000',
            'max_length' => 'sometimes|integer|min:20|max:500',
            'temperature' => 'sometimes|numeric|min:0.1|max:1.0',
            'top_k' => 'sometimes|integer|min:1|max:100',
            'top_p' => 'sometimes|numeric|min:0.1|max:1.0',
        ]);

        $response = $this->gpt2Service->generateText(
            $validated['prompt'],
            $request->only(['max_length', 'temperature', 'top_k', 'top_p'])
        );

        if (isset($response['error'])) {
            return response()->json(['error' => $response['error']], 500);
        }

        return response()->json([
            'response' => $response['results'][0]['text'] ?? 'No response generated'
        ]);
    }

    public function batchGenerate(Request $request)
    {
           $validated = $request->validate([
                'prompts' => 'required|array',
                'prompts.*' => 'string|min:1|max:1000',
                'max_length' => 'sometimes|integer|min:20|max:500',
                'temperature' => 'sometimes|numeric|min:0.1|max:1.0',
            ]);

            $response = $this->gpt2Service->batchGenerate(
                $validated['prompts'],
                $request->only(['max_length', 'temperature'])
            );

            if (isset($response['error'])) {
                return response()->json(['error' => $response['error']], 500);
            }

            return response()->json(['results' => $response['results'] ?? []]);
    }

    public function health()
    {
        $response = $this->gpt2Service->healthCheck();
        return response()->json($response);
    }
}