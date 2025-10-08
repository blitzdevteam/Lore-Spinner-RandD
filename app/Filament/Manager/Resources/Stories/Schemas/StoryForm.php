<?php

namespace App\Filament\Manager\Resources\Stories\Schemas;

use App\Enums\Story\RatingEnum;
use App\Enums\Story\StatusEnum;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class StoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->relationship('category', 'title')
                    ->required(),
                Select::make('writer_id')
                    ->relationship('writer', 'id')
                    ->required(),
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Select::make('status')
                    ->options(StatusEnum::class)
                    ->required(),
                Select::make('rating')
                    ->options(RatingEnum::class)
                    ->required(),
                DateTimePicker::make('published_at'),
            ]);
    }
}
