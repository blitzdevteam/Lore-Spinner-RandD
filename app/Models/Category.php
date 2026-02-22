<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $title
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read Collection<int, Story> $stories
 * @property-read int|null $stories_count
 */
final class Category extends Model
{
    /** @use HasFactory<CategoryFactory> */
    use HasFactory;

    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];

    /**
     * @return HasMany<Story, $this>
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
