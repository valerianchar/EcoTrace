<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PromptGeneratorController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $lat = $request->input('lat');
        $lon = $request->input('lon');

        $prompt = <<<EOT
Tu es un expert en environnement. Voici des coordonnées : latitude $lat, longitude $lon.

Donne-moi les 3 éléments suivants concernant une catastrophe écologique historique proche de cet endroit :
1. Un titre court et marquant
2. Une anecdote sur la catastrophe
3. Un message préventif pour l’avenir

Réponds exactement au format JSON :
[
  "Titre ici",
  "Anecdote ici",
  "Préventif ici"
]
EOT;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('MISTRAL_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api.mistral.ai/v1/chat/completions', [
                    'model' => 'mistral-large-latest',
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => $prompt,
                        ]
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 500,
                ]);

        $text = $response->json()['choices'][0]['message']['content'] ?? null;

        // Tente d'extraire un tableau JSON
        if (preg_match('/\[(.*?)\]/s', $text, $matches)) {
            $json = "[" . $matches[1] . "]";
            return response()->json(json_decode($json, true));
        }

        return response()->json(['error' => 'Réponse non valide'], 500);
    }
}
