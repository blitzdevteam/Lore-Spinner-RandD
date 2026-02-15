<?php

namespace App\Filament\Creator\Resources\Stories\Resources\Chapters\Schemas;

use App\Enums\Chapter\ChapterStatusEnum;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ChapterInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->heading('Chapter details')
                    ->description('View the basic information.')
                    ->columns(2)
                    ->schema([
                        Fieldset::make('Basic information')
                            ->columns(2)
                            ->schema([
                                TextEntry::make('story.title')
                                    ->label('Story')
                                    ->placeholder('-'),
                                TextEntry::make('status')
                                    ->color(fn (ChapterStatusEnum $state): string => $state->getSeverity())
                                    ->badge()
                                    ->placeholder('-'),
                            ])
                            ->columnSpanFull(),
                        TextEntry::make('title')
                            ->placeholder('-')
                            ->columnSpan(2),
                        TextEntry::make('teaser')
                            ->placeholder('-')
                            ->columnSpan(2),
                        TextEntry::make('content')
                            ->placeholder('-')
                            ->columnSpan(2),
                    ]),
                Section::make()
                    ->columnSpanFull()
                    ->columns(2)
                    ->heading('Metadata')
                    ->description('System information')
                    ->schema([
                        TextEntry::make('created_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->dateTime()
                            ->placeholder('-'),
                    ]),
            ]);
    }
}
