<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Filament\Intern\Pages\Auth\Login; // Make sure to import your custom login class

use App\Filament\Intern\Widgets\InternStatsOverview;
use App\Filament\Intern\Widgets\RecentSubmissions;

class InternPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('intern')
            ->path('intern')
            //->login()
            ->authGuard('intern')
            // Tell the panel to use your custom Login class!
            ->login(Login::class)
            ->loginRouteSlug('login')
            ->brandName('Intern Portal')
            ->colors([
                'primary' => Color::Blue,
            ])
            // ✅ COLLAPSIBLE SIDEBAR
            ->sidebarCollapsibleOnDesktop()
            ->sidebarWidth('16rem')
            ->collapsedSidebarWidth('4.5rem')
             // ✅ FULL WIDTH CONTENT
            ->maxContentWidth('full')
            ->discoverResources(in: app_path('Filament/Intern/Resources'), for: 'App\\Filament\\Intern\\Resources')
            ->discoverPages(in: app_path('Filament/Intern/Pages'), for: 'App\\Filament\\Intern\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Intern/Widgets'), for: 'App\\Filament\\Intern\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
                InternStatsOverview::class,
                RecentSubmissions::class,

            ])
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
            ]);
    }
}
