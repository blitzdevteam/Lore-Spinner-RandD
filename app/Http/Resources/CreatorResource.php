<?php

namespace App\Http\Resources;

use App\Models\Creator;
use Illuminate\Http\Request;

/**
 * @mixin Creator
 */
class CreatorResource extends BaseResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'username' => $this->username,
            'avatar' => $this->avatar,
            'bio' => $this->bio,
            'is_active' => $this->is_active,

            // Relations
            'stories' => StoryResource::collection($this->whenLoaded('stories')),

            // Counts
            'stories_count' => $this->whenCounted('stories'),
        ];
    }
}
