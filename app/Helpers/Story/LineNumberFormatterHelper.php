<?php

declare(strict_types=1);

namespace App\Helpers\Story;

readonly final class LineNumberFormatterHelper
{
    /**
     * Return an array with:
     *  - length: "1=5\n2=10\n..." (lineIndex=lineLength)
     *  - content: "#1# line one\n#2# line two\n..."
     *
     * @return array{length: string, content: string}
     */
    public static function handle(string $content): array
    {
        // Normalize CRLF to LF to match explode("\n", ...) semantics across platforms.
        $normalized = str_replace("\r\n", "\n", $content);
        $lines = explode("\n", $normalized);

        $lengthLines = [];
        $numberedLines = [];

        foreach ($lines as $i => $line) {
            $index = $i + 1;
            $len = mb_strlen($line, 'UTF-8');
            $lengthLines[] = "{$index}={$len}";
            $numberedLines[] = "#{$index}# {$line}";
        }

        return [
            'length'  => implode("\n", $lengthLines),
            'content' => implode("\n", $numberedLines),
        ];
    }
}

