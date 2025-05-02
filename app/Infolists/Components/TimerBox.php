<?php

namespace App\Filament\Infolists\Components;

use Filament\Infolists\Components\Component;
use Illuminate\Support\Facades\Blade;
use Illuminate\Contracts\View\View;

class TimerBox extends Component
{
    protected string $view = 'filament.infolists.components.timer-box';
    
    // Properti yang bisa dikonfigurasi
    protected bool $showPendingTimer = true;
    protected int $targetHours = 24;
    
    public static function make(): static
    {
        return app(static::class);
    }
    
    // Method untuk mengkonfigurasi properti
    public function showPendingTimer(bool $condition = true): static
    {
        $this->showPendingTimer = $condition;
        return $this;
    }
    
    public function targetHours(int $hours): static
    {
        $this->targetHours = $hours;
        return $this;
    }
    
    public function getTargetHours(): int
    {
        return $this->targetHours;
    }
    
    public function shouldShowPendingTimer(): bool
    {
        return $this->showPendingTimer;
    }
}