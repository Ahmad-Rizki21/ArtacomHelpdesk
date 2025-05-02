<!-- resources/views/components/exact-match-timer.blade.php -->
<div style="background: #1e293b; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); text-align: center;">
        <div style="padding: 16px; background: #1a2234;">
            <div style="color: #9ca3af; font-size: 14px; margin-bottom: 8px;">Open</div>
            <div style="color: white; font-family: monospace; font-size: 18px; font-weight: 500;" id="open-time">00:00:00</div>
        </div>
        <div style="padding: 16px; background: #1a2234;">
            <div style="color: #9ca3af; font-size: 14px; margin-bottom: 8px;">Pending</div>
            <div style="color: white; font-family: monospace; font-size: 18px; font-weight: 500;" id="pending-time">00:00:00</div>
        </div>
        <div style="padding: 16px; background: #1a2234;">
            <div style="color: #9ca3af; font-size: 14px; margin-bottom: 8px;">Total</div>
            <div style="color: white; font-family: monospace; font-size: 18px; font-weight: 500;" id="total-time">00:00:00</div>
        </div>
    </div>
    
    <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 16px;">
        <div style="color: white; font-size: 14px;">SLA Progress</div>
        <div style="color: white; font-size: 14px;" id="sla-percent">0%</div>
    </div>
    
    <div style="background: #111827; height: 6px; overflow: hidden; position: relative;">
        <div style="background: #3b82f6; height: 100%; width: 0%;" id="sla-bar"></div>
    </div>
</div>

<script>
// Fungsi untuk format waktu
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

// Ambil status tiket dan report date
const ticketStatus = "{{ $ticket->status }}";
const reportDate = "{{ $ticket->report_date }}";

// Inisialisasi timer
let openSeconds = 0;
let pendingSeconds = 0;

// Hitung waktu awal
if (reportDate) {
    try {
        const reportTime = new Date(reportDate).getTime();
        const now = new Date().getTime();
        const diffSeconds = Math.floor((now - reportTime) / 1000);
        
        if (ticketStatus === "OPEN") {
            openSeconds = diffSeconds;
        } else if (ticketStatus === "PENDING") {
            pendingSeconds = diffSeconds;
        }
    } catch (e) {
        console.error("Error:", e);
    }
}

// Update elemen timer
function updateTimer() {
    // Hitung waktu
    if (ticketStatus === "OPEN") {
        openSeconds++;
    } else if (ticketStatus === "PENDING") {
        pendingSeconds++;
    }
    
    const totalSeconds = openSeconds + pendingSeconds;
    
    // Update tampilan
    document.getElementById("open-time").textContent = formatTime(openSeconds);
    document.getElementById("pending-time").textContent = formatTime(pendingSeconds);
    document.getElementById("total-time").textContent = formatTime(totalSeconds);
    
    // Update progress bar SLA (target 24 jam)
    const slaTarget = 24 * 60 * 60; // dalam detik
    const progress = Math.min(100, (totalSeconds / slaTarget) * 100);
    
    document.getElementById("sla-percent").textContent = Math.floor(progress) + "%";
    document.getElementById("sla-bar").style.width = progress + "%";
    
    // Update warna progress bar
    let barColor = "#3b82f6"; // blue-500
    if (progress >= 75) {
        barColor = "#ef4444"; // red-500
    } else if (progress >= 50) {
        barColor = "#f59e0b"; // amber-500
    }
    document.getElementById("sla-bar").style.backgroundColor = barColor;
}

// Update timer awal
updateTimer();

// Set interval untuk update timer
const timerInterval = setInterval(updateTimer, 1000);

// Bersihkan interval saat pengguna meninggalkan halaman
window.addEventListener("beforeunload", function() {
    clearInterval(timerInterval);
});
</script>