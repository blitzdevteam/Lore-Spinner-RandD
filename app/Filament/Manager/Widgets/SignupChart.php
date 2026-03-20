<?php

declare(strict_types=1);

namespace App\Filament\Manager\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

final class SignupChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected ?string $heading = 'User Signups';

    protected ?string $description = 'Daily registrations over the last 30 days';

    protected ?string $maxHeight = '300px';

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $days = match ($this->filter) {
            '7' => 7,
            '90' => 90,
            default => 30,
        };

        $labels = [];
        $signups = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('M j');
            $signups[] = User::whereDate('created_at', $date)->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Signups',
                    'data' => $signups,
                    'borderColor' => 'rgb(147, 51, 234)',
                    'backgroundColor' => 'rgba(147, 51, 234, 0.1)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getFilters(): ?array
    {
        return [
            '30' => 'Last 30 days',
            '7' => 'Last 7 days',
            '90' => 'Last 90 days',
        ];
    }
}
