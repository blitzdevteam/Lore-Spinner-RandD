<?php

declare(strict_types=1);

namespace App\Filament\Writer\Resources\Stories\Schemas;

use App\Enums\Story\StoryRatingEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

final class StoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->columns(2)
                    ->heading('Story details')
                    ->schema([
                        Fieldset::make('Basic information')
                            ->columns(2)
                            ->schema([
                                Select::make('category_id')
                                    ->label('Category')
                                    ->searchable()
                                    ->preload()
                                    ->relationship('category', 'title')
                                    ->required(),
                                Select::make('rating')
                                    ->label('Rating')
                                    ->options(StoryRatingEnum::class)
                                    ->required(),
                            ])
                            ->columnSpanFull(),
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),
                        Textarea::make('overview')
                            ->required()
                            ->helperText('Provide a short overview of the story without revealing spoilers.')
                            ->rows(3)
                            ->columnSpan(2),
                        Fieldset::make('Images')
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('cover')
                                    ->label('Cover Image')
                                    ->imageEditor()
                                    ->collection('cover')
                                    ->image()
                                    ->required()
                                    ->columnSpanFull(),
                                SpatieMediaLibraryFileUpload::make('gallery')
                                    ->label('Gallery Images')
                                    ->collection('gallery')
                                    ->image()
                                    ->imageEditor()
                                    ->multiple()
                                    ->panelLayout('grid')
                                    ->reorderable()
                                    ->columnSpanFull(),
                            ])
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
