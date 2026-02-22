<?php

declare(strict_types=1);

namespace App\Filament\Manager\Resources\Comments\Schemas;

use App\Enums\Comment\CommentStatusEnum;
use App\Models\Comment;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

final class CommentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->heading('Comment details')
                    ->schema([
                        TextEntry::make('status')
                            ->badge()
                            ->color(fn (CommentStatusEnum $state): string => $state->getSeverity()),
                        Fieldset::make('Author information')
                            ->schema([
                                TextEntry::make('author_type')
                                    ->formatStateUsing(fn (Comment $record): string => class_basename($record->author_type))
                                    ->badge()
                                    ->color(fn (Comment $record): string => match (class_basename($record->author_type)) {
                                        'User' => 'success',
                                        'Creator' => 'info',
                                        default => 'secondary',
                                    }),
                                TextEntry::make('author.full_name')
                                    ->placeholder('-'),
                            ])
                            ->columnSpanFull(),
                        Fieldset::make('Commentable information')
                            ->schema([
                                TextEntry::make('commentable_type')
                                    ->formatStateUsing(fn (Comment $record): string => class_basename($record->commentable_type))
                                    ->badge()
                                    ->color('primary'),
                                TextEntry::make('commentable.title')
                                    ->limit(50)
                                    ->placeholder('-'),
                            ])
                            ->columnSpanFull(),
                        TextEntry::make('content')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ]),
                Section::make()
                    ->columnSpanFull()
                    ->heading('Metadata')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('created_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('approved_at')
                            ->dateTime()
                            ->placeholder('-'),
                    ]),
            ]);
    }
}
