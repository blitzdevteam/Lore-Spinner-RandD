<?php

declare(strict_types=1);

namespace App\Filament\Manager\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

final class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->withCount(['games', 'bookmarkedStories', 'comments']))
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('username')
                    ->searchable(),
                TextColumn::make('full_name')
                    ->searchable(['first_name', 'last_name']),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                IconColumn::make('is_active')
                    ->sortable()
                    ->boolean(),
                TextColumn::make('games_count')
                    ->label('Games')
                    ->sortable(),
                TextColumn::make('bookmarked_stories_count')
                    ->label('Bookmarks')
                    ->sortable(),
                TextColumn::make('comments_count')
                    ->label('Comments')
                    ->sortable(),
                TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Signed Up')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
