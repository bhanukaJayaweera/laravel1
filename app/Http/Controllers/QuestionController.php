<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use Illuminate\Support\Facades\Http;

class QuestionController extends Controller
{
    public function fetchInsert()
    {
        try {
            $response = Http::withoutVerifying()->get('https://quizapi.io/api/v1/questions', [
                'apiKey' => '9hG9f1jXJZSXTsUl6ngIH8txZYbXFaiTtLtEhPLf',
                'limit' => 100,
                //'category' => 'computer',
            ]);

            // Check if the request was successful
            if ($response->successful()) {
                $questions = $response->json();
                
                // Process and save each question
                foreach ($questions as $q) {
                    $question = new Question();
                    $question->question = $q['question'] ?? null;
                    $question->answer_a = $q['answers']['answer_a'] ?? null;
                    $question->answer_b = $q['answers']['answer_b'] ?? null;
                    $question->answer_c = $q['answers']['answer_c'] ?? null;
                    $question->save();
                }
                
                return response()->json([
                    'message' => 'API data fetched and saved to DB',
                    'count' => count($questions)
                ]);
                
            } else {
                return response()->json([
                    'error' => 'API request failed',
                    'status' => $response->status()
                ], $response->status());
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch data',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    //get from db and show
    public function show(){
        $data['questions'] = Question::paginate(20);
        return view('questions',$data);
    }
}
