<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Chapter\ChapterStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $story_id
 * @property int $position
 * @property string $title
 * @property string $teaser
 * @property string $content
 * @property ChapterStatusEnum $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read Story $story
 */
final class Chapter extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];

    protected $casts = [
        'status' => ChapterStatusEnum::class,
    ];

    /**
     * @return BelongsTo<Story, $this>
     */
    public function story(): BelongsTo
    {
        return $this->belongsTo(Story::class);
    }

    /**
     * @return HasMany<Event, $this>
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
