<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Event;
use Illuminate\Http\Request;

/**
 * @mixin Event
 */
class EventResource extends BaseResource
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
            'content' => $this->content,
            'attributes' => $this->attributes,
            'objectives' => $this->objectives,

            // Relations
            'chapter' => ChapterResource::make($this->whenLoaded('chapter')),
        ];
    }
}
