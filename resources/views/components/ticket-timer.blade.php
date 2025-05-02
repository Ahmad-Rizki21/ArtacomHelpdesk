<!-- resources/views/components/ticket-timer.blade.php -->
@props(['ticket'])

<div class="ticket-timer bg-gray-800 p-3 rounded-lg border border-gray-600">
    <div class="mb-2 text-white font-medium">Timer Status Tiket</div>
    
    <div class="grid grid-cols-3 gap-2 mb-2">
        <div class="flex flex-col items-center">
            <span class="text-xs text-gray-400">Open Clock</span>
            <span class="timer-display text-white font-mono" id="open-timer">00:00:00</span>
        </div>
        <div class="flex flex-col items-center">
            <span class="text-xs text-gray-400">Pending Clock</span>
            <span class="timer-display text-white font-mono" id="pending-timer">00:00:00</span>
        </div>
        <div class="flex flex-col items-center">
            <span class="text-xs text-gray-400">Start Clock</span>
            <span class="timer-display text-white font-mono" id="start-timer">00:00:00</span>
        </div>
    </div>
    
    <div class="relative pt-1">
        <div class="flex mb-2 items-center justify-between">
            <div>
                <span class="text-xs font-semibold inline-block py-1 px-2 rounded-full text-white bg-blue-600">
                    SLA Progress
                </span>
            </div>
            <div class="text-right">
                <span class="text-xs font-semibold inline-block text-white" id="sla-percentage">
                    0%
                </span>
            </div>
        </div>
        <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-gray-700">
            <div id="sla-progress-bar" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-500" style="width: 0%"></div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mendapatkan data tiket dari data attribute
    const ticketStatus = '{{ $ticket->status }}';
    const reportDate = '{{ $ticket->report_date }}';
    const pendingClock = '{{ $ticket->pending_clock ?? 0 }}';
    const closedDate = '{{ $ticket->closed_date }}';
    
    // Konversi ke timestamps
    const reportTimestamp = new Date('{{ $ticket->report_date }}').getTime();
    const pendingStartTimestamp = '{{ $ticket->pending_clock }}' ? new Date('{{ $ticket->pending_clock }}').getTime() : null;
    const nowTimestamp = new Date().getTime();
    
    // Timer elements
    const openTimerEl = document.getElementById('open-timer');
    const pendingTimerEl = document.getElementById('pending-timer');
    const startTimerEl = document.getElementById('start-timer');
    const slaPercentageEl = document.getElementById('sla-percentage');
    const slaProgressBarEl = document.getElementById('sla-progress-bar');
    
    // Data untuk hitung SLA
    let openTime = 0;
    let pendingTime = 0;
    let startTime = 0;
    
    // Timer total waktu sejak dilaporkan (untuk Open Clock)
    let totalElapsedSeconds = Math.floor((nowTimestamp - reportTimestamp) / 1000);
    
    // Jika status PENDING, hitung waktu pending
    if (ticketStatus === 'PENDING' && pendingStartTimestamp) {
        pendingTime = Math.floor((nowTimestamp - pendingStartTimestamp) / 1000);
    }
    
    // Fungsi untuk update timer
    function updateTimers() {
        // Jika tiket sudah closed, hentikan timer
        if (ticketStatus === 'CLOSED') {
            return;
        }
        
        // Update timer tergantung status
        if (ticketStatus === 'OPEN') {
            totalElapsedSeconds++;
            openTime = totalElapsedSeconds;
        } else if (ticketStatus === 'PENDING') {
            totalElapsedSeconds++;
            pendingTime++;
        }
        
        // Format waktu untuk display
        openTimerEl.textContent = formatTime(openTime);
        pendingTimerEl.textContent = formatTime(pendingTime);
        startTimerEl.textContent = formatTime(startTime);
        
        // Update SLA progress (asumsi target SLA 24 jam = 86400 detik)
        const slaTarget = 86400; // 24 jam dalam detik
        const slaProgress = Math.min(100, (totalElapsedSeconds / slaTarget) * 100);
        slaPercentageEl.textContent = `${Math.floor(slaProgress)}%`;
        slaProgressBarEl.style.width = `${slaProgress}%`;
        
        // Ubah warna progress bar berdasarkan persentase
        if (slaProgress < 50) {
            slaProgressBarEl.classList.remove('bg-yellow-500', 'bg-red-500');
            slaProgressBarEl.classList.add('bg-blue-500');
        } else if (slaProgress < 75) {
            slaProgressBarEl.classList.remove('bg-blue-500', 'bg-red-500');
            slaProgressBarEl.classList.add('bg-yellow-500');
        } else {
            slaProgressBarEl.classList.remove('bg-blue-500', 'bg-yellow-500');
            slaProgressBarEl.classList.add('bg-red-500');
        }
    }
    
    // Format time dari detik ke HH:MM:SS
    function formatTime(totalSeconds) {
        const hours = Math.floor(totalSeconds / 3600);
        const minutes = Math.floor((totalSeconds % 3600) / 60);
        const seconds = totalSeconds % 60;
        
        return [
            hours.toString().padStart(2, '0'),
            minutes.toString().padStart(2, '0'),
            seconds.toString().padStart(2, '0')
        ].join(':');
    }
    
    // Update timer setiap detik
    const timerInterval = setInterval(updateTimers, 1000);
    
    // Initial update
    updateTimers();
    
    // Clean up interval on page leave
    window.addEventListener('beforeunload', function() {
        clearInterval(timerInterval);
    });
});
</script>