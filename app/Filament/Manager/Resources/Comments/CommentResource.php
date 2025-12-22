<?php

declare(strict_types=1);

namespace App\Filament\Manager\Resources\Comments;

use App\Enums\Comment\StatusEnum;
use App\Filament\Manager\Resources\Comments\Pages\EditComment;
use App\Filament\Manager\Resources\Comments\Pages\ListComments;
use App\Filament\Manager\Resources\Comments\Pages\ViewComment;
use App\Filament\Manager\Resources\Comments\Schemas\CommentForm;
use App\Filament\Manager\Resources\Comments\Schemas\CommentInfolist;
use App\Filament\Manager\Resources\Comments\Tables\CommentsTable;
use App\Models\Comment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Override;
use UnitEnum;

final class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;

    protected static string|UnitEnum|null $navigationGroup = 'Moderation';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleBottomCenterText;

    public static function getNavigationBadge(): string
    {
        return (string) self::getEloquentQuery()->where('status', StatusEnum::PENDING)->count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return CommentForm::configure($schema);
    }

    #[Override]
    public static function infolist(Schema $schema): Schema
    {
        return CommentInfolist::configure($schema);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return CommentsTable::configure($table);
    }

    #[Override]
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListComments::route('/'),
            'view' => ViewComment::route('/{record}'),
        ];
    }
}
