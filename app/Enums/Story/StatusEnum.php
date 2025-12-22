<?php

declare(strict_types=1);

namespace App\Enums\Story;

use App\Traits\EnumToArray;
use Filament\Support\Contracts\HasLabel;

enum StatusEnum: string implements HasLabel
{
    use EnumToArray;

    case PENDING = 'pending';
    case AI_REVIEWED = 'ai-reviewed';
    case APPROVED = 'approved';
    case DECLINED = 'declined';
    case PUBLISHED = 'published';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::AI_REVIEWED => 'Ai Reviewed',
            self::APPROVED => 'Approved',
            self::DECLINED => 'Declined',
            self::PUBLISHED => 'Published',
        };
    }

    public function getSeverity(): string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::AI_REVIEWED => 'info',
            self::APPROVED => 'primary',
            self::DECLINED => 'danger',
            self::PUBLISHED => 'success',
        };
    }
}
