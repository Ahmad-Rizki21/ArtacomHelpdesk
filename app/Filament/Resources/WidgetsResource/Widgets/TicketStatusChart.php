<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;

class TicketStatusChart extends Widget
{
    protected static string $view = 'filament.widgets.ticket-status-chart';

    public function getData(): array
    {
        // Query the database to get ticket counts
        $openTickets = DB::table('tickets')->where('status', 'open')->count();
        $pendingTickets = DB::table('tickets')->where('status', 'pending')->count();
        $closedTickets = DB::table('tickets')->where('status', 'closed')->count();

        return [
            'openTickets' => $openTickets,
            'pendingTickets' => $pendingTickets,
            'closedTickets' => $closedTickets,
        ];
    }
}