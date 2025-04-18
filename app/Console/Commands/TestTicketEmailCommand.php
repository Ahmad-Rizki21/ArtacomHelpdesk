<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ticket;
use App\Notifications\TicketAssignedNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class TestTicketEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ticket:test-email {ticket_id? : ID tiket untuk digunakan} {email? : Alamat email untuk pengujian}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menguji pengiriman email notifikasi tiket ke teknisi';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $ticketId = $this->argument('ticket_id');
        $testEmail = $this->argument('email');
        
        if (!$ticketId) {
            // Jika tidak ada ID tiket, coba dapatkan tiket terbaru
            $ticket = Ticket::latest()->first();
            
            if (!$ticket) {
                $this->error('Tidak ada tiket yang ditemukan di database. Silakan buat tiket terlebih dahulu atau tentukan ID tiket.');
                return 1;
            }
            
            $ticketId = $ticket->id;
        } else {
            $ticket = Ticket::find($ticketId);
            
            if (!$ticket) {
                $this->error("Tiket dengan ID {$ticketId} tidak ditemukan.");
                return 1;
            }
        }
        
        if (!$testEmail) {
            if (!$ticket->assigned_to) {
                $testEmail = $this->ask('Tiket tidak memiliki email teknisi yang ditugaskan. Masukkan alamat email untuk pengujian:');
            } else {
                $testEmail = $ticket->assigned_to;
                $useAssignedEmail = $this->confirm("Gunakan email yang sudah ditugaskan: {$testEmail}?", true);
                
                if (!$useAssignedEmail) {
                    $testEmail = $this->ask('Masukkan alamat email alternatif untuk pengujian:');
                }
            }
        }
        
        $this->info("Mengirim email pengujian untuk tiket #{$ticket->id} ({$ticket->ticket_number}) ke {$testEmail}...");
        
        try {
            // Kirim notifikasi menggunakan route untuk email pengujian
            Notification::route('mail', $testEmail)
                ->notify(new TicketAssignedNotification($ticket));
            
            Log::info("Email pengujian dikirim ke {$testEmail} untuk tiket {$ticket->ticket_number}");
            $this->info("Email pengujian berhasil dikirim ke {$testEmail}!");
            $this->info("Periksa mailbox di Mailtrap untuk melihat email.");
            return 0;
        } catch (\Exception $e) {
            Log::error("Gagal mengirim email pengujian: " . $e->getMessage());
            $this->error("Gagal mengirim email: " . $e->getMessage());
            $this->newLine();
            $this->info("Detail error:");
            $this->error($e->getTraceAsString());
            return 1;
        }
    }
}