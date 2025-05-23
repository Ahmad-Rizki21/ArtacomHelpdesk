<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Tiket {{ $ticket->ticket_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #000;
            background: #fff;
            font-size: 10px;
            margin: 0;
            padding: 0 8px;
            line-height: 1.3;
        }
    
        .header {
            padding: 5px 0 2px 0;
            border-bottom: 0.5px solid #ccc;
            margin-bottom: 6px;
            text-align: left;
        }
    
        .logo-title {
            display: flex;
            align-items: center;
            gap: 6px;
        }
    
        .company-name {
            font-size: 12px;
            font-weight: normal;
            letter-spacing: 0.3px;
        }
    
        .main-title {
            font-size: 11px;
            font-weight: normal;
            margin: 5px 0 1px 0;
            letter-spacing: 0.3px;
            text-align: left;
        }
    
        .ticket-number {
            color: #000;
            font-size: 10px;
            margin-bottom: 6px;
            font-weight: normal;
        }
    
        .row-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
    
        .row-table td {
            padding: 2px 4px;
            border: 0.5px solid #ddd;
        }
    
        .row-table .label {
            font-weight: normal;
            width: 110px;
            font-size: 10px;
            vertical-align: top;
            background: #fafafa;
        }
    
        .row-table .value {
            min-width: 100px;
            font-size: 10px;
            background: #ffffff;
        }
    
        .section-title {
            font-weight: normal;
            font-size: 10px;
            margin: 10px 0 4px 0;
            padding-bottom: 2px;
            border-bottom: 1px solid #eee;
            letter-spacing: 0.2px;
        }
    
        .description-box {
            background: #fafafa;
            border: 0.5px solid #ddd;
            border-radius: 1px;
            padding: 4px 6px;
            margin-bottom: 6px;
            white-space: pre-wrap;
            font-family: monospace;
            font-size: 9px;
            color: #333;
            line-height: 1.2;
            border-left: 1px solid #ccc;
        }
    
        .history-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
            font-size: 9px;
            border: 0.5px solid #ddd;
        }
    
        .history-table th {
            background: #f5f5f5;
            color: #333;
            font-size: 9px;
            padding: 3px 5px;
            text-align: left;
            border: 0.5px solid #ddd;
            font-weight: normal;
        }
    
        .history-table th, .history-table td {
            padding: 4px 6px;
            border: 1px solid #ccc;
            vertical-align: top;
            font-size: 9.5px;
        }
        
        .history-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    
        .status-indicator {
            display: inline-block;
            padding: 1px 4px;
            border-radius: 1px;
            color: #333;
            font-size: 9px;
            font-weight: normal;
            text-transform: uppercase;
            letter-spacing: 0.2px;
            border: 0.5px solid #ccc;
            background-color: #fafafa;
        }
    
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            font-size: 8.5px;
            text-align: right;
            color: #333;
            padding: 4px 10px 4px 0;
            border-top: 1px solid #ccc;
            background: #f9f9f9;
        }
        
        pre {
            margin: 0;
            white-space: pre-wrap;
            font-size: 9px;
            font-family: monospace;
            color: #000;
            line-height: 1.3;
        }
        
        .action-badge {
            display: inline-block;
            font-size: 8px;
            font-weight: normal;
            padding: 1px 3px;
            border-radius: 1px;
            margin-right: 3px;
            text-transform: uppercase;
            background-color: #fafafa;
            border: 0.5px solid #ddd;
            color: #333;
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
            
            .section-title {
                page-break-before: auto;
                page-break-after: avoid;
            }
            
            .description-box, .row-table {
                page-break-inside: avoid;
            }
            
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
            <span class="company-name">{{ $company ?? 'FTTH HELPDESK SYSTEM' }}</span>
        </div>
    </div>
    {{-- <div class="main-title">LAPORAN TIKET HELPDESK</div> --}}
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
            <span class="status-indicator">
                {{ $ticket->status ?? 'UNKNOWN' }}
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
<div class="description-box">{{ $ticket->action_description }}</div>
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
                <span class="action-badge">{{ $progress->action_type }}</span>
                <pre>{{ $progress->description }}</pre>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="3" style="text-align:center;padding:8px;">Belum ada data progress.</td>
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