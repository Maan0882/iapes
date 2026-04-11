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

use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandname('IAPES_Admin')
            ->colors([
            'primary' => '#f7a93b', // The Orange/Gold from your logo
            ])
            ->brandLogo(asset('images/TsLogo.png'))
            ->brandLogoHeight('3rem')
            // ✅ COLLAPSIBLE SIDEBAR
            ->sidebarCollapsibleOnDesktop()
            ->sidebarWidth('16rem')
            ->collapsedSidebarWidth('4.5rem')
             // ✅ FULL WIDTH CONTENT
            ->maxContentWidth('full')

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
                \App\Filament\Widgets\ApplicationStats::class,
                
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
            ])
            ->renderHook(
                PanelsRenderHook::TOPBAR_END,
                fn(): string => Blade::render('
                    <div id="fi-greeting-wrap" style="
                        display: flex;
                        align-items: center;
                        height: 100%;
                        padding: 0 0.5rem 0 0;
                        font-family: Poppins, sans-serif;
                        font-size: 0.95rem;
                        font-weight: 600;
                        color: #2a4795;
                        letter-spacing: 0.01em;
                        white-space: nowrap;
                    ">
                        Hello..!!&nbsp;<span style="color: #f16b3f;">{{ auth()->user()?->name ?? \'\' }}</span>&nbsp;👋
                    </div>
                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            function fixOrder() {
                                const greeting = document.getElementById("fi-greeting-wrap");
                                const userMenu = document.querySelector(".fi-user-menu");
                                if (!greeting || !userMenu) return;
 
                                const parent = userMenu.parentElement;
                                if (!parent) return;
 
                                // Move greeting before userMenu, userMenu stays last
                                parent.insertBefore(greeting, userMenu);
                            }
                            fixOrder();
                            setTimeout(fixOrder, 300);
                        });
                    </script>
                ')
            );

    }


    
}
