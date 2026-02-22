<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;

class ChapterResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'position' => $this->position,
            'title' => $this->title,
            'teaser' => $this->teaser,
            'content' => $this->content,
            'status' => $this->status->toResource(),

            // Relations
            'story' => StoryResource::make($this->whenLoaded('story')),
            'events' => EventResource::collection($this->whenLoaded('events')),

            // Counts
            'events_count' => $this->whenCounted('events'),
        ];
    }
}
