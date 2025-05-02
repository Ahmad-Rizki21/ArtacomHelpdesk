<div class="p-3 bg-gray-800 rounded-lg border border-gray-700">
    <div class="grid grid-cols-3 gap-3 mb-3">
        <div class="p-2 bg-gray-700 rounded text-center">
            <span class="text-xs text-gray-400 block">Open</span>
            <span class="text-lg font-mono text-white" id="open-timer-{{ $ticket->id }}">00:00:00</span>
        </div>
        <div class="p-2 bg-gray-700 rounded text-center">
            <span class="text-xs text-gray-400 block">Pending</span>
            <span class="text-lg font-mono text-white" id="pending-timer-{{ $ticket->id }}">00:00:00</span>
        </div>
        <div class="p-2 bg-gray-700 rounded text-center">
            <span class="text-xs text-gray-400 block">Total</span>
            <span class="text-lg font-mono text-white" id="total-timer-{{ $ticket->id }}">00:00:00</span>
        </div>
    </div>
    
    <div class="mb-1 flex justify-between items-center">
        <span class="text-sm text-white">SLA Progress</span>
        <span class="text-sm font-medium text-white" id="sla-percentage-{{ $ticket->id }}">0%</span>
    </div>
    
    <div class="h-2 bg-gray-700 rounded-full overflow-hidden">
        <div class="h-full bg-blue-600" id="sla-progress-{{ $ticket->id }}" style="width: 0%"></div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    console.log("SLA Timer script starting");
    
    // Data tiket
    const ticketId = "{{ $ticket->id }}";
    const ticketStatus = "{{ $ticket->status }}";
    const reportDate = "{{ $ticket->report_date }}";
    
    console.log("Ticket ID:", ticketId);
    console.log("Ticket Status:", ticketStatus);
    console.log("Report Date:", reportDate);

    // Elemen timer
    const openTimerEl = document.getElementById("open-timer-" + ticketId);
    const pendingTimerEl = document.getElementById("pending-timer-" + ticketId);
    const totalTimerEl = document.getElementById("total-timer-" + ticketId);
    const slaPercentageEl = document.getElementById("sla-percentage-" + ticketId);
    const slaProgressEl = document.getElementById("sla-progress-" + ticketId);
    
    if (!openTimerEl || !pendingTimerEl || !totalTimerEl) {
        console.error("Timer elements not found");
        return;
    }
    
    console.log("Elements found:", openTimerEl, pendingTimerEl, totalTimerEl);
    
    // Waktu awal
    let openSeconds = 0;
    let pendingSeconds = 0;
    
    // Hitung waktu sejak tiket dibuat
    if (reportDate) {
        try {
            const reportTimestamp = new Date(reportDate).getTime();
            console.log("Report timestamp:", reportTimestamp);
            
            if (isNaN(reportTimestamp)) {
                console.error("Invalid date format:", reportDate);
                return;
            }
            
            const now = new Date().getTime();
            const diffSeconds = Math.floor((now - reportTimestamp) / 1000);
            console.log("Initial diff seconds:", diffSeconds);
            
            // Tentukan timer berdasarkan status
            if (ticketStatus === "OPEN") {
                openSeconds = diffSeconds;
            } else if (ticketStatus === "PENDING") {
                pendingSeconds = diffSeconds;
            }
            
            console.log("Initial open seconds:", openSeconds);
            console.log("Initial pending seconds:", pendingSeconds);
        } catch (e) {
            console.error("Error calculating time:", e);
        }
    }
    
    // Format waktu fungsi
    function formatTime(seconds) {
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        const secs = seconds % 60;
        
        return [
            hours.toString().padStart(2, "0"),
            minutes.toString().padStart(2, "0"),
            secs.toString().padStart(2, "0")
        ].join(":");
    }
    
    // Update timer fungsi
    function updateTimers() {
        // Tambah waktu berdasarkan status
        if (ticketStatus === "OPEN") {
            openSeconds++;
        } else if (ticketStatus === "PENDING") {
            pendingSeconds++;
        }
        
        // Hitung total
        const totalSeconds = openSeconds + pendingSeconds;
        
        // Update tampilan
        openTimerEl.textContent = formatTime(openSeconds);
        pendingTimerEl.textContent = formatTime(pendingSeconds);
        totalTimerEl.textContent = formatTime(totalSeconds);
        
        // Update SLA progress (anggap target SLA 24 jam)
        const slaTargetSeconds = 24 * 60 * 60; // 24 jam dalam detik
        const slaProgress = Math.min(100, (totalSeconds / slaTargetSeconds) * 100);
        
        slaPercentageEl.textContent = Math.floor(slaProgress) + "%";
        slaProgressEl.style.width = slaProgress + "%";
        
        // Ubah warna berdasarkan persentase
        if (slaProgress < 50) {
            slaProgressEl.className = "h-full bg-blue-600";
        } else if (slaProgress < 75) {
            slaProgressEl.className = "h-full bg-yellow-500";
        } else {
            slaProgressEl.className = "h-full bg-red-500";
        }
    }
    
    // Initial update
    updateTimers();
    console.log("Initial update completed");
    
    // Update timer setiap detik
    const timerInterval = setInterval(updateTimers, 1000);
    console.log("Timer interval started");
    
    // Bersihkan interval saat user meninggalkan halaman
    window.addEventListener("beforeunload", function() {
        clearInterval(timerInterval);
    });
});
</script>