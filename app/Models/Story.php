<?php

namespace App\Models;

use App\Enums\Story\RatingEnum;
use App\Enums\Story\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Story extends Model
{
    use HasFactory;

    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => StatusEnum::class,
            'rating' => RatingEnum::class,
            'published_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<$this, Writer>
     */
    public function writer(): BelongsTo
    {
        return $this->belongsTo(Writer::class);
    }

    /**
     * @return BelongsTo<$this, Category>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
