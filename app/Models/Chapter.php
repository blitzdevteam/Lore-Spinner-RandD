<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Chapter\ChapterStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Chapter extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];

    #[\Override]
    protected function casts(): array
    {
        return [
            'status' => ChapterStatusEnum::class,
        ];
    }

    /**
     * @return BelongsTo<$this, Story>
     */
    public function story(): BelongsTo
    {
        return $this->belongsTo(Story::class);
    }

    /**
     * @return HasMany<$this, Event>
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
