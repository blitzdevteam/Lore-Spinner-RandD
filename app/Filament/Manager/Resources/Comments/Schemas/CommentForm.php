<?php

namespace App\Filament\Manager\Resources\Comments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CommentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('author_type')
                    ->required(),
                TextInput::make('author_id')
                    ->required()
                    ->numeric(),
                TextInput::make('commentable_type')
                    ->required(),
                TextInput::make('commentable_id')
                    ->required()
                    ->numeric(),
                Textarea::make('content')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
                DateTimePicker::make('approved_at'),
            ]);
    }
}
