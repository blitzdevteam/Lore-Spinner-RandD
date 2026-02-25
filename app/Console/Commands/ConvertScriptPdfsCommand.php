<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Smalot\PdfParser\Parser;

final class ConvertScriptPdfsCommand extends Command
{
    protected $signature = 'stories:convert-scripts
                            {--dir=database/stories : Directory containing PDF scripts}';

    protected $description = 'Convert screenplay PDF scripts to .txt files for the extraction pipeline';

    public function handle(): int
    {
        $dir = base_path($this->option('dir'));

        if (! File::isDirectory($dir)) {
            $this->error("Directory not found: {$dir}");

            return self::FAILURE;
        }

        $pdfs = File::glob($dir . '/*_script.pdf');

        if (empty($pdfs)) {
            $this->error('No *_script.pdf files found in: ' . $dir);

            return self::FAILURE;
        }

        $parser = new Parser;
        $converted = 0;

        foreach ($pdfs as $pdfPath) {
            $basename = pathinfo($pdfPath, PATHINFO_FILENAME);
            $txtPath = $dir . '/' . $basename . '.txt';

            $this->info("Converting: {$basename}.pdf");

            try {
                $pdf = $parser->parseFile($pdfPath);
                $text = $pdf->getText();

                $text = $this->cleanScreenplayText($text);

                File::put($txtPath, $text);
                $this->info("  -> {$basename}.txt (" . strlen($text) . ' bytes)');
                $converted++;
            } catch (\Throwable $e) {
                $this->error("  Failed: {$e->getMessage()}");
            }
        }

        $this->newLine();
        $this->info("Converted {$converted}/" . count($pdfs) . ' PDF scripts.');

        return self::SUCCESS;
    }

    private function cleanScreenplayText(string $text): string
    {
        // Normalize line endings
        $text = str_replace("\r\n", "\n", $text);
        $text = str_replace("\r", "\n", $text);

        // Remove standalone page numbers (lines containing only a number and optional period)
        $text = preg_replace('/^\d+\.?\s*$/m', '', $text);

        // Remove excessive blank lines (more than 2 consecutive)
        $text = preg_replace("/\n{4,}/", "\n\n\n", $text);

        // Trim trailing whitespace per line
        $text = implode("\n", array_map('rtrim', explode("\n", $text)));

        return trim($text) . "\n";
    }
}
