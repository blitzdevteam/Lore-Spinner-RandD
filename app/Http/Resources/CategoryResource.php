<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;

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
