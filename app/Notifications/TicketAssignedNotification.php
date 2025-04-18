<?php

namespace App\Notifications;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class TicketAssignedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected Ticket $ticket)
    {
        // Konstruktor
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        try {
            // Format tanggal laporan
            $reportDate = $this->ticket->report_date instanceof Carbon
                ? $this->ticket->report_date->format('d M Y H:i')
                : Carbon::parse($this->ticket->report_date)->format('d M Y H:i');

            // Dapatkan nama pelanggan jika tersedia
            $customerName = $this->ticket->customer ? $this->ticket->customer->composite_data : 'Unknown Customer';
            
            // Dapatkan tingkat SLA jika tersedia
            $slaLevel = $this->ticket->sla ? $this->ticket->sla->name : 'Standard';
            
            // Dapatkan warna untuk status dan SLA
            $statusColor = $this->getStatusColor($this->ticket->status);
            $slaColor = $this->getSlaColor($slaLevel);
            
            // URL untuk tombol lihat tiket
            $url = url('/admin/tickets/' . $this->ticket->id . '/edit');
            
            // Dapatkan nama pembuat tiket
            $creatorName = $this->getCreatorName();
            
            // Dapatkan nama teknisi
            $technicianName = $this->getTechnicianName();
            
            // Kirim email dengan markdown
            return (new MailMessage)
                ->subject('Tiket Baru Ditugaskan: ' . $this->ticket->ticket_number)
                ->markdown('emails.ticket-assigned', [
                    'ticket' => $this->ticket,
                    'customerName' => $customerName,
                    'reportDate' => $reportDate,
                    'slaLevel' => $slaLevel,
                    'slaColor' => $slaColor,
                    'statusColor' => $statusColor,
                    'url' => $url,
                    'creatorName' => $creatorName,
                    'technicianName' => $technicianName // Tambahkan nama teknisi
                ]);
                
        } catch (\Exception $e) {
            Log::error('Kesalahan saat menyiapkan email notifikasi: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            // Fallback sederhana jika ada kesalahan
            return (new MailMessage)
                ->subject('Tiket Baru Ditugaskan: ' . $this->ticket->ticket_number)
                ->line('Anda telah ditugaskan ke tiket baru. Silakan login ke sistem untuk melihat detailnya.')
                ->action('Lihat Tiket', url('/admin/tickets/' . $this->ticket->id . '/edit'));
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'status' => $this->ticket->status,
            'problem' => $this->ticket->problem_summary,
        ];
    }
    
    /**
     * Tentukan alamat email untuk pengiriman notifikasi.
     */
    public function routeNotificationForMail($notifiable)
    {
        Log::info('Mengirim email notifikasi ke: ' . $this->ticket->assigned_to);
        return $this->ticket->assigned_to;
    }
    
    /**
     * Mendapatkan nama pembuat tiket
     */
    private function getCreatorName(): string
    {
        try {
            // Cek apakah relasi creator ada dan memiliki nama
            if ($this->ticket->creator && $this->ticket->creator->name) {
                return $this->ticket->creator->name;
            }
            
            // Cek apakah ada user_id di created_by
            if ($this->ticket->created_by) {
                // Coba dapatkan user dari created_by
                $user = User::find($this->ticket->created_by);
                if ($user && $user->name) {
                    return $user->name;
                }
            }
            
            // Fallback jika tidak ada informasi
            return 'Sistem';
        } catch (\Exception $e) {
            Log::error('Error saat mendapatkan nama pembuat tiket: ' . $e->getMessage());
            return 'Tidak Diketahui';
        }
    }
    
    /**
     * Mendapatkan nama teknisi yang ditugaskan
     */
    private function getTechnicianName(): string
    {
        try {
            if (!empty($this->ticket->assigned_to)) {
                $user = User::where('email', $this->ticket->assigned_to)->first();
                if ($user && $user->name) {
                    return $user->name;
                }
                return $this->ticket->assigned_to;
            }
            return 'Belum Ditugaskan';
        } catch (\Exception $e) {
            Log::error('Error saat mendapatkan nama teknisi: ' . $e->getMessage());
            return $this->ticket->assigned_to ?? 'Tidak Diketahui';
        }
    }
    
    /**
     * Mendapatkan warna latar belakang sesuai level SLA
     */
    private function getSlaColor(string $slaLevel): string
    {
        return match (strtoupper($slaLevel)) {
            'HIGH' => '#dc2626', // Merah untuk prioritas tinggi
            'MEDIUM' => '#ea580c', // Oranye untuk prioritas menengah
            'LOW' => '#0284c7', // Biru untuk prioritas rendah
            default => '#6b7280', // Abu-abu untuk yang lain
        };
    }

    /**
     * Mendapatkan warna latar belakang sesuai status tiket
     */
    private function getStatusColor(string $status): string
    {
        return match (strtoupper($status)) {
            'OPEN' => '#dc2626', // Merah untuk tiket terbuka
            'PENDING' => '#d97706', // Kuning/oranye untuk tiket pending
            'CLOSED' => '#059669', // Hijau untuk tiket tertutup
            default => '#6b7280', // Abu-abu untuk yang lain
        };
    }
}