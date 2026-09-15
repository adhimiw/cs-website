<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
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
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('ClimbSphere Control Center')
            ->brandLogo(fn (): View => view('filament.components.brand-logo'))
            ->font('Chakra Petch')
            ->colors([
                'primary' => [
                    50 => '#fdf6ee',
                    100 => '#fbe8d5',
                    200 => '#f6cfa9',
                    300 => '#f0ad73',
                    400 => '#e8833e',
                    500 => '#c25e2e', // Burnt Orange
                    600 => '#a84d20',
                    700 => '#873a17',
                    800 => '#6d2f14',
                    900 => '#592713',
                    950 => '#301107',
                ],
                'gray' => [
                    50 => '#faf7f0', // Paper cream
                    100 => '#f4eedc', // Parchment
                    200 => '#e8deca', // Manila paper
                    300 => '#d6c8ad', // Warm sand
                    400 => '#a89a7f', // Muted ink
                    500 => '#786e58', // Deep sepia
                    600 => '#544c3c', // Ink charcoal
                    700 => '#3a3428', // Dark ink
                    800 => '#26221a', // Rich ink
                    900 => '#1a1711', // True ink
                    950 => '#100e0b',
                ],
                'warning' => Color::Amber,
                'success' => Color::Emerald,
                'danger' => Color::Rose,
                'info' => Color::Sky,
            ])
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): View => view('filament.retro-paper-theme')
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
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
