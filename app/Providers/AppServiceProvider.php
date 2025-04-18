<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Ticket;
use App\Observers\TicketObserver;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Log::info('AppServiceProvider booted');
        
        // Register observer here
        Ticket::observe(TicketObserver::class);
        Log::info('Ticket observer registered in AppServiceProvider');
    }
}