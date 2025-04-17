<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\TicketStatsWidget;
use App\Filament\Widgets\BackboneTicketStatsWidget;
use App\Filament\Widgets\TicketChartWidget;
use App\Filament\Widgets\BackboneTicketChartWidget;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?string $title = 'Dashboard';
    
    // Configure to use one column layout for charts
    // This makes charts display full width
    public function getColumns(): int
    {
        return 1;
    }

    // Configure header widgets (stats cards)
    protected function getHeaderWidgets(): array
    {
        return [
            TicketStatsWidget::class,
            BackboneTicketStatsWidget::class,
        ];
    }

    // Configure content widgets (charts)
    public function getWidgets(): array
    {
        return [
            TicketChartWidget::class,
            BackboneTicketChartWidget::class,
        ];
    }
}