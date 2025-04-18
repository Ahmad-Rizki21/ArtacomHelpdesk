<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\{FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithColumnFormatting, WithStyles, WithTitle};
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use App\Models\TicketBackbone;

class TicketBackboneExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithColumnFormatting, WithStyles, WithTitle
{
    protected Collection $tickets;

    public function __construct($tickets = null)
    {
        if ($tickets === null) {
            // Ambil data dari session (data yang tampil di Filament)
            $this->tickets = collect(session('filtered_tickets', []));
        } else {
            $this->tickets = $tickets instanceof Collection ? $tickets : collect($tickets);
        }
    }

    public function collection(): Collection
    {
        return $this->tickets;
    }

    public function headings(): array
    {
        return [
            ['LAPORAN TICKET BACKBONE'],
            ['Dicetak pada: ' . Carbon::now()->translatedFormat('d F Y H:i:s')],
            [''],
            // Tambahkan baris untuk ringkasan status
            ['Ringkasan Status:', '', '', ''],
            ['OPEN:', $this->getStatusCount('OPEN'), '', ''],
            ['PENDING:', $this->getStatusCount('PENDING'), '', ''],
            ['CLOSED:', $this->getStatusCount('CLOSED'), '', ''],
            ['TOTAL:', $this->tickets->count(), '', ''],
            [''],
            [
                'No Ticket',
                'CID',
                'Jenis ISP',
                'Lokasi',
                'Extra Description',
                'Action Description',
                'Status',
                'Open Date',
                'Pending Date',
                'Closed Date',
                'Created By',
                'Created At',
                'Updated At'
            ]
        ];
    }

    private function getStatusCount($status)
    {
        if ($this->tickets->first() instanceof TicketBackbone) {
            return $this->tickets->where('status', $status)->count();
        }
        
        return $this->tickets->where('status', $status)->count();
    }

    public function map($ticket): array
    {
        if ($ticket instanceof TicketBackbone) {
            return [
                $ticket->no_ticket,
                $ticket->cidRelation ? $ticket->cidRelation->cid : 'N/A',
                $ticket->jenis_isp,
                $ticket->lokasiRelation ? $ticket->lokasiRelation->lokasi : 'N/A',
                $ticket->extra_description,
                $ticket->action_description ?? 'Belum ada Penanganan',
                $ticket->status,
                optional($ticket->open_date)->format('d/m/Y H:i:s'),
                optional($ticket->pending_date)->format('d/m/Y H:i:s') ?? 'Belum ada Pending',
                optional($ticket->closed_date)->format('d/m/Y H:i:s') ?? 'Belum ada Ticket Closed',
                $ticket->creator ? $ticket->creator->name : 'Unknown',
                optional($ticket->created_at)->format('d/m/Y H:i:s'),
                optional($ticket->updated_at)->format('d/m/Y H:i:s'),
            ];
        } else {
            // For data from session
            return [
                $ticket['no_ticket'] ?? 'N/A',
                $ticket['cid'] ?? 'N/A',
                $ticket['jenis_isp'] ?? 'N/A',
                $ticket['lokasi'] ?? 'N/A',
                $ticket['extra_description'] ?? 'N/A',
                $ticket['action_description'] ?? 'Belum ada Penanganan',
                $ticket['status'] ?? 'N/A',
                $ticket['open_date'] ?? 'N/A',
                $ticket['pending_date'] ?? 'Belum ada Pending',
                $ticket['closed_date'] ?? 'Belum ada Ticket Closed',
                $ticket['created_by'] ?? 'Unknown',
                $ticket['created_at'] ? Carbon::parse($ticket['created_at'])->format('d/m/Y H:i:s') : 'N/A',
                $ticket['updated_at'] ? Carbon::parse($ticket['updated_at'])->format('d/m/Y H:i:s') : 'N/A',
            ];
        }
    }

    public function columnFormats(): array
    {
        return [
            'G' => NumberFormat::FORMAT_DATE_DATETIME,
            'H' => NumberFormat::FORMAT_DATE_DATETIME,
            'I' => NumberFormat::FORMAT_DATE_DATETIME,
            'K' => NumberFormat::FORMAT_DATE_DATETIME,
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
        $sheet->getColumnDimension('F')->setWidth(30);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->getColumnDimension('K')->setAutoSize(true);
        $sheet->getColumnDimension('L')->setAutoSize(true);
        $sheet->getColumnDimension('M')->setAutoSize(true);
        
        // Tambahkan border pada tabel
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle("A10:M{$lastRow}")->getBorders()->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        
        // Menyetel style untuk wrap text pada kolom deskripsi
        $sheet->getStyle('E')->getAlignment()->setWrapText(true);
        $sheet->getStyle('F')->getAlignment()->setWrapText(true);
        
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
        return 'Data Ticket Backbone';
    }
}