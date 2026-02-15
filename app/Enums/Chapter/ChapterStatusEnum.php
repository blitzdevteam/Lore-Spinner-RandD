<?php

declare(strict_types=1);

namespace App\Enums\Chapter;

use App\Traits\EnumToArray;
use Filament\Support\Contracts\HasLabel;

enum ChapterStatusEnum: string implements HasLabel
{
    use EnumToArray;

    case AWAITING_CREATOR_REVIEW = 'awaiting-creator-review';
    case AWAITING_EXTRACTING_EVENTS_REQUEST = 'awaiting-extracting-events-request';
    case EXTRACTING_EVENTS = 'extracting-events';
    case WAITING_FOR_EVENT_PREPARATION = 'waiting-for-event-preparation';
    case READY_TO_PLAY = 'ready-to-play';
    case REJECTED = 'rejected';

    public function getLabel(): string
    {
        return match ($this) {
            self::AWAITING_CREATOR_REVIEW => 'Awaiting Creator Review',
            self::AWAITING_EXTRACTING_EVENTS_REQUEST => 'Awaiting Event Extraction Request',
            self::EXTRACTING_EVENTS => 'Extracting Events',
            self::WAITING_FOR_EVENT_PREPARATION => 'Waiting for Event Preparation',
            self::READY_TO_PLAY => 'Ready to Play',
            self::REJECTED => 'Rejected',
        };
    }

    public function getSeverity(): string
    {
        return match ($this) {
            self::AWAITING_CREATOR_REVIEW => 'warning',
            self::AWAITING_EXTRACTING_EVENTS_REQUEST => 'warning',
            self::EXTRACTING_EVENTS => 'info',
            self::WAITING_FOR_EVENT_PREPARATION => 'warning',
            self::READY_TO_PLAY => 'success',
            self::REJECTED => 'danger',
        };
    }
}
