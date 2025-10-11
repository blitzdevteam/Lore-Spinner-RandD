<?php

namespace App\Models;

use App\Enums\Comment\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Comment extends Model
{
    use HasFactory;

    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    protected function casts(): array
    {
        return [
            'status' => StatusEnum::class
        ];
    }

    /**
     * @return MorphTo<$this>
     */
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return MorphTo<$this>
     */
    public function author(): MorphTo
    {
        return $this->morphTo();
    }
}
