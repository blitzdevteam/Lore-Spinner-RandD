<?php

declare(strict_types=1);

namespace App\Filament\Manager\Resources\Writers\Schemas;

use App\Models\Writer;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

final class WriterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Toggle::make('is_active')
                    ->required()
                    ->columnSpanFull(),
                Grid::make(3)
                    ->schema([
                        TextInput::make('first_name')
                            ->required(),
                        TextInput::make('last_name')
                            ->required(),
                        TextInput::make('nickname')
                            ->nullable(),
                        TextInput::make('username')
                            ->unique(table: Writer::class, ignoreRecord: true)
                            ->unique()
                            ->required(),
                        TextInput::make('email')
                            ->unique(table: Writer::class, ignoreRecord: true)
                            ->email()
                            ->required(),
                        TextInput::make('password')
                            ->password()
                            ->required(fn (string $operation): bool => $operation === 'create'),
                    ])
                    ->columnSpanFull(),
                Textarea::make('bio')
                    ->columnSpanFull(),
            ]);
    }
}
