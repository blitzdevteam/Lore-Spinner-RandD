<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Prompt extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    /**
     * @return BelongsTo<Game, $this>
     */
    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    /**
    * @return BelongsTo<Event, $this>
    */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
