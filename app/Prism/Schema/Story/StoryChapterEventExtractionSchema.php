<?php

declare(strict_types=1);

namespace App\Prism\Schema\Story;

use Prism\Prism\Schema\ArraySchema;
use Prism\Prism\Schema\NumberSchema;
use Prism\Prism\Schema\ObjectSchema;
use Prism\Prism\Schema\StringSchema;

final readonly class StoryChapterEventExtractionSchema
{
    public static function getSchema(): ObjectSchema
    {
        return new ObjectSchema(
            name: 'story_chapter_event_extraction',
            description: 'Extracts actionable, immutable canon events from a line-marked story excerpt. The model MUST return coordinates only (line + char offsets) so the application can slice the original text verbatim. No paraphrasing, no evidence text.',
            properties: [
                new ArraySchema(
                    name: 'events',
                    description: 'Ordered list of extracted events in reading order. Each event must reference start/end coordinates within the provided #line# marked excerpt (excluding the marker from char indexing).',
                    items: new ObjectSchema(
                        name: 'event',
                        description: 'A single actionable event represented by a title and coordinate boundaries (no evidence text).',
                        properties: [
                            new NumberSchema(
                                name: 'index',
                                description: '1-based sequential event index in this extraction output (1, 2, 3...).',
                            ),
                            new StringSchema(
                                name: 'title',
                                description: 'Concise event title (3–10 words). Must not add details not present in the excerpt.',
                            ),
                            new ObjectSchema(
                                name: 'start',
                                description: 'Start coordinate for the event. line is the numeric line marker; char is 0-based index within the line text EXCLUDING the "#<LINE># " marker.',
                                properties: [
                                    new NumberSchema(
                                        name: 'line',
                                        description: 'Start line number (must exist in the provided excerpt).',
                                    ),
                                    new NumberSchema(
                                        name: 'char',
                                        description: 'Start character offset (0-based) within the line text (excluding the line marker).',
                                    ),
                                ],
                            ),
                            new ObjectSchema(
                                name: 'end',
                                description: 'End coordinate for the event. end_char is EXCLUSIVE (slice up to but not including this char). May end mid-line.',
                                properties: [
                                    new NumberSchema(
                                        name: 'line',
                                        description: 'End line number (must exist in the provided excerpt).',
                                    ),
                                    new NumberSchema(
                                        name: 'char',
                                        description: 'End character offset (0-based, EXCLUSIVE) within the line text (excluding the line marker).',
                                    ),
                                ],
                            ),
                        ],
                    ),
                ),
            ],
        );
    }
}
