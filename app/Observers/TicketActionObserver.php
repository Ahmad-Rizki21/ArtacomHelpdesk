<?php

namespace App\Observers;

use App\Models\TicketAction;
use App\Models\User;

class TicketActionObserver
{
    /**
     * Handle the TicketAction "created" event.
     */
    public function created(TicketAction $ticketAction): void
    {
        // Ambil user yang melakukan tindakan
        $user = User::find($ticketAction->user_id);
        if ($user) {
            $user->updateScore();
            \Illuminate\Support\Facades\Log::info('Skor user ' . $user->name . ' diperbarui setelah melakukan tindakan pada ticket ID: ' . $ticketAction->ticket_id);
        }
    }

    /**
     * Handle the TicketAction "updated" event.
     */
    public function updated(TicketAction $ticketAction): void
    {
        // Ambil user yang melakukan tindakan
        $user = User::find($ticketAction->user_id);
        if ($user) {
            $user->updateScore();
            \Illuminate\Support\Facades\Log::info('Skor user ' . $user->name . ' diperbarui setelah memperbarui tindakan pada ticket ID: ' . $ticketAction->ticket_id);
        }
    }
}