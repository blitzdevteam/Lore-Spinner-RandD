<?php

declare(strict_types=1);

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

    /**
     * @return HasMany<$this, Story>
     */
    public function stories(): HasMany
    {
        return $this->hasMany(Story::class);
    }

    #[\Override]
    protected function casts(): array
    {
        return [];
    }
}
