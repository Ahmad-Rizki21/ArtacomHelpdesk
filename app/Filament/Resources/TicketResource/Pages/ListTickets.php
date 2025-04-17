<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use App\Exports\TicketsExport;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Ticket;

class ListTickets extends ListRecords
{
    protected static string $resource = TicketResource::class;

    public bool $hasActiveFilters = false;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('create')
                ->label('Buat Tiket')
                ->url(static::getResource()::getUrl('create'))
                ->icon('heroicon-o-plus'),
                
            Action::make('export')
                ->label(fn () => $this->hasActiveFilters ? 'Ekspor Data' : 'Ekspor Semua Data')
                ->color('success')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function () {
                    return $this->hasActiveFilters 
                        ? $this->exportFilteredData() 
                        : $this->exportAllData();
                })
        ];
    }

    public function exportFilteredData()
    {
        // Ambil query dari tabel dengan filter yang diterapkan
        $query = $this->getFilteredQuery();

        // Cek apakah ada data yang difilter
        if ($query->count() === 0) {
            Notification::make()
                ->title('Tidak ada data yang difilter.')
                ->warning()
                ->send();
            return null;
        }

        // Ambil data hasil filter
        $tickets = $query->get();

        // Buat nama file berdasarkan filter
        $fileName = "laporan_tickets";
        
        // Ambil nilai filter dengan cara yang lebih aman
        $periodFilter = $this->tableFilters['created_at_period'] ?? [];
        $year = $periodFilter['year'] ?? null;
        $month = $periodFilter['month'] ?? null;
        
        // Filter status - ambil nilai dengan aman
        $statusFilter = $this->tableFilters['status'] ?? [];
        $status = $statusFilter['value'] ?? null;
        
        // Filter problem - ambil nilai dengan aman
        $problemFilter = $this->tableFilters['problem_summary'] ?? [];
        $problemType = is_array($problemFilter) ? ($problemFilter['value'] ?? null) : $problemFilter;
        
        // Tambahkan ke nama file jika ada nilai
        if ($year) $fileName .= "_{$year}";
        if ($month) $fileName .= "_{$month}";
        if ($status) $fileName .= "_{$status}";
        if ($problemType) $fileName .= "_{$problemType}";
        $fileName .= ".xlsx";

        // Ekspor data ke Excel
        return Excel::download(new TicketsExport($tickets), $fileName);
    }

    public function exportAllData()
    {
        // Ambil semua data tanpa filter
        $tickets = Ticket::all();

        // Ekspor semua data ke Excel
        return Excel::download(new TicketsExport($tickets), 'laporan_tickets_all.xlsx');
    }

    public function mount(): void
    {
        parent::mount();
        $this->updateFiltersStatus();
    }

    public function updatedTableFilters(): void
    {
        parent::updatedTableFilters();
        $this->updateFiltersStatus();
    }

    protected function updateFiltersStatus(): void
    {
        // Periksa apakah ada filter yang telah dipilih
        $this->hasActiveFilters = false;
        
        if (isset($this->tableFilters['created_at_period'])) {
            $periodFilter = $this->tableFilters['created_at_period'];
            if (!empty($periodFilter['year']) || !empty($periodFilter['month'])) {
                $this->hasActiveFilters = true;
            }
        }
        
        if (isset($this->tableFilters['status']) && !empty($this->tableFilters['status']['value'])) {
            $this->hasActiveFilters = true;
        }
        
        if (isset($this->tableFilters['problem_summary'])) {
            $problemFilter = $this->tableFilters['problem_summary'];
            if (is_array($problemFilter) && !empty($problemFilter['value'])) {
                $this->hasActiveFilters = true;
            } elseif (!is_array($problemFilter) && !empty($problemFilter)) {
                $this->hasActiveFilters = true;
            }
        }
    }

    /**
     * Ambil query yang sudah difilter dari tabel.
     *
     * @return Builder
     */
    protected function getFilteredQuery(): Builder
    {
        $query = $this->getTableQuery();

        // Terapkan filter tambahan jika ada
        
        // Filter berdasarkan status jika ada
        if (isset($this->tableFilters['status']) && !empty($this->tableFilters['status']['value'])) {
            $status = $this->tableFilters['status']['value'];
            $query->where('status', $status);
        }
        
        // Filter berdasarkan problem summary jika ada
        if (isset($this->tableFilters['problem_summary'])) {
            $problemFilter = $this->tableFilters['problem_summary'];
            if (is_array($problemFilter) && !empty($problemFilter['value'])) {
                $query->where('problem_summary', $problemFilter['value']);
            } elseif (!is_array($problemFilter) && !empty($problemFilter)) {
                $query->where('problem_summary', $problemFilter);
            }
        }
        
        // Filter berdasarkan periode
        if (isset($this->tableFilters['created_at_period'])) {
            $periodFilter = $this->tableFilters['created_at_period'];
            
            if (!empty($periodFilter['year'])) {
                $query->whereYear('created_at', $periodFilter['year']);
            }
            
            if (!empty($periodFilter['month'])) {
                $query->whereMonth('created_at', $periodFilter['month']);
            }
        }
        
        return $query;
    }
}