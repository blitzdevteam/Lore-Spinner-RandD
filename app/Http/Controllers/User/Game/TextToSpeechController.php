<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Game;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Prompt;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class TextToSpeechController extends Controller
{
    public function __invoke(Game $game, Prompt $prompt): StreamedResponse
    {
        abort_unless($prompt->game_id === $game->id, 404);
        abort_unless(filled($prompt->response), 404);

        $text = strip_tags($prompt->response);
        $voiceId = config('services.elevenlabs.voice_id');
        $apiKey = config('services.elevenlabs.api_key');

        abort_unless(filled($apiKey), 503, 'Voice generation is not configured.');

        $response = Http::withHeaders([
            'xi-api-key' => $apiKey,
        ])->withOptions([
            'stream' => true,
        ])->post("https://api.elevenlabs.io/v1/text-to-speech/{$voiceId}/stream?output_format=mp3_44100_128", [
            'text' => $text,
            'model_id' => config('services.elevenlabs.model_id', 'eleven_multilingual_v2'),
            'voice_settings' => [
                'stability' => 0.50,
                'similarity_boost' => 0.75,
                'style' => 0.0,
                'speed' => 1.0,
            ],
        ]);

        if (! $response->successful()) {
            logger()->warning('ElevenLabs TTS failed', [
                'status' => $response->status(),
                'prompt_id' => $prompt->id,
            ]);

            abort($response->status() === 403 ? 502 : $response->status(), 'Voice generation unavailable.');
        }

        return new StreamedResponse(function () use ($response): void {
            $body = $response->toPsrResponse()->getBody();

            while (! $body->eof()) {
                echo $body->read(8192);

                if (ob_get_level()) {
                    ob_flush();
                }

                flush();
            }
        }, 200, [
            'Content-Type' => 'audio/mpeg',
            'Cache-Control' => 'no-cache, no-store',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
