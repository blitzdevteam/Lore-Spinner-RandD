<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

final class CreatorPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->brandName('Lore Spinner')
            ->id('creator')
            ->path('creator')
            ->login()
            ->loginRouteSlug('authentication/login')
            ->colors([
                'primary' => Color::Purple,
            ])
            ->topbar(false)
            ->discoverResources(in: app_path('Filament/Creator/Resources'), for: 'App\Filament\Creator\Resources')
            ->discoverPages(in: app_path('Filament/Creator/Pages'), for: 'App\Filament\Creator\Pages')
            ->discoverWidgets(in: app_path('Filament/Creator/Widgets'), for: 'App\Filament\Creator\Widgets')
            ->pages([
                Dashboard::class,
            ])
            ->widgets([])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->authGuard('creator');
    }
}
