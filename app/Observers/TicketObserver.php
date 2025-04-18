<?php

namespace App\Observers;

use App\Models\Ticket;
use App\Notifications\TicketAssignedNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TicketObserver
{
    /**
     * Handle the Ticket "created" event.
     */
    public function created(Ticket $ticket): void
    {
        Log::info('Tiket baru dibuat dengan ID: ' . $ticket->id);
        Log::info('Assigned To: ' . ($ticket->assigned_to ?? 'Belum ditugaskan'));
        
        // Periksa apakah tiket memiliki nilai assigned_to saat dibuat
        if (!empty($ticket->assigned_to)) {
            Log::info('Mengirim notifikasi untuk tiket baru ke: ' . $ticket->assigned_to);
            
            try {
                // Gunakan cara yang sama seperti di command testing yang berhasil
                Notification::route('mail', $ticket->assigned_to)
                    ->notify(new TicketAssignedNotification($ticket));
                
                Log::info('Notifikasi untuk tiket baru berhasil dikirim ke: ' . $ticket->assigned_to);
                
                // Catat tindakan penugasan dalam history tiket
                $ticket->actions()->create([
                    'user_id' => Auth::id() ?? 1,
                    'action_type' => 'Assignment',
                    'description' => 'Tiket baru dibuat dan ditugaskan ke ' . $ticket->assigned_to,
                    'status' => $ticket->status,
                ]);
            } catch (\Exception $e) {
                Log::error('Gagal mengirim notifikasi untuk tiket baru: ' . $e->getMessage());
                Log::error($e->getTraceAsString());
            }
        } else {
            Log::info('Tiket baru dibuat tanpa assigned_to');
        }
    }

    /**
     * Handle the Ticket "updated" event.
     */
    public function updated(Ticket $ticket): void
    {
        Log::info('TicketObserver dipicu untuk pembaruan tiket dengan ID: ' . $ticket->id);
        
        // Cek apakah assigned_to diubah dan tidak kosong
        if ($ticket->wasChanged('assigned_to') && !empty($ticket->assigned_to)) {
            $oldAssignedTo = $ticket->getOriginal('assigned_to');
            Log::info('Mengubah penugasan tiket dari: ' . ($oldAssignedTo ?: 'tidak ada') . ' ke: ' . $ticket->assigned_to);
            
            try {
                // Kirim notifikasi ke teknisi yang baru ditugaskan
                Notification::route('mail', $ticket->assigned_to)
                    ->notify(new TicketAssignedNotification($ticket));
                
                Log::info('Notifikasi berhasil dikirim ke: ' . $ticket->assigned_to);
                
                // Catat perubahan penugasan dalam history tiket
                $ticket->actions()->create([
                    'user_id' => Auth::id() ?? 1,
                    'action_type' => 'Assignment',
                    'description' => 'Tiket ditugaskan ulang ke ' . $ticket->assigned_to,
                    'status' => $ticket->status,
                ]);
            } catch (\Exception $e) {
                Log::error('Gagal mengirim notifikasi pembaruan penugasan: ' . $e->getMessage());
                Log::error($e->getTraceAsString());
            }
        } else if ($ticket->wasChanged('assigned_to') && empty($ticket->assigned_to)) {
            Log::info('Penugasan tiket dihapus');
            
            // Catat penghapusan penugasan dalam history tiket
            $ticket->actions()->create([
                'user_id' => Auth::id() ?? 1,
                'action_type' => 'Assignment',
                'description' => 'Penugasan tiket dihapus',
                'status' => $ticket->status,
            ]);
        }
        
        // Cek jika status berubah menjadi CLOSED
        if ($ticket->wasChanged('status') && $ticket->status === 'CLOSED') {
            Log::info('Status tiket berubah menjadi CLOSED');
            
            // Jika ada teknisi yang ditugaskan, kirim notifikasi bahwa tiket telah ditutup
            if (!empty($ticket->assigned_to)) {
                try {
                    // Anda bisa membuat notifikasi baru untuk tiket yang ditutup jika diperlukan
                    // atau menggunakan notifikasi yang sama dengan pesan yang berbeda
                } catch (\Exception $e) {
                    Log::error('Gagal mengirim notifikasi tiket ditutup: ' . $e->getMessage());
                }
            }
        }
    }
}