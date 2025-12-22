<?php

declare(strict_types=1);

namespace App\Filament\Writer\Resources\Stories\Tables;

use App\Enums\Story\RatingEnum;
use App\Enums\Story\StatusEnum;
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
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (StatusEnum $state): string => match ($state) {
                        StatusEnum::PENDING => 'warning',
                        StatusEnum::APPROVED => 'info',
                        StatusEnum::DECLINED => 'danger',
                        StatusEnum::PUBLISHED => 'success',
                    })
                    ->searchable(),
                TextColumn::make('rating')
                    ->badge()
                    ->color(fn (RatingEnum $state): string => match ($state) {
                        RatingEnum::EVERYONE => 'success',
                        RatingEnum::TEEN => 'info',
                        RatingEnum::YOUNG_ADULT => 'warning',
                        RatingEnum::MATURE => 'danger',
                    })
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
