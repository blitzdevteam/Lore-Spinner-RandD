<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Chapter;
use Illuminate\Http\Request;

/**
 * @mixin Chapter
 */
class CategoryResource extends BaseResource
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
            'title' => $this->title,

            // Relations
            'stories' => StoryResource::collection($this->whenLoaded('story')),
        ];
    }
}
