<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Story\StoryRatingEnum;
use App\Enums\Story\StoryStatusEnum;
use Database\Factories\StoryFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property int $id
 * @property int $creator_id
 * @property int $category_id
 * @property string $title
 * @property string|null $description
 * @property StoryStatusEnum $status
 * @property StoryRatingEnum $rating
 * @property Carbon|null $published_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Creator $creator
 * @property-read Category $category
 * @property-read Collection<int, Chapter> $chapters
 * @property-read int|null $chapters_count
 * @property-read Collection<int, Comment> $comments
 * @property-read int|null $comments_count
 */
final class Story extends Model implements HasMedia
{
    /** @use HasFactory<StoryFactory> */
    use HasFactory;

    use InteractsWithMedia;

    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];

    protected $casts = [
        'status' => StoryStatusEnum::class,
        'rating' => StoryRatingEnum::class,
        'published_at' => 'datetime',
    ];

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('script')
            ->acceptsMimeTypes(['text/plain'])
            ->singleFile()
            ->useDisk('private');

        $this
            ->addMediaCollection('cover')
            ->acceptsMimeTypes(['image/jpeg', 'image/png'])
            ->singleFile()
            ->useDisk('public');

        $this
            ->addMediaCollection('gallery')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->useDisk('public');
    }

    /**
     * @return BelongsTo<Creator, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Creator::class);
    }

    /**
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return HasMany<Chapter, $this>
     */
    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class);
    }

    /**
     * @return MorphMany<Comment, $this>
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * @param  Builder<Story>  $query
     */
    #[Scope]
    protected function draft(Builder $query): void
    {
        $query->where('status', StoryStatusEnum::DRAFT);
    }

    /**
     * @param  Builder<Story>  $query
     */
    #[Scope]
    protected function awaitingExtractingChaptersRequest(Builder $query): void
    {
        $query->where('status', StoryStatusEnum::AWAITING_EXTRACTING_CHAPTERS_REQUEST);
    }

    /**
     * @param  Builder<Story>  $query
     */
    #[Scope]
    protected function extractingChapters(Builder $query): void
    {
        $query->where('status', StoryStatusEnum::EXTRACTING_CHAPTERS);
    }

    /**
     * @param  Builder<Story>  $query
     */
    #[Scope]
    protected function published(Builder $query): void
    {
        $query->where('status', StoryStatusEnum::PUBLISHED);
    }
}
