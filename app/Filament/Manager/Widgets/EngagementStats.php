<?php

declare(strict_types=1);

namespace App\Filament\Manager\Widgets;

use App\Models\Game;
use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

final class EngagementStats extends ChartWidget
{
    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    protected ?string $heading = 'Engagement Breakdown';

    protected ?string $maxHeight = '300px';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $totalPrompts = DB::table('prompts')->count();
        $gameCount = Game::count();

        return [
            'datasets' => [
                [
                    'label' => 'Users',
                    'data' => [
                        User::whereHas('games')->count(),
                        User::whereHas('bookmarkedStories')->count(),
                        User::whereHas('comments')->count(),
                        User::whereHas('games', fn ($q) => $q->where('created_at', '>=', Carbon::now()->subDays(7)))->count(),
                    ],
                    'backgroundColor' => 'rgba(34, 197, 94, 0.8)',
                ],
                [
                    'label' => 'Totals',
                    'data' => [
                        $gameCount,
                        DB::table('bookmarks')->count(),
                        DB::table('comments')->count(),
                        $totalPrompts,
                    ],
                    'backgroundColor' => 'rgba(147, 51, 234, 0.8)',
                ],
            ],
            'labels' => [
                'Games',
                'Bookmarks',
                'Comments',
                'Active (7d) / Prompts',
            ],
        ];
    }
}
