<?php

declare(strict_types=1);

namespace App\Enums\StoryChapter;

use App\Traits\EnumToArray;
use Filament\Support\Contracts\HasLabel;

enum StoryChapterStatusEnum: string implements HasLabel
{
    use EnumToArray;

    case AWAITING_PARAPHRASE_REQUEST = 'awaiting-paraphrase-request';
    case PARAPHRASING = 'paraphrasing';
    case AWAITING_PARAPHRASE_REVIEW = 'awaiting-paraphrase-review';
    case APPROVED_BY_WRITER = 'approved-by-writer';
    case AWAITING_MANAGER_REVIEW = 'awaiting-manager-review';
    case REJECTED = 'rejected';
    case AWAITING_EVENT_EXTRACTION = 'awaiting-event-extraction';
    case EXTRACTING_EVENTS = 'extracting-events';
    case READY_TO_PLAY = 'ready-to-play';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::AWAITING_PARAPHRASE_REQUEST => 'Awaiting Paraphrase Request',
            self::PARAPHRASING => 'Paraphrasing',
            self::AWAITING_PARAPHRASE_REVIEW => 'Awaiting Paraphrase Review',
            self::APPROVED_BY_WRITER => 'Approved by Writer',
            self::AWAITING_MANAGER_REVIEW => 'Awaiting Manager Review',
            self::REJECTED => 'Rejected',
            self::AWAITING_EVENT_EXTRACTION => 'Awaiting Event Extraction',
            self::EXTRACTING_EVENTS => 'Extracting Events',
            self::READY_TO_PLAY => 'Ready to Play',
        };
    }

    public function getSeverity(): string
    {
        return match ($this) {
            self::AWAITING_PARAPHRASE_REQUEST => 'warning',
            self::PARAPHRASING => 'info',
            self::AWAITING_PARAPHRASE_REVIEW => 'warning',
            self::APPROVED_BY_WRITER => 'success',
            self::AWAITING_MANAGER_REVIEW => 'warning',
            self::REJECTED => 'danger',
            self::AWAITING_EVENT_EXTRACTION => 'warning',
            self::EXTRACTING_EVENTS => 'info',
            self::READY_TO_PLAY => 'success',
        };
    }
}
