<?php

declare(strict_types=1);

namespace App\Filament\Creator\Resources\Stories;

use App\Filament\Creator\Resources\Stories\Pages\CreateStory;
use App\Filament\Creator\Resources\Stories\Pages\EditStory;
use App\Filament\Creator\Resources\Stories\Pages\ListStories;
use App\Filament\Creator\Resources\Stories\Pages\ViewStory;
use App\Filament\Creator\Resources\Stories\RelationManagers\ChaptersRelationManager;
use App\Filament\Creator\Resources\Stories\Schemas\StoryForm;
use App\Filament\Creator\Resources\Stories\Schemas\StoryInfolist;
use App\Filament\Creator\Resources\Stories\Tables\StoriesTable;
use App\Models\Story;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Override;
use UnitEnum;

final class StoryResource extends Resource
{
    protected static ?string $model = Story::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static string|UnitEnum|null $navigationGroup = 'Entities';

    #[\Override]
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereBelongsTo(auth('creator')->user());
    }

    public static function getNavigationBadge(): string
    {
        return (string)self::getEloquentQuery()->count();
    }

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return StoryForm::configure($schema);
    }

    #[Override]
    public static function infolist(Schema $schema): Schema
    {
        return StoryInfolist::configure($schema);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return StoriesTable::configure($table);
    }

    #[Override]
    public static function getRelations(): array
    {
        return [
            ChaptersRelationManager::class,
        ];
    }

    #[\Override]
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
