<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use Filament\Widgets\Widget;
use Carbon\Carbon;

class TicketSlaStats extends Widget
{
    protected static string $view = 'filament.widgets.ticket-sla-stats';
    protected int | string | array $columnSpan = 'full';
    protected static ?string $pollingInterval = '15s'; // Refresh setiap 15 detik
    
    public function getViewData(): array
    {
        // Ambil parameter dari URL
        $activeTab = request()->query('activeTab', 'All');
        $filterService = 'FTTH'; // Fokus pada FTTH
        
        // Buat query dasar
        $query = Ticket::query()->where('service', $filterService);
        
        // Terapkan filter periode jika ada
        $filters = request()->query('tableFilters');
        if (!empty($filters['periode'])) {
            if (!empty($filters['periode']['start_date'])) {
                $query->whereDate('created_at', '>=', $filters['periode']['start_date']);
            }
            if (!empty($filters['periode']['end_date'])) {
                $query->whereDate('created_at', '<=', $filters['periode']['end_date']);
            }
        }
        
        // Filter berdasarkan status jika tab aktif bukan 'All' atau 'SLA'
        if (!in_array($activeTab, ['All', 'SLA'])) {
            if ($activeTab === 'Active') {
                $query->whereIn('status', ['OPEN', 'PENDING']);
            } elseif ($activeTab === 'Open') {
                $query->where('status', 'OPEN');
            } elseif ($activeTab === 'Pending') {
                $query->where('status', 'PENDING');
            } elseif ($activeTab === 'Closed') {
                $query->where('status', 'CLOSED');
            }
        }
        
        // Ambil semua tiket yang memenuhi kriteria
        $tickets = $query->get();
        
        // Hitung statistik
        $totalTickets = $tickets->count();
        $closedTickets = $tickets->where('status', 'CLOSED')->count();
        
        // Hitung SLA berdasarkan status
        $meetingSla = $tickets->filter(function($ticket) {
            return $ticket->status === 'CLOSED' && $ticket->sla_status === 'Memenuhi SLA';
        })->count();
        
        $exceedingSla = $tickets->filter(function($ticket) {
            return $ticket->status === 'CLOSED' && $ticket->sla_status === 'Melebihi SLA';
        })->count();
        
        // Hitung compliance percentage
        $compliancePercentage = $closedTickets > 0 ? 
            round(($meetingSla / $closedTickets) * 100, 2) : 0;
        
        // Format untuk tampilan
        $now = Carbon::now();
        $monthName = $now->format('F Y');
        
        if (!empty($filters['periode'])) {
            if (!empty($filters['periode']['start_date'])) {
                $date = Carbon::parse($filters['periode']['start_date']);
                $monthName = $date->format('F Y');
            }
        }
        
        // Debug info - tambahkan ini untuk membantu debug
        $debugInfo = [
            'query_params' => request()->query(),
            'active_tab' => $activeTab,
            'filter_count' => count($filters ?? []),
            'ticket_count' => $totalTickets,
        ];
        
        return [
            'totalTickets' => $totalTickets,
            'closedTickets' => $closedTickets,
            'meetingSla' => $meetingSla,
            'exceedingSla' => $exceedingSla,
            'compliancePercentage' => $compliancePercentage,
            'period' => $monthName,
            'debug' => $debugInfo, // Debug info
        ];
    }
}