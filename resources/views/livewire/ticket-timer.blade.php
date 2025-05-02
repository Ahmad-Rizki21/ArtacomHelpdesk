{{-- resources/views/livewire/ticket-timer.blade.php --}}
<div class="p-4 bg-gray-800 rounded-lg border border-gray-700 shadow-lg" wire:poll.1000ms>
    <h3 class="text-xl font-medium text-white mb-4">SLA Timer</h3>
    
    <div class="grid grid-cols-3 gap-4 mb-4">
        <div class="p-3 bg-gray-700 rounded-lg text-center">
            <div class="text-sm text-gray-300 mb-1">Open</div>
            <div class="text-xl text-white font-mono">{{ $formattedOpenTime }}</div>
        </div>
        <div class="p-3 bg-gray-700 rounded-lg text-center">
            <div class="text-sm text-gray-300 mb-1">Pending</div>
            <div class="text-xl text-white font-mono">{{ $formattedPendingTime }}</div>
        </div>
        <div class="p-3 bg-gray-700 rounded-lg text-center">
            <div class="text-sm text-gray-300 mb-1">Total</div>
            <div class="text-xl text-white font-mono">{{ $formattedTotalTime }}</div>
        </div>
    </div>
    
    <div class="mb-2 flex justify-between items-center">
        <div class="text-sm text-white">SLA Progress</div>
        <div class="text-sm font-medium 
            @if($slaPercentage < 50) text-green-400 
            @elseif($slaPercentage < 75) text-yellow-400 
            @else text-red-400 
            @endif">
            {{ number_format($slaPercentage, 1) }}%
        </div>
    </div>
    
    <div class="h-2 bg-gray-700 rounded-full overflow-hidden">
        <div class="h-full rounded-full 
            @if($slaPercentage < 50) bg-green-500 
            @elseif($slaPercentage < 75) bg-yellow-500 
            @else bg-red-500 
            @endif"
            style="width: {{ $slaPercentage }}%"></div>
    </div>
    
    <div class="mt-2 text-xs text-gray-400">
        <span class="inline-block mr-2">
            <span class="inline-block w-3 h-3 bg-green-500 rounded-full mr-1"></span> Baik
        </span>
        <span class="inline-block mr-2">
            <span class="inline-block w-3 h-3 bg-yellow-500 rounded-full mr-1"></span> Peringatan
        </span>
        <span class="inline-block">
            <span class="inline-block w-3 h-3 bg-red-500 rounded-full mr-1"></span> Kritis
        </span>
    </div>
    
    @if($ticket->status === 'CLOSED')
        <div class="mt-3 text-center py-2 px-3 bg-gray-700 text-gray-300 rounded-md text-sm">
            Tiket sudah ditutup - Timer berhenti
        </div>
    @else
        <div class="mt-3 text-center py-2 px-3 
            @if($ticket->status === 'OPEN') bg-blue-900 text-blue-300 
            @elseif($ticket->status === 'PENDING') bg-yellow-900 text-yellow-300 
            @endif rounded-md text-sm">
            Status: {{ $ticket->status }}
        </div>
    @endif
</div>