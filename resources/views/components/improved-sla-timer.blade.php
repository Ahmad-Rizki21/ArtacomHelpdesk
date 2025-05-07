<!-- resources/views/components/adaptive-sla-timer.blade.php -->
<div
    x-data="{
        darkMode: localStorage.getItem('darkMode') === 'true' || true,
        openSeconds: 0,
        pendingSeconds: 0,
        totalSeconds: 0,
        slaPercentage: 0,
        slaMessage: 'Menunggu Kalkulasi SLA...',
        slaTargetHours: 24,
        toggleDarkMode() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('darkMode', this.darkMode);
        },
        init() {
            this.setupSLA();
            this.setupTimer();
        },
        setupSLA() {
            // Menentukan target SLA berdasarkan level
            const slaName = '{{ $ticket->sla->name ?? 'MEDIUM' }}';
            if (slaName === 'HIGH') {
                this.slaTargetHours = 4; // 4 jam
            } else if (slaName === 'LOW') {
                this.slaTargetHours = 48; // 48 jam
            } else {
                this.slaTargetHours = 24; // Default MEDIUM (24 jam)
            }
        },
        setupTimer() {
            const ticketStatus = '{{ $ticket->status }}';
            const reportDate = '{{ $ticket->report_date }}';
            const closedDate = '{{ $ticket->closed_date }}';
            const openTimeDB = {{ $ticket->open_time_seconds ?? 0 }};
            const pendingTimeDB = {{ $ticket->pending_time_seconds ?? 0 }};
            
            // Inisialisasi timer dengan nilai dari database
            this.openSeconds = Math.max(0, openTimeDB);
            this.pendingSeconds = Math.max(0, pendingTimeDB);
            this.totalSeconds = this.openSeconds + this.pendingSeconds;
            
            // Kalkulasi SLA
            this.updateSLAProgress();
            
            // Handling untuk tiket yang CLOSED
            if (ticketStatus === 'CLOSED') {
                this.slaMessage = 'Tiket sudah ditutup pada {{ \Carbon\Carbon::parse($ticket->closed_date)->format('d M Y H:i') }}';
                return;
            }
            
            // Setup timer untuk tiket yang masih aktif
            if (reportDate) {
                // Kalkulasi waktu jika nilai dari DB kosong
                if (this.openSeconds === 0 && this.pendingSeconds === 0) {
                    try {
                        const reportTime = new Date(reportDate).getTime();
                        const now = new Date().getTime();
                        const diffSeconds = Math.floor((now - reportTime) / 1000);
                        
                        if (ticketStatus === 'OPEN') {
                            this.openSeconds = diffSeconds;
                        } else if (ticketStatus === 'PENDING') {
                            this.pendingSeconds = diffSeconds;
                        }
                        
                        this.totalSeconds = this.openSeconds + this.pendingSeconds;
                    } catch (e) {
                        console.error('Error:', e);
                        this.slaMessage = 'Error saat kalkulasi timer: ' + e.message;
                    }
                }
                
                // Start timer untuk tiket aktif
                this.startTimer(ticketStatus);
            } else {
                this.slaMessage = 'Tidak ada data waktu untuk tiket ini';
            }
        },
        startTimer(ticketStatus) {
            // Update awal
            this.updateTimerDisplay();
            
            // Set interval untuk update setiap detik
            setInterval(() => {
                // Increment waktu berdasarkan status
                if (ticketStatus === 'OPEN') {
                    this.openSeconds++;
                } else if (ticketStatus === 'PENDING') {
                    this.pendingSeconds++;
                }
                
                this.totalSeconds = this.openSeconds + this.pendingSeconds;
                this.updateTimerDisplay();
                this.updateSLAProgress();
            }, 1000);
        },
        updateTimerDisplay() {
            // Tidak perlu lagi, Alpine akan handle display
        },
        updateSLAProgress() {
            const slaTarget = this.slaTargetHours * 60 * 60; // dalam detik
            this.slaPercentage = Math.min(100, (this.totalSeconds / slaTarget) * 100);
            
            // Update pesan SLA
            this.slaMessage = this.getSLAMessage(this.slaPercentage);
        },
        getSLAMessage(progress) {
            if ('{{ $ticket->status }}' === 'CLOSED') {
                return 'Tiket sudah ditutup pada {{ \Carbon\Carbon::parse($ticket->closed_date)->format('d M Y H:i') }}';
            }
            
            if (progress < 25) {
                return 'Waktu penyelesaian masih banyak tersisa';
            } else if (progress < 50) {
                return 'Waktu penyelesaian masih cukup';
            } else if (progress < 75) {
                return 'Waktu penyelesaian mulai menipis';
            } else if (progress < 90) {
                return 'Segera selesaikan tiket ini!';
            } else {
                return 'PERHATIAN: SLA hampir terlampaui!';
            }
        },
        formatTime(seconds) {
            const absSeconds = Math.abs(seconds);
            const hours = Math.floor(absSeconds / 3600);
            const minutes = Math.floor((absSeconds % 3600) / 60);
            const secs = absSeconds % 60;
            
            return [
                hours.toString().padStart(2, '0'),
                minutes.toString().padStart(2, '0'),
                secs.toString().padStart(2, '0')
            ].join(':');
        }
    }"
    :class="darkMode ? 'sla-timer-dark' : 'sla-timer-light'"
    class="sla-timer-wrapper rounded-lg overflow-hidden shadow-lg transition-colors duration-300"
>
    <!-- Header with clock icon -->
    <div class="sla-timer-header flex items-center justify-between">
        <div class="flex items-center space-x-2">
            <svg class="sla-timer-clock-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
            <h3 class="sla-timer-title">SLA Timer</h3>
        </div>
        <button 
            @click="toggleDarkMode()"
            class="sla-timer-mode-toggle"
            :class="darkMode ? 'sla-timer-mode-toggle-dark' : 'sla-timer-mode-toggle-light'"
        >
            <svg x-show="darkMode" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" />
            </svg>
            <svg x-show="!darkMode" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
            </svg>
        </button>
    </div>

    <!-- Time display grid -->
    <div class="sla-timer-grid">
        <div class="sla-timer-cell">
            <div class="sla-timer-label sla-timer-label-open">Open</div>
            <div class="sla-timer-value sla-timer-value-open" x-text="formatTime(openSeconds)">00:00:00</div>
        </div>
        <div class="sla-timer-cell">
            <div class="sla-timer-label sla-timer-label-pending">Pending</div>
            <div class="sla-timer-value sla-timer-value-pending" x-text="formatTime(pendingSeconds)">00:00:00</div>
        </div>
        <div class="sla-timer-cell">
            <div class="sla-timer-label sla-timer-label-total">Total</div>
            <div class="sla-timer-value sla-timer-value-total" x-text="formatTime(totalSeconds)">00:00:00</div>
        </div>
    </div>
    
    <!-- Progress section -->
    <div class="sla-timer-progress-section">
        <!-- Progress header -->
        <div class="sla-timer-progress-header">
            <div class="flex items-center">
                <svg class="sla-timer-progress-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                <span class="sla-timer-progress-title">SLA Progress</span>
            </div>
            <div 
                class="sla-timer-progress-percent"
                :class="{ 
                    'sla-timer-progress-good': slaPercentage < 50,
                    'sla-timer-progress-warning': slaPercentage >= 50 && slaPercentage < 75,
                    'sla-timer-progress-critical': slaPercentage >= 75
                }"
                :style="slaPercentage > 90 ? 'animation: sla-pulse 1s infinite' : ''"
                x-text="Math.floor(slaPercentage) + '%'"
            >66%</div>
        </div>
        
        <!-- Progress bar -->
        <div class="sla-timer-progress-bar-container">
            <div class="sla-timer-progress-bar-bg"></div>
            <div 
                class="sla-timer-progress-bar"
                :class="{ 
                    'sla-timer-progress-bar-good': slaPercentage < 50,
                    'sla-timer-progress-bar-warning': slaPercentage >= 50 && slaPercentage < 75,
                    'sla-timer-progress-bar-critical': slaPercentage >= 75
                }"
                :style="'width: ' + slaPercentage + '%'"
            ></div>
        </div>

        <!-- Status message -->
        <div 
            class="sla-timer-message"
            :class="{ 
                'sla-timer-message-good': slaPercentage < 50,
                'sla-timer-message-warning': slaPercentage >= 50 && slaPercentage < 75,
                'sla-timer-message-critical': slaPercentage >= 75
            }"
            :style="slaPercentage > 90 ? 'animation: sla-pulse 1s infinite' : ''"
            x-text="slaMessage"
        >Waktu penyelesaian mulai menipis</div>
    </div>
    
    <!-- Status indicators -->
    <div class="sla-timer-status-indicators">
        <div class="sla-timer-status-indicator">
            <span class="sla-timer-status-indicator-dot sla-timer-status-good"></span>
            <span class="sla-timer-status-indicator-text">Baik (<50%)</span>
        </div>
        <div class="sla-timer-status-indicator">
            <span class="sla-timer-status-indicator-dot sla-timer-status-warning"></span>
            <span class="sla-timer-status-indicator-text">Peringatan (50-75%)</span>
        </div>
        <div class="sla-timer-status-indicator">
            <span class="sla-timer-status-indicator-dot sla-timer-status-critical"></span>
            <span class="sla-timer-status-indicator-text">Kritis (>75%)</span>
        </div>
    </div>
</div>

<style>
/* Base Styles */
.sla-timer-wrapper {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
}

/* Dark Theme Styles */
.sla-timer-dark {
    background-color: #0f172a;
    border: 1px solid #1e293b;
}

.sla-timer-dark .sla-timer-header {
    background-color: #0f172a;
    border-bottom: 1px solid #1e293b;
    padding: 12px 16px;
}

.sla-timer-dark .sla-timer-title {
    color: #f8fafc;
    font-weight: 600;
    font-size: 15px;
}

.sla-timer-dark .sla-timer-clock-icon {
    width: 18px;
    height: 18px;
    color: #60a5fa;
}

.sla-timer-dark .sla-timer-mode-toggle {
    color: #f8fafc;
    background-color: #1e293b;
    padding: 6px;
    border-radius: 9999px;
    transition: background-color 0.2s;
}

.sla-timer-dark .sla-timer-mode-toggle:hover {
    background-color: #334155;
}

.sla-timer-dark .sla-timer-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    background-color: #0f172a;
    border-bottom: 1px solid #1e293b;
}

.sla-timer-dark .sla-timer-cell {
    padding: 16px 12px;
    text-align: center;
    background-color: #0f172a;
    border-right: 1px solid #1e293b;
}

.sla-timer-dark .sla-timer-cell:last-child {
    border-right: none;
}

.sla-timer-dark .sla-timer-label {
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 6px;
    text-transform: uppercase;
}

.sla-timer-dark .sla-timer-label-open {
    color: #60a5fa;
}

.sla-timer-dark .sla-timer-label-pending {
    color: #fbbf24;
}

.sla-timer-dark .sla-timer-label-total {
    color: #c4b5fd;
}

.sla-timer-dark .sla-timer-value {
    font-family: 'JetBrains Mono', 'Courier New', monospace;
    font-size: 20px;
    font-weight: 700;
    color: #f8fafc;
}

.sla-timer-dark .sla-timer-value-open {
    text-shadow: 0 0 8px rgba(96, 165, 250, 0.4);
}

.sla-timer-dark .sla-timer-value-pending {
    text-shadow: 0 0 8px rgba(251, 191, 36, 0.4);
}

.sla-timer-dark .sla-timer-value-total {
    text-shadow: 0 0 8px rgba(196, 181, 253, 0.4);
}

.sla-timer-dark .sla-timer-progress-section {
    padding: 12px 16px;
    background-color: #0f172a;
    border-bottom: 1px solid #1e293b;
}

.sla-timer-dark .sla-timer-progress-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.sla-timer-dark .sla-timer-progress-icon {
    width: 16px;
    height: 16px;
    color: #94a3b8;
    margin-right: 8px;
}

.sla-timer-dark .sla-timer-progress-title {
    color: #e2e8f0;
    font-size: 14px;
    font-weight: 500;
}

.sla-timer-dark .sla-timer-progress-percent {
    font-size: 14px;
    font-weight: 700;
}

.sla-timer-dark .sla-timer-progress-good {
    color: #60a5fa;
}

.sla-timer-dark .sla-timer-progress-warning {
    color: #fbbf24;
}

.sla-timer-dark .sla-timer-progress-critical {
    color: #f87171;
}

.sla-timer-dark .sla-timer-progress-bar-container {
    position: relative;
    height: 8px;
    border-radius: 4px;
    overflow: hidden;
    margin-bottom: 10px;
}

.sla-timer-dark .sla-timer-progress-bar-bg {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #1e293b;
    background-image: repeating-linear-gradient(
        45deg,
        rgba(255, 255, 255, 0.05) 0px,
        rgba(255, 255, 255, 0.05) 4px,
        transparent 4px,
        transparent 8px
    );
}

.sla-timer-dark .sla-timer-progress-bar {
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    transition: width 0.3s ease-in-out;
}

.sla-timer-dark .sla-timer-progress-bar-good {
    background: linear-gradient(to right, #2563eb, #60a5fa);
    box-shadow: 0 0 6px rgba(37, 99, 235, 0.6);
}

.sla-timer-dark .sla-timer-progress-bar-warning {
    background: linear-gradient(to right, #d97706, #fbbf24);
    box-shadow: 0 0 6px rgba(217, 119, 6, 0.6);
}

.sla-timer-dark .sla-timer-progress-bar-critical {
    background: linear-gradient(to right, #dc2626, #f87171);
    box-shadow: 0 0 6px rgba(220, 38, 38, 0.6);
}

.sla-timer-dark .sla-timer-message {
    font-size: 13px;
    text-align: center;
    padding: 4px 0;
}

.sla-timer-dark .sla-timer-message-good {
    color: #93c5fd;
}

.sla-timer-dark .sla-timer-message-warning {
    color: #fcd34d;
}

.sla-timer-dark .sla-timer-message-critical {
    color: #fca5a5;
}

.sla-timer-dark .sla-timer-status-indicators {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    padding: 8px 12px;
    background-color: #0f172a;
    text-align: center;
}

.sla-timer-dark .sla-timer-status-indicator {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 6px;
}

.sla-timer-dark .sla-timer-status-indicator-dot {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
}

.sla-timer-dark .sla-timer-status-good {
    background-color: #60a5fa;
}

.sla-timer-dark .sla-timer-status-warning {
    background-color: #fbbf24;
}

.sla-timer-dark .sla-timer-status-critical {
    background-color: #f87171;
}

.sla-timer-dark .sla-timer-status-indicator-text {
    font-size: 11px;
    color: #94a3b8;
}

/* Light Theme Styles */
.sla-timer-light {
    background-color: #ffffff;
    border: 1px solid #e2e8f0;
}

.sla-timer-light .sla-timer-header {
    background-color: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    padding: 12px 16px;
}

.sla-timer-light .sla-timer-title {
    color: #1e293b;
    font-weight: 600;
    font-size: 15px;
}

.sla-timer-light .sla-timer-clock-icon {
    width: 18px;
    height: 18px;
    color: #3b82f6;
}

.sla-timer-light .sla-timer-mode-toggle {
    color: #334155;
    background-color: #f1f5f9;
    padding: 6px;
    border-radius: 9999px;
    transition: background-color 0.2s;
}

.sla-timer-light .sla-timer-mode-toggle:hover {
    background-color: #e2e8f0;
}

.sla-timer-light .sla-timer-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    background-color: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}

.sla-timer-light .sla-timer-cell {
    padding: 16px 12px;
    text-align: center;
    background-color: #ffffff;
    border-right: 1px solid #e2e8f0;
}

.sla-timer-light .sla-timer-cell:last-child {
    border-right: none;
}

.sla-timer-light .sla-timer-label {
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 6px;
    text-transform: uppercase;
}

.sla-timer-light .sla-timer-label-open {
    color: #2563eb;
}

.sla-timer-light .sla-timer-label-pending {
    color: #d97706;
}

.sla-timer-light .sla-timer-label-total {
    color: #7c3aed;
}

.sla-timer-light .sla-timer-value {
    font-family: 'JetBrains Mono', 'Courier New', monospace;
    font-size: 20px;
    font-weight: 700;
    color: #1e293b;
}

.sla-timer-light .sla-timer-progress-section {
    padding: 12px 16px;
    background-color: #ffffff;
    border-bottom: 1px solid #e2e8f0;
}

.sla-timer-light .sla-timer-progress-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.sla-timer-light .sla-timer-progress-icon {
    width: 16px;
    height: 16px;
    color: #64748b;
    margin-right: 8px;
}

.sla-timer-light .sla-timer-progress-title {
    color: #334155;
    font-size: 14px;
    font-weight: 500;
}

.sla-timer-light .sla-timer-progress-percent {
    font-size: 14px;
    font-weight: 700;
}

.sla-timer-light .sla-timer-progress-good {
    color: #2563eb;
}

.sla-timer-light .sla-timer-progress-warning {
    color: #d97706;
}

.sla-timer-light .sla-timer-progress-critical {
    color: #dc2626;
}

.sla-timer-light .sla-timer-progress-bar-container {
    position: relative;
    height: 8px;
    border-radius: 4px;
    overflow: hidden;
    margin-bottom: 10px;
}

.sla-timer-light .sla-timer-progress-bar-bg {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #f1f5f9;
    background-image: repeating-linear-gradient(
        45deg,
        rgba(0, 0, 0, 0.03) 0px,
        rgba(0, 0, 0, 0.03) 4px,
        transparent 4px,
        transparent 8px
    );
}

.sla-timer-light .sla-timer-progress-bar {
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    transition: width 0.3s ease-in-out;
}

.sla-timer-light .sla-timer-progress-bar-good {
    background: linear-gradient(to right, #2563eb, #60a5fa);
    box-shadow: 0 0 6px rgba(37, 99, 235, 0.4);
}

.sla-timer-light .sla-timer-progress-bar-warning {
    background: linear-gradient(to right, #d97706, #fbbf24);
    box-shadow: 0 0 6px rgba(217, 119, 6, 0.4);
}

.sla-timer-light .sla-timer-progress-bar-critical {
    background: linear-gradient(to right, #dc2626, #f87171);
    box-shadow: 0 0 6px rgba(220, 38, 38, 0.4);
}

.sla-timer-light .sla-timer-message {
    font-size: 13px;
    text-align: center;
    padding: 4px 0;
}

.sla-timer-light .sla-timer-message-good {
    color: #2563eb;
}

.sla-timer-light .sla-timer-message-warning {
    color: #d97706;
}

.sla-timer-light .sla-timer-message-critical {
    color: #dc2626;
}

.sla-timer-light .sla-timer-status-indicators {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    padding: 8px 12px;
    background-color: #f8fafc;
    text-align: center;
}

.sla-timer-light .sla-timer-status-indicator {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 6px;
}

.sla-timer-light .sla-timer-status-indicator-dot {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
}

.sla-timer-light .sla-timer-status-good {
    background-color: #2563eb;
}

.sla-timer-light .sla-timer-status-warning {
    background-color: #d97706;
}

.sla-timer-light .sla-timer-status-critical {
    background-color: #dc2626;
}

.sla-timer-light .sla-timer-status-indicator-text {
    font-size: 11px;
    color: #64748b;
}

/* Animation */
@keyframes sla-pulse {
  0% { opacity: 1; }
  50% { opacity: 0.6; }
  100% { opacity: 1; }
}
</style>