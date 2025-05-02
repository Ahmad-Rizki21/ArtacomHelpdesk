<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ticket;

class TicketTimer extends Component
{
    public Ticket $ticket;
    public bool $isRunning = true;
    public int $openTimeSeconds = 0;
    public int $pendingTimeSeconds = 0;
    public int $totalTimeSeconds = 0;
    public float $slaPercentage = 0;
    
    // Polling tiap 1 detik untuk update timer
    public function getListeners()
    {
        return [
            'echo:tickets.' . $this->ticket->id . ',TicketStatusUpdated' => 'refreshTicket',
        ];
    }
    
    public function mount(Ticket $ticket)
    {
        $this->ticket = $ticket;
        $this->initializeTimer();
    }
    
    public function initializeTimer()
    {
        // Hentikan timer jika status CLOSED
        if ($this->ticket->status === 'CLOSED') {
            $this->isRunning = false;
        }
        
        // Ambil data timer dari ticket
        $timer = $this->ticket->getCurrentTimer();
        $this->openTimeSeconds = $timer['open']['seconds'];
        $this->pendingTimeSeconds = $timer['pending']['seconds'];
        $this->totalTimeSeconds = $timer['total']['seconds'];
        
        // Hitung persentase SLA berdasarkan waktu total vs allowedDowntime
        $allowedSeconds = $this->ticket->calculateAllowedDowntimeInMonth() * 60; // konversi menit ke detik
        $this->slaPercentage = min(100, ($this->totalTimeSeconds / $allowedSeconds) * 100);
    }
    
    public function refreshTicket()
    {
        // Refresh ticket dari database
        $this->ticket->refresh();
        $this->initializeTimer();
    }
    
    public function render()
    {
        return view('livewire.ticket-timer', [
            'formattedOpenTime' => $this->formatTime($this->openTimeSeconds),
            'formattedPendingTime' => $this->formatTime($this->pendingTimeSeconds),
            'formattedTotalTime' => $this->formatTime($this->totalTimeSeconds),
            'slaPercentage' => $this->slaPercentage,
            'slaColor' => $this->getSlaColor(),
        ]);
    }
    
    protected function formatTime(int $seconds): string
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = $seconds % 60;
        
        return sprintf('%02d:%02d:%02d', $hours, $minutes, $secs);
    }
    
    protected function getSlaColor(): string
    {
        if ($this->slaPercentage < 50) {
            return 'success';
        } elseif ($this->slaPercentage < 75) {
            return 'warning';
        } else {
            return 'danger';
        }
    }
}