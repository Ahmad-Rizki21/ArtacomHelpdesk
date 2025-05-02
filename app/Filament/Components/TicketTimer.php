<?php

namespace App\Filament\Components;

use Filament\Support\Components\ViewComponent;
use Illuminate\Contracts\View\View;
use App\Models\Ticket;
use Carbon\Carbon;

class TicketTimer extends ViewComponent
{
    protected string $view = 'components.ticket-timer';
    
    public Ticket $ticket;
    public ?int $allowedDowntimeMinutes = null;
    
    public static function make(): static
    {
        return new static();
    }
    
    public function ticket(Ticket $ticket): static
    {
        $this->ticket = $ticket;
        
        // Hitung allowed downtime dari SLA ticket
        if ($ticket->sla) {
            $this->allowedDowntimeMinutes = $ticket->calculateAllowedDowntimeInMonth();
        }
        
        return $this;
    }
    
    public function allowedDowntime(int $minutes): static
    {
        $this->allowedDowntimeMinutes = $minutes;
        return $this;
    }
    
    public function getAllowedDowntimeSeconds(): int
    {
        // Default ke 24 jam jika tidak ada SLA
        return ($this->allowedDowntimeMinutes ?? 1440) * 60;
    }
    
    public function getOpenTime(): int
    {
        if (!$this->ticket->report_date) {
            return 0;
        }
        
        $now = Carbon::now();
        $reportDate = $this->ticket->report_date;
        
        // Jika status CLOSED, gunakan closed_date sebagai akhir
        if ($this->ticket->status === 'CLOSED' && $this->ticket->closed_date) {
            $now = $this->ticket->closed_date;
        }
        
        // Hitung total waktu dalam detik
        $totalSeconds = $now->diffInSeconds($reportDate);
        
        // Kurangi dengan waktu pending jika ada
        if ($this->ticket->pending_clock && $this->ticket->pending_clock > 0) {
            $totalSeconds -= (int) $this->ticket->pending_clock * 60; // konversi ke detik
        }
        
        return $totalSeconds;
    }
    
    public function getPendingTime(): int
    {
        if ($this->ticket->status !== 'PENDING' || !$this->ticket->pending_clock) {
            return 0;
        }
        
        $pendingStart = Carbon::parse($this->ticket->pending_clock);
        $now = Carbon::now();
        
        return $now->diffInSeconds($pendingStart);
    }
    
    public function getStartTime(): int
    {
        // TODO: Implement start time calculation if needed
        return 0;
    }
    
    public function getSlaProgressPercentage(): float
    {
        $openTime = $this->getOpenTime();
        $allowedTime = $this->getAllowedDowntimeSeconds();
        
        return min(100, ($openTime / $allowedTime) * 100);
    }
    
    public function render(): View
    {
        return view($this->view, [
            'ticket' => $this->ticket,
            'openTime' => $this->getOpenTime(),
            'pendingTime' => $this->getPendingTime(),
            'startTime' => $this->getStartTime(),
            'slaProgress' => $this->getSlaProgressPercentage(),
            'allowedDowntimeSeconds' => $this->getAllowedDowntimeSeconds(),
        ]);
    }
}