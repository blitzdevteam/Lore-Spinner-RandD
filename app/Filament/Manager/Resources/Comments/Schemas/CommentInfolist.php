<?php

namespace App\Filament\Manager\Resources\Comments\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CommentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('author_type'),
                TextEntry::make('author_id')
                    ->numeric(),
                TextEntry::make('commentable_type'),
                TextEntry::make('commentable_id')
                    ->numeric(),
                TextEntry::make('content')
                    ->columnSpanFull(),
                TextEntry::make('status'),
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
