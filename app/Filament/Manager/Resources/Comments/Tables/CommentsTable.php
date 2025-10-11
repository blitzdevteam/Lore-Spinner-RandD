<?php

namespace App\Filament\Manager\Resources\Comments\Tables;

use App\Enums\Comment\StatusEnum;
use App\Filament\Manager\Resources\Stories\Pages\ViewStory;
use App\Models\Comment;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CommentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('author_type')
                    ->formatStateUsing(function ($record) {
                        return class_basename($record->author_type);
                    })
                    ->badge()
                    ->color(fn ($record) => match (class_basename($record->author_type)) {
                        'User' => 'success',
                        'Writer' => 'info',
                        default => 'secondary',
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('author.full_name')
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(),
                TextColumn::make('commentable.title')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (StatusEnum $state): string => match ($state) {
                        StatusEnum::PENDING => 'warning',
                        StatusEnum::APPROVED => 'info',
                        StatusEnum::DECLINED => 'danger',
                    })
                    ->sortable()
                    ->searchable(),
                TextColumn::make('approved_at')
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
                EditAction::make()
                    ->visible(fn (Comment $record) => $record->status !== StatusEnum::PENDING),
                DeleteAction::make()
                    ->visible(fn (Comment $record) => $record->status !== StatusEnum::PENDING),
            ])
            ->toolbarActions([]);
    }
}
