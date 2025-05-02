<?php

namespace App\Exports;

use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\{FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithColumnFormatting, WithStyles, WithTitle};
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class TicketsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithColumnFormatting, WithStyles, WithTitle
{
    protected Collection $tickets;
    protected $averageUptime = 0;
    protected $slaMetCount = 0;
    protected $slaMissedCount = 0;
    protected $monthlyStats = [];

    public function __construct(Collection $tickets)
    {
        $this->tickets = $tickets;
        $this->calculateStatistics();
    }

    private function calculateStatistics()
    {
        $closedTickets = $this->tickets->filter(function ($ticket) {
            return $ticket->closed_date && $ticket->report_date;
        });
        
        if ($closedTickets->isEmpty()) {
            return;
        }
        
        // Inisialisasi statistik bulanan
        $this->initializeMonthlyStats($closedTickets);
        
        $totalUptime = 0;
        $validTicketsCount = 0;
        
        foreach ($closedTickets as $ticket) {
            $uptimePercentage = $ticket->calculateUptimePercentage();
            
            if ($uptimePercentage !== null) {
                $totalUptime += $uptimePercentage;
                $validTicketsCount++;
                
                // Cek apakah tiket memenuhi SLA
                $isMeetingSla = $ticket->isMeetingSlaTarget();
                if ($isMeetingSla === true) {
                    $this->slaMetCount++;
                    
                    // Tambahkan ke statistik bulanan
                    $month = $ticket->report_date->format('Y-m');
                    if (isset($this->monthlyStats[$month])) {
                        $this->monthlyStats[$month]['met']++;
                    }
                } elseif ($isMeetingSla === false) {
                    $this->slaMissedCount++;
                    
                    // Tambahkan ke statistik bulanan
                    $month = $ticket->report_date->format('Y-m');
                    if (isset($this->monthlyStats[$month])) {
                        $this->monthlyStats[$month]['missed']++;
                    }
                }
            }
        }
        
        // Hitung rata-rata uptime
        $this->averageUptime = $validTicketsCount > 0 ? 
            $totalUptime / $validTicketsCount : 0;
            
        // Hitung persentase kepatuhan SLA untuk setiap bulan
        foreach ($this->monthlyStats as $month => &$stats) {
            $total = $stats['met'] + $stats['missed'];
            $stats['compliance'] = $total > 0 ? 
                ($stats['met'] / $total) * 100 : 0;
            
            // Format bulan untuk tampilan
            $date = Carbon::createFromFormat('Y-m', $month);
            $stats['month_name'] = $date->translatedFormat('F Y');
            
            // Hitung downtime maksimum yang diizinkan
            $stats['allowed_downtime'] = Ticket::calculateAllowedDowntimeInMonth($date);
        }
    }
    
    private function initializeMonthlyStats($tickets)
    {
        // Kelompokkan tiket berdasarkan bulan
        $months = $tickets->map(function ($ticket) {
            return $ticket->report_date->format('Y-m');
        })->unique();
        
        // Inisialisasi statistik untuk setiap bulan
        foreach ($months as $month) {
            $this->monthlyStats[$month] = [
                'met' => 0,
                'missed' => 0,
                'compliance' => 0,
                'month_name' => '',
                'allowed_downtime' => 0,
            ];
        }
    }

    public function collection(): Collection
    {
        return $this->tickets;
    }

    public function headings(): array
    {
        // Format rata-rata uptime
        $formattedAvgUptime = number_format($this->averageUptime, 2) . '%';
        
        // Hitung persentase kepatuhan SLA
        $totalTicketsWithSla = $this->slaMetCount + $this->slaMissedCount;
        $slaCompliancePercentage = $totalTicketsWithSla > 0 ? 
            round(($this->slaMetCount / $totalTicketsWithSla) * 100, 2) : 0;
        
        $headers = [
            ['LAPORAN TICKET HELPDESK FTTH'],
            ['Dicetak pada: ' . Carbon::now()->translatedFormat('d F Y H:i:s')],
            [''],
            // Ringkasan status ticket
            ['Ringkasan Status:', '', '', ''],
            ['OPEN:', $this->tickets->where('status', 'OPEN')->count(), '', ''],
            ['PENDING:', $this->tickets->where('status', 'PENDING')->count(), '', ''],
            ['CLOSED:', $this->tickets->where('status', 'CLOSED')->count(), '', ''],
            ['TOTAL:', $this->tickets->count(), '', ''],
            [''],
            // Ringkasan SLA
            ['Ringkasan SLA (Target Uptime: ' . Ticket::TARGET_UPTIME_PERCENTAGE . '%):', '', '', ''],
            ['Rata-rata Uptime:', $formattedAvgUptime, '', ''],
            ['Tiket Memenuhi SLA:', $this->slaMetCount, '', ''],
            ['Tiket Melebihi SLA:', $this->slaMissedCount, '', ''],
            ['Persentase Kepatuhan SLA:', number_format($slaCompliancePercentage, 2) . '%', '', ''],
            [''],
        ];
        
        // Tambahkan statistik bulanan jika ada
        if (!empty($this->monthlyStats)) {
            $headers[] = ['Statistik Bulanan:', '', '', ''];
            $headers[] = ['Bulan', 'Memenuhi SLA', 'Melebihi SLA', 'Kepatuhan', 'Downtime Maks (jam:menit)'];
            
            foreach ($this->monthlyStats as $stats) {
                $hours = floor($stats['allowed_downtime'] / 60);
                $minutes = $stats['allowed_downtime'] % 60;
                $formattedDowntime = sprintf('%02d:%02d', $hours, round($minutes));
                
                $headers[] = [
                    $stats['month_name'],
                    $stats['met'],
                    $stats['missed'],
                    number_format($stats['compliance'], 2) . '%',
                    $formattedDowntime
                ];
            }
            
            $headers[] = [''];
        }
        
        // Header untuk data tiket
        $headers[] = [
            'No Ticket',
            'Layanan',
            'Pelanggan',
            'Problem Summary',
            'Report Date',
            'Status',
            'Pending (menit)',
            'Closed Date',
            'Waktu Resolusi',
            'Uptime (%)',
            'Downtime Maks',
            'Status SLA',
            'Action Description',
            'Created By',
        ];
        
        return $headers;
    }

    public function map($ticket): array
    {
        // Hitung uptime
        $uptime = $ticket->calculateUptimePercentage();
        $formattedUptime = $uptime !== null ? 
            number_format($uptime, 2) . '%' : 'N/A';
        
        return [
            $ticket->ticket_number,
            $ticket->service,
            $ticket->customer->composite_data ?? '(Tidak Ada)',
            $ticket->problem_summary,
            $ticket->report_date ? $ticket->report_date->format('d/m/Y H:i:s') : '-',
            $ticket->status,
            $ticket->pending_clock ?? '0',
            $ticket->closed_date ? $ticket->closed_date->format('d/m/Y H:i:s') : '-',
            $ticket->resolution_time,
            $formattedUptime,
            $ticket->allowed_downtime,
            $ticket->sla_status,
            $ticket->action_description,
            $ticket->creator->name ?? '(Tidak Ada)',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_DATE_DATETIME,
            'H' => NumberFormat::FORMAT_DATE_DATETIME,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Ambil jumlah baris dalam statistik bulanan
        $monthlyStatsRowCount = count($this->monthlyStats);
        
        // Tentukan baris awal untuk header tabel data
        $dataHeaderRow = 16 + ($monthlyStatsRowCount > 0 ? $monthlyStatsRowCount + 2 : 0);
        
        // Judul laporan
        $sheet->mergeCells('A1:N1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        
        // Tanggal cetak
        $sheet->mergeCells('A2:N2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');
        
        // Styling ringkasan status
        $sheet->getStyle('A4')->getFont()->setBold(true);
        $sheet->mergeCells('A4:D4');
        
        // Styling untuk setiap baris status
        $sheet->getStyle('A5:A8')->getAlignment()->setIndent(1);
        
        // Mewarnai baris status
        $sheet->getStyle('A5:B5')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('FFCCCC'); // OPEN - Light red
            
        $sheet->getStyle('A6:B6')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('FFE699'); // PENDING - Light yellow
            
        $sheet->getStyle('A7:B7')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('C6EFCE'); // CLOSED - Light green
            
        $sheet->getStyle('A8:B8')->getFont()->setBold(true);
        
        // Styling ringkasan SLA
        $sheet->getStyle('A10')->getFont()->setBold(true);
        $sheet->mergeCells('A10:D10');
        
        $sheet->getStyle('A11:A14')->getAlignment()->setIndent(1);
        
        // Mewarnai baris ringkasan SLA
        $sheet->getStyle('A11:B11')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('D8E4BC'); // Light green - Uptime
            
        $sheet->getStyle('A12:B12')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('C6EFCE'); // Light green - Memenuhi SLA
            
        $sheet->getStyle('A13:B13')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('FFCCCC'); // Light red - Melebihi SLA
            
        $sheet->getStyle('A14:B14')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('BDD7EE'); // Light blue - Persentase
            
        $sheet->getStyle('B11:B14')->getAlignment()->setHorizontal('center');
        
        // Styling untuk statistik bulanan
        if ($monthlyStatsRowCount > 0) {
            $statsStartRow = 16;
            $statsEndRow = $statsStartRow + $monthlyStatsRowCount;
            
            // Header statistik bulanan
            $sheet->getStyle("A{$statsStartRow}")->getFont()->setBold(true);
            $sheet->mergeCells("A{$statsStartRow}:E{$statsStartRow}");
            
            // Header kolom
            $sheet->getStyle("A" . ($statsStartRow + 1) . ":E" . ($statsStartRow + 1))->getFont()->setBold(true);
            $sheet->getStyle("A" . ($statsStartRow + 1) . ":E" . ($statsStartRow + 1))->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('D9D9D9');
                
            // Semua sel di area statistik bulanan
            $sheet->getStyle("A{$statsStartRow}:E{$statsEndRow}")->getBorders()->getAllBorders()
                ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                
            // Alignment untuk kolom statistik
            $sheet->getStyle("B" . ($statsStartRow + 2) . ":E{$statsEndRow}")->getAlignment()->setHorizontal('center');
        }
        
        // Header tabel data
        $sheet->getStyle("A{$dataHeaderRow}:N{$dataHeaderRow}")->getFont()->setBold(true);
        $sheet->getStyle("A{$dataHeaderRow}:N{$dataHeaderRow}")->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('D9D9D9');
        
        // Atur lebar kolom
        foreach (range('A', 'N') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Kolom deskripsi dengan wrapping text
        $sheet->getStyle('D')->getAlignment()->setWrapText(true);
        $sheet->getStyle('M')->getAlignment()->setWrapText(true);
        
        // Alignment untuk kolom tertentu
        $sheet->getStyle('E:L')->getAlignment()->setHorizontal('center');
        
        // Conditional formatting untuk data
        $lastDataRow = $sheet->getHighestRow();
        for ($row = $dataHeaderRow + 1; $row <= $lastDataRow; $row++) {
            $statusValue = $sheet->getCell("F{$row}")->getValue();
            $slaStatusValue = $sheet->getCell("L{$row}")->getValue();
            
            // Warna untuk Status
            if ($statusValue === 'OPEN') {
                $sheet->getStyle("F{$row}")->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('FFCCCC'); // Light red
            } elseif ($statusValue === 'PENDING') {
                $sheet->getStyle("F{$row}")->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('FFE699'); // Light yellow
            } elseif ($statusValue === 'CLOSED') {
                $sheet->getStyle("F{$row}")->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('C6EFCE'); // Light green
            }
            
            // Warna untuk Status SLA
            if ($slaStatusValue === 'Memenuhi SLA') {
                $sheet->getStyle("L{$row}")->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('C6EFCE'); // Light green
            } elseif ($slaStatusValue === 'Melebihi SLA') {
                $sheet->getStyle("L{$row}")->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('FFCCCC'); // Light red
            }
        }
        
        // Tambahkan border pada tabel
        $sheet->getStyle("A{$dataHeaderRow}:N{$lastDataRow}")->getBorders()->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        
        return [];
    }
    
    public function title(): string
    {
        return 'Data Ticket FTTH';
    }
}