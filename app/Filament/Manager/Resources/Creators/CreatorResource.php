<?php

declare(strict_types=1);

namespace App\Filament\Manager\Resources\Creators;

use App\Filament\Manager\Resources\Creators\Pages\CreateCreator;
use App\Filament\Manager\Resources\Creators\Pages\EditCreator;
use App\Filament\Manager\Resources\Creators\Pages\ListCreators;
use App\Filament\Manager\Resources\Creators\RelationManagers\StoriesRelationManager;
use App\Filament\Manager\Resources\Creators\Schemas\CreatorForm;
use App\Filament\Manager\Resources\Creators\Tables\CreatorsTable;
use App\Models\Creator;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Override;
use UnitEnum;

final class CreatorResource extends Resource
{
    protected static ?string $model = Creator::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static string|UnitEnum|null $navigationGroup = 'Entities';

    public static function getNavigationBadge(): string
    {
        return (string) self::getEloquentQuery()->count();
    }

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return CreatorForm::configure($schema);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return CreatorsTable::configure($table);
    }

    #[Override]
    public static function getRelations(): array
    {
        return [
            StoriesRelationManager::class,
        ];
    }

    #[\Override]
    public static function getPages(): array
    {
        return [
            'index' => ListCreators::route('/'),
            'create' => CreateCreator::route('/create'),
            'edit' => EditCreator::route('/{record}/edit'),
        ];
    }
}
