<?php

namespace App\Filament\Manager\Resources\Stories\RelationManagers;

use App\Filament\Manager\Resources\Comments\CommentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';

    protected static ?string $relatedResource = CommentResource::class;

    public function table(Table $table): Table
    {
        return $table;
    }
}
