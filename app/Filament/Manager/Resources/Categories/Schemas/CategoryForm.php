<?php

declare(strict_types=1);

namespace App\Filament\Manager\Resources\Categories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

final class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->minLength(3)
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
