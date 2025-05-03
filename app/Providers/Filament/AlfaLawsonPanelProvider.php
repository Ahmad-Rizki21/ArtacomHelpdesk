<?php

namespace App\Providers\Filament;

use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;

use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Awcodes\FilamentStickyHeader\StickyHeaderPlugin;
use Devonab\FilamentEasyFooter\EasyFooterPlugin;

// Import resource AlfaLawson
use App\Filament\AlfaLawson\Resources\CustomerResource;

class AlfaLawsonPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('alfaLawson')
            ->path('alfaLawson')
            ->brandName('ARTACOM HELPDESK')
            ->darkMode(true)
            ->login()
            ->colors([
                'primary' => Color::Indigo,
                'danger' => Color::Rose,
                'warning' => Color::Orange,
                'success' => Color::Emerald,
                'info' => Color::Blue,
                'gray' => Color::Slate,
            ])
            
            
            // Daftarkan resource secara eksplisit
            ->resources([
                CustomerResource::class,
                // Tambahkan resource lain di sini jika ada
                // ProductResource::class,
                // OrderResource::class,
            ])
            
            // Discovery resources
            ->discoverResources(in: app_path('Filament/AlfaLawson/Resources'), for: 'App\\Filament\\AlfaLawson\\Resources')
            ->discoverPages(in: app_path('Filament/AlfaLawson/Pages'), for: 'App\\Filament\\AlfaLawson\\Pages')
            ->discoverWidgets(in: app_path('Filament/AlfaLawson/Widgets'), for: 'App\\Filament\\AlfaLawson\\Widgets')

            ->navigationItems([
                NavigationItem::make()
                    ->label('Panel Switcher')
                    ->icon('heroicon-o-squares-2x2')
                    ->url('/alfaLawson')
                    ->sort(-1)
                    ->group('Panel Switcher')
                    ->childItems([
                        NavigationItem::make()
                            ->label('ALFA LAWSON HELPDESK')
                            ->url('/alfaLawson')
                            ->icon('heroicon-o-arrow-right-circle')
                            ->isActiveWhen(fn () => request()->is('alfaLawson*')),
                        NavigationItem::make()
                            ->label('FTTH HELPDESK TICKET')
                            ->url('/admin')
                            ->icon('heroicon-o-check-circle'),
                    ]),
            ])
            ->pages([
                Pages\Dashboard::class,
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
            ->plugins([
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

                StickyHeaderPlugin::make()
                    ->floating()
                    ->colored()
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}