<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class StoryChapter extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];

    /**
     * @return BelongsTo<$this, Story>
     */
    public function story(): BelongsTo
    {
        return $this->belongsTo(Story::class);
    }
}
