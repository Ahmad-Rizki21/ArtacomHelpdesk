<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use PDF;

class TicketExportController extends Controller
{
    public function pdf(Ticket $ticket)
    {
        $today = now()->format('d-m-Y H:i:s');
        $company = 'FTTH JELANTIK HELPDESK';
        // Pastikan relasi actions dan customer dipanggil
        $ticket->load(['actions.user','customer','sla']);

        $pdf = PDF::loadView('pdf.ticket-report', compact('ticket', 'today', 'company'));
        $filename = 'TICKET-'.$ticket->ticket_number.'.pdf';
        return $pdf->download($filename);
    }
}