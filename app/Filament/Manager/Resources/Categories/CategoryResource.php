<?php

declare(strict_types=1);

namespace App\Filament\Manager\Resources\Categories;

use App\Filament\Manager\Resources\Categories\Pages\ListCategories;
use App\Filament\Manager\Resources\Categories\Schemas\CategoryForm;
use App\Filament\Manager\Resources\Categories\Tables\CategoriesTable;
use App\Models\Category;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Override;
use UnitEnum;

final class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Bars2;

    protected static string|UnitEnum|null $navigationGroup = 'Entities';

    public static function getNavigationBadge(): string
    {
        return (string) self::getEloquentQuery()->count();
    }

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return CategoryForm::configure($schema);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return CategoriesTable::configure($table);
    }

    #[Override]
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    #[\Override]
    public static function getPages(): array
    {
        return [
            'index' => ListCategories::route('/'),
        ];
    }
}
