<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Comment\CommentStatusEnum;
use Database\Factories\CommentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

final class Comment extends Model
{
    /** @use HasFactory<CommentFactory> */
    use HasFactory;

    protected $guarded = [
        'id', 'created_at', 'updated_at',
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

    #[\Override]
    protected function casts(): array
    {
        return [
            'status' => CommentStatusEnum::class,
        ];
    }
}
