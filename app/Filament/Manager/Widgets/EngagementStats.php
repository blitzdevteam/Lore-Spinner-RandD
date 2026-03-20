<?php

declare(strict_types=1);

namespace App\Filament\Manager\Widgets;

use App\Models\Game;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

final class EngagementStats extends StatsOverviewWidget
{
    protected static ?int $sort = 4;

    protected ?string $heading = 'Engagement Metrics';

    protected function getStats(): array
    {
        try {
            $totalUsers = User::count();

            $usersWithGames = User::whereHas('games')->count();
            $usersWithBookmarks = User::whereHas('bookmarkedStories')->count();
            $usersWithComments = User::whereHas('comments')->count();

            $totalPrompts = DB::table('prompts')->count();
            $avgPromptsPerGame = Game::count() > 0
                ? round($totalPrompts / Game::count(), 1)
                : 0;

            $activeLastWeek = User::whereHas('games', function ($q) {
                $q->where('created_at', '>=', Carbon::now()->subDays(7));
            })->count();

            $stories = DB::table('stories')->where('status', 'published')->count();

            return [
                Stat::make('Users Playing Games', number_format($usersWithGames))
                    ->description($totalUsers > 0
                        ? round(($usersWithGames / $totalUsers) * 100) . '% of users'
                        : 'No users yet')
                    ->descriptionColor('success')
                    ->color('success'),

                Stat::make('Users with Bookmarks', number_format($usersWithBookmarks))
                    ->description($totalUsers > 0
                        ? round(($usersWithBookmarks / $totalUsers) * 100) . '% of users'
                        : 'No users yet')
                    ->descriptionColor('warning')
                    ->color('warning'),

                Stat::make('Users Commenting', number_format($usersWithComments))
                    ->description($totalUsers > 0
                        ? round(($usersWithComments / $totalUsers) * 100) . '% of users'
                        : 'No users yet')
                    ->descriptionColor('info')
                    ->color('info'),

                Stat::make('Total Prompts Sent', number_format($totalPrompts))
                    ->description($avgPromptsPerGame . ' avg per game')
                    ->color('primary'),

                Stat::make('Active Last 7 Days', number_format($activeLastWeek))
                    ->description('Users who played a game')
                    ->descriptionColor($activeLastWeek > 0 ? 'success' : 'gray')
                    ->color('success'),

                Stat::make('Published Stories', number_format($stories))
                    ->description('Available to play')
                    ->color('gray'),
            ];
        } catch (\Throwable) {
            return [];
        }
    }
}
