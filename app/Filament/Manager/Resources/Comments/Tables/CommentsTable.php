<?php

declare(strict_types=1);

namespace App\Filament\Manager\Resources\Comments\Tables;

use App\Enums\Comment\CommentStatusEnum;
use App\Models\Comment;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

final class CommentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('author_type')
                    ->formatStateUsing(fn (Comment $record): string => class_basename($record->author_type))
                    ->badge()
                    ->color(fn (Comment $record): string => match (class_basename($record->author_type)) {
                        'User' => 'success',
                        'Creator' => 'info',
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
                    ->color(fn (CommentStatusEnum $state): string => $state->getSeverity())
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
            ])
            ->toolbarActions([]);
    }
}
