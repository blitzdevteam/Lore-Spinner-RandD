<?php

declare(strict_types=1);

namespace App\Enums\Comment;

use App\Traits\EnumToArray;
use Filament\Support\Contracts\HasLabel;

enum CommentStatusEnum: string implements HasLabel
{
    use EnumToArray;

    case PENDING = 'pending';
    case APPROVED = 'approved';
    case DECLINED = 'declined';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::APPROVED => 'Approved',
            self::DECLINED => 'Declined',
        };
    }

    public function getSeverity(): string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::APPROVED => 'info',
            self::DECLINED => 'danger',
        };
    }
}
