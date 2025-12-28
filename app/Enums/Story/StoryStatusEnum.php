<?php

declare(strict_types=1);

namespace App\Enums\Story;

use App\Traits\EnumToArray;
use Filament\Support\Contracts\HasLabel;

enum StoryStatusEnum: string implements HasLabel
{
    use EnumToArray;

    case DRAFT = 'draft';
    case AWAITING_EXTRACTING_CHAPTERS_REQUEST = 'awaiting-extracting-chapters-request';
    case EXTRACTING_CHAPTERS = 'extracting-chapters';
    case PUBLISHED = 'published';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::AWAITING_EXTRACTING_CHAPTERS_REQUEST => 'Awaiting extracting chapters request',
            self::EXTRACTING_CHAPTERS => 'Extracting chapters',
            self::PUBLISHED => 'Published',
        };
    }

    public function getSeverity(): string
    {
        return match ($this) {
            self::DRAFT => 'warning',
            self::AWAITING_EXTRACTING_CHAPTERS_REQUEST => 'warning',
            self::EXTRACTING_CHAPTERS => 'info',
            self::PUBLISHED => 'success',
        };
    }
}
