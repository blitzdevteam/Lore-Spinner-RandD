<?php

declare(strict_types=1);

namespace App\Filament\Manager\Resources\Stories\RelationManagers;

use App\Filament\Manager\Resources\Comments\CommentResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

final class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';

    protected static ?string $relatedResource = CommentResource::class;

    public function table(Table $table): Table
    {
        return $table;
    }
}
