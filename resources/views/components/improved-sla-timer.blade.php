<!-- resources/views/components/improved-sla-timer.blade.php -->
<div style="background: linear-gradient(to right, #1e293b, #0f172a); border-radius: 12px; overflow: hidden; box-shadow: 0 4px 8px rgba(0,0,0,0.3); border: 1px solid #334155;">
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); text-align: center; gap: 1px; background: #0f172a;">
        <div style="padding: 16px; background: #1e293b;">
            <div style="color: #93c5fd; font-size: 14px; margin-bottom: 8px; font-weight: 600; text-transform: uppercase;">Open</div>
            <div style="color: white; font-family: 'JetBrains Mono', monospace; font-size: 22px; font-weight: 700; text-shadow: 0 0 10px rgba(147, 197, 253, 0.5);" id="open-time">00:00:00</div>
        </div>
        <div style="padding: 16px; background: #1e293b;">
            <div style="color: #fcd34d; font-size: 14px; margin-bottom: 8px; font-weight: 600; text-transform: uppercase;">Pending</div>
            <div style="color: white; font-family: 'JetBrains Mono', monospace; font-size: 22px; font-weight: 700; text-shadow: 0 0 10px rgba(252, 211, 77, 0.5);" id="pending-time">00:00:00</div>
        </div>
        <div style="padding: 16px; background: #1e293b;">
            <div style="color: #a5b4fc; font-size: 14px; margin-bottom: 8px; font-weight: 600; text-transform: uppercase;">Total</div>
            <div style="color: white; font-family: 'JetBrains Mono', monospace; font-size: 22px; font-weight: 700; text-shadow: 0 0 10px rgba(165, 180, 252, 0.5);" id="total-time">00:00:00</div>
        </div>
    </div>
    
    <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 16px; background: #1e293b; border-top: 1px solid #334155; border-bottom: 1px solid #334155;">
        <div style="display: flex; align-items: center; gap: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #ffffff;"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            <div style="color: white; font-size: 14px; font-weight: 600;">SLA Progress</div>
        </div>
        <div style="color: white; font-size: 16px; font-weight: 700;" id="sla-percent">0%</div>
    </div>
    
    <div style="background: #0f172a; height: 12px; overflow: hidden; position: relative;">
        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-image: linear-gradient(to right, rgba(59, 130, 246, 0.2) 0%, rgba(59, 130, 246, 0.2) 100%); background-size: 20px 12px;"></div>
        <div style="background: linear-gradient(to right, #3b82f6, #2563eb); height: 100%; width: 0%; transition: width 0.5s ease-in-out; box-shadow: 0 0 8px rgba(59, 130, 246, 0.7);" id="sla-bar"></div>
    </div>

    <div style="padding: 10px 16px; font-size: 12px; color: #94a3b8; text-align: center; background: #1e293b; border-top: 1px solid #334155;">
        <span id="sla-message">Menunggu Kalkulasi SLA...</span>
    </div>
</div>

<script>
// Fungsi untuk format waktu
function formatTime(seconds) {
    // Pastikan nilai selalu positif
    const absSeconds = Math.abs(seconds);
    const hours = Math.floor(absSeconds / 3600);
    const minutes = Math.floor((absSeconds % 3600) / 60);
    const secs = absSeconds % 60;
    
    return [
        hours.toString().padStart(2, "0"),
        minutes.toString().padStart(2, "0"),
        secs.toString().padStart(2, "0")
    ].join(":");
}

// Ambil status tiket dan report date
const ticketStatus = "{{ $ticket->status }}";
const reportDate = "{{ $ticket->report_date }}";
const slaName = "{{ $ticket->sla->name ?? 'MEDIUM' }}";
const openTimeDB = {{ $ticket->open_time_seconds ?? 0 }}; // Ambil dari database
const pendingTimeDB = {{ $ticket->pending_time_seconds ?? 0 }}; // Ambil dari database
const closedDate = "{{ $ticket->closed_date }}";

// Tentukan target SLA berdasarkan level SLA
let slaTargetHours = 24; // Default MEDIUM
if (slaName === 'HIGH') {
    slaTargetHours = 4; // 4 jam
} else if (slaName === 'LOW') {
    slaTargetHours = 48; // 48 jam
}

// Inisialisasi timer
let openSeconds = Math.max(0, openTimeDB); // Pastikan tidak negatif
let pendingSeconds = Math.max(0, pendingTimeDB); // Pastikan tidak negatif
let totalSeconds = openSeconds + pendingSeconds;

// Fungsi untuk mendapatkan pesan status SLA
function getSlaMessage(progress) {
    // Jika tiket sudah CLOSED
    if (ticketStatus === "CLOSED") {
        return "Tiket sudah ditutup pada {{ \Carbon\Carbon::parse($ticket->closed_date)->format('d M Y H:i') }}";
    }
    
    // Pesan berdasarkan persentase SLA
    if (progress < 25) {
        return "Waktu penyelesaian masih banyak tersisa";
    } else if (progress < 50) {
        return "Waktu penyelesaian masih cukup";
    } else if (progress < 75) {
        return "Waktu penyelesaian mulai menipis";
    } else if (progress < 90) {
        return "Segera selesaikan tiket ini!";
    } else {
        return "PERHATIAN: SLA hampir terlampaui!";
    }
}

// Jika tiket sudah closed dan nilai timer dari DB valid
if (ticketStatus === "CLOSED") {
    // Update tampilan untuk tiket closed
    document.getElementById("open-time").textContent = formatTime(openTimeDB);
    document.getElementById("pending-time").textContent = formatTime(pendingTimeDB);
    document.getElementById("total-time").textContent = formatTime(openTimeDB + pendingTimeDB);
    
    // Update SLA progress
    const slaTarget = slaTargetHours * 60 * 60; // dalam detik
    const totalSeconds = Math.abs(openTimeDB) + Math.abs(pendingTimeDB);
    const progress = Math.min(100, (totalSeconds / slaTarget) * 100);
    const progressPercentage = Math.floor(progress);
    
    document.getElementById("sla-percent").textContent = progressPercentage + "%";
    document.getElementById("sla-bar").style.width = progress + "%";
    document.getElementById("sla-message").textContent = getSlaMessage(progress);
    
    // Update warna progress bar untuk tiket closed
    let barColor = "linear-gradient(to right, #3b82f6, #2563eb)"; // blue default
    let shadowColor = "rgba(59, 130, 246, 0.7)"; // blue shadow

    if (progress >= 75) {
        barColor = "linear-gradient(to right, #ef4444, #dc2626)"; // red
        shadowColor = "rgba(239, 68, 68, 0.7)"; // red shadow
        document.getElementById("sla-message").style.color = "#fca5a5";
    } else if (progress >= 50) {
        barColor = "linear-gradient(to right, #f59e0b, #d97706)"; // amber
        shadowColor = "rgba(245, 158, 11, 0.7)"; // amber shadow
        document.getElementById("sla-message").style.color = "#fcd34d";
    }
    
    document.getElementById("sla-bar").style.background = barColor;
    document.getElementById("sla-bar").style.boxShadow = `0 0 8px ${shadowColor}`;
} 
// Jika tiket masih open/pending
else if (reportDate) {
    try {
        // Hitung waktu awal jika belum ada nilai di database
        if (openSeconds === 0 && pendingSeconds === 0) {
            const reportTime = new Date(reportDate).getTime();
            const now = new Date().getTime();
            const diffSeconds = Math.floor((now - reportTime) / 1000);
            
            if (ticketStatus === "OPEN") {
                openSeconds = diffSeconds;
            } else if (ticketStatus === "PENDING") {
                pendingSeconds = diffSeconds;
            }
            
            totalSeconds = openSeconds + pendingSeconds;
        }
        
        // Update timer untuk tiket yang masih aktif
        function updateTimer() {
            // Increment waktu berdasarkan status
            if (ticketStatus === "OPEN") {
                openSeconds++;
            } else if (ticketStatus === "PENDING") {
                pendingSeconds++;
            }
            
            totalSeconds = openSeconds + pendingSeconds;
            
            // Update tampilan
            document.getElementById("open-time").textContent = formatTime(openSeconds);
            document.getElementById("pending-time").textContent = formatTime(pendingSeconds);
            document.getElementById("total-time").textContent = formatTime(totalSeconds);
            
            // Update progress bar SLA
            const slaTarget = slaTargetHours * 60 * 60; // dalam detik
            const progress = Math.min(100, (totalSeconds / slaTarget) * 100);
            const progressPercentage = Math.floor(progress);
            
            document.getElementById("sla-percent").textContent = progressPercentage + "%";
            document.getElementById("sla-bar").style.width = progress + "%";
            document.getElementById("sla-message").textContent = getSlaMessage(progress);
            
            // Update warna progress bar
            let barColor = "linear-gradient(to right, #3b82f6, #2563eb)"; // blue default
            let shadowColor = "rgba(59, 130, 246, 0.7)"; // blue shadow

            if (progress >= 75) {
                barColor = "linear-gradient(to right, #ef4444, #dc2626)"; // red
                shadowColor = "rgba(239, 68, 68, 0.7)"; // red shadow
                document.getElementById("sla-message").style.color = "#fca5a5";
            } else if (progress >= 50) {
                barColor = "linear-gradient(to right, #f59e0b, #d97706)"; // amber
                shadowColor = "rgba(245, 158, 11, 0.7)"; // amber shadow
                document.getElementById("sla-message").style.color = "#fcd34d";
            }
            
            document.getElementById("sla-bar").style.background = barColor;
            document.getElementById("sla-bar").style.boxShadow = `0 0 8px ${shadowColor}`;
            
            // Animasi pulsing jika hampir mencapai batas
            if (progress > 90) {
                document.getElementById("sla-percent").style.animation = "pulse 1s infinite";
                document.getElementById("sla-message").style.animation = "pulse 1s infinite";
            } else {
                document.getElementById("sla-percent").style.animation = "none";
                document.getElementById("sla-message").style.animation = "none";
            }
        }
        
        // Tambahkan style untuk animasi pulse
        const style = document.createElement('style');
        style.textContent = `
        @keyframes pulse {
          0% { opacity: 1; }
          50% { opacity: 0.5; }
          100% { opacity: 1; }
        }
        `;
        document.head.appendChild(style);
        
        // Update timer awal
        updateTimer();
        
        // Set interval untuk update timer
        const timerInterval = setInterval(updateTimer, 1000);
        
        // Bersihkan interval saat pengguna meninggalkan halaman
        window.addEventListener("beforeunload", function() {
            clearInterval(timerInterval);
        });
    } catch (e) {
        console.error("Error:", e);
        document.getElementById("sla-message").textContent = "Error saat kalkulasi timer: " + e.message;
    }
} else {
    // Jika tidak ada report_date
    document.getElementById("sla-message").textContent = "Tidak ada data waktu untuk tiket ini";
}
</script>