<?php

namespace App\Models;

use App\Enums\Story\RatingEnum;
use App\Enums\Story\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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

    /**
     * @return HasMany<$this, StoryChapter>
     */
    public function chapters(): HasMany
    {
        return $this->hasMany(StoryChapter::class);
    }

    /**
     * @return MorphMany<$this, Comment>
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
