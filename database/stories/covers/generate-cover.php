<?php

$apiKey = getenv('OPENAI_API_KEY') ?: die("Set OPENAI_API_KEY env var\n");
$outDir = __DIR__;

$slug = 'the-wonderful-wizard-of-oz';
$file = "{$outDir}/{$slug}.png";

if (file_exists($file)) {
    echo "SKIP: {$slug}.png already exists\n";
    exit(0);
}

$prompt = "Create a cinematic, atmospheric scene illustration for a story cover.\n\n"
    ."STORY: \"The Wonderful Wizard of Oz\"\n"
    ."TEASER: Swept from Kansas by a cyclone into the magical Land of Oz, a young girl and her unlikely companions must journey to the Emerald City and confront a powerful witch to find their way home.\n"
    ."GENRE: Fantasy Adventure\n\n"
    ."STYLE REQUIREMENTS:\n"
    ."- Dark, moody atmosphere with deep blacks and rich shadows\n"
    ."- Accent lighting using teal/cyan (#54f4da) and warm golden highlights\n"
    ."- Cinematic composition with dramatic depth of field\n"
    ."- Painterly digital art style, NOT photorealistic, NOT cartoonish\n"
    ."- Scene: A young girl in a blue gingham dress stands before towering emerald gates, silhouetted against a swirling green-gold sky. The yellow brick road winds behind her through a dark enchanted landscape. A small dog at her feet. Scarecrow, Tin Woodman, and Cowardly Lion visible as shadowy companions on the road.\n"
    ."- No text, no letters, no words, no titles, no watermarks\n"
    ."- No UI elements, no borders, no frames\n"
    ."- Square composition suitable as a cover thumbnail";

echo "Generating: {$slug}.png... ";

$payload = json_encode([
    'model' => 'gpt-image-1',
    'prompt' => $prompt,
    'n' => 1,
    'size' => '1024x1024',
    'quality' => 'medium',
]);

$curl = curl_init('https://api.openai.com/v1/images/generations');
curl_setopt_array($curl, [
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'Authorization: Bearer '.$apiKey],
    CURLOPT_POSTFIELDS => $payload,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 300,
]);

$resp = curl_exec($curl);
$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

if ($code !== 200) {
    echo "FAILED (HTTP {$code})\n";
    echo $resp."\n";
    exit(1);
}

$json = json_decode($resp, true);
$b64 = $json['data'][0]['b64_json'] ?? null;

if (! $b64) {
    echo "NO DATA\n";
    exit(1);
}

file_put_contents($file, base64_decode($b64));
$size = round(filesize($file) / 1024);
echo "OK ({$size}KB)\n";
