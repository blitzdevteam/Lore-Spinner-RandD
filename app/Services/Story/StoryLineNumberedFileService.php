<?php

declare(strict_types=1);

namespace App\Services\Story;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

readonly final class StoryLineNumberedFileService
{
    public static function handle(string $content): string
    {
        $numberedContent = self::addLineNumbers($content);

        return self::createTempFile($numberedContent);
    }

    private static function addLineNumbers(string $content): string
    {
        $lines = explode("\n", $content);

        $numberedLines = array_map(
            fn (string $line, int $index): string => "#{$index}# {$line}",
            $lines,
            range(1, count($lines))
        );

        return implode("\n", $numberedLines);
    }

    private static function createTempFile(string $content): string
    {
        $disk = Storage::disk('private');

        $filename = Str::uuid()->toString().'.txt';
        $tempPath = "temp/{$filename}";

        $disk->put($tempPath, $content);

        return $disk->path($tempPath);
    }
}

