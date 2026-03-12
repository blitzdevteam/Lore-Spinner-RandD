<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Game;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Prompt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class TextToSpeechController extends Controller
{
    public function __invoke(Game $game, Prompt $prompt): BinaryFileResponse
    {
        abort_unless($prompt->game_id === $game->id, 404);
        abort_unless(filled($prompt->response), 404);

        $path = "tts/{$prompt->id}.mp3";

        if (! Storage::disk('local')->exists($path)) {
            $this->generate($prompt, $path);
        }

        return new BinaryFileResponse(Storage::disk('local')->path($path), 200, [
            'Content-Type' => 'audio/mpeg',
            'Accept-Ranges' => 'bytes',
            'Cache-Control' => 'private, max-age=86400',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    private function generate(Prompt $prompt, string $path): void
    {
        $text = strip_tags($prompt->response);
        $voiceId = config('services.elevenlabs.voice_id');
        $apiKey = config('services.elevenlabs.api_key');

        abort_unless(filled($apiKey), 503, 'Voice generation is not configured.');

        $response = Http::withHeaders([
            'xi-api-key' => $apiKey,
        ])->timeout(120)->post("https://api.elevenlabs.io/v1/text-to-speech/{$voiceId}/stream?output_format=mp3_44100_128", [
            'text' => $text,
            'model_id' => config('services.elevenlabs.model_id', 'eleven_v3'),
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

        Storage::disk('local')->put($path, $response->body());
    }
}
