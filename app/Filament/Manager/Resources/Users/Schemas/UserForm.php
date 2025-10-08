<?php

namespace App\Filament\Manager\Resources\Users\Schemas;

use App\Models\User;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Toggle::make('is_active')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('first_name')
                    ->required(),
                TextInput::make('last_name')
                    ->required(),
                Grid::make(3)
                    ->schema([
                        TextInput::make('username')
                            ->unique(table: User::class, ignoreRecord: true)
                            ->unique()
                            ->required(),
                        TextInput::make('email')
                            ->unique(table: User::class, ignoreRecord: true)
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
