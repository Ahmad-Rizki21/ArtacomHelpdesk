<?php

namespace App\Providers;

use App\Models\Ticket;
use App\Observers\TicketObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Log;

class EventServiceProvider extends ServiceProvider
{
    protected $observers = [
        Ticket::class => [TicketObserver::class],
    ];

    public function boot(): void
    {
        parent::boot();
        
        // Log untuk debugging
        Log::info('EventServiceProvider booted');
        
        // Registrasi manual untuk memastikan
        \App\Models\Ticket::observe(\App\Observers\TicketObserver::class);
        Log::info('Ticket observer registered manually');
    }
}