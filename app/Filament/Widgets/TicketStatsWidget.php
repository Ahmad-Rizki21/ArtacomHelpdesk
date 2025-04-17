<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TicketStatsWidget extends BaseWidget
{
    // Keep sort as static since it's defined as static in Widget class
    protected static ?int $sort = 1;
    
    // Keep heading as non-static since it's defined as non-static in StatsOverviewWidget
    protected ?string $heading = 'LAPORAN TICKET FTTH BULAN INI!';
    
    // Based on error message, pollingInterval needs to be static in StatsOverviewWidget
    protected static ?string $pollingInterval = null;

    protected function getStats(): array
    {
        return [
            Stat::make('Ticket Open', Ticket::where('status', 'OPEN')->count())
                ->description('Open tickets')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger')
                ->chart([0, 0, 0, 0, 0, 0, 0]),

            Stat::make('Ticket Pending', Ticket::where('status', 'PENDING')->count())
                ->description('Pending tickets')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('warning')
                ->chart([0, 0, 0, 0, 0, 0, 0]),

            Stat::make('Ticket Closed', Ticket::where('status', 'CLOSED')->count())
                ->description('Closed tickets')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->chart([0, 0, 0, 0, 0, 0, 0]),
        ];
    }
}