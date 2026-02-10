<?php

namespace App\Filament\Writer\Resources\Stories\Resources\Chapters;

use App\Filament\Writer\Resources\Stories\Resources\Chapters\Pages\CreateChapter;
use App\Filament\Writer\Resources\Stories\Resources\Chapters\Pages\EditChapter;
use App\Filament\Writer\Resources\Stories\Resources\Chapters\Pages\ViewChapter;
use App\Filament\Writer\Resources\Stories\Resources\Chapters\Schemas\ChapterForm;
use App\Filament\Writer\Resources\Stories\Resources\Chapters\Schemas\ChapterInfolist;
use App\Filament\Writer\Resources\Stories\Resources\Chapters\Tables\ChaptersTable;
use App\Filament\Writer\Resources\Stories\StoryResource;
use App\Models\Chapter;
use BackedEnum;
use Filament\Resources\ParentResourceRegistration;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ChapterResource extends Resource
{
    protected static ?string $model = Chapter::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    public static function getParentResourceRegistration(): ?ParentResourceRegistration
    {
        return StoryResource::asParent()
            ->relationship('chapters')
            ->inverseRelationship('story');
    }

    public static function form(Schema $schema): Schema
    {
        return ChapterForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ChapterInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ChaptersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'create' => CreateChapter::route('/create'),
            'view' => ViewChapter::route('/{record}'),
            'edit' => EditChapter::route('/{record}/edit'),
        ];
    }
}
