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


use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;

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
            ->brandName('FTTH JELANTIK HELPDESK')
            ->darkMode(true)
            
            ->navigationGroups([
                NavigationGroup::make()->label('Helpdesk'),
                NavigationGroup::make()->label('Backbone'),
                NavigationGroup::make()->label('Management'),
                NavigationGroup::make()->label('System'),
            ])
            
            ->resources([
                TicketResource::class,
                config('filament-logger.activity_resource'),
            ])
            
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            
            ->pages([
                Pages\Dashboard::class,
            ])
            
            ->widgets([
                TicketStatsWidget::class,
                BackboneTicketStatsWidget::class,
                TicketChartWidget::class,
                BackboneTicketChartWidget::class,
            ])
            
            // Consolidate plugin registration
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
                
                // Move Filament Shield plugin here
                FilamentShieldPlugin::make(),
            ])
            
            // Remove duplicate resources block
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