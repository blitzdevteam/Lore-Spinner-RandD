<?php

declare(strict_types=1);

namespace App\Enums\Story;

use App\Traits\EnumToArray;
use Filament\Support\Contracts\HasLabel;

enum StoryStatusEnum: string implements HasLabel
{
    use EnumToArray;

    case DRAFT = 'draft';
    case PUBLISHED = 'published';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::PUBLISHED => 'Published',
        };
    }

    public function getSeverity(): string
    {
        return match ($this) {
            self::DRAFT => 'warning',
            self::PUBLISHED => 'success',
        };
    }
}
