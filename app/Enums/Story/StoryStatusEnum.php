<?php

declare(strict_types=1);

namespace App\Enums\Story;

use App\Traits\EnumToArray;
use Filament\Support\Contracts\HasLabel;

enum StoryStatusEnum: string implements HasLabel
{
    use EnumToArray;

    case DRAFT = 'draft';
    case MANAGER_REVIEW = 'manager-review';
    case REJECT = 'reject';
    case AWAITING_EXTRACTING_EVENTS = 'awaiting-extracting-events';
    case EXTRACTING_EVENTS = 'extracting-events';
    case READY_TO_PLAY = 'ready-to-play';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::MANAGER_REVIEW => 'Manager Review',
            self::REJECT => 'Rejected',
            self::AWAITING_EXTRACTING_EVENTS => 'Awaiting Extracting Events',
            self::EXTRACTING_EVENTS => 'Extracting Events',
            self::READY_TO_PLAY => 'Ready to Play',
        };
    }

    public function getSeverity(): string
    {
        return match ($this) {
            self::DRAFT => 'warning',
            self::MANAGER_REVIEW => 'info',
            self::REJECT => 'danger',
            self::AWAITING_EXTRACTING_EVENTS => 'warning',
            self::EXTRACTING_EVENTS => 'info',
            self::READY_TO_PLAY => 'success',
        };
    }
}
