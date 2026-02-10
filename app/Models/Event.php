<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Event extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];

    /**
     * @return BelongsTo<$this, Chapter>
     */
    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }
}
