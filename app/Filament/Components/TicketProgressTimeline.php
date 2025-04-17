<?php

namespace App\Filament\Components;

use Filament\Support\Components\ViewComponent;
use Illuminate\Contracts\View\View;
use App\Models\Ticket;

class TicketProgressTimeline extends ViewComponent
{
    protected string $view = 'filament.components.ticket-progress-timeline';
    
    public Ticket $ticket;
    
    public static function make(): static
    {
        return new static();
    }
    
    public function ticket(Ticket $ticket): static
    {
        $this->ticket = $ticket;
        return $this;
    }
    
    public function render(): View
    {
        $actions = $this->ticket->actions()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();
            
        return view($this->view, [
            'actions' => $actions,
        ]);
    }
}