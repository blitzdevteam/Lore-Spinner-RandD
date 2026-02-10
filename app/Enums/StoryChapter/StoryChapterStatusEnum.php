<?php

declare(strict_types=1);

namespace App\Enums\StoryChapter;

use App\Traits\EnumToArray;
use Filament\Support\Contracts\HasLabel;

enum StoryChapterStatusEnum: string implements HasLabel
{
    use EnumToArray;

    case AWAITING_WRITER_REVIEW = 'awaiting-writer-review';
    case APPROVED_BY_WRITER = 'approved-by-writer';
    case AWAITING_MANAGER_REVIEW = 'awaiting-manager-review';
    case APPROVED_BY_MANAGER = 'approved-by-manager';
    case AWAITING_EXTRACTING_EVENTS_REQUEST = 'awaiting-extracting-events-request';
    case EXTRACTING_EVENTS = 'extracting-events';
    case READY_TO_PLAY = 'ready-to-play';
    case REJECTED = 'rejected';

    public function getLabel(): string
    {
        return match ($this) {
            self::AWAITING_WRITER_REVIEW => 'Awaiting Writer Review',
            self::APPROVED_BY_WRITER => 'Approved by Writer',
            self::AWAITING_MANAGER_REVIEW => 'Awaiting Manager Review',
            self::APPROVED_BY_MANAGER => 'Approved by Manager',
            self::AWAITING_EXTRACTING_EVENTS_REQUEST => 'Awaiting Event Extraction Request',
            self::EXTRACTING_EVENTS => 'Extracting Events',
            self::READY_TO_PLAY => 'Ready to Play',
            self::REJECTED => 'Rejected',
        };
    }

    public function getSeverity(): string
    {
        return match ($this) {
            self::AWAITING_WRITER_REVIEW => 'warning',
            self::APPROVED_BY_WRITER => 'success',
            self::AWAITING_MANAGER_REVIEW => 'warning',
            self::APPROVED_BY_MANAGER => 'success',
            self::AWAITING_EXTRACTING_EVENTS_REQUEST => 'warning',
            self::EXTRACTING_EVENTS => 'info',
            self::READY_TO_PLAY => 'success',
            self::REJECTED => 'danger',
        };
    }
}
