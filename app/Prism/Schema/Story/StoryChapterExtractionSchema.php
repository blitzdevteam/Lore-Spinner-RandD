<?php

declare(strict_types=1);

namespace App\Prism\Schema\Story;

use Prism\Prism\Schema\ArraySchema;
use Prism\Prism\Schema\NumberSchema;
use Prism\Prism\Schema\ObjectSchema;
use Prism\Prism\Schema\StringSchema;

final readonly class StoryChapterExtractionSchema
{
    public static function getSchema(): ObjectSchema
    {
        return new ObjectSchema(
            name: 'story_chapter_extraction',
            description: 'Chapterization output for a long fiction story. The model MUST split the ENTIRE provided story into a SMALL number of LARGE chapters using line number anchors. Chapters must be sequential, non-overlapping, and cover the text from line 1 to the final line with no gaps. The model MUST NOT rewrite/paraphrase/correct the story.',
            properties: [
                new ArraySchema(
                    name: 'chapters',
                    description: 'Ordered list of extracted chapters. Must be 1-indexed, strictly ascending, sequential, and must cover the entire story without gaps or overlaps. Prefer fewer, larger chapters over many small ones; avoid creating a chapter for a short beat unless it is a true act break.',
                    items: new ObjectSchema(
                        name: 'chapter',
                        description: 'A single chapter unit with spoiler-free metadata and line number anchors. The start_line must be exactly (previous chapter end_line + 1).',
                        properties: [
                            new NumberSchema(
                                name: 'position',
                                description: 'Sequential chapter index (1-indexed). Must start at 1, be unique, and increase by 1 with no skips (1,2,3...).',
                            ),
                            new StringSchema(
                                name: 'title',
                                description: 'Short, human-readable, non-spoiler chapter title. Should capture theme/setting/atmosphere without revealing plot twists, secrets, betrayals, character fates, or major revelations.',
                            ),
                            new StringSchema(
                                name: 'teaser',
                                description: 'Spoiler-free teaser (1–2 sentences) that hints at atmosphere or situation only. MUST NOT reveal plot outcomes, character deaths, betrayals, secrets, surprises, or anything the reader would only learn by reading the chapter.',
                            ),
                            new NumberSchema(
                                name: 'start_line',
                                description: 'The line number where this chapter begins. First chapter must start at 1. Each subsequent chapter must start at (previous chapter end_line + 1).',
                            ),
                            new NumberSchema(
                                name: 'end_line',
                                description: 'The line number where this chapter ends. Must be >= start_line. Last chapter must end at the final line number of the story.',
                            ),
                        ],
                    ),
                ),
            ],
        );
    }
}
