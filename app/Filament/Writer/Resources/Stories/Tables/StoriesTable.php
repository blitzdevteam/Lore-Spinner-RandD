<?php

declare(strict_types=1);

namespace App\Filament\Writer\Resources\Stories\Tables;

use App\Enums\Story\StoryRatingEnum;
use App\Enums\Story\StoryStatusEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

final class StoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category.title')
                    ->searchable(),
                TextColumn::make('chapters_count')
                    ->counts('chapters')
                    ->badge()
                    ->color('primary')
                    ->sortable(),
                TextColumn::make('writer.full_name')
                    ->searchable(['first_name', 'last_name']),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (StoryStatusEnum $state): string => $state->getSeverity())
                    ->searchable(),
                TextColumn::make('rating')
                    ->badge()
                    ->color(fn (StoryRatingEnum $state): string => $state->getSeverity())
                    ->searchable(),
                TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
