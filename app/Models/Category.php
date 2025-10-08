<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Category extends Model
{
    use HasFactory;

    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];

    protected function casts(): array
    {
        return [];
    }

    /**
     * @return HasMany<$this, Story>
     */
    public function stories(): HasMany
    {
        return $this->hasMany(Story::class);
    }
}
