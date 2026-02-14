<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WriterResource extends BaseResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'username' => $this->username,
            'avatar' => $this->avatar,
            'bio' => $this->bio,
            'is_active' => $this->is_active,

            // Relations
            'stories' => StoryResource::collection($this->whenLoaded('stories')),
        ];
    }
}
