<?php

use Livewire\Component;

class CountdownTimer extends Component
{
    public int $duration = 900; // 15 menit dalam detik
    public int $remainingTime;

    public function mount()
    {
        $this->remainingTime = $this->duration;
        $this->startCountdown();
    }

    public function startCountdown()
    {
        // Logika untuk mengurangi waktu yang tersisa setiap detik
        $this->dispatchBrowserEvent('start-timer', ['duration' => $this->duration]);
    }

    public function render()
    {
        return view('livewire.countdown-timer');
    }
}
