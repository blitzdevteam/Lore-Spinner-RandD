<?php

declare(strict_types=1);

namespace App\Services\Story;

readonly final class StoryAddLineToContentService
{
    public static function handle(string $content): string
    {
        $lines = explode("\n", $content);

        $numberedLines = array_map(
            fn (string $line, int $index): string => "#{$index}# {$line}",
            $lines,
            range(1, count($lines))
        );

        return implode("\n", $numberedLines);
    }
}

