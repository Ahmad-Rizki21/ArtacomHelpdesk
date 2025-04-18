<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Notifications\TicketAssignedNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class MailTestController extends Controller
{
    /**
     * Tampilkan halaman pengujian email
     */
    public function index()
    {
        $tickets = Ticket::latest()->take(10)->get();
        return view('mail-test', compact('tickets'));
    }
    
    /**
     * Kirim email pengujian
     */
    public function sendTestMail(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'email' => 'required|email'
        ]);
        
        $ticket = Ticket::findOrFail($request->ticket_id);
        $testEmail = $request->email;
        
        try {
            Notification::route('mail', $testEmail)
                ->notify(new TicketAssignedNotification($ticket));
            
            Log::info("Email pengujian dikirim ke {$testEmail} untuk tiket {$ticket->ticket_number}");
            
            return back()->with('success', "Email berhasil dikirim ke {$testEmail}. Periksa inbox di Mailtrap.");
        } catch (\Exception $e) {
            Log::error("Gagal mengirim email pengujian: " . $e->getMessage());
            
            return back()->with('error', "Gagal mengirim email: " . $e->getMessage())
                         ->withInput();
        }
    }
    
    /**
     * Kirim email pengujian via API (untuk debugging via AJAX)
     */
    public function apiSendTestMail(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'email' => 'required|email'
        ]);
        
        $ticket = Ticket::findOrFail($request->ticket_id);
        $testEmail = $request->email;
        
        try {
            Notification::route('mail', $testEmail)
                ->notify(new TicketAssignedNotification($ticket));
            
            Log::info("Email pengujian via API dikirim ke {$testEmail} untuk tiket {$ticket->ticket_number}");
            
            return response()->json([
                'success' => true,
                'message' => "Email berhasil dikirim ke {$testEmail}. Periksa inbox di Mailtrap."
            ]);
        } catch (\Exception $e) {
            Log::error("Gagal mengirim email pengujian via API: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => "Gagal mengirim email: " . $e->getMessage(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}