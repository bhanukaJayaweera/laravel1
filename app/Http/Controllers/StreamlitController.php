<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StreamlitController extends Controller
{
    public function showForm()
    {
        return view('fruit_classifier');
    }

    public function predict(Request $request)
    {
        $request->validate([
            'fruit_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $response = Http::attach(
            'image', 
            file_get_contents($request->file('fruit_image')), 
            $request->file('fruit_image')->getClientOriginalName()
        )->post('http://127.0.0.1:8001/predict');

        return back()->with([
            'prediction' => $response->json()['prediction'],
            'confidence' => $response->json()['confidence'],
            'probabilities' => $response->json()['probabilities']
        ]);
    }
}