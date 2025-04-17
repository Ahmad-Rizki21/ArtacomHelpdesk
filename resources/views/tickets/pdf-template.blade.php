<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Tiket {{ $ticket->ticket_number }}</title>
    <style>
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            color: #1e293b;
            background: #fff;
            font-size: 10px; /* Reduced from 11px */
            margin: 0;
            padding: 0 8px; /* Reduced side padding */
            line-height: 1.3; /* Reduced from 1.4 */
        }
    
        .header {
            padding: 6px 0 3px 0; /* Reduced padding */
            border-bottom: 2px solid #2563eb; /* Thinner border */
            margin-bottom: 8px; /* Reduced margin */
            text-align: left;
            background: linear-gradient(to right, #f0f9ff, #ffffff);
        }
    
        .logo-title {
            display: flex;
            align-items: center;
            gap: 6px; /* Reduced gap */
        }
    
        .logo {
            max-height: 30px; /* Smaller logo */
        }
    
        .company-name {
            font-size: 16px; /* Reduced from 18px */
            color: #2563eb;
            font-weight: bold;
            text-shadow: 1px 1px 1px rgba(0,0,0,0.05);
            letter-spacing: 0.5px;
        }
    
        .main-title {
            font-size: 14px; /* Reduced from 15px */
            font-weight: bold;
            color: #2563eb;
            margin: 5px 0 1px 0; /* Reduced margins */
            letter-spacing: 0.5px;
            text-align: left;
            text-shadow: 1px 1px 1px rgba(0,0,0,0.05);
        }
    
        .ticket-number {
            color: #475569;
            font-size: 11px; /* Reduced from 12px */
            margin-bottom: 6px; /* Reduced margin */
            font-weight: 600;
        }
    
        .row-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px; /* Reduced margin */
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
    
        .row-table td {
            padding: 3px 5px; /* Reduced padding */
        }
    
        .row-table .label {
            font-weight: bold;
            color: #1e40af;
            width: 110px; /* Slightly reduced */
            font-size: 10px; /* Reduced from 11px */
            vertical-align: top;
            background: #f8fafc;
            border-right: 1px solid #e2e8f0;
        }
    
        .row-table .value {
            color: #334155;
            min-width: 100px;
            font-size: 10px; /* Reduced from 11px */
            background: #ffffff;
        }
    
        .section-title {
            font-weight: bold;
            color: #2563eb;
            font-size: 12px; /* Reduced from 13px */
            margin: 12px 0 4px 0; /* Reduced margins */
            padding-bottom: 2px; /* Reduced padding */
            border-bottom: 1px solid #e2e8f0;
            letter-spacing: 0.3px;
            text-shadow: 1px 1px 1px rgba(0,0,0,0.05);
        }
    
        .description-box {
            background: #f8fafc;
            border: 1px solid #dae1e9;
            border-radius: 4px; /* Reduced from 5px */
            padding: 6px 8px; /* Reduced padding */
            margin-bottom: 8px; /* Reduced margin */
            white-space: pre-wrap;
            font-family: 'Consolas', monospace;
            font-size: 9.5px; /* Reduced from 10.5px */
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            color: #334155;
            line-height: 1.3; /* Reduced from 1.4 */
            border-left: 2px solid #3b82f6; /* Thinner border */
        }
    
        .history-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px; /* Reduced margin */
            font-size: 9.5px; /* Reduced from 10.5px */
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
            border: 1px solid #e2e8f0;
            border-radius: 4px; /* Reduced from 5px */
            overflow: hidden;
        }
    
        .history-table th {
            background: #2563eb;
            color: #fff;
            font-size: 10px; /* Reduced from 11px */
            padding: 4px 6px; /* Reduced padding */
            text-align: left;
            border: none;
        }
    
        .history-table th, .history-table td {
            padding: 4px 6px; /* Reduced padding */
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
            font-size: 9.5px; /* Reduced from 10.5px */
        }
        
        .history-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        
        .history-table tr:nth-child(odd) {
            background-color: #fff;
        }
    
        .history-table tr:last-child td {
            border-bottom: none;
        }
    
        .status-box {
            display: inline-block;
            padding: 2px 6px; /* Reduced padding */
            border-radius: 3px; /* Reduced from 4px */
            color: #fff !important;
            font-size: 9px; /* Reduced from 10px */
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 1px 1px rgba(0,0,0,0.1);
            border: 1px solid #000;
        }
    
        .status-CLOSED { 
            background: #dc2626 !important; 
        }
        
        .status-PENDING { 
            background: #f59e0b !important; 
        }
        
        .status-OPEN { 
            background: #3b82f6 !important; 
        }
        
        .status-OTHER { 
            background: #1e293b !important; 
        }
    
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            font-size: 8.5px; /* Reduced from 9.5px */
            text-align: right;
            color: #64748b;
            padding: 4px 10px 4px 0; /* Reduced padding */
            border-top: 1px solid #e5e7eb;
            background: #f8fafc;
        }
        
        /* Styling untuk pre tag pada description */
        pre {
            margin: 0;
            white-space: pre-wrap;
            font-size: 9px; /* Reduced from 10px */
            font-family: 'Consolas', monospace;
            color: #334155;
            line-height: 1.3; /* Reduced from 1.4 */
        }
        
        /* Styling untuk action type badges */
        .action-badge {
            display: inline-block;
            font-size: 8px; /* Reduced from 9px */
            font-weight: bold;
            padding: 1px 4px; /* Reduced padding */
            border-radius: 2px; /* Reduced from 3px */
            margin-right: 4px; /* Reduced margin */
            text-transform: uppercase;
        }
        
        .action-open {
            background-color: #dbeafe;
            color: #1e3a8a;
        }
        
        .action-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .action-start {
            background-color: #dcfce7;
            color: #166534;
        }
        
        .action-completed {
            background-color: #e2e8f0;
            color: #475569;
        }
        
        .action-note {
            background-color: #f3e8ff;
            color: #6b21a8;
        }

        /* Media query for print */
        @media print {
            body {
                font-size: 9px; /* Even smaller for print */
            }
            
            .footer {
                position: fixed;
                bottom: 0;
            }
            
            /* Ensure page breaks happen at logical places */
            .section-title {
                page-break-before: auto;
                page-break-after: avoid;
            }
            
            .description-box, .row-table {
                page-break-inside: avoid;
            }
            
            /* Allow history table to break across pages if needed */
            .history-table {
                page-break-inside: auto;
            }
            
            .history-table tr {
                page-break-inside: avoid;
            }
        }
    </style>


</head>
<body>
    <!-- HEADER/LOGO -->
    <div class="header">
        <div class="logo-title">
            {{-- Kalau mau nambah logo, tapi sepertinya engga usah --}}
            {{-- <img src="{{ $logoPath }}" class="logo" alt="Company Logo">  --}}
            <span class="company-name">{{ $company ?? 'FTTH HELPDESK SYSTEM' }}</span>
        </div>
    </div>
    <div class="main-title">LAPORAN TIKET HELPDESK</div>
    <div class="ticket-number">Nomor Tiket: {{ $ticket->ticket_number }}</div>

<!-- DETAIL TIKET -->
<div class="section-title">Informasi Tiket</div>
<table class="row-table">
    <tr>
        <td class="label">Layanan</td>
        <td class="value">{{ $ticket->service }}</td>
    </tr>
    <tr>
        <td class="label">ID Pelanggan</td>
        <td class="value">{{ $ticket->customer->composite_data ?? '-' }}</td>
    </tr>
    <tr>
        <td class="label">Jenis Masalah</td>
        <td class="value">{{ $ticket->problem_summary }}</td>
    </tr>
    <tr>
        <td class="label">Status</td>
        <td class="value">
            @php
                $statusText = $ticket->status ?? 'UNKNOWN';
                $statusClass = strtoupper($statusText);
            @endphp
            <span style="display:inline-block; padding:2px 6px; border-radius:3px; background-color:{{ $statusClass === 'CLOSED' ? '#dc2626' : ($statusClass === 'PENDING' ? '#f59e0b' : ($statusClass === 'OPEN' ? '#3b82f6' : '#1e293b')) }}; color:white; font-weight:bold; font-size:9px; text-transform:uppercase; letter-spacing:0.5px; border:1px solid #000;">
                {{ $statusText }}
            </span>
        </td>
    </tr>
    <tr>
        <td class="label">SLA</td>
        <td class="value">{{ $ticket->sla->name ?? '-' }}</td>
    </tr>
    <tr>
        <td class="label">Tanggal Laporan</td>
        <td class="value">{{ \Carbon\Carbon::parse($ticket->report_date)->format('d/m/Y H:i:s') }}</td>
    </tr>
    @if($ticket->closed_date)
    <tr>
        <td class="label">Tanggal Ditutup</td>
        <td class="value">{{ \Carbon\Carbon::parse($ticket->closed_date)->format('d/m/Y H:i:s') }}</td>
    </tr>
    @endif
    @if($ticket->pending_clock && $ticket->pending_clock != 'No Pending Yet')
    <tr>
        <td class="label">Waktu Pending</td>
        <td class="value">{{ $ticket->pending_clock }}</td>
    </tr>
    @endif
</table>


<!-- DESKRIPSI MASALAH -->
<div class="section-title">Deskripsi Masalah</div>
<div class="description-box" style="margin-bottom:10px;">{{ $ticket->extra_description ?? '-' }}</div>

<!-- RESOLUTION / ACTION -->
@if($ticket->action_description)
<div class="section-title">Resolution / Action</div>
<div class="description-box" style="border-left-color: #0284c7; background-color: #f0f9ff;">{{ $ticket->action_description }}</div>
@endif

<!-- TINDAKAN YANG DILAKUKAN SEBAGAI HISTORY TABLE -->
<div class="section-title">Riwayat Tindakan</div>
<table class="history-table">
    <thead>
        <tr>
            <th style="width: 75px;">Tanggal/Waktu</th>
            <th style="width: 85px;">User</th>
            <th>Tindakan (Action Description)</th>
        </tr>
    </thead>
    <tbody>
        @forelse($ticket->actions->sortByDesc('created_at') as $progress)
        <tr>
            <td>{{ \Carbon\Carbon::parse($progress->created_at)->format('d/m/Y H:i:s') }}</td>
            <td>{{ $progress->user->name ?? 'System' }}</td>
            <td>
                @php
                    $actionClass = match($progress->action_type) {
                        'Open Clock' => 'action-open',
                        'Pending Clock' => 'action-pending',
                        'Start Clock' => 'action-start', 
                        'Completed' => 'action-completed',
                        default => 'action-note'
                    };
                @endphp
                <span class="action-badge {{ $actionClass }}">{{ $progress->action_type }}</span>
                <pre>{{ $progress->description }}</pre>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="3" style="text-align:center;color:#a0aec0;padding:8px;">Belum ada data progress.</td>
        </tr>
        @endforelse
    </tbody>
</table>


<!-- INFORMASI SISTEM -->
<div class="section-title">Informasi Sistem</div>
<table class="row-table">
    <tr>
        <td class="label">Tanggal Dibuat</td>
        <td class="value">{{ \Carbon\Carbon::parse($ticket->created_at)->format('d/m/Y H:i:s') }}</td>
    </tr>
    <tr>
        <td class="label">Terakhir Diperbarui</td>
        <td class="value">{{ \Carbon\Carbon::parse($ticket->updated_at)->format('d/m/Y H:i:s') }}</td>
    </tr>
</table>


<div class="footer">
    Dokumen ini dicetak pada: {{ $today ?? now()->format('d-m-Y H:i:s') }} | FTTH JELANTIK HELPDESK
</div>


</body>
</html>