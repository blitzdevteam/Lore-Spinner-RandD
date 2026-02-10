<?php

declare(strict_types=1);

namespace App\Helpers\Story;

final class NumberedLineExtractorHelper
{
    /**
     * Extract content segments from full text using line number markers.
     *
     * @param  string  $fullText  The complete text with #{line}# markers
     * @param  array<int, array{position: int, title: string, teaser: string, start_line: int, end_line: int}>  $segments
     * @return array<int, array{position: int, title: string, teaser: string, content: string}>
     */
    public static function handle(string $fullText, array $segments): array
    {
        $lines = self::parseNumberedLines($fullText);

        return array_map(fn (array $segment): array => [
            'position' => $segment['position'],
            'title' => $segment['title'],
            'teaser' => $segment['teaser'],
            'content' => self::extractContent($lines, $segment['start_line'], $segment['end_line']),
        ], $segments);
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
        $segmentLines = [];

        for ($i = $startLine; $i <= $endLine; $i++) {
            if (isset($lines[$i])) {
                $segmentLines[] = $lines[$i];
            }
        }

        return implode("\n", $segmentLines);
    }
}
