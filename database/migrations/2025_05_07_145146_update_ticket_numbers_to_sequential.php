<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateTicketNumbersToSequential extends Migration
{
    /**
     * Run the migration.
     *
     * @return void
     */
    public function up()
    {
        // Define ticket prefix constant
        $ticketPrefix = 'TFTTH-';
        
        // Get all tickets sorted by ID (creation order)
        $tickets = DB::table('tickets')->orderBy('id')->get();
        
        // Start counter from 1
        $counter = 1;
        
        // Update each ticket with a sequential number
        foreach ($tickets as $ticket) {
            // Format the number with leading zeros (4 digits)
            $newTicketNumber = $ticketPrefix . str_pad($counter, 4, '0', STR_PAD_LEFT);
            
            // Update the ticket record
            DB::table('tickets')
                ->where('id', $ticket->id)
                ->update(['ticket_number' => $newTicketNumber]);
            
            // Increment counter for next ticket
            $counter++;
        }
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        // This migration cannot be reversed as the original random ticket numbers are lost
        // You could restore from a backup if needed
    }
}