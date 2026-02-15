<?php

declare(strict_types=1);

namespace App\Filament\Manager\Resources\Users\Schemas;

use App\Models\User;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

final class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->columns(2)
                    ->heading('User details')
                    ->schema([
                        Toggle::make('is_active')
                            ->required()
                            ->columnSpanFull(),
                        Fieldset::make('Basic information')
                            ->schema([
                                TextInput::make('first_name')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('last_name')
                                    ->required()
                                    ->maxLength(255),
                            ])
                            ->columns(3)
                            ->columnSpanFull(),
                        Fieldset::make('Account information')
                            ->schema([
                                TextInput::make('username')
                                    ->unique(table: User::class, ignoreRecord: true)
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('email')
                                    ->unique(table: User::class, ignoreRecord: true)
                                    ->email()
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('password')
                                    ->password()
                                    ->required(fn (string $operation): bool => $operation === 'create')
                                    ->maxLength(255),
                            ])
                            ->columns(3)
                            ->columnSpanFull(),
                        Textarea::make('bio')
                            ->rows(3)
                            ->columnSpan(2),
                    ]),
            ]);
    }
}
