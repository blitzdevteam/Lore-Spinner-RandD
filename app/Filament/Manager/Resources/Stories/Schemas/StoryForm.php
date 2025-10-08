<?php

namespace App\Filament\Manager\Resources\Stories\Schemas;

use App\Enums\Story\RatingEnum;
use App\Enums\Story\StatusEnum;
use App\Models\Writer;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class StoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->schema([
                        Select::make('category_id')
                            ->searchable()
                            ->preload()
                            ->relationship('category', 'title')
                            ->required(),
                        Select::make('writer_id')
                            ->searchable()
                            ->preload()
                            ->relationship('writer')
                            ->getOptionLabelFromRecordUsing(fn (Writer $record) => $record->full_name),
                        Select::make('rating')
                            ->options(RatingEnum::class)
                            ->required(),
                    ])
                    ->columnSpanFull(),
                TextInput::make('title')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
