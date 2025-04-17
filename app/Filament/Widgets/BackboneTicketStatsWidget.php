<?php

namespace App\Filament\Widgets;

use App\Models\TicketBackbone;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BackboneTicketStatsWidget extends BaseWidget
{
    // Keep sort as static since it's defined as static in Widget class
    protected static ?int $sort = 3;
    
    // Keep heading as non-static since it's defined as non-static in StatsOverviewWidget
    protected ?string $heading = 'LAPORAN TICKET BACKBONE BULAN INI!';
    
    // Based on error message, pollingInterval needs to be static in StatsOverviewWidget
    protected static ?string $pollingInterval = null;

    protected function getStats(): array
    {
        return [
            Stat::make('Ticket Open', TicketBackbone::where('status', 'OPEN')->count())
                ->description('Open backbone tickets')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger')
                ->chart([0, 0, 0, 0, 0, 0, 0]),

            Stat::make('Ticket Pending', TicketBackbone::where('status', 'PENDING')->count())
                ->description('Pending backbone tickets')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('warning')
                ->chart([0, 0, 0, 0, 0, 0, 0]),

            Stat::make('Ticket Closed', TicketBackbone::where('status', 'CLOSED')->count())
                ->description('Closed backbone tickets')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->chart([0, 0, 0, 0, 0, 0, 0]),
        ];
    }
}