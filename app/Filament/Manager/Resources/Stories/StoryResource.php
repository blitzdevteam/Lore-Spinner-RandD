<?php

namespace App\Filament\Manager\Resources\Stories;

use App\Filament\Manager\Resources\Stories\Pages\CreateStory;
use App\Filament\Manager\Resources\Stories\Pages\EditStory;
use App\Filament\Manager\Resources\Stories\Pages\ListStories;
use App\Filament\Manager\Resources\Stories\Pages\ViewStory;
use App\Filament\Manager\Resources\Stories\Schemas\StoryForm;
use App\Filament\Manager\Resources\Stories\Schemas\StoryInfolist;
use App\Filament\Manager\Resources\Stories\Tables\StoriesTable;
use App\Models\Story;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StoryResource extends Resource
{
    protected static ?string $model = Story::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    #[\Override]
    public static function form(Schema $schema): Schema
    {
        return StoryForm::configure($schema);
    }

    #[\Override]
    public static function infolist(Schema $schema): Schema
    {
        return StoryInfolist::configure($schema);
    }

    #[\Override]
    public static function table(Table $table): Table
    {
        return StoriesTable::configure($table);
    }

    #[\Override]
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStories::route('/'),
            'create' => CreateStory::route('/create'),
            'view' => ViewStory::route('/{record}'),
            'edit' => EditStory::route('/{record}/edit'),
        ];
    }
}
