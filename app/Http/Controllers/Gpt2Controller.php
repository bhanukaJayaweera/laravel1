<?php

namespace App\Http\Controllers;

use App\Services\Gpt2Service;
use Illuminate\Http\Request;

class Gpt2Controller extends Controller
{
    protected Gpt2Service $gpt2Service;

    public function __construct(Gpt2Service $gpt2Service)
    {
        $this->gpt2Service = $gpt2Service;
    }

    public function showForm()
    {
        return view('gpt2.form');
    }
    
    public function generate(Request $request)
    {
        $validated = $request->validate([
            'prompt' => 'required|string',
            'max_length' => 'sometimes|integer|min:10|max:500',
            'temperature' => 'sometimes|numeric|min:0.1|max:1.0',
            'top_k' => 'sometimes|integer|min:1|max:100',
            'top_p' => 'sometimes|numeric|min:0.1|max:1.0',
            'num_return_sequences' => 'sometimes|integer|min:1|max:5',
        ]);

        $response = $this->gpt2Service->generateText(
            $validated['prompt'],
            $request->only(['max_length', 'temperature', 'top_k', 'top_p', 'num_return_sequences'])
        );

        if (isset($response['error'])) {
            return response()->json($response, 500);
        }

        return response()->json($response);
    }

    public function batchGenerate(Request $request)
    {
        $validated = $request->validate([
            'prompts' => 'required|array',
            'prompts.*' => 'string|min:1',
        ]);

        $response = $this->gpt2Service->batchGenerate($validated['prompts']);

        if (isset($response['error'])) {
            return response()->json($response, 500);
        }

        return response()->json($response);
    }

    public function health()
    {
        $response = $this->gpt2Service->healthCheck();
        return response()->json($response);
    }
}