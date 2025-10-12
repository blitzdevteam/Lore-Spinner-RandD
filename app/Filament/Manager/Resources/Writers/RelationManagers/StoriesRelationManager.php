<?php

namespace App\Filament\Manager\Resources\Writers\RelationManagers;

use App\Filament\Manager\Resources\Stories\StoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class StoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'stories';

    protected static ?string $relatedResource = StoryResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
