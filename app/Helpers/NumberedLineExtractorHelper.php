<?php

declare(strict_types=1);

namespace App\Helpers;

final class NumberedLineExtractorHelper
{
    /**
     * Extract content segment from full text using line number markers.
     *
     * @param string $fullText The complete text with #{line}# markers
     * @param int $startLine Starting line number
     * @param int $endLine Ending line number
     */
    public static function handle(string $fullText, int $startLine, int $endLine): string
    {
        return self::extractContent(
            self::parseNumberedLines($fullText),
            $startLine,
            $endLine
        );
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
                $lineNumber = (int)$matches[1];
                $lines[$lineNumber] = $matches[2];
            }
        }

        return $lines;
    }

    /**
     *  Extract content between start and end line numbers.
     *
     * @param string[] $lines
     * @param int $startLine
     * @param int $endLine
     * @return string
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
