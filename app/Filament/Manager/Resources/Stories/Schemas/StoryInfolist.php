<?php

namespace App\Filament\Manager\Resources\Stories\Schemas;

use App\Enums\Story\RatingEnum;
use App\Enums\Story\StatusEnum;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class StoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                ImageEntry::make('cover')
                    ->label('Cover Image')
                    ->placeholder('No cover image')
                    ->columnSpanFull(),
                ImageEntry::make('gallery')
                    ->label('Gallery')
                    ->placeholder('No gallery images')
                    ->columnSpanFull(),
                Grid::make(4)
                    ->schema([
                        TextEntry::make('category.title')
                            ->label('Category'),
                        TextEntry::make('writer.full_name')
                            ->label('Writer'),
                        TextEntry::make('status')
                            ->color(fn(StatusEnum $state): string => match ($state) {
                                StatusEnum::PENDING => 'warning',
                                StatusEnum::APPROVED => 'info',
                                StatusEnum::DECLINED => 'danger',
                                StatusEnum::PUBLISHED => 'success',
                            })
                            ->badge(),
                        TextEntry::make('rating')
                            ->color(fn(RatingEnum $state): string => match ($state) {
                                RatingEnum::EVERYONE => 'success',
                                RatingEnum::TEEN => 'info',
                                RatingEnum::YOUNG_ADULT => 'warning',
                                RatingEnum::MATURE => 'danger',
                            })
                            ->badge(),
                    ])
                    ->columnSpanFull(),
                TextEntry::make('title'),
                TextEntry::make('description')
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
                    ->columnSpanFull()
            ]);
    }
}
