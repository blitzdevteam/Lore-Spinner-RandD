<?php

declare(strict_types=1);

namespace App\Filament\Manager\Resources\Stories\Schemas;

use App\Enums\Story\StoryRatingEnum;
use App\Models\Writer;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;

final class StoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('Images')
                        ->schema([
                            SpatieMediaLibraryFileUpload::make('cover')
                                ->imageEditor()
                                ->imageCropAspectRatio('16:9')
                                ->collection('cover')
                                ->image()
                                ->required()
                                ->columnSpanFull(),
                            SpatieMediaLibraryFileUpload::make('gallery')
                                ->collection('gallery')
                                ->image()
                                ->imageEditor()
                                ->imageCropAspectRatio('16:9')
                                ->multiple()
                                ->panelLayout('grid')
                                ->reorderable()
                                ->columnSpanFull(),
                        ]),
                    Step::make('Details')
                        ->schema([
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
                                        ->options(StoryRatingEnum::class)
                                        ->required(),
                                ])
                                ->columnSpanFull(),
                            TextInput::make('title')
                                ->required()
                                ->columnSpanFull(),
                            Textarea::make('overview')
                                ->required()
                                ->columnSpanFull(),
                        ]),
                ])
                    ->columnSpanFull(),
            ]);
    }
}
