<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class CustomersExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    protected Collection $customers;

    public function __construct(Collection $customers)
    {
        $this->customers = $customers;
    }

    public function collection(): Collection
    {
        return $this->customers;
    }

    public function headings(): array
    {
        return [
            ['LAPORAN DATA PELANGGAN'],
            ['Dicetak pada: ' . Carbon::now()->translatedFormat('d F Y H:i:s')],
            [''],
            ['No', 'Nama Pelanggan', 'Customer ID', 'Jenis Layanan', 'IP Address', 'Tanggal Registrasi'],
        ];
    }

    public function map($customer): array
    {
        return [
            $customer->no,
            $customer->name,
            $customer->customer_id,
            $customer->service,
            $customer->ip_address,
            $customer->created_at ? $customer->created_at->format('d/m/Y') : '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Merge and style the title
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Style the printed date
        $sheet->mergeCells('A2:F2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Style the header row
        $sheet->getStyle('A4:F4')->getFont()->setBold(true);
        $sheet->getStyle('A4:F4')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('D9D9D9');

        // Center align specific columns
        $sheet->getStyle('A:E')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Apply borders to the data table
        $lastDataRow = $sheet->getHighestRow();
        $sheet->getStyle("A4:F{$lastDataRow}")->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        // Style service type cells
        for ($row = 5; $row <= $lastDataRow; $row++) {
            $serviceValue = $sheet->getCell("D{$row}")->getValue();
            
            if ($serviceValue === 'ISP-JELANTIK') {
                $sheet->getStyle("D{$row}")->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('C6EFCE');
            } elseif ($serviceValue === 'ISP-JAKINET') {
                $sheet->getStyle("D{$row}")->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('FFE699');
            }
        }

        return [];
    }

    public function title(): string
    {
        return 'Data Pelanggan';
    }
}