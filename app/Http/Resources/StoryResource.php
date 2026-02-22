<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Story;
use Illuminate\Http\Request;

/**
 * @mixin Story
 */
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
            'status' => $this->status->toResource(),
            'rating' => $this->rating->toResource(),
            'published_at' => $this->published_at,
            'updated_at' => $this->updated_at,
            'cover' => $this->getFirstMediaUrl('cover'),

            // Relations
            'category' => CategoryResource::make($this->whenLoaded('category')),
            'creator' => CreatorResource::make($this->whenLoaded('creator')),
            'chapters' => ChapterResource::collection($this->whenLoaded('chapters')),

            // Counts
            'chapters_count' => $this->whenCounted('chapters'),
            'comments_count' => $this->whenCounted('comments'),
        ];
    }
}
