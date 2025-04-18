<?php

namespace App\Filament\Components;

use Filament\Support\Components\ViewComponent;
use Illuminate\Contracts\View\View;
use App\Models\TicketBackbone;

class TicketBackboneProgressTimeline extends ViewComponent
{
    protected string $view = 'filament.components.ticket-backbone-progress-timeline';
    
    public TicketBackbone $ticket;
    
    public static function make(): static
    {
        return new static();
    }
    
    public function ticket(TicketBackbone $ticket): static
    {
        $this->ticket = $ticket;
        return $this;
    }
    
    public function render(): View
    {
        // Get actions in reverse chronological order (newest first)
        $actions = $this->ticket->actions()
            ->with('user')
            ->latest('created_at')
            ->get();
            
        return view($this->view, [
            'actions' => $actions,
        ]);
    }
}