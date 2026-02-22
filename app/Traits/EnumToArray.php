<?php

declare(strict_types=1);

namespace App\Traits;

trait EnumToArray
{
    /**
     * @return array<int, string>
     */
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * @return array<string, string>
     */
    public static function array(): array
    {
        return array_combine(self::values(), self::names());
    }

    /**
     * @return list<array{value: string, name: string, label: mixed}>
     */
    public static function fullCase(): array
    {
        return array_map(fn ($value, $name): array => [
            'value' => $value,
            'name' => $name,
            'label' => self::from($value)->getLabel(),
        ], self::values(), self::names());
    }

    /**
     * Transform the enum instance to an array with value and label for API resources.
     *
     * @return array{value: string, label: mixed}
     */
    public function toResource(): array
    {
        return [
            'value' => $this->value,
            'label' => $this->getLabel(),
        ];
    }
}
