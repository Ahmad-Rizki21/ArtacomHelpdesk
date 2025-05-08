<?php

namespace App\Exports;

use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class TicketsExport implements WithMultipleSheets
{
    protected Collection $tickets;
    protected $averageUptime = 0;
    protected $slaMetCount = 0;
    protected $slaMissedCount = 0;
    protected $monthlyStats = [];
    protected $problemTypes = [];
    protected $serviceTypes = [];
    protected $ticketStatusCount = [];

    public function __construct(Collection $tickets)
    {
        $this->tickets = $tickets;
        $this->calculateStatistics();
        $this->analyzeProblems();
    }

    public function sheets(): array
    {
        return [
            new DashboardSheet($this->tickets, $this->averageUptime, $this->slaMetCount, $this->slaMissedCount, $this->monthlyStats, $this->ticketStatusCount),
            new TicketDataSheet($this->tickets, $this->averageUptime, $this->slaMetCount, $this->slaMissedCount, $this->monthlyStats, $this->ticketStatusCount),
            new TicketAnalysisSheet($this->problemTypes, $this->serviceTypes),
        ];
    }

    private function calculateStatistics()
    {
        // Hitung status tiket
        $this->ticketStatusCount = [
            'OPEN' => $this->tickets->where('status', 'OPEN')->count(),
            'PENDING' => $this->tickets->where('status', 'PENDING')->count(),
            'CLOSED' => $this->tickets->where('status', 'CLOSED')->count()
        ];

        // Filter tiket yang sudah ditutup dan memiliki tanggal laporan
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
                    $month = $ticket->report_date->format('Y-m');
                    if (isset($this->monthlyStats[$month])) {
                        $this->monthlyStats[$month]['met']++;
                    }
                } elseif ($isMeetingSla === false) {
                    $this->slaMissedCount++;
                    $month = $ticket->report_date->format('Y-m');
                    if (isset($this->monthlyStats[$month])) {
                        $this->monthlyStats[$month]['missed']++;
                    }
                }
            }
        }

        // Hitung rata-rata uptime
        $this->averageUptime = $validTicketsCount > 0 ? $totalUptime / $validTicketsCount : 0;

        // Hitung persentase kepatuhan SLA untuk setiap bulan
        foreach ($this->monthlyStats as $month => &$stats) {
            $total = $stats['met'] + $stats['missed'];
            $stats['compliance'] = $total > 0 ? ($stats['met'] / $total) * 100 : 0;
            $date = Carbon::createFromFormat('Y-m', $month);
            $stats['month_name'] = $date->translatedFormat('F Y');
            $stats['allowed_downtime'] = Ticket::calculateAllowedDowntimeInMonth($date);
        }

        // Urutkan berdasarkan bulan
        ksort($this->monthlyStats);
    }

    private function analyzeProblems()
    {
        // Analisis jenis masalah
        foreach ($this->tickets as $ticket) {
            if (!empty($ticket->problem_summary)) {
                $problemType = $ticket->problem_summary;
                $this->problemTypes[$problemType] = ($this->problemTypes[$problemType] ?? 0) + 1;
            }

            // Analisis jenis layanan
            if (!empty($ticket->service)) {
                $this->serviceTypes[$ticket->service] = ($this->serviceTypes[$ticket->service] ?? 0) + 1;
            }
        }

        // Urutkan berdasarkan jumlah (terbanyak terlebih dahulu)
        arsort($this->problemTypes);
        // Ambil 10 jenis masalah teratas saja
        $this->problemTypes = array_slice($this->problemTypes, 0, 10, true);
        
        // Urutkan jenis layanan
        arsort($this->serviceTypes);
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
}

class DashboardSheet implements WithTitle, WithStyles, WithEvents
{
    protected Collection $tickets;
    protected $averageUptime;
    protected $slaMetCount;
    protected $slaMissedCount;
    protected $monthlyStats;
    protected $ticketStatusCount;

    public function __construct(Collection $tickets, $averageUptime, $slaMetCount, $slaMissedCount, array $monthlyStats, array $ticketStatusCount)
    {
        $this->tickets = $tickets;
        $this->averageUptime = $averageUptime;
        $this->slaMetCount = $slaMetCount;
        $this->slaMissedCount = $slaMissedCount;
        $this->monthlyStats = $monthlyStats;
        $this->ticketStatusCount = $ticketStatusCount;
    }

    public function title(): string
    {
        return 'Dashboard';
    }

    public function styles(Worksheet $sheet)
    {
        // Judul
        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A1', 'DASHBOARD TICKET HELPDESK FTTH');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16)->setColor(new Color('FFFFFF'));
        $sheet->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('2F4F4F');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Subtitle
        $sheet->mergeCells('A2:G2');
        $sheet->setCellValue('A2', 'Dicetak pada: ' . Carbon::now()->translatedFormat('d F Y H:i:s'));
        $sheet->getStyle('A2')->getFont()->setSize(10)->setColor(new Color('FFFFFF'));
        $sheet->getStyle('A2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('4682B4');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // KPI Cards
        $sheet->mergeCells('A4:B4');
        $sheet->setCellValue('A4', 'Total Tickets: ' . $this->tickets->count());
        $sheet->getStyle('A4')->getFont()->setBold(true)->setColor(new Color('FFFFFF'));
        $sheet->getStyle('A4:B4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('32CD32');
        $sheet->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('C4:D4');
        $sheet->setCellValue('C4', 'Average Uptime: ' . number_format($this->averageUptime, 2) . '%');
        $sheet->getStyle('C4')->getFont()->setBold(true)->setColor(new Color('FFFFFF'));
        $sheet->getStyle('C4:D4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('1E90FF');
        $sheet->getStyle('C4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('E4:F4');
        $totalSla = $this->slaMetCount + $this->slaMissedCount;
        $slaCompliance = $totalSla > 0 ? round(($this->slaMetCount / $totalSla) * 100, 2) : 0;
        $sheet->setCellValue('E4', 'SLA Compliance: ' . number_format($slaCompliance, 2) . '%');
        $sheet->getStyle('E4')->getFont()->setBold(true)->setColor(new Color('FFFFFF'));
        $sheet->getStyle('E4:F4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFD700');
        $sheet->getStyle('E4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Auto-size columns
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Status Distribution Pie Chart
                $sheet->setCellValue('A5', 'Status Distribution');
                $sheet->getStyle('A5')->getFont()->setBold(true);
                $sheet->setCellValue('A6', 'OPEN');
                $sheet->setCellValue('A7', 'PENDING');
                $sheet->setCellValue('A8', 'CLOSED');
                $sheet->setCellValue('B6', $this->ticketStatusCount['OPEN']);
                $sheet->setCellValue('B7', $this->ticketStatusCount['PENDING']);
                $sheet->setCellValue('B8', $this->ticketStatusCount['CLOSED']);

                try {
                    $statusLabels = new DataSeriesValues(
                        DataSeriesValues::DATASERIES_TYPE_STRING,
                        'Dashboard!$A$6:$A$8',
                        null,
                        3
                    );
                    $statusValues = new DataSeriesValues(
                        DataSeriesValues::DATASERIES_TYPE_NUMBER,
                        'Dashboard!$B$6:$B$8',
                        null,
                        3
                    );
                    
                    $statusValues->setFillColor([
                        'FF6B6B', // OPEN - Light red
                        'FFD700', // PENDING - Light yellow
                        '32CD32'  // CLOSED - Light green
                    ]);

                    $statusSeries = new DataSeries(
                        DataSeries::TYPE_PIECHART,
                        DataSeries::GROUPING_STANDARD,
                        range(0, 0),
                        [$statusLabels],
                        [null], // Empty array causes issues, use [null] instead
                        [$statusValues]
                    );

                    $statusPlotArea = new PlotArea(null, [$statusSeries]);
                    $statusLegend = new Legend(Legend::POSITION_RIGHT, null, false);
                    $statusTitle = new Title('Status Distribution');

                    $statusChart = new Chart(
                        'StatusChart',
                        $statusTitle,
                        $statusLegend,
                        $statusPlotArea
                    );

                    $statusChart->setTopLeftPosition('A10');
                    $statusChart->setBottomRightPosition('D20');
                    $sheet->addChart($statusChart);
                } catch (\Exception $e) {
                    // Log error if chart creation fails, but continue processing
                    error_log('Error creating status chart: ' . $e->getMessage());
                }

                // SLA Trend Bar Chart
                if (!empty($this->monthlyStats)) {
                    $startRow = 22;
                    $sheet->setCellValue("A" . ($startRow - 1), 'Monthly SLA Trend');
                    $sheet->getStyle("A" . ($startRow - 1))->getFont()->setBold(true);

                    $sheet->setCellValue("A{$startRow}", 'Month');
                    $sheet->setCellValue("B{$startRow}", 'Met SLA');
                    $sheet->setCellValue("C{$startRow}", 'Missed SLA');
                    $sheet->getStyle("A{$startRow}:C{$startRow}")->getFont()->setBold(true);
                    $sheet->getStyle("A{$startRow}:C{$startRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('D3D3D3');

                    $row = $startRow + 1;
                    foreach ($this->monthlyStats as $stats) {
                        $sheet->setCellValue("A{$row}", $stats['month_name']);
                        $sheet->setCellValue("B{$row}", $stats['met']);
                        $sheet->setCellValue("C{$row}", $stats['missed']);
                        $row++;
                    }

                    $lastRow = $row - 1;

                    try {
                        // Create Bar Chart
                        $labels = new DataSeriesValues(
                            DataSeriesValues::DATASERIES_TYPE_STRING,
                            "Dashboard!A" . ($startRow + 1) . ":A" . $lastRow,
                            null,
                            count($this->monthlyStats)
                        );

                        $metValues = new DataSeriesValues(
                            DataSeriesValues::DATASERIES_TYPE_NUMBER,
                            "Dashboard!B" . ($startRow + 1) . ":B" . $lastRow,
                            null,
                            count($this->monthlyStats)
                        );
                        $metValues->setFillColor(['32CD32']); // Light green

                        $missedValues = new DataSeriesValues(
                            DataSeriesValues::DATASERIES_TYPE_NUMBER,
                            "Dashboard!C" . ($startRow + 1) . ":C" . $lastRow,
                            null,
                            count($this->monthlyStats)
                        );
                        $missedValues->setFillColor(['FF6B6B']); // Light red

                        $series = new DataSeries(
                            DataSeries::TYPE_BARCHART,
                            DataSeries::GROUPING_STACKED,
                            range(0, 1),
                            [$labels],
                            [null, null], // Empty array causes issues, use [null, null] instead
                            [$metValues, $missedValues]
                        );

                        $plotArea = new PlotArea(null, [$series]);
                        $legend = new Legend(Legend::POSITION_RIGHT, null, false);
                        $title = new Title('Monthly SLA Performance');

                        $chart = new Chart(
                            'SlaTrendChart',
                            $title,
                            $legend,
                            $plotArea
                        );

                        $chart->setTopLeftPosition('A' . ($lastRow + 2));
                        $chart->setBottomRightPosition('F' . ($lastRow + 15));
                        $sheet->addChart($chart);
                    } catch (\Exception $e) {
                        // Log error if chart creation fails, but continue processing
                        error_log('Error creating SLA trend chart: ' . $e->getMessage());
                    }
                }
            },
        ];
    }
}

class TicketDataSheet implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithColumnFormatting, WithStyles, WithTitle, WithEvents
{
    protected Collection $tickets;
    protected $averageUptime;
    protected $slaMetCount;
    protected $slaMissedCount;
    protected $monthlyStats;
    protected $ticketStatusCount;
    protected $dataHeaderRow;

    public function __construct(Collection $tickets, $averageUptime, $slaMetCount, $slaMissedCount, array $monthlyStats, array $ticketStatusCount)
    {
        $this->tickets = $tickets;
        $this->averageUptime = $averageUptime;
        $this->slaMetCount = $slaMetCount;
        $this->slaMissedCount = $slaMissedCount;
        $this->monthlyStats = $monthlyStats;
        $this->ticketStatusCount = $ticketStatusCount;
        $this->dataHeaderRow = 16 + (count($this->monthlyStats) > 0 ? count($this->monthlyStats) + 2 : 0);
    }

    public function collection(): Collection
    {
        return $this->tickets;
    }

    public function title(): string
    {
        return 'Data Ticket FTTH';
    }

    public function headings(): array
    {
        $formattedAvgUptime = number_format($this->averageUptime, 2) . '%';
        $totalTicketsWithSla = $this->slaMetCount + $this->slaMissedCount;
        $slaCompliancePercentage = $totalTicketsWithSla > 0 ? round(($this->slaMetCount / $totalTicketsWithSla) * 100, 2) : 0;

        $headers = [
            ['LAPORAN TICKET HELPDESK FTTH'],
            ['Dicetak pada: ' . Carbon::now()->translatedFormat('d F Y H:i:s')],
            [''],
            ['Ringkasan Status:', '', '', ''],
            ['OPEN:', $this->ticketStatusCount['OPEN'], '', ''],
            ['PENDING:', $this->ticketStatusCount['PENDING'], '', ''],
            ['CLOSED:', $this->ticketStatusCount['CLOSED'], '', ''],
            ['TOTAL:', $this->tickets->count(), '', ''],
            [''],
            ['Ringkasan SLA (Target Uptime: ' . Ticket::TARGET_UPTIME_PERCENTAGE . '%):', '', '', ''],
            ['Rata-rata Uptime:', $formattedAvgUptime, '', ''],
            ['Tiket Memenuhi SLA:', $this->slaMetCount, '', ''],
            ['Tiket Melebihi SLA:', $this->slaMissedCount, '', ''],
            ['Persentase Kepatuhan SLA:', number_format($slaCompliancePercentage, 2) . '%', '', ''],
            [''],
        ];

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

        // Header kolom untuk data tiket
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
        // Formatkan data tiket untuk tabel
        $uptime = $ticket->calculateUptimePercentage();
        $formattedUptime = $uptime !== null ? number_format($uptime, 2) . '%' : 'N/A';
        
        // Pastikan nilai tidak NULL untuk semua field
        $closedDate = $ticket->closed_date ? $ticket->closed_date->format('d/m/Y H:i:s') : 'Belum Selesai';
        $resolutionTime = $ticket->resolution_time ?: '00:00';
        
        return [
            $ticket->ticket_number,
            $ticket->service,
            $ticket->customer->composite_data ?? '(Tidak Ada)',
            $ticket->problem_summary,
            $ticket->report_date ? $ticket->report_date->format('d/m/Y H:i:s') : '-',
            $ticket->status,
            $ticket->pending_clock ?? '0',
            $closedDate,
            $resolutionTime,
            $formattedUptime,
            $ticket->allowed_downtime,
            $ticket->sla_status ?: '-',
            $ticket->action_description ?: '-',
            $ticket->creator->name ?? '(Tidak Ada)',
        ];
    }

    public function columnFormats(): array
    {
        return [
            // Format tanggal untuk kolom Report Date dan Closed Date
            'E' => NumberFormat::FORMAT_DATE_DATETIME,
            'H' => NumberFormat::FORMAT_DATE_DATETIME,
            // Format numerik untuk kolom Pending (menit)
            'G' => NumberFormat::FORMAT_NUMBER,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $monthlyStatsRowCount = count($this->monthlyStats);
        $dataHeaderRow = $this->dataHeaderRow;
        $lastDataRow = $dataHeaderRow + $this->tickets->count();

        // Header styling
        $sheet->mergeCells('A1:N1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('2F4F4F');
        $sheet->getStyle('A1')->getFont()->setColor(new Color('FFFFFF'));
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A2:N2');
        $sheet->getStyle('A2')->getFont()->setSize(10);
        $sheet->getStyle('A2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('4682B4');
        $sheet->getStyle('A2')->getFont()->setColor(new Color('FFFFFF'));
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Status Summary
        $sheet->getStyle('A4')->getFont()->setBold(true);
        $sheet->getStyle('A4:D4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('4682B4');
        $sheet->getStyle('A4')->getFont()->setColor(new Color('FFFFFF'));
        $sheet->mergeCells('A4:D4');

        $statusStyles = [
            'A5:B5' => 'FF6B6B', // OPEN - Light red
            'A6:B6' => 'FFD700', // PENDING - Light yellow
            'A7:B7' => '32CD32', // CLOSED - Light green
            'A8:B8' => '4682B4'  // TOTAL - Light blue
        ];

        foreach ($statusStyles as $range => $color) {
            $sheet->getStyle($range)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($color);
            $sheet->getStyle($range)->getFont()->setColor(new Color('FFFFFF'));
        }

        // SLA Summary
        $sheet->getStyle('A10')->getFont()->setBold(true);
        $sheet->getStyle('A10:D10')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('4682B4');
        $sheet->getStyle('A10')->getFont()->setColor(new Color('FFFFFF'));
        $sheet->mergeCells('A10:D10');

        $slaStyles = [
            'A11:B11' => '1E90FF', // Uptime - Blue
            'A12:B12' => '32CD32', // Met SLA - Green
            'A13:B13' => 'FF6B6B', // Missed SLA - Red
            'A14:B14' => '4682B4'  // Percentage - Blue
        ];

        foreach ($slaStyles as $range => $color) {
            $sheet->getStyle($range)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($color);
            $sheet->getStyle($range)->getFont()->setColor(new Color('FFFFFF'));
        }

        // Monthly Stats
        if ($monthlyStatsRowCount > 0) {
            $statsStartRow = 16;
            $statsEndRow = $statsStartRow + $monthlyStatsRowCount;

            $sheet->getStyle("A{$statsStartRow}")->getFont()->setBold(true);
            $sheet->getStyle("A{$statsStartRow}:E{$statsStartRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('4682B4');
            $sheet->getStyle("A{$statsStartRow}")->getFont()->setColor(new Color('FFFFFF'));
            $sheet->mergeCells("A{$statsStartRow}:E{$statsStartRow}");

            $sheet->getStyle("A" . ($statsStartRow + 1) . ":E" . ($statsStartRow + 1))->getFont()->setBold(true);
            $sheet->getStyle("A" . ($statsStartRow + 1) . ":E" . ($statsStartRow + 1))->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('D3D3D3');

            $sheet->getStyle("A{$statsStartRow}:E{$statsEndRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle("B" . ($statsStartRow + 2) . ":E{$statsEndRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        // Data Table Styling
        $sheet->getStyle("A{$dataHeaderRow}:N{$dataHeaderRow}")->getFont()->setBold(true);
        $sheet->getStyle("A{$dataHeaderRow}:N{$dataHeaderRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('4682B4');
        $sheet->getStyle("A{$dataHeaderRow}:N{$dataHeaderRow}")->getFont()->setColor(new Color('FFFFFF'));

        $sheet->getStyle('D')->getAlignment()->setWrapText(true);
        $sheet->getStyle('M')->getAlignment()->setWrapText(true);
        $sheet->getStyle('E:L')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Conditional Formatting for Status
        $statusConditionals = [];
        $statusColors = [
            'OPEN' => 'FF6B6B',
            'PENDING' => 'FFD700',
            'CLOSED' => '32CD32'
        ];

        foreach ($statusColors as $status => $color) {
            $conditional = new Conditional();
            $conditional->setConditionType(Conditional::CONDITION_CONTAINSTEXT)
                ->setOperatorType(Conditional::OPERATOR_CONTAINSTEXT)
                ->setText($status)
                ->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($color);
            $conditional->getStyle()->getFont()->setColor(new Color('FFFFFF'));
            $statusConditionals[] = $conditional;
        }

        $sheet->getStyle("F" . ($dataHeaderRow + 1) . ":F{$lastDataRow}")->setConditionalStyles($statusConditionals);

        // Conditional Formatting for SLA
        $slaConditionals = [
            ['text' => 'Memenuhi SLA', 'color' => '32CD32'],
            ['text' => 'Melebihi SLA', 'color' => 'FF6B6B']
        ];

        $slaConditionalStyles = [];
        foreach ($slaConditionals as $cond) {
            $conditional = new Conditional();
            $conditional->setConditionType(Conditional::CONDITION_CONTAINSTEXT)
                ->setOperatorType(Conditional::OPERATOR_CONTAINSTEXT)
                ->setText($cond['text'])
                ->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($cond['color']);
            $conditional->getStyle()->getFont()->setColor(new Color('FFFFFF'));
            $slaConditionalStyles[] = $conditional;
        }

        $sheet->getStyle("L{$dataHeaderRow}:L{$lastDataRow}")->setConditionalStyles($slaConditionalStyles);

        $sheet->getStyle("A{$dataHeaderRow}:N{$lastDataRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        foreach (range('A', 'N') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $startRow = $this->dataHeaderRow + $this->tickets->count() + 5;

                // Visualization Section
                $sheet->mergeCells("A{$startRow}:N" . ($startRow));
                $sheet->setCellValue("A{$startRow}", "VISUALISASI DATA TICKET");
                $sheet->getStyle("A{$startRow}")->getFont()->setBold(true)->setSize(12);
                $sheet->getStyle("A{$startRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('2F4F4F');
                $sheet->getStyle("A{$startRow}")->getFont()->setColor(new Color('FFFFFF'));
                $sheet->getStyle("A{$startRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $startRow += 2;

                // Status Distribution Table
                $sheet->setCellValue("A" . ($startRow - 1), "Distribusi Status Ticket");
                $sheet->getStyle("A" . ($startRow - 1))->getFont()->setBold(true);
                $sheet->mergeCells("A" . ($startRow - 1) . ":C" . ($startRow - 1));

                $sheet->setCellValue("A{$startRow}", 'Status');
                $sheet->setCellValue("B{$startRow}", 'Jumlah');
                $sheet->setCellValue("C{$startRow}", 'Persentase');

                $totalTickets = array_sum($this->ticketStatusCount);
                
                $sheet->setCellValue("A" . ($startRow + 1), 'OPEN');
                $sheet->setCellValue("B" . ($startRow + 1), $this->ticketStatusCount['OPEN']);
                $openPercent = $totalTickets > 0 ? ($this->ticketStatusCount['OPEN'] / $totalTickets) * 100 : 0;
                $sheet->setCellValue("C" . ($startRow + 1), number_format($openPercent, 2) . '%');
                
                $sheet->setCellValue("A" . ($startRow + 2), 'PENDING');
                $sheet->setCellValue("B" . ($startRow + 2), $this->ticketStatusCount['PENDING']);
                $pendingPercent = $totalTickets > 0 ? ($this->ticketStatusCount['PENDING'] / $totalTickets) * 100 : 0;
                $sheet->setCellValue("C" . ($startRow + 2), number_format($pendingPercent, 2) . '%');
                
                $sheet->setCellValue("A" . ($startRow + 3), 'CLOSED');
                $sheet->setCellValue("B" . ($startRow + 3), $this->ticketStatusCount['CLOSED']);
                $closedPercent = $totalTickets > 0 ? ($this->ticketStatusCount['CLOSED'] / $totalTickets) * 100 : 0;
                $sheet->setCellValue("C" . ($startRow + 3), number_format($closedPercent, 2) . '%');
                
                $sheet->setCellValue("A" . ($startRow + 4), 'TOTAL');
                $sheet->setCellValue("B" . ($startRow + 4), $totalTickets);
                $sheet->setCellValue("C" . ($startRow + 4), '100.00%');

                $sheet->getStyle("A{$startRow}:C" . ($startRow + 4))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle("A{$startRow}:C{$startRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('4682B4');
                $sheet->getStyle("A{$startRow}:C{$startRow}")->getFont()->setBold(true);
                $sheet->getStyle("A{$startRow}:C{$startRow}")->getFont()->setColor(new Color('FFFFFF'));
                $sheet->getStyle("A" . ($startRow + 4) . ":C" . ($startRow + 4))->getFont()->setBold(true);

                $statusColors = [
                    ($startRow + 1) => 'FF6B6B',
                    ($startRow + 2) => 'FFD700',
                    ($startRow + 3) => '32CD32'
                ];

                foreach ($statusColors as $row => $color) {
                    $sheet->getStyle("A{$row}:C{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($color);
                    $sheet->getStyle("A{$row}:C{$row}")->getFont()->setColor(new Color('FFFFFF'));
                }

                $sheet->getStyle("B{$startRow}:C" . ($startRow + 4))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}

class TicketAnalysisSheet implements WithTitle, WithStyles, WithEvents
{
    protected $problemTypes;
    protected $serviceTypes;

    public function __construct(array $problemTypes, array $serviceTypes)
    {
        $this->problemTypes = $problemTypes;
        $this->serviceTypes = $serviceTypes;
    }

    public function title(): string
    {
        return 'Analisis Masalah';
    }

    public function styles(Worksheet $sheet)
    {
        // Header
        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A1', 'ANALISIS MASALAH DAN LAYANAN TICKET FTTH');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('2F4F4F');
        $sheet->getStyle('A1')->getFont()->setColor(new Color('FFFFFF'));
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Problem Types
        $sheet->setCellValue('A3', 'Jenis Masalah Terbanyak');
        $sheet->getStyle('A3')->getFont()->setBold(true);
        $sheet->getStyle('A3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('4682B4');
        $sheet->getStyle('A3')->getFont()->setColor(new Color('FFFFFF'));

        $sheet->setCellValue('A4', 'Problem Summary');
        $sheet->setCellValue('B4', 'Jumlah');
        $sheet->setCellValue('C4', 'Persentase');
        $sheet->getStyle('A4:C4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('D3D3D3');
        $sheet->getStyle('A4:C4')->getFont()->setBold(true);

        $row = 5;
        $totalProblems = array_sum($this->problemTypes);

        foreach ($this->problemTypes as $problem => $count) {
            $sheet->setCellValue('A' . $row, $problem);
            $sheet->setCellValue('B' . $row, $count);
            $percentage = $totalProblems > 0 ? ($count / $totalProblems) * 100 : 0;
            $sheet->setCellValue('C' . $row, number_format($percentage, 2) . '%');
            $row++;
        }

        $lastProblemRow = $row - 1;

        $sheet->setCellValue('A' . $row, 'TOTAL');
        $sheet->setCellValue('B' . $row, $totalProblems);
        $sheet->setCellValue('C' . $row, '100.00%');
        $sheet->getStyle('A' . $row . ':C' . $row)->getFont()->setBold(true);
        $sheet->getStyle('A' . $row . ':C' . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('D3D3D3');

        $sheet->getStyle('A4:C' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Services
        $row += 2;
        $serviceStartRow = $row;

        $sheet->setCellValue('A' . $serviceStartRow, 'Distribusi Layanan');
        $sheet->getStyle('A' . $serviceStartRow)->getFont()->setBold(true);
        $sheet->getStyle('A' . $serviceStartRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('4682B4');
        $sheet->getStyle('A' . $serviceStartRow)->getFont()->setColor(new Color('FFFFFF'));

        $sheet->setCellValue('A' . ($serviceStartRow + 1), 'Jenis Layanan');
        $sheet->setCellValue('B' . ($serviceStartRow + 1), 'Jumlah');
        $sheet->setCellValue('C' . ($serviceStartRow + 1), 'Persentase');
        $sheet->getStyle('A' . ($serviceStartRow + 1) . ':C' . ($serviceStartRow + 1))->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('D3D3D3');
        $sheet->getStyle('A' . ($serviceStartRow + 1) . ':C' . ($serviceStartRow + 1))->getFont()->setBold(true);

        $row = $serviceStartRow + 2;
        $totalServices = array_sum($this->serviceTypes);

        foreach ($this->serviceTypes as $service => $count) {
            $sheet->setCellValue('A' . $row, $service);
            $sheet->setCellValue('B' . $row, $count);
            $percentage = $totalServices > 0 ? ($count / $totalServices) * 100 : 0;
            $sheet->setCellValue('C' . $row, number_format($percentage, 2) . '%');
            $row++;
        }

        $lastServiceRow = $row - 1;

        $sheet->setCellValue('A' . $row, 'TOTAL');
        $sheet->setCellValue('B' . $row, $totalServices);
        $sheet->setCellValue('C' . $row, '100.00%');
        $sheet->getStyle('A' . $row . ':C' . $row)->getFont()->setBold(true);
        $sheet->getStyle('A' . $row . ':C' . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('D3D3D3');

        $sheet->getStyle('A' . ($serviceStartRow + 1) . ':C' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        foreach (range('A', 'C') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Problem Distribution Chart
                $totalProblems = array_sum($this->problemTypes);
                $row = 5;
                $chartData = [];
                foreach ($this->problemTypes as $problem => $count) {
                    $sheet->setCellValue('E' . $row, $problem);
                    $sheet->setCellValue('F' . $row, $count);
                    $chartData[] = [$problem, $count];
                    $row++;
                }

                $labels = new DataSeriesValues(
                    DataSeriesValues::DATASERIES_TYPE_STRING,
                    'Analisis Masalah!E5:E' . ($row - 1),
                    null,
                    count($chartData)
                );

                $values = new DataSeriesValues(
                    DataSeriesValues::DATASERIES_TYPE_NUMBER,
                    'Analisis Masalah!F5:F' . ($row - 1),
                    null,
                    count($chartData)
                );

                $series = new DataSeries(
                    DataSeries::TYPE_BARCHART,
                    DataSeries::GROUPING_STANDARD,
                    range(0, 0),
                    [$labels],
                    [],
                    [$values]
                );

                $plotArea = new PlotArea(null, [$series]);
                $legend = new Legend(Legend::POSITION_RIGHT, null, false);
                $title = new Title('Top Problems Distribution');

                $chart = new Chart(
                    'ProblemChart',
                    $title,
                    $legend,
                    $plotArea
                );

                $chart->setTopLeftPosition('E3');
                $chart->setBottomRightPosition('J15');
                $sheet->addChart($chart);
            },
        ];
    }
}