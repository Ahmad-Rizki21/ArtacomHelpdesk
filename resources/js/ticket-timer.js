// resources/js/ticket-timer.js
class TicketTimer {
    constructor(options) {
        this.options = {
            ticketId: null,
            status: 'OPEN',
            reportDate: null,
            pendingDate: null,
            allowedDowntime: 3600, // default 1 jam dalam detik
            onUpdate: null,
            ...options
        };
        
        this.timers = {
            open: 0,
            pending: 0,
            start: 0,
            total: 0
        };
        
        this.initialize();
    }
    
    initialize() {
        // Setup timer awal
        if (this.options.reportDate) {
            const reportTime = new Date(this.options.reportDate).getTime();
            const now = new Date().getTime();
            this.timers.total = Math.floor((now - reportTime) / 1000);
            
            // Assign ke timer berdasarkan status
            if (this.options.status === 'OPEN') {
                this.timers.open = this.timers.total;
            } else if (this.options.status === 'PENDING' && this.options.pendingDate) {
                const pendingTime = new Date(this.options.pendingDate).getTime();
                this.timers.pending = Math.floor((now - pendingTime) / 1000);
                this.timers.open = this.timers.total - this.timers.pending;
            }
        }
        
        // Start interval
        this.startTimer();
    }
    
    startTimer() {
        this.interval = setInterval(() => {
            this.updateTimers();
        }, 1000);
    }
    
    updateTimers() {
        // Increment timers berdasarkan status
        this.timers.total++;
        
        if (this.options.status === 'OPEN') {
            this.timers.open++;
        } else if (this.options.status === 'PENDING') {
            this.timers.pending++;
        } else if (this.options.status === 'START') {
            this.timers.start++;
        }
        
        // Hitung SLA progress
        const slaPercentage = Math.min(100, (this.timers.total / this.options.allowedDowntime) * 100);
        
        // Trigger callback jika ada
        if (typeof this.options.onUpdate === 'function') {
            this.options.onUpdate({
                timers: this.timers,
                slaPercentage: slaPercentage,
                formattedTimers: {
                    open: this.formatTime(this.timers.open),
                    pending: this.formatTime(this.timers.pending),
                    start: this.formatTime(this.timers.start),
                    total: this.formatTime(this.timers.total)
                }
            });
        }
    }
    
    formatTime(seconds) {
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        const secs = seconds % 60;
        
        return [
            hours.toString().padStart(2, '0'),
            minutes.toString().padStart(2, '0'),
            secs.toString().padStart(2, '0')
        ].join(':');
    }
    
    stop() {
        if (this.interval) {
            clearInterval(this.interval);
        }
    }
}

// Inisialisasi penggunaan
document.addEventListener('DOMContentLoaded', function() {
    const ticketElements = document.querySelectorAll('[data-ticket-timer]');
    
    ticketElements.forEach(element => {
        const ticketId = element.dataset.ticketId;
        const status = element.dataset.ticketStatus;
        const reportDate = element.dataset.reportDate;
        const pendingDate = element.dataset.pendingDate;
        const allowedDowntime = parseInt(element.dataset.allowedDowntime || 86400); // default 24 jam
        
        const timer = new TicketTimer({
            ticketId,
            status,
            reportDate,
            pendingDate,
            allowedDowntime,
            onUpdate: (data) => {
                // Update element displays
                const openTimerEl = element.querySelector('.open-timer');
                const pendingTimerEl = element.querySelector('.pending-timer');
                const startTimerEl = element.querySelector('.start-timer');
                const slaProgressEl = element.querySelector('.sla-progress');
                const slaPercentageEl = element.querySelector('.sla-percentage');
                
                if (openTimerEl) openTimerEl.textContent = data.formattedTimers.open;
                if (pendingTimerEl) pendingTimerEl.textContent = data.formattedTimers.pending;
                if (startTimerEl) startTimerEl.textContent = data.formattedTimers.start;
                if (slaProgressEl) slaProgressEl.style.width = `${data.slaPercentage}%`;
                if (slaPercentageEl) slaPercentageEl.textContent = `${Math.floor(data.slaPercentage)}%`;
                
                // Update warna progress bar
                if (slaProgressEl) {
                    if (data.slaPercentage < 50) {
                        slaProgressEl.className = 'sla-progress bg-blue-500';
                    } else if (data.slaPercentage < 75) {
                        slaProgressEl.className = 'sla-progress bg-yellow-500';
                    } else {
                        slaProgressEl.className = 'sla-progress bg-red-500';
                    }
                }
            }
        });
        
        // Store reference to timer instance to allow cleanup
        element._ticketTimer = timer;
    });
    
    // Cleanup timers on page unload
    window.addEventListener('beforeunload', () => {
        ticketElements.forEach(element => {
            if (element._ticketTimer) {
                element._ticketTimer.stop();
            }
        });
    });
});

// Export untuk reuse
window.TicketTimer = TicketTimer;