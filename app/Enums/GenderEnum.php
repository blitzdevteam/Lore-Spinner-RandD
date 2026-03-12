<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\EnumToArray;
use Filament\Support\Contracts\HasLabel;

enum GenderEnum: string implements HasLabel
{
    use EnumToArray;

    case MALE = 'male';
    case FEMALE = 'female';
    case NON_BINARY = 'non-binary';
    case PREFER_NOT_TO_SAY = 'prefer-not-to-say';

    public function getLabel(): string
    {
        return match ($this) {
            self::MALE => 'Male',
            self::FEMALE => 'Female',
            self::NON_BINARY => 'Non-binary',
            self::PREFER_NOT_TO_SAY => 'Prefer not to say',
        };
    }
}
