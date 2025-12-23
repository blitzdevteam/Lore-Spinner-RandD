<?php

namespace App\Filament\Writer\Resources\Stories\RelationManagers;

use App\Filament\Writer\Resources\Stories\Resources\Chapters\ChapterResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class ChaptersRelationManager extends RelationManager
{
    protected static string $relationship = 'chapters';

    protected static ?string $relatedResource = ChapterResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
