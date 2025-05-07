{{-- resources/views/filament/infolists/components/timer-box.blade.php --}}
<div
    x-data="{
        openSeconds: 0,
        pendingSeconds: 0,
        totalSeconds: 0,
        progress: 0,
        darkMode: localStorage.getItem('darkMode') === 'true' || true,
        init() {
            this.initTimer();
            this.updateTimer();
            setInterval(() => this.updateTimer(), 1000);
        },
        initTimer() {
            const ticketStatus = '{{ $getRecord()->status }}';
            const reportDate = '{{ $getRecord()->report_date }}';
            
            if (reportDate) {
                try {
                    const reportTime = new Date(reportDate).getTime();
                    const now = new Date().getTime();
                    const diffSeconds = Math.floor((now - reportTime) / 1000);
                    
                    if (ticketStatus === 'OPEN') {
                        this.openSeconds = diffSeconds;
                    } else if (ticketStatus === 'PENDING') {
                        this.pendingSeconds = diffSeconds;
                    }
                } catch (e) {
                    console.error('Error:', e);
                }
            }
        },
        formatTime(seconds) {
            const hours = Math.floor(seconds / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);
            const secs = seconds % 60;
            
            return [
                hours.toString().padStart(2, '0'),
                minutes.toString().padStart(2, '0'),
                secs.toString().padStart(2, '0')
            ].join(':');
        },
        updateTimer() {
            const ticketStatus = '{{ $getRecord()->status }}';
            
            if (ticketStatus === 'OPEN') {
                this.openSeconds++;
            } else if (ticketStatus === 'PENDING') {
                this.pendingSeconds++;
            }
            
            this.totalSeconds = this.openSeconds + this.pendingSeconds;
            
            // Update progress bar SLA (target 24 jam)
            const slaTarget = 24 * 60 * 60; // dalam detik
            this.progress = Math.min(100, (this.totalSeconds / slaTarget) * 100);
        },
        toggleDarkMode() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('darkMode', this.darkMode);
        },
        getProgressColor() {
            if (this.progress >= 75) return 'bg-red-500';
            if (this.progress >= 50) return 'bg-yellow-500';
            return 'bg-blue-500';
        }
    }"
    @keydown.escape="darkMode = !darkMode"
    :class="darkMode ? 'bg-slate-800 text-white' : 'bg-white text-slate-800'"
    class="rounded-lg overflow-hidden shadow-lg transition-colors duration-300"
>
    <!-- Header with mode toggle -->
    <div class="flex justify-between items-center px-4 py-3">
        <h3 class="text-base font-semibold">SLA Timer</h3>
        <button 
            @click="toggleDarkMode()" 
            class="p-1 rounded-md transition-colors"
            :class="darkMode ? 'text-slate-300 hover:bg-slate-700' : 'text-slate-600 hover:bg-slate-100'"
        >
            <svg x-show="darkMode" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <!-- Sun icon -->
                <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" />
            </svg>
            <svg x-show="!darkMode" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <!-- Moon icon -->
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
            </svg>
        </button>
    </div>

    <!-- Timer Grid -->
    <div class="grid grid-cols-3 text-center">
        <div :class="darkMode ? 'bg-slate-900' : 'bg-slate-100'" class="p-4 transition-colors">
            <div :class="darkMode ? 'text-blue-400' : 'text-blue-600'" class="text-sm font-medium mb-1">OPEN</div>
            <div class="font-mono text-lg font-medium" x-text="formatTime(openSeconds)">00:00:00</div>
        </div>
        <div :class="darkMode ? 'bg-slate-900' : 'bg-slate-100'" class="p-4 transition-colors">
            <div :class="darkMode ? 'text-amber-400' : 'text-amber-600'" class="text-sm font-medium mb-1">PENDING</div>
            <div class="font-mono text-lg font-medium" x-text="formatTime(pendingSeconds)">00:00:00</div>
        </div>
        <div :class="darkMode ? 'bg-slate-900' : 'bg-slate-100'" class="p-4 transition-colors">
            <div :class="darkMode ? 'text-purple-400' : 'text-purple-600'" class="text-sm font-medium mb-1">TOTAL</div>
            <div class="font-mono text-lg font-medium" x-text="formatTime(totalSeconds)">00:00:00</div>
        </div>
    </div>
    
    <!-- Progress Section -->
    <div class="px-4 py-3">
        <div class="flex justify-between items-center mb-2">
            <div class="flex items-center gap-1">
                <svg class="w-4 h-4" :class="darkMode ? 'text-blue-400' : 'text-blue-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-medium">SLA Progress</span>
            </div>
            <div 
                class="text-sm font-bold"
                :class="{
                    'text-blue-500': progress < 50,
                    'text-yellow-500': progress >= 50 && progress < 75,
                    'text-red-500': progress >= 75
                }"
                x-text="Math.floor(progress) + '%'"
            >0%</div>
        </div>
        
        <!-- Progress Bar -->
        <div :class="darkMode ? 'bg-slate-700' : 'bg-slate-200'" class="h-2 rounded-full overflow-hidden mb-3 transition-colors">
            <div 
                class="h-full transition-all duration-300"
                :class="getProgressColor()"
                :style="'width: ' + progress + '%'"
            ></div>
        </div>

        <!-- Indicators -->
        <div class="grid grid-cols-3 gap-2 text-xs mb-4">
            <div class="flex items-center gap-1">
                <span class="inline-block w-2 h-2 bg-blue-500 rounded-full"></span>
                <span :class="darkMode ? 'text-slate-300' : 'text-slate-600'">Baik (<50%)</span>
            </div>
            <div class="flex items-center gap-1">
                <span class="inline-block w-2 h-2 bg-yellow-500 rounded-full"></span>
                <span :class="darkMode ? 'text-slate-300' : 'text-slate-600'">Warning (50-75%)</span>
            </div>
            <div class="flex items-center gap-1">
                <span class="inline-block w-2 h-2 bg-red-500 rounded-full"></span>
                <span :class="darkMode ? 'text-slate-300' : 'text-slate-600'">Kritis (>75%)</span>
            </div>
        </div>

        <!-- Status Badge -->
        <div 
            x-show="'{{ $getRecord()->status }}' === 'OPEN'"
            :class="darkMode ? 'bg-blue-900/30 text-blue-300 border-blue-800' : 'bg-blue-50 text-blue-700 border-blue-200'"
            class="border text-sm rounded-md p-2 flex items-center justify-center gap-2 transition-colors"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Waktu penyelesaian mulai menipis
        </div>
        
        <div 
            x-show="'{{ $getRecord()->status }}' === 'PENDING'"
            :class="darkMode ? 'bg-yellow-900/30 text-yellow-300 border-yellow-800' : 'bg-yellow-50 text-yellow-700 border-yellow-200'"
            class="border text-sm rounded-md p-2 flex items-center justify-center gap-2 transition-colors"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Tiket dalam status pending
        </div>
        
        <div 
            x-show="'{{ $getRecord()->status }}' === 'CLOSED'"
            :class="darkMode ? 'bg-slate-700 text-slate-300 border-slate-600' : 'bg-slate-100 text-slate-700 border-slate-200'"
            class="border text-sm rounded-md p-2 flex items-center justify-center gap-2 transition-colors"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            Tiket sudah ditutup - Timer berhenti
        </div>
    </div>
</div>