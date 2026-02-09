<?php

declare(strict_types=1);

use App\Ai\Schema\Story\StoryChapterEventExtractorSchema;
use App\Http\Controllers;
use App\Services\Story\StoryAddLineToContentService;
use App\Services\Story\StoryChapterExtractorByContentService;
use Illuminate\Support\Facades\Route;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Facades\Prism;

require __DIR__.'/routes/user.php';

Route::get('/', Controllers\IndexController::class)->name('index');

Route::get('c', function () {
    $story = \App\Models\Story::first();
    $linedContent = StoryAddLineToContentService::handle(
        file_get_contents($story->getFirstMediaPath('script'))
    );

    $response = Prism::structured()
        ->using(Provider::OpenAI, 'gpt-5.2')
        ->usingTemperature(0)
        ->withSystemPrompt(view('ai-prompts.chapter-extraction.system-prompt'))
        ->withClientOptions([
            'connect_timeout' => 10,
            'timeout' => 600,
        ])
        ->withSchema(ChapterExtractorSchema::getSchema())
        ->withPrompt("Story:\n\n" . $linedContent['content'])
        ->asStructured();

    $chapters = StoryChapterExtractorByContentService::handle(
        $linedContent['content'],
        $response->structured['chapters']
    );

    dd($chapters);
});

Route::get('test', function () {
    $chapters = \App\Models\Story::first()->chapters()->orderBy('position')->get();
    $linedContent = StoryAddLineToContentService::handle($chapters[1]->content);

    $response = Prism::structured()
        ->using(Provider::OpenAI, 'gpt-5.2')
        ->usingTemperature(0)
        ->withSystemPrompt(view('ai-prompts.chapter-event-extraction.system-prompt'))
        ->withClientOptions([
            'connect_timeout' => 10,
            'timeout' => 600,
            'token_limit' => 32000,
        ])
        ->withProviderOptions([
            'prompt_cache_key' => bin2hex(random_bytes(16))
        ])
        ->withSchema(StoryChapterEventExtractorSchema::getSchema())
        ->withPrompt(implode("\n", [
            'Line Lengths:',
            '',
            $linedContent['length'],
            '',
            '',
            'Chapter Content:',
            '',
            $linedContent['content']
        ]))
        ->asStructured();

    dump($response->usage);
    echo json_encode($response->structured);

    $chapters = \App\Models\Story::first()->chapters()->orderBy('position')->get();
    $content = $chapters[1]->content;
    $events = $response->structured['events'];

    function extractEventText(array $rawLines, array $event): string
    {
        $sLineIdx = $event['start']['line'] - 1;
        $eLineIdx = $event['end']['line'] - 1;

        if ($sLineIdx < 0 || $eLineIdx >= count($rawLines)) {
            throw new RuntimeException("Line out of range for event {$event['index']}");
        }

        $startLine = $rawLines[$sLineIdx];
        $endLine   = $rawLines[$eLineIdx];

        // Helper: mb_substr with UTF-8
        $mb = fn(string $s, int $start, ?int $len = null) =>
        $len === null ? mb_substr($s, $start, null, 'UTF-8') : mb_substr($s, $start, $len, 'UTF-8');

        if ($event['start']['line'] === $event['end']['line']) {
            $len = $event['end']['char'] - $event['start']['char'];
            return $mb($startLine, $event['start']['char'], $len);
        }

        $parts = [];

        // start line slice
        $parts[] = $mb($startLine, $event['start']['char']);

        // middle full lines
        for ($i = $sLineIdx + 1; $i < $eLineIdx; $i++) {
            $parts[] = $rawLines[$i];
        }

        // end line slice (0..endChar exclusive)
        $parts[] = $mb($endLine, 0, $event['end']['char']);

        return implode("\n", $parts);
    }

    function extractAllEvents(string $rawChapterText, array $events): array
    {
        $rawLines = preg_split("/\r\n|\n|\r/", $rawChapterText); // must be aligned to original lines
        $out = [];

        foreach ($events as $ev) {
            $out[] = [
                'index' => $ev['index'],
                'title' => $ev['title'],
                'text'  => extractEventText($rawLines, $ev),
            ];
        }

        return $out;
    }

    $newContent = '';
    foreach (extractAllEvents($content, $events) as $ev) {
        dump($ev);
        $newContent .= $ev['text'];
    }
    dump(md5($newContent), md5($content));
    echo($newContent);
    echo "<br>";
    echo "<br>";
    echo "<br>";
    echo $content;
});

Route::get('test1', function () {
    $story = \App\Models\Story::first();
    $linedContent = StoryAddLineToContentService::handle(
        file_get_contents($story->getFirstMediaPath('script'))
    );

    $response = new \App\Ai\Agents\ChapterExtractorAgent()
        ->prompt("Story:\n\n" . $linedContent['content']);

    dd($response);
});
