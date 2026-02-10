<?php

declare(strict_types=1);

namespace App\Helpers\Story;

final class NumberedLineExtractorHelper
{
    /**
     * Extract chapters content from full story text using line number markers.
     *
     * @param  string  $fullText  The complete story text with #{line}# markers
     * @param  array<int, array{position: int, title: string, teaser: string, start_line: int, end_line: int}>  $chapters
     * @return array<int, array{position: int, title: string, teaser: string, content: string}>
     */
    public static function handle(string $fullText, array $chapters): array
    {
        $lines = self::parseNumberedLines($fullText);

        return array_map(fn (array $chapter): array => [
            'position' => $chapter['position'],
            'title' => $chapter['title'],
            'teaser' => $chapter['teaser'],
            'content' => self::extractContent($lines, $chapter['start_line'], $chapter['end_line']),
        ], $chapters);
    }

    /**
     * Parse the text and return an array keyed by line number.
     *
     * @return array<int, string>
     */
    private static function parseNumberedLines(string $text): array
    {
        $lines = [];
        $rawLines = explode("\n", $text);

        foreach ($rawLines as $rawLine) {
            if (preg_match('/^#(\d+)#\s?(.*)$/', $rawLine, $matches)) {
                $lineNumber = (int) $matches[1];
                $lines[$lineNumber] = $matches[2];
            }
        }

        return $lines;
    }

    /**
     * Extract content between start and end line numbers.
     */
    private static function extractContent(array $lines, int $startLine, int $endLine): string
    {
        $chapterLines = [];

        for ($i = $startLine; $i <= $endLine; $i++) {
            if (isset($lines[$i])) {
                $chapterLines[] = $lines[$i];
            }
        }

        return implode("\n", $chapterLines);
    }
}
