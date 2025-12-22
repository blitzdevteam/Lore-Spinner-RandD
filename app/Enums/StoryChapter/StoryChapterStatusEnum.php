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
    case APPROVED = 'approved';
    case LOCKED = 'locked';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::AWAITING_PARAPHRASE_REQUEST => 'Awaiting Paraphrase Request',
            self::PARAPHRASING => 'Paraphrasing',
            self::AWAITING_PARAPHRASE_REVIEW => 'Awaiting Paraphrase Review',
            self::APPROVED => 'Approved',
            self::LOCKED => 'Locked',
        };
    }

    public function getSeverity(): string
    {
        return match ($this) {
            self::AWAITING_PARAPHRASE_REQUEST => 'warning',
            self::PARAPHRASING => 'info',
            self::AWAITING_PARAPHRASE_REVIEW => 'warning',
            self::APPROVED => 'success',
            self::LOCKED => 'gray',
        };
    }
}
