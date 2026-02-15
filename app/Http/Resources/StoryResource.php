<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;

class StoryResource extends BaseResource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'teaser' => $this->teaser,
            'status' => $this->status,
            'rating' => $this->rating,
            'published_at' => $this->published_at,
            'cover' => $this->getFirstMediaUrl('cover'),

            // Relations
            'category' => CategoryResource::make($this->whenLoaded('category')),
            'creator' => CreatorResource::make($this->whenLoaded('creator')),
            'chapters' => ChapterResource::collection($this->whenLoaded('chapters')),
        ];
    }
}
