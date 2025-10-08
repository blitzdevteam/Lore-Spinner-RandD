<?php

declare(strict_types=1);

namespace App\Enums\Story;

use App\Traits\EnumToArray;
use Filament\Support\Contracts\HasLabel;

enum StatusEnum: string implements HasLabel
{
    use EnumToArray;

    case PENDING = 'pending';
    case APPROVED = 'approved';
    case DECLINED = 'declined';
    case PUBLISHED = 'published';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::APPROVED => 'Approved',
            self::DECLINED => 'Declined',
            self::PUBLISHED => 'Published',
        };
    }
}
