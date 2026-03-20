<?php

declare(strict_types=1);

namespace App\Filament\Manager\Widgets;

use App\Models\Feedback;
use App\Models\Game;
use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

final class PlatformStatsOverview extends ChartWidget
{
    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    protected ?string $heading = 'Platform Overview';

    protected ?string $maxHeight = '300px';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Count',
                    'data' => [
                        User::count(),
                        User::whereNotNull('email_verified_at')->count(),
                        User::whereNotNull('username')->count(),
                        Game::count(),
                        DB::table('bookmarks')->count(),
                        Feedback::count(),
                    ],
                    'backgroundColor' => [
                        'rgba(147, 51, 234, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(34, 197, 94, 0.8)',
                        'rgba(234, 179, 8, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(107, 114, 128, 0.8)',
                    ],
                ],
            ],
            'labels' => [
                'Users',
                'Verified',
                'Profiles Done',
                'Games',
                'Bookmarks',
                'Feedback',
            ],
        ];
    }
}
