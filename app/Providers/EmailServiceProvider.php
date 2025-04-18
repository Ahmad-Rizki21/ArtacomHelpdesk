<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;

class EmailServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Log semua aktivitas pengiriman email
        \Illuminate\Support\Facades\Mail::alwaysFrom(
            config('mail.from.address', 'helpdesk@ajnusa.com'),
            config('mail.from.name', 'Helpdesk System')
        );

        // Log semua email yang dikirim
        \Illuminate\Support\Facades\Mail::beforeSending(function ($message) {
            $recipients = $message->getTo();
            $subject = $message->getSubject();
            
            $recipientAddresses = array_keys($recipients);
            
            Log::info('Mengirim email', [
                'recipients' => implode(', ', $recipientAddresses),
                'subject' => $subject,
                'message_id' => $message->getMessageId()
            ]);
        });
        
        // Log ketika email berhasil dikirim
        \Illuminate\Support\Facades\Mail::sent(function ($message) {
            $recipients = $message->getTo();
            $subject = $message->getSubject();
            
            $recipientAddresses = array_keys($recipients);
            
            Log::info('Email berhasil dikirim', [
                'recipients' => implode(', ', $recipientAddresses),
                'subject' => $subject,
                'message_id' => $message->getMessageId()
            ]);
        });
        
        // Log jika terjadi kesalahan saat mengirim email
        \Illuminate\Support\Facades\Mail::failed(function ($event) {
            $message = $event->message;
            $error = $event->error;
            
            // Ekstrak detil penerima jika tersedia
            $recipients = method_exists($message, 'getTo') ? $message->getTo() : [];
            $recipientAddresses = is_array($recipients) ? array_keys($recipients) : [];
            
            Log::error('Gagal mengirim email', [
                'recipients' => is_array($recipientAddresses) ? implode(', ', $recipientAddresses) : 'Unknown',
                'subject' => method_exists($message, 'getSubject') ? $message->getSubject() : 'Unknown',
                'error' => $error->getMessage()
            ]);
        });
    }
}