<?php

declare(strict_types=1);

namespace App\Filament\Manager\Resources\Stories\Schemas;

use App\Enums\Story\StoryRatingEnum;
use App\Enums\Story\StoryStatusEnum;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

final class StoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
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
                Grid::make(4)
                    ->schema([
                        TextEntry::make('category.title')
                            ->label('Category'),
                        TextEntry::make('writer.full_name')
                            ->label('Writer'),
                        TextEntry::make('status')
                            ->color(fn (StoryStatusEnum $state): string => $state->getSeverity())
                            ->badge(),
                        TextEntry::make('rating')
                            ->color(fn (StoryRatingEnum $state): string => $state->getSeverity())
                            ->badge(),
                    ])
                    ->columnSpanFull(),
                TextEntry::make('title'),
                TextEntry::make('overview')
                    ->columnSpanFull(),
                Grid::make(3)
                    ->schema([
                        TextEntry::make('published_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('created_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->dateTime()
                            ->placeholder('-'),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
