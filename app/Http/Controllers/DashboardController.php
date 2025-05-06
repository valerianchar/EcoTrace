<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        if(!$request->input('lat') || !$request->input('lon')) {
            return Inertia::render('Dashboard', [
                'prompt' => fn () => '',
            ]);
        }
        $lat = $request->input('lat');
        $lon = $request->input('lon');

        $prompt = "Tu es un expert en environnement. Donne-moi ces trois éléments sur une catastrophe écologique proche des coordonnées $lat, $lon :
1. Un titre accrocheur
2. Une anecdote marquante liée à la catastrophe
3. Un message préventif.

Formate la réponse strictement ainsi :
[
  \"Titre\",
  \"Anecdote\",
  \"Préventif\"
]";

        $response = Http::withHeaders([
            'x-api-key' => env('ANTHROPIC_API_KEY'),
            'content-type' => 'application/json',
            'anthropic-version' => '2023-06-01',
        ])->post('https://api.anthropic.com/v1/messages', [
            'model' => 'claude-3-opus-20240229',
            'max_tokens' => 500,
            'temperature' => 0.7,
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        $content = $response->json()['content'][0]['text'] ?? null;

        if ($content) {
            // Essaie d'extraire le tableau JSON de la réponse
            $matches = [];
            preg_match('/\[(.*?)\]/s', $content, $matches);
            $json = isset($matches[0]) ? $matches[0] : '[]';
//            return response()->json(json_decode($json, true));
            return Inertia::render('Dashboard', [
                'prompt' => fn () => $json,
            ]);
        }
        return Inertia::render('Dashboard', [
            'prompt' => 'Aucune données scrapper',
        ]);
    }
}
