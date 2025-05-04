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
use BezhanSalleh\FilamentShield\Facades\FilamentShield;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Contracts\View\View;
use App\Filament\Resources\TicketResource;
use App\Filament\Resources\TicketBackboneResource;
use App\Filament\Resources\LogActivityResource;
use App\Filament\Resources\UserResource;
use App\Filament\Widgets\TicketStatsWidget;
use App\Filament\Widgets\BackboneTicketStatsWidget;
use App\Filament\Widgets\TicketChartWidget;
use App\Filament\Widgets\BackboneTicketChartWidget;
use Devonab\FilamentEasyFooter\EasyFooterPlugin;
use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Awcodes\FilamentStickyHeader\StickyHeaderPlugin;
use Kenepa\Banner\BannerPlugin;
use Andreia\FilamentNordTheme\FilamentNordThemePlugin;

use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use CharrafiMed\GlobalSearchModal\GlobalSearchModalPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
                'danger' => Color::Rose,
                'warning' => Color::Orange,
                'success' => Color::Emerald,
                'gray' => Color::Slate,
                ])
            ->brandName('ARTACOM HELPDESK')
            ->favicon(asset('images/Favicon.png'))
            
            ->brandLogo(asset('images/LightMode-Logo.png'))
            ->darkModeBrandLogo(asset('images/DarkMode-Logo1.png'))

            ->brandLogoHeight(fn () => \Illuminate\Support\Facades\Auth::check() ? '3.5rem' : '9rem')
            
            ->darkMode(true)
            ->navigationGroups([
                NavigationGroup::make()->label('Helpdesk'),
                NavigationGroup::make()->label('Backbone'),
                NavigationGroup::make()->label('Management'),
                NavigationGroup::make()->label('System'),
            ])
            
            // Dashboard tetap didaftarkan melalui pages
            ->pages([
                Pages\Dashboard::class,
            ])
            
            // Menggunakan navigationItems dengan childItems untuk Panel Switcher
            ->navigationItems([
                // Panel Switcher dengan dropdown
                NavigationItem::make()
                    ->label('Panel Switcher')
                    ->icon('heroicon-o-squares-2x2')
                    ->sort(-1) // Nilai sort yang lebih tinggi dari Dashboard (biasanya Dashboard adalah 0)
                    ->group('Panel Switcher')
                    ->childItems([
                        NavigationItem::make()
                            ->label('FTTH HELPDESK TICKET')
                            ->url('/admin')
                            ->icon('heroicon-o-check-circle')
                            ->isActiveWhen(fn (): bool => request()->is('admin*')),
                        NavigationItem::make()
                            ->label('ALFA LAWSON HELPDESK')
                            ->url('/alfaLawson')
                            ->icon('heroicon-o-arrow-right-circle'),
                    ]),
            ])
            
            ->resources([
                TicketResource::class,
                config('filament-logger.activity_resource'),
            ])
            
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            
            ->widgets([
                TicketStatsWidget::class,
                BackboneTicketStatsWidget::class,
                TicketChartWidget::class,
                BackboneTicketChartWidget::class,
            ])
            
            // Plugin registration
            ->plugins([
                FilamentNordThemePlugin::make(),
                FilamentApexChartsPlugin::make(),
                EasyFooterPlugin::make()
                    ->withBorder()
                    ->withLogo(
                        'https://ajnusa.com/images/artacom.png',
                        'https://ajnusa.com/'
                    )
                    ->withLinks([
                        ['title' => 'Dev', 'url' => 'https://www.instagram.com/amad.dyk/'],
                    ])
                    ->withLoadTime('This page loaded in'),
                
                FilamentShieldPlugin::make(),

                GlobalSearchModalPlugin::make()
                ->scopes(UserResource::class),
                
                StickyHeaderPlugin::make()
                    ->floating()
                    ->colored()
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