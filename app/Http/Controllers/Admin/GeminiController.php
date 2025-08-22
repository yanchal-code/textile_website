<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GeminiController extends Controller
{

    public function generate(Request $request)
    {
        $prompt = $request->input('prompt');

        if (empty($prompt)) {
            return response()->json(['status' => 'error', 'message' => 'Prompt is required.'], 400);
        }

        $apiKey = env('GEMINI_API_KEY'); // Store API key in .env
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=$apiKey";

        // Modify the prompt to explicitly ask for short title and description
        $fullPrompt = "Generate a short and catchy title and a detailed product description for the following product:\n\n$prompt\n\nOutput in this format:\nTitle: [Short Title]\nDescription: [Product Description]";

        $response = Http::post($url, [
            "contents" => [
                [
                    "parts" => [
                        ["text" => $fullPrompt]
                    ]
                ]
            ]
        ]);

        if ($response->failed()) {
            return response()->json(['status' => 'error', 'message' => 'Failed to generate response.'], 500);
        }

        // Extract AI response
        $responseData = $response->json();
        $generatedText = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? 'No response generated.';

        // Parse title and description from AI response
        preg_match('/Title:\s*(.*?)\n/', $generatedText, $titleMatch);
        preg_match('/Description:\s*(.*)/s', $generatedText, $descriptionMatch);

        $title = $titleMatch[1] ?? 'No title generated.';
        $description = $descriptionMatch[1] ?? 'No description generated.';

        return response()->json([
            'status' => 'success',
            'title' => $title,
            'description' => $description
        ]);
    }
}
