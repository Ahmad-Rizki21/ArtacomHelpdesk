<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Tiket {{ $ticket->no_ticket }}</title>
    <style>
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            color: #1e293b;
            background: #fff;
            font-size: 10px;
            margin: 0;
            padding: 0 8px;
            line-height: 1.3;
        }
    
        .header {
            padding: 6px 0 3px 0;
            border-bottom: 2px solid #2563eb;
            margin-bottom: 8px;
            text-align: left;
            background: linear-gradient(to right, #f0f9ff, #ffffff);
        }
    
        .logo-title {
            display: flex;
            align-items: center;
            gap: 6px;
        }
    
        .logo {
            max-height: 30px;
        }
    
        .company-name {
            font-size: 16px;
            color: #2563eb;
            font-weight: bold;
            text-shadow: 1px 1px 1px rgba(0,0,0,0.05);
            letter-spacing: 0.5px;
        }
    
        .main-title {
            font-size: 14px;
            font-weight: bold;
            color: #2563eb;
            margin: 5px 0 1px 0;
            letter-spacing: 0.5px;
            text-align: left;
            text-shadow: 1px 1px 1px rgba(0,0,0,0.05);
        }
    
        .ticket-number {
            color: #475569;
            font-size: 11px;
            margin-bottom: 6px;
            font-weight: 600;
        }
    
        .row-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
    
        .row-table td {
            padding: 3px 5px;
        }
    
        .row-table .label {
            font-weight: bold;
            color: #1e40af;
            width: 110px;
            font-size: 10px;
            vertical-align: top;
            background: #f8fafc;
            border-right: 1px solid #e2e8f0;
        }
    
        .row-table .value {
            color: #334155;
            min-width: 100px;
            font-size: 10px;
            background: #ffffff;
        }
    
        .section-title {
            font-weight: bold;
            color: #2563eb;
            font-size: 12px;
            margin: 12px 0 4px 0;
            padding-bottom: 2px;
            border-bottom: 1px solid #e2e8f0;
            letter-spacing: 0.3px;
            text-shadow: 1px 1px 1px rgba(0,0,0,0.05);
        }
    
        .description-box {
            background: #f8fafc;
            border: 1px solid #dae1e9;
            border-radius: 4px;
            padding: 6px 8px;
            margin-bottom: 8px;
            white-space: pre-wrap;
            font-family: 'Consolas', monospace;
            font-size: 9.5px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            color: #334155;
            line-height: 1.3;
            border-left: 2px solid #3b82f6;
        }
    
        .history-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            font-size: 9.5px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
        }
    
        .history-table th {
            background: #2563eb;
            color: #fff;
            font-size: 10px;
            padding: 4px 6px;
            text-align: left;
            border: none;
        }
    
        .history-table th, .history-table td {
            padding: 4px 6px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
            font-size: 9.5px;
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
            padding: 2px 6px;
            border-radius: 3px;
            color: #fff !important;
            font-size: 9px;
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
            font-size: 8.5px;
            text-align: right;
            color: #64748b;
            padding: 4px 10px 4px 0;
            border-top: 1px solid #e5e7eb;
            background: #f8fafc;
        }
        
        /* Styling untuk pre tag pada description */
        pre {
            margin: 0;
            white-space: pre-wrap;
            font-size: 9px;
            font-family: 'Consolas', monospace;
            color: #334155;
            line-height: 1.3;
        }
        
        /* Styling untuk action type badges */
        .action-badge {
            display: inline-block;
            font-size: 8px;
            font-weight: bold;
            padding: 1px 4px;
            border-radius: 2px;
            margin-right: 4px;
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
                font-size: 9px;
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
            <span class="company-name">{{ $company ?? 'BACKBONE HELPDESK SYSTEM' }}</span>
        </div>
    </div>
    <div class="main-title">LAPORAN TIKET BACKBONE</div>
    <div class="ticket-number">Nomor Tiket: {{ $ticket->no_ticket }}</div>

<!-- DETAIL TIKET -->
<div class="section-title">Informasi Tiket</div>
<table class="row-table">
    <tr>
        <td class="label">CID</td>
        <td class="value">{{ $ticket->cidRelation->cid ?? '-' }}</td>
    </tr>
    <tr>
        <td class="label">Jenis ISP</td>
        <td class="value">{{ $ticket->jenis_isp ?? '-' }}</td>
    </tr>
    <tr>
        <td class="label">Lokasi</td>
        <td class="value">{{ $ticket->lokasiRelation->lokasi ?? '-' }}</td>
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
        <td class="label">Tanggal Laporan</td>
        <td class="value">{{ optional($ticket->open_date)->format('d/m/Y H:i:s') }}</td>
    </tr>
    @if($ticket->closed_date)
    <tr>
        <td class="label">Tanggal Ditutup</td>
        <td class="value">{{ \Carbon\Carbon::parse($ticket->closed_date)->format('d/m/Y H:i:s') }}</td>
    </tr>
    @endif
    @if($ticket->pending_date)
    <tr>
        <td class="label">Waktu Pending</td>
        <td class="value">{{ \Carbon\Carbon::parse($ticket->pending_date)->format('d/m/Y H:i:s') }}</td>
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
    Dokumen ini dicetak pada: {{ $today ?? now()->format('d-m-Y H:i:s') }} | BACKBONE HELPDESK
</div>


</body>
</html>