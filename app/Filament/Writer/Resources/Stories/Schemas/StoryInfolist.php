<?php

declare(strict_types=1);

namespace App\Filament\Writer\Resources\Stories\Schemas;

use App\Enums\Story\StoryRatingEnum;
use App\Enums\Story\StoryStatusEnum;
use App\Models\Story;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

final class StoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->heading('Story details')
                    ->description('View the basic information.')
                    ->columns(2)
                    ->schema([
                        Fieldset::make('Images')
                            ->schema([
                                SpatieMediaLibraryImageEntry::make('cover')
                                    ->label('Cover Image')
                                    ->collection('cover')
                                    ->placeholder('No cover image')
                                    ->columnSpanFull(),
                                SpatieMediaLibraryImageEntry::make('gallery')
                                    ->label('Gallery')
                                    ->collection('gallery')
                                    ->placeholder('No gallery images')
                                    ->columnSpanFull(),
                            ])
                            ->columnSpanFull(),
                        Fieldset::make('Basic information')
                            ->columns(3)
                            ->schema([
                                TextEntry::make('category.title')
                                    ->label('Category')
                                    ->placeholder('-'),
                                TextEntry::make('status')
                                    ->color(fn (StoryStatusEnum $state): string => $state->getSeverity())
                                    ->badge()
                                    ->placeholder('-'),
                                TextEntry::make('rating')
                                    ->color(fn (StoryRatingEnum $state): string => $state->getSeverity())
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
                        TextEntry::make('published_at')
                            ->dateTime()
                            ->placeholder('-')
                            ->visible(fn (Story $record): bool => ! is_null($record->published_at))
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
