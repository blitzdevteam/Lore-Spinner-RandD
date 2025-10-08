<?php

namespace App\Filament\Manager\Resources\Writers;

use App\Filament\Manager\Resources\Writers\Pages\CreateWriter;
use App\Filament\Manager\Resources\Writers\Pages\EditWriter;
use App\Filament\Manager\Resources\Writers\Pages\ListWriters;
use App\Filament\Manager\Resources\Writers\Schemas\WriterForm;
use App\Filament\Manager\Resources\Writers\Tables\WritersTable;
use App\Models\Writer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WriterResource extends Resource
{
    protected static ?string $model = Writer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    #[\Override]
    public static function form(Schema $schema): Schema
    {
        return WriterForm::configure($schema);
    }

    #[\Override]
    public static function table(Table $table): Table
    {
        return WritersTable::configure($table);
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
            'index' => ListWriters::route('/'),
            'create' => CreateWriter::route('/create'),
            'edit' => EditWriter::route('/{record}/edit'),
        ];
    }
}
