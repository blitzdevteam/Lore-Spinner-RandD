<?php

declare(strict_types=1);

namespace App\Helpers;

use RuntimeException;

readonly final class TextRangeExtractorHelper
{
    /**
     * Extract text from raw lines using start/end line and character positions.
     *
     * @param string $content Raw text content
     * @param array{line: int, char: int} $start
     * @param array{line: int, char: int} $end
     *
     * @throws RuntimeException When line indices are out of range
     */
    public static function handle(string $content, array $start, array $end): string
    {
        $rawLines = explode("\n", str_replace("\r\n", "\n", $content));

        $startLineIdx = $start['line'] - 1;
        $endLineIdx = $end['line'] - 1;

        if ($startLineIdx < 0 || $endLineIdx >= count($rawLines)) {
            throw new RuntimeException('Line out of range');
        }

        $startLine = $rawLines[$startLineIdx];
        $endLine = $rawLines[$endLineIdx];

        $mb = fn (string $s, int $offset, ?int $len = null): string => mb_substr($s, $offset, $len, 'UTF-8');

        if ($start['line'] === $end['line']) {
            return $mb($startLine, $start['char'], $end['char'] - $start['char']);
        }

        $parts = [];

        $parts[] = $mb($startLine, $start['char']);

        for ($i = $startLineIdx + 1; $i < $endLineIdx; $i++) {
            $parts[] = $rawLines[$i];
        }

        $parts[] = $mb($endLine, 0, $end['char']);

        return implode("\n", $parts);
    }
}

