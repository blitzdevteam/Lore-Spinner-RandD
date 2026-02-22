<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Comment\CommentStatusEnum;
use Database\Factories\CommentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $author_type
 * @property int $author_id
 * @property string $commentable_type
 * @property int $commentable_id
 * @property string $content
 * @property CommentStatusEnum $status
 * @property Carbon|null $approved_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read Model $commentable
 * @property-read Model $author
 */
final class Comment extends Model
{
    /** @use HasFactory<CommentFactory> */
    use HasFactory;

    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];

    protected $casts = [
        'status' => CommentStatusEnum::class,
    ];

    /**
     * @return MorphTo<Model, $this>
     */
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return MorphTo<Model, $this>
     */
    public function author(): MorphTo
    {
        return $this->morphTo();
    }
}
