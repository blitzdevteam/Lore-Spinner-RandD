<?php

namespace App\Filament\Manager\Resources\Comments\Schemas;

use App\Enums\Comment\StatusEnum;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CommentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('author_type')
                    ->formatStateUsing(fn($record): string => class_basename($record->author_type))
                    ->badge()
                    ->color(fn ($record): string => match (class_basename($record->author_type)) {
                        'User' => 'success',
                        'Writer' => 'info',
                        default => 'secondary',
                    }),
                TextEntry::make('author.full_name'),
                TextEntry::make('commentable.title')
                    ->columnSpanFull()
                    ->limit(50),
                TextEntry::make('content')
                    ->columnSpanFull(),
                TextEntry::make('status')
                    ->badge()
                    ->color(fn (StatusEnum $state): string => match ($state) {
                        StatusEnum::PENDING => 'warning',
                        StatusEnum::APPROVED => 'info',
                        StatusEnum::DECLINED => 'danger',
                    }),
                TextEntry::make('approved_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
