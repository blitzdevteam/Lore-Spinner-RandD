<?php

declare(strict_types=1);

namespace App\Filament\Manager\Widgets;

use App\Models\Creator;
use App\Models\Feedback;
use App\Models\Game;
use App\Models\Story;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

final class PlatformStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected ?string $heading = 'Platform Overview';

    protected function getStats(): array
    {
        $now = Carbon::now();
        $weekAgo = $now->copy()->subDays(7);
        $monthAgo = $now->copy()->subDays(30);

        $totalUsers = User::count();
        $usersThisWeek = User::where('created_at', '>=', $weekAgo)->count();
        $usersThisMonth = User::where('created_at', '>=', $monthAgo)->count();

        $weeklySignupTrend = $this->getWeeklySignupTrend();

        $totalGames = Game::count();
        $gamesThisWeek = Game::where('created_at', '>=', $weekAgo)->count();

        $totalBookmarks = DB::table('bookmarks')->count();
        $totalFeedback = Feedback::count();

        $verifiedUsers = User::whereNotNull('email_verified_at')->count();
        $profileCompleted = User::whereNotNull('username')->count();

        return [
            Stat::make('Total Users', number_format($totalUsers))
                ->description($usersThisWeek . ' this week / ' . $usersThisMonth . ' this month')
                ->descriptionColor($usersThisWeek > 0 ? 'success' : 'gray')
                ->chart($weeklySignupTrend)
                ->chartColor('primary')
                ->color('primary'),

            Stat::make('Verified Emails', number_format($verifiedUsers))
                ->description($totalUsers > 0
                    ? round(($verifiedUsers / $totalUsers) * 100) . '% of all users'
                    : 'No users yet')
                ->descriptionColor('info')
                ->color('info'),

            Stat::make('Profiles Completed', number_format($profileCompleted))
                ->description($totalUsers > 0
                    ? round(($profileCompleted / $totalUsers) * 100) . '% completion rate'
                    : 'No users yet')
                ->descriptionColor('success')
                ->color('success'),

            Stat::make('Games Played', number_format($totalGames))
                ->description($gamesThisWeek . ' this week')
                ->descriptionColor($gamesThisWeek > 0 ? 'success' : 'gray')
                ->color('warning'),

            Stat::make('Bookmarks', number_format($totalBookmarks))
                ->description('Stories saved by users')
                ->color('danger'),

            Stat::make('Feedback', number_format($totalFeedback))
                ->description('User submissions')
                ->color('gray'),
        ];
    }

    /**
     * @return array<float>
     */
    private function getWeeklySignupTrend(): array
    {
        $counts = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $counts[] = (float) User::whereDate('created_at', $date)->count();
        }

        return $counts;
    }
}
