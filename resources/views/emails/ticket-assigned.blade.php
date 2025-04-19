{{-- resources/views/emails/ticket-assigned.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f9fafb;
            color: #1f2937;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        
        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .email-header {
            padding: 20px 0;
            text-align: center;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 20px;
        }
        
        .logo {
            max-height: 60px;
            margin-bottom: 15px;
        }
        
        .ticket-badge {
            display: inline-block;
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            color: white;
            font-size: 18px;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 10px;
            margin: 10px 0;
            box-shadow: 0 4px 6px rgba(59, 130, 246, 0.3);
        }
        
        .highlight {
            color: #3b82f6;
            font-weight: 600;
        }
        
        .panel {
            background-color: #f8fafc;
            border-radius: 10px;
            padding: 20px;
            margin: 25px 0;
            border-left: 4px solid #3b82f6;
        }
        
        .panel-title {
            font-size: 18px;
            font-weight: 600;
            color: #1e40af;
            margin-top: 0;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        
        .panel-title-icon {
            margin-right: 10px;
            background-color: #1e40af;
            color: white;
            width: 28px;
            height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 16px;
            font-weight: bold;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        
        .ticket-info-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin: 15px 0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .ticket-info-table tr:nth-child(even) {
            background-color: #f3f4f6;
        }
        
        .ticket-info-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .ticket-info-table tr:last-child td {
            border-bottom: none;
        }
        
        .label {
            font-weight: 600;
            color: #4b5563;
            width: 40%;
        }
        
        .tag {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 700;
            line-height: 1.4;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
            text-transform: uppercase;
        }
        
        .tag-blue {
            background-color: #2563eb;
            color: white;
        }
        
        .tag-green {
            background-color: #059669;
            color: white;
        }
        
        .tag-yellow {
            background-color: #d97706;
            color: white;
        }
        
        .tag-red {
            background-color: #dc2626;
            color: white;
        }
        
        .tag-purple {
            background-color: #7c3aed;
            color: white;
        }
        
        .tag-status-open {
            background-color: #dc2626;
            color: white;
            font-weight: 800;
        }
        
        .tag-status-in-progress {
            background-color: #2563eb;
            color: white;
            font-weight: 800;
        }
        
        .tag-status-resolved {
            background-color: #059669;
            color: white;
            font-weight: 800;
        }
        
        .tag-sla-high {
            background-color: #dc2626;
            color: white;
            font-weight: 800;
        }
        
        .tag-sla-medium {
            background-color: #ea580c;
            color: white;
            font-weight: 800;
        }
        
        .tag-sla-low {
            background-color: #059669;
            color: white;
            font-weight: 800;
        }
        
        .steps {
            counter-reset: step-counter;
            list-style-type: none;
            padding: 0;
        }
        
        .step-item {
            position: relative;
            padding-left: 45px;
            margin-bottom: 15px;
            counter-increment: step-counter;
        }
        
        .step-item:before {
            content: counter(step-counter);
            position: absolute;
            left: 0;
            top: 0;
            background-color: #3b82f6;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        
        .step-item:not(:last-child):after {
            content: '';
            position: absolute;
            left: 15px;
            top: 30px;
            height: calc(100% - 15px);
            width: 2px;
            background-color: #e5e7eb;
        }
        
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            color: white;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            margin: 20px 0;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(59, 130, 246, 0.3);
        }
        
        .cta-button:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1e3a8a 100%);
            box-shadow: 0 6px 8px rgba(59, 130, 246, 0.4);
            transform: translateY(-1px);
        }
        
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            margin-top: 30px;
            color: #6b7280;
            font-size: 14px;
        }
        
        .footer-logo {
            max-height: 40px;
            margin-bottom: 10px;
        }
        
        .social-links {
            margin: 15px 0;
        }
        
        .social-link {
            display: inline-block;
            margin: 0 5px;
            color: #6b7280;
            text-decoration: none;
        }
        
        @media only screen and (max-width: 600px) {
            .email-wrapper {
                padding: 15px;
            }
            
            .ticket-info-table td {
                padding: 10px;
            }
            
            .step-item {
                padding-left: 35px;
            }
            
            .step-item:before {
                width: 25px;
                height: 25px;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-header">
            <img src="{{ asset('images/Capture-removebg-preview.png') }}" alt="{{ config('app.name') }}" class="logo">
            <h1 style="margin: 0; color: #1e40af; font-size: 24px;">Tiket Baru Ditugaskan</h1>
            <div class="ticket-badge" style="font-size: 20px; padding: 10px 20px; letter-spacing: 1px;">{{ $ticket->ticket_number }}</div>
        </div>
        
        <p>Halo <span class="highlight">{{ $technicianName }}</span>,</p>
        
        <p>Sebuah tiket baru telah ditugaskan kepada Anda. Mohon segera ditindaklanjuti sesuai dengan tingkat prioritas SLA yang telah ditentukan.</p>
        
        <div class="panel">
            <h2 class="panel-title">
                <span class="panel-title-icon">i</span>
                Informasi Tiket
            </h2>
            
            <table class="ticket-info-table">
                <tr>
                    <td class="label">Nomor Tiket</td>
                    <td><span class="tag tag-blue">{{ $ticket->ticket_number }}</span></td>
                </tr>
                <tr>
                    <td class="label">Teknisi yang Ditugaskan</td>
                    <td>{{ $technicianName }}</td>
                </tr>
                <tr>
                    <td class="label">Pelanggan</td>
                    <td>{{ $customerName }}</td>
                </tr>
                <tr>
                    <td class="label">Layanan</td>
                    <td><span class="tag tag-purple">{{ $ticket->service }}</span></td>
                </tr>
                <tr>
                    <td class="label">Ringkasan Masalah</td>
                    <td><span class="tag tag-blue">{{ $ticket->problem_summary }}</span></td>
                </tr>
                <tr>
                    <td class="label">Deskripsi Detail</td>
                    <td>{{ $ticket->extra_description ?: 'Tidak ada deskripsi tambahan' }}</td>
                </tr>
                <tr>
                    <td class="label">Status</td>
                    <td>
                        @if($ticket->status == 'OPEN')
                            <span class="tag tag-status-open">{{ $ticket->status }}</span>
                        @elseif($ticket->status == 'IN PROGRESS')
                            <span class="tag tag-status-in-progress">{{ $ticket->status }}</span>
                        @elseif($ticket->status == 'RESOLVED' || $ticket->status == 'CLOSED')
                            <span class="tag tag-status-resolved">{{ $ticket->status }}</span>
                        @else
                            <span class="tag" style="background-color: {{ $statusColor }};">{{ $ticket->status }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="label">SLA</td>
                    <td>
                        @if($slaLevel == 'HIGH')
                            <span class="tag tag-sla-high">{{ $slaLevel }}</span>
                        @elseif($slaLevel == 'MEDIUM')
                            <span class="tag tag-sla-medium">{{ $slaLevel }}</span>
                        @elseif($slaLevel == 'LOW')
                            <span class="tag tag-sla-low">{{ $slaLevel }}</span>
                        @else
                            <span class="tag" style="background-color: {{ $slaColor }};">{{ $slaLevel }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="label">Dilaporkan Pada</td>
                    <td>{{ $reportDate }}</td>
                </tr>
                <tr>
                    <td class="label">Dibuat Oleh</td>
                    <td>{{ $creatorName }}</td>
                </tr>
            </table>
        </div>
        
        <div class="panel">
            <h2 class="panel-title">
                <span class="panel-title-icon">!</span>
                Informasi Penting
            </h2>
            <p>
                Harap segera menindaklanjuti tiket ini sesuai dengan tingkat prioritas SLA.
            </p>
            <p style="text-align: center; margin: 15px 0;">
                @if($slaLevel == 'HIGH')
                    <span class="tag tag-sla-high" style="font-size: 16px; padding: 8px 20px;">SLA: {{ $slaLevel }}</span>
                @elseif($slaLevel == 'MEDIUM')
                    <span class="tag tag-sla-medium" style="font-size: 16px; padding: 8px 20px;">SLA: {{ $slaLevel }}</span>
                @elseif($slaLevel == 'LOW')
                    <span class="tag tag-sla-low" style="font-size: 16px; padding: 8px 20px;">SLA: {{ $slaLevel }}</span>
                @else
                    <span class="tag" style="background-color: {{ $slaColor }}; font-size: 16px; padding: 8px 20px;">SLA: {{ $slaLevel }}</span>
                @endif
            </p>
            <p>
                Tiket ini harus ditangani sesuai dengan prioritasnya.
            </p>
        </div>
        
        <div class="panel">
            <h2 class="panel-title">
                <span class="panel-title-icon">✓</span>
                Langkah Selanjutnya
            </h2>
            <ul class="steps">
                <li class="step-item">Periksa detail tiket dengan seksama</li>
                <li class="step-item">Hubungi pelanggan untuk konfirmasi masalah</li>
                <li class="step-item">Lakukan troubleshooting sesuai prosedur</li>
                <li class="step-item">Update progress tiket di sistem secara berkala</li>
                <li class="step-item">Setelah selesai, tutup tiket dengan solusi yang lengkap</li>
            </ul>
        </div>
        
        <div style="text-align: center;">
            <a href="{{ url('admin/tickets/' . $ticket->id) }}" class="cta-button">Lihat Detail Tiket</a>
        </div>
        
        <div class="footer">
            <img src="{{ asset('images/Capture-removebg-preview.png') }}" alt="{{ config('app.name') }}" class="footer-logo">
            <p>Terima kasih atas kerja keras Anda dalam memberikan layanan terbaik kepada pelanggan kami.</p>
            <p><strong>Tim NOC {{ config('app.name') }}Artacomindo</strong></p>
            <div class="social-links">
                <a href="#" class="social-link">Facebook</a> • 
                <a href="#" class="social-link">Twitter</a> • 
                <a href="#" class="social-link">Instagram</a>
            </div>
            <p style="font-size: 12px; margin-top: 20px;">
                Email ini dikirim secara otomatis, mohon jangan membalas email ini.
            </p>
        </div>
    </div>
</body>
</html>