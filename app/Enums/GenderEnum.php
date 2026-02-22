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

    public function getLabel(): string
    {
        return match ($this) {
            self::MALE => 'Male',
            self::FEMALE => 'Female',
        };
    }
}
