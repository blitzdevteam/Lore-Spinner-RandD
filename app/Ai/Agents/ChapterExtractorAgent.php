<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Attributes\Model;
use Laravel\Ai\Attributes\Timeout;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Stringable;
use Throwable;

#[Model('gpt-5.2')]
#[Timeout(120)]
class ChapterExtractorAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    /**
     * Get the instructions that the agent should follow.
     * @throws Throwable
     */
    public function instructions(): Stringable|string
    {
        return view('ai.agents.chapter-extractor.system-prompt')->render();
    }

    /**
     * Get the agent's structured output schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'chapters' => $schema
                ->array()
                ->title('Chapters')
                ->description('Ordered list of extracted chapters. Must be 1-indexed, strictly ascending, sequential, and must cover the entire story without gaps or overlaps. Prefer fewer, larger chapters over many small ones; avoid creating a chapter for a short beat unless it is a true act break.')
                ->items(
                    $schema
                        ->object([
                            'position' => $schema
                                ->number()
                                ->title('Position')
                                ->description('Sequential chapter index (1-indexed). Must start at 1, be unique, and increase by 1 with no skips (1,2,3...).'),
                            'title' => $schema
                                ->string()
                                ->title('Title')
                                ->description('Short, human-readable, non-spoiler chapter title. Should capture theme/setting/atmosphere without revealing plot twists, secrets, betrayals, character fates, or major revelations.'),
                            'teaser' => $schema
                                ->string()
                                ->title('Teaser')
                                ->description('Spoiler-free teaser (1–2 sentences) that hints at atmosphere or situation only. MUST NOT reveal plot outcomes, character deaths, betrayals, secrets, surprises, or anything the reader would only learn by reading the chapter.'),
                            'start_line' => $schema
                                ->number()
                                ->title('Start Line')
                                ->description('The line number where this chapter begins. First chapter must start at 1. Each subsequent chapter must start at (previous chapter end_line + 1).'),
                            'end_line' => $schema
                                ->number()
                                ->title('End Line')
                                ->description('The line number where this chapter ends. Must be >= start_line. Last chapter must end at the final line number of the story.'),
                        ])
                        ->title('Chapter')
                        ->description('A single chapter unit with spoiler-free metadata and line number anchors. The start_line must be exactly (previous chapter end_line + 1).')
                )
        ];
    }
}
