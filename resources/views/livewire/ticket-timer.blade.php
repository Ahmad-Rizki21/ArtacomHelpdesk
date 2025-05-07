{{-- resources/views/livewire/ticket-timer.blade.php --}}

<div 
    x-data="{ 
        darkMode: localStorage.getItem('darkMode') === 'true', 
        toggleDarkMode() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('darkMode', this.darkMode);
        }
    }" 
    :class="darkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200'"
    class="p-4 rounded-lg border shadow-lg transition-all duration-300" 
    wire:poll.1000ms
>
    <!-- Header with Mode Toggle -->
    <div class="flex justify-between items-center mb-4">
        <h3 :class="darkMode ? 'text-white' : 'text-gray-800'" class="text-xl font-medium transition-colors">SLA Timer</h3>
        <button 
            @click="toggleDarkMode()" 
            class="p-2 rounded-full transition-colors" 
            :class="darkMode ? 'bg-gray-700 text-yellow-400 hover:bg-gray-600' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
        >
            <svg x-show="darkMode" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" />
            </svg>
            <svg x-show="!darkMode" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
            </svg>
        </button>
    </div>
    
    <!-- Timer Boxes -->
    <div class="grid grid-cols-3 gap-3 mb-5">
        <div 
            :class="darkMode ? 'bg-gray-700' : 'bg-gray-100'"
            class="p-4 rounded-lg text-center transition-colors shadow"
        >
            <div :class="darkMode ? 'text-blue-400' : 'text-blue-600'" class="text-sm font-medium mb-1 transition-colors">OPEN</div>
            <div :class="darkMode ? 'text-white' : 'text-gray-800'" class="text-xl font-mono font-bold transition-colors">{{ $formattedOpenTime }}</div>
        </div>
        <div 
            :class="darkMode ? 'bg-gray-700' : 'bg-gray-100'"
            class="p-4 rounded-lg text-center transition-colors shadow"
        >
            <div :class="darkMode ? 'text-yellow-400' : 'text-yellow-600'" class="text-sm font-medium mb-1 transition-colors">PENDING</div>
            <div :class="darkMode ? 'text-white' : 'text-gray-800'" class="text-xl font-mono font-bold transition-colors">{{ $formattedPendingTime }}</div>
        </div>
        <div 
            :class="darkMode ? 'bg-gray-700' : 'bg-gray-100'" 
            class="p-4 rounded-lg text-center transition-colors shadow"
        >
            <div :class="darkMode ? 'text-purple-400' : 'text-purple-600'" class="text-sm font-medium mb-1 transition-colors">TOTAL</div>
            <div :class="darkMode ? 'text-white' : 'text-gray-800'" class="text-xl font-mono font-bold transition-colors">{{ $formattedTotalTime }}</div>
        </div>
    </div>
    
    <!-- Progress Bar -->
    <div class="mb-2 flex justify-between items-center">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-1" :class="darkMode ? 'text-blue-400' : 'text-blue-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div :class="darkMode ? 'text-gray-300' : 'text-gray-700'" class="text-sm font-medium transition-colors">SLA Progress</div>
        </div>
        <div class="text-sm font-bold" 
            :class="{
                'text-green-500': $slaPercentage < 50,
                'text-yellow-500': $slaPercentage >= 50 && $slaPercentage < 75,
                'text-red-500': $slaPercentage >= 75
            }">
            {{ number_format($slaPercentage, 0) }}%
        </div>
    </div>
    
    <div :class="darkMode ? 'bg-gray-700' : 'bg-gray-200'" class="h-2.5 rounded-full overflow-hidden mb-4 transition-colors">
        <div class="h-full rounded-full transition-all duration-300"
            :class="{
                'bg-green-500': $slaPercentage < 50,
                'bg-yellow-500': $slaPercentage >= 50 && $slaPercentage < 75,
                'bg-red-500': $slaPercentage >= 75
            }"
            style="width: {{ $slaPercentage }}%"></div>
    </div>
    
    <!-- Status Indicators -->
    <div class="grid grid-cols-3 gap-2 mb-4">
        <div class="flex items-center">
            <span class="inline-block w-3 h-3 bg-green-500 rounded-full mr-2"></span>
            <span :class="darkMode ? 'text-gray-300' : 'text-gray-600'" class="text-xs transition-colors">Baik (<50%)</span>
        </div>
        <div class="flex items-center">
            <span class="inline-block w-3 h-3 bg-yellow-500 rounded-full mr-2"></span>
            <span :class="darkMode ? 'text-gray-300' : 'text-gray-600'" class="text-xs transition-colors">Peringatan</span>
        </div>
        <div class="flex items-center">
            <span class="inline-block w-3 h-3 bg-red-500 rounded-full mr-2"></span>
            <span :class="darkMode ? 'text-gray-300' : 'text-gray-600'" class="text-xs transition-colors">Kritis (>75%)</span>
        </div>
    </div>
    
    <!-- Status Badge -->
    @if($ticket->status === 'CLOSED')
        <div class="flex items-center justify-center py-3 px-4 rounded-md text-sm font-medium transition-colors"
            :class="darkMode ? 'bg-gray-700 text-gray-300' : 'bg-gray-100 text-gray-600'">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            Tiket sudah ditutup - Timer berhenti
        </div>
    @elseif($ticket->status === 'OPEN')
        <div class="flex items-center justify-center py-3 px-4 rounded-md text-sm font-medium transition-colors"
            :class="darkMode ? 'bg-blue-900/30 text-blue-300' : 'bg-blue-100 text-blue-700'">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Tiket sedang diproses
        </div>
    @elseif($ticket->status === 'PENDING')
        <div class="flex items-center justify-center py-3 px-4 rounded-md text-sm font-medium transition-colors"
            :class="darkMode ? 'bg-yellow-900/30 text-yellow-300' : 'bg-yellow-100 text-yellow-700'">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Tiket dalam status pending
        </div>
    @endif
</div>