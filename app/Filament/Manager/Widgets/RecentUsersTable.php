<?php

declare(strict_types=1);

namespace App\Filament\Manager\Widgets;

use App\Models\User;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

final class RecentUsersTable extends TableWidget
{
    protected static ?int $sort = 3;

    protected static bool $isLazy = false;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Recent Signups')
            ->description('Latest users who registered on the platform')
            ->query(
                User::query()
                    ->withCount(['games', 'bookmarkedStories', 'comments'])
                    ->latest()
            )
            ->columns([
                TextColumn::make('full_name')
                    ->label('Name')
                    ->searchable(['first_name', 'last_name']),

                TextColumn::make('username')
                    ->label('Username')
                    ->placeholder('— not set —')
                    ->searchable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                IconColumn::make('email_verified_at')
                    ->label('Verified')
                    ->boolean()
                    ->getStateUsing(fn (User $record): bool => $record->email_verified_at !== null),

                IconColumn::make('is_profile_completed')
                    ->label('Profile')
                    ->boolean()
                    ->getStateUsing(fn (User $record): bool => $record->username !== null),

                TextColumn::make('games_count')
                    ->label('Games')
                    ->sortable()
                    ->alignCenter(),

                TextColumn::make('bookmarked_stories_count')
                    ->label('Bookmarks')
                    ->sortable()
                    ->alignCenter(),

                TextColumn::make('comments_count')
                    ->label('Comments')
                    ->sortable()
                    ->alignCenter(),

                TextColumn::make('created_at')
                    ->label('Signed Up')
                    ->dateTime('M j, Y g:i A')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50]);
    }
}
