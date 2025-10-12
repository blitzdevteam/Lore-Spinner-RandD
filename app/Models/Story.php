<?php

namespace App\Models;

use App\Enums\Story\RatingEnum;
use App\Enums\Story\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

final class Story extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

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

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')
            ->acceptsMimeTypes(['image/jpeg', 'image/png'])
            ->singleFile();

        $this->addMediaCollection('gallery');
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
