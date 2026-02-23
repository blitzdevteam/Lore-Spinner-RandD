<?php

namespace App\Filament\Creator\Resources\Stories\Resources\Chapters;

use App\Filament\Creator\Resources\Stories\Resources\Chapters\Pages\CreateChapter;
use App\Filament\Creator\Resources\Stories\Resources\Chapters\Pages\EditChapter;
use App\Filament\Creator\Resources\Stories\Resources\Chapters\Pages\ViewChapter;
use App\Filament\Creator\Resources\Stories\Resources\Chapters\Schemas\ChapterForm;
use App\Filament\Creator\Resources\Stories\Resources\Chapters\Schemas\ChapterInfolist;
use App\Filament\Creator\Resources\Stories\Resources\Chapters\Tables\ChaptersTable;
use App\Filament\Creator\Resources\Stories\StoryResource;
use App\Models\Chapter;
use BackedEnum;
use Filament\Resources\ParentResourceRegistration;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ChapterResource extends Resource
{
    protected static ?string $model = Chapter::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    #[\Override]
    public static function getParentResourceRegistration(): ?ParentResourceRegistration
    {
        return StoryResource::asParent()
            ->relationship('chapters')
            ->inverseRelationship('story');
    }

    #[\Override]
    public static function form(Schema $schema): Schema
    {
        return ChapterForm::configure($schema);
    }

    #[\Override]
    public static function infolist(Schema $schema): Schema
    {
        return ChapterInfolist::configure($schema);
    }

    #[\Override]
    public static function table(Table $table): Table
    {
        return ChaptersTable::configure($table);
    }

    #[\Override]
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
            'create' => CreateChapter::route('/create'),
            'view' => ViewChapter::route('/{record}'),
            'edit' => EditChapter::route('/{record}/edit'),
        ];
    }

    #[\Override]
    public static function canEdit(Model $record): bool
    {
        return $record->isEditable();
    }
}
