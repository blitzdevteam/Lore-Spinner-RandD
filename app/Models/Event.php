<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $chapter_id
 * @property string $name
 * @property array<string> $attributes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read Chapter $chapter
 */
final class Event extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'attributes' => 'json'
        ];
    }

    /**
     * @return BelongsTo<Chapter, $this>
     */
    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }
}
