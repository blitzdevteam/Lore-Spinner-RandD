<?php

declare(strict_types=1);

namespace App\Filament\Writer\Resources\Stories\Schemas;

use App\Enums\Story\StoryRatingEnum;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
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
                            ->columns(2)
                            ->columnSpanFull(),
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug($state)))
                            ->columnSpan(2),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('Auto-generated from title, but you can edit it if needed.')
                            ->columnSpan(2),
                        Textarea::make('teaser')
                            ->required()
                            ->helperText('Provide a short teaser of the story without revealing spoilers.')
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
                        Fieldset::make('scripts')
                            ->schema([
                                Checkbox::make('use_script_upload')
                                    ->label('Upload a script and auto-chapterize')
                                    ->helperText('Enable this to upload a .txt script. Chapters will be generated automatically, and you can edit them afterward.')
                                    ->default(false)
                                    ->columnSpanFull()
                                    ->live(),
                                SpatieMediaLibraryFileUpload::make('script')
                                    ->label('Story Script')
                                    ->helperText('Upload the story script file here and chapterizing will be done automatically.')
                                    ->placeholder('Only `.txt` file are allowed')
                                    ->collection('script')
                                    ->acceptedFileTypes(['text/plain'])
                                    ->preserveFilenames()
                                    ->downloadable()
                                    ->openable()
                                    ->required(fn (Get $get) => (bool) $get('use_script_upload'))
                                    ->visible(fn (Get $get) => (bool) $get('use_script_upload'))
                                    ->columnSpanFull(),
                            ])
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
