<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Story\StoryRatingEnum;
use App\Enums\Story\StoryStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

final class Story extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('script')
            ->acceptsMimeTypes(['text/plain'])
            ->singleFile();

        $this
            ->addMediaCollection('cover')
            ->acceptsMimeTypes(['image/jpeg', 'image/png'])
            ->singleFile();

        $this
            ->addMediaCollection('gallery')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }

    /**
     * @return BelongsTo<$this, Creator>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Creator::class);
    }

    /**
     * @return BelongsTo<$this, Category>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return HasMany<$this, Chapter>
     */
    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class);
    }

    /**
     * @return MorphMany<$this, Comment>
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    protected function casts(): array
    {
        return [
            'status' => StoryStatusEnum::class,
            'rating' => StoryRatingEnum::class,
            'published_at' => 'datetime',
        ];
    }
}
