<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;

class EventResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'position' => $this->position,
            'title' => $this->title,
            'content' => $this->content,
            'objectives' => $this->teaser,
            'attributes' => $this->status,

            // Relations
            'chapter' => ChapterResource::make($this->whenLoaded('chapter')),
        ];
    }
}
