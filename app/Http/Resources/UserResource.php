<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;

/**
 * @mixin User
 */
class UserResource extends BaseResource
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
            'email' => $this->email,
            'gender' => $this->gender,
            'avatar' => $this->avatar,
            'bio' => $this->bio,
            'is_active' => $this->is_active,
            'email_verified_at' => $this->email_verified_at,

            // Relations
            'games' => GameResource::collection($this->whenLoaded('games')),

            // Counts
            'games_count' => $this->whenCounted('games'),
        ];
    }
}
