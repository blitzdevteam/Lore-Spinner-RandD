<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\StoryChapter\StoryChapterStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class StoryChapter extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => StoryChapterStatusEnum::class,
        ];
    }

    /**
     * @return BelongsTo<$this, Story>
     */
    public function story(): BelongsTo
    {
        return $this->belongsTo(Story::class);
    }
}
