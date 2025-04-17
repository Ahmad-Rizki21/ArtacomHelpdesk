<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\{FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithColumnFormatting, WithStyles, WithTitle};
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class TicketsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithColumnFormatting, WithStyles, WithTitle
{
    protected Collection $tickets;

    public function __construct(Collection $tickets)
    {
        $this->tickets = $tickets;
    }

    public function collection(): Collection
    {
        return $this->tickets;
    }

    public function headings(): array
    {
        return [
            ['LAPORAN TICKET HELPDESK'],
            ['Dicetak pada: ' . Carbon::now()->translatedFormat('d F Y H:i:s')],
            [''],
            // Tambahkan baris untuk ringkasan status
            ['Ringkasan Status:', '', '', ''],
            ['OPEN:', $this->tickets->where('status', 'OPEN')->count(), '', ''],
            ['PENDING:', $this->tickets->where('status', 'PENDING')->count(), '', ''],
            ['CLOSED:', $this->tickets->where('status', 'CLOSED')->count(), '', ''],
            ['TOTAL:', $this->tickets->count(), '', ''],
            [''],
            [
                'No Ticket',
                'Layanan',
                'Pelanggan',
                'Problem Summary',
                'Extra Description',
                'Report Date',
                'Status',
                'Pending Clock',
                'Closed Date',
                'Action Description',
                'SLA',
                'Created At',
                'Created By',
            ]
        ];
    }

    public function map($ticket): array
    {
        return [
            $ticket->ticket_number,
            $ticket->service,
            $ticket->customer->composite_data ?? '(Tidak Ada)',
            $ticket->problem_summary,
            $ticket->extra_description,
            optional($ticket->report_date)->format('d/m/Y H:i:s'),
            $ticket->status,
            $ticket->pending_clock,
            optional($ticket->closed_date)->format('d/m/Y H:i:s'),
            $ticket->action_description,
            $ticket->sla->name ?? '(Tidak Ada)',
            optional($ticket->created_at)->format('d/m/Y H:i:s'),
            $ticket->creator->name ?? '(Tidak Ada)',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_DATE_DATETIME,
            'I' => NumberFormat::FORMAT_DATE_DATETIME,
            'L' => NumberFormat::FORMAT_DATE_DATETIME,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Judul laporan
        $sheet->mergeCells('A1:M1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        
        // Tanggal cetak
        $sheet->mergeCells('A2:M2');
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
        $sheet->getStyle('B5:B8')->getAlignment()->setHorizontal('center');
        
        // Header tabel data
        $sheet->getStyle('A10:M10')->getFont()->setBold(true);
        $sheet->getStyle('A10:M10')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('D9D9D9');
        
        // Atur lebar kolom
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setWidth(30);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setWidth(30);
        $sheet->getColumnDimension('K')->setAutoSize(true);
        $sheet->getColumnDimension('L')->setAutoSize(true);
        $sheet->getColumnDimension('M')->setAutoSize(true);
        
        // Tambahkan border pada tabel
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle("A10:M{$lastRow}")->getBorders()->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        
        // Menyetel style untuk wrap text pada kolom deskripsi
        $sheet->getStyle('E')->getAlignment()->setWrapText(true);
        $sheet->getStyle('J')->getAlignment()->setWrapText(true);
        
        // Menambahkan conditional formatting untuk kolom status
        $lastDataRow = $sheet->getHighestRow();
        for ($row = 11; $row <= $lastDataRow; $row++) {
            $statusValue = $sheet->getCell("G{$row}")->getValue();
            
            if ($statusValue === 'OPEN') {
                $sheet->getStyle("G{$row}")->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('FFCCCC'); // Light red
            } elseif ($statusValue === 'PENDING') {
                $sheet->getStyle("G{$row}")->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('FFE699'); // Light yellow
            } elseif ($statusValue === 'CLOSED') {
                $sheet->getStyle("G{$row}")->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('C6EFCE'); // Light green
            }
        }
        
        return [];
    }
    
    public function title(): string
    {
        return 'Data Ticket';
    }
}