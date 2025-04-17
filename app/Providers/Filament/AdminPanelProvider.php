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
            // ->brandLogo(asset('images/Capture-removebg-preview.png'))
            // ->brandLogoHeight('60px')
            ->darkMode(true) // Enable dark mode for better chart visibility
            
            // Define navigation groups without icons
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Helpdesk'),
                NavigationGroup::make()
                    ->label('Backbone'),
                NavigationGroup::make()
                    ->label('Management'),
                NavigationGroup::make()
                    ->label('System'),
            ])
            
            // Register resources
            ->resources([
                TicketResource::class,
            ])
            
            // Discover custom pages and resources
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            
            // Important: Use the correct base Dashboard class
            ->pages([
                Pages\Dashboard::class,
            ])
            
            // Register widgets - remove AccountWidget
            ->widgets([
                TicketStatsWidget::class,
                BackboneTicketStatsWidget::class,
                TicketChartWidget::class,
                BackboneTicketChartWidget::class,
                // Removed Widgets\AccountWidget::class
            ])
            
            // Register Filament Apex Charts plugin
            ->plugins([
                FilamentApexChartsPlugin::make(),
                EasyFooterPlugin::make()
                ->withBorder()
                ->withLogo(
                    'https://ajnusa.com/images/artacom.png', // Path to logo
                    'https://ajnusa.com/'                                // URL for logo link (optional)
                )
                ->withLinks([
                    ['title' => 'Dev', 'url' => 'https://www.instagram.com/amad.dyk/'],
                ])
                ->withLoadTime('This page loaded in'),
            ])
            
            // Middleware configuration
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