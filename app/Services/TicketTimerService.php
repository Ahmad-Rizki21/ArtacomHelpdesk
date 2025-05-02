<?php

namespace App\Services;

use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TicketTimerService
{
    /**
     * Update timer saat status ticket berubah
     *
     * @param Ticket $ticket
     * @param string $oldStatus
     * @param string $newStatus
     * @return void
     */
    public function updateTimerOnStatusChange(Ticket $ticket, string $oldStatus, string $newStatus): void
    {
        $now = Carbon::now();
        
        // Jika previous_status_change_at kosong, itu tiket baru
        if (!$ticket->last_status_change_at) {
            $ticket->last_status_change_at = $now;
            $ticket->save();
            return;
        }
        
        // Hitung durasi sejak terakhir status berubah
        $lastStatusChange = Carbon::parse($ticket->last_status_change_at);
        $durationInSeconds = $now->diffInSeconds($lastStatusChange);
        
        // Update timer berdasarkan status sebelumnya
        if ($oldStatus === 'OPEN') {
            $ticket->open_time_seconds = ($ticket->open_time_seconds ?? 0) + $durationInSeconds;
        } elseif ($oldStatus === 'PENDING') {
            $ticket->pending_time_seconds = ($ticket->pending_time_seconds ?? 0) + $durationInSeconds;
        }
        
        // Update last_status_change_at
        $ticket->last_status_change_at = $now;
        $ticket->save();
        
        // Log perubahan status dan timer
        Log::info("Ticket #{$ticket->ticket_number} status changed from {$oldStatus} to {$newStatus}. Timer updated.", [
            'ticket_id' => $ticket->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'duration_seconds' => $durationInSeconds,
            'open_time_seconds' => $ticket->open_time_seconds,
            'pending_time_seconds' => $ticket->pending_time_seconds,
        ]);
    }
    
    /**
     * Menghitung total resolution time (dalam detik) untuk tiket
     *
     * @param Ticket $ticket
     * @return int|null
     */
    public function calculateResolutionTimeSeconds(Ticket $ticket): ?int
    {
        // Jika tiket belum ditutup, return null
        if ($ticket->status !== 'CLOSED' || !$ticket->closed_date) {
            return null;
        }
        
        // Ambil total waktu OPEN dan waktu respon SLA dari ticket model
        return ($ticket->open_time_seconds ?? 0);
    }
    
    /**
     * Menghitung persentase SLA
     *
     * @param Ticket $ticket
     * @return float|null
     */
    public function calculateSlaPercentage(Ticket $ticket): ?float
    {
        // Ambil waktu resolusi dalam detik
        $resolutionTimeSeconds = $this->calculateResolutionTimeSeconds($ticket);
        
        if ($resolutionTimeSeconds === null) {
            // Jika tiket belum closed, hitung berdasarkan waktu saat ini
            $currentTimer = $ticket->getCurrentTimer();
            $resolutionTimeSeconds = $currentTimer['open']['seconds'];
        }
        
        // Hitung allowedDowntime dalam detik
        $allowedDowntimeSeconds = $ticket->calculateAllowedDowntimeInMonth() * 60; // konversi menit ke detik
        
        // Hitung persentase (tidak melebihi 100%)
        return min(100, ($resolutionTimeSeconds / $allowedDowntimeSeconds) * 100);
    }
    
    /**
     * Memeriksa apakah tiket masih dalam batas SLA
     *
     * @param Ticket $ticket
     * @return bool|null
     */
    public function isWithinSla(Ticket $ticket): ?bool
    {
        $slaPercentage = $this->calculateSlaPercentage($ticket);
        
        if ($slaPercentage === null) {
            return null;
        }
        
        return $slaPercentage <= 100;
    }
}