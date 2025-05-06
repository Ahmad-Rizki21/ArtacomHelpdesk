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
use Filament\Support\Enums\IconPosition;
use Filament\Resources\Components\Tab;
use Carbon\Carbon;

class ListTickets extends ListRecords
{
    protected static string $resource = TicketResource::class;

    public bool $hasActiveFilters = false;

    // Default tab untuk halaman
    public function getDefaultActiveTab(): ?string
    {
        return 'Active';
    }

    public function getTabs(): array
    {
        return [
            'Active' => Tab::make('Tiket Aktif')
                ->badge(fn () => Ticket::whereIn('status', ['OPEN', 'PENDING'])->count())
                ->icon('heroicon-o-bell-alert')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status', ['OPEN', 'PENDING'])),
                
            'Open' => Tab::make('Tiket Terbuka')
                ->badge(fn () => Ticket::where('status', 'OPEN')->count())
                ->badgeColor('danger')
                ->icon('heroicon-o-exclamation-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'OPEN')),
                
            'Pending' => Tab::make('Tiket Pending')
                ->badge(fn () => Ticket::where('status', 'PENDING')->count())
                ->badgeColor('warning')
                ->icon('heroicon-o-clock')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'PENDING')),
                
            'Closed' => Tab::make('Tiket Selesai')
                ->badge(fn () => Ticket::where('status', 'CLOSED')->count())
                ->badgeColor('success')
                ->icon('heroicon-o-check-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'CLOSED')),

            'SLA' => Tab::make('Laporan SLA')
            ->badge(fn () => Ticket::where('service', 'FTTH')->count())
            ->badgeColor('info')
            ->icon('heroicon-o-chart-bar')
            ->modifyQueryUsing(fn (Builder $query) => $query->where('service', 'FTTH')),
                
            'All' => Tab::make('Semua Tiket')
                ->badge(fn () => Ticket::count())
                ->icon('heroicon-o-ticket'),
        ];
    }
    

    protected function getHeaderActions(): array
    {
        return [
            Action::make('create')
                ->label('Buat Tiket')
                ->url(static::getResource()::getUrl('create'))
                ->icon('heroicon-o-plus')
                ->iconPosition(IconPosition::Before)
                ->color('primary'),
                
            Action::make('export')
                ->label(fn () => $this->hasActiveFilters ? 'Ekspor Data Terfilter' : 'Ekspor Semua Data')
                ->color('success')
                ->icon('heroicon-o-arrow-down-tray')
                ->iconPosition(IconPosition::Before)
                ->action(function () {
                    return $this->hasActiveFilters 
                        ? $this->exportFilteredData() 
                        : $this->exportAllData();
                })
        ];
    }

    // public function exportFilteredData()
    // {
    //     // Ambil query dari tabel dengan filter yang diterapkan
    //     $query = $this->getFilteredQuery();

    //     // Cek apakah ada data yang difilter
    //     if ($query->count() === 0) {
    //         Notification::make()
    //             ->title('Tidak ada data yang difilter.')
    //             ->warning()
    //             ->send();
    //         return null;
    //     }

    //     // Ambil data hasil filter
    //     $tickets = $query->get();

    //     // Buat nama file berdasarkan filter
    //     $fileName = "laporan_tickets";
        
    //     // Ambil nilai filter dengan cara yang lebih aman
    //     $periodFilter = $this->tableFilters['created_at_period'] ?? [];
    //     $year = $periodFilter['year'] ?? null;
    //     $month = $periodFilter['month'] ?? null;
        
    //     // Filter status - ambil nilai dengan aman
    //     $statusFilter = $this->tableFilters['status'] ?? [];
    //     $status = $statusFilter['value'] ?? null;
        
    //     // Filter problem - ambil nilai dengan aman
    //     $problemFilter = $this->tableFilters['problem_summary'] ?? [];
    //     $problemType = is_array($problemFilter) ? ($problemFilter['value'] ?? null) : $problemFilter;
        
    //     // Tambahkan ke nama file jika ada nilai
    //     if ($year) $fileName .= "_{$year}";
    //     if ($month) $fileName .= "_{$month}";
    //     if ($status) $fileName .= "_{$status}";
    //     if ($problemType) $fileName .= "_{$problemType}";
    //     $fileName .= ".xlsx";

    //     // Ekspor data ke Excel
    //     return Excel::download(new TicketsExport($tickets), $fileName);
    // }
    public function exportFilteredData()
    {
        $query = $this->getFilteredQuery();

        if ($query->count() === 0) {
            Notification::make()
                ->title('Tidak ada data yang difilter.')
                ->warning()
                ->send();
            return null;
        }

        $tickets = $query->get();

        // Create filename based on filters
        $fileName = "laporan_tickets";
        
        // Get date range filter values
        $dateFilter = $this->tableFilters['created_at'] ?? [];
        $from = $dateFilter['created_from'] ?? null;
        $until = $dateFilter['created_until'] ?? null;
        
        if ($from) $fileName .= "_from_" . Carbon::parse($from)->format('Y-m-d');
        if ($until) $fileName .= "_to_" . Carbon::parse($until)->format('Y-m-d');
        
        // Add other filters to filename
        $statusFilter = $this->tableFilters['status'] ?? [];
        $status = $statusFilter['value'] ?? null;
        
        $problemFilter = $this->tableFilters['problem_summary'] ?? [];
        $problemType = is_array($problemFilter) ? ($problemFilter['value'] ?? null) : $problemFilter;
        
        if ($status) $fileName .= "_{$status}";
        if ($problemType) $fileName .= "_{$problemType}";
        $fileName .= ".xlsx";

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

    // protected function updateFiltersStatus(): void
    // {
    //     // Periksa apakah ada filter yang telah dipilih
    //     $this->hasActiveFilters = false;
        
    //     if (isset($this->tableFilters['created_at_period'])) {
    //         $periodFilter = $this->tableFilters['created_at_period'];
    //         if (!empty($periodFilter['year']) || !empty($periodFilter['month'])) {
    //             $this->hasActiveFilters = true;
    //         }
    //     }
        
    //     if (isset($this->tableFilters['status']) && !empty($this->tableFilters['status']['value'])) {
    //         $this->hasActiveFilters = true;
    //     }
        
    //     if (isset($this->tableFilters['problem_summary'])) {
    //         $problemFilter = $this->tableFilters['problem_summary'];
    //         if (is_array($problemFilter) && !empty($problemFilter['value'])) {
    //             $this->hasActiveFilters = true;
    //         } elseif (!is_array($problemFilter) && !empty($problemFilter)) {
    //             $this->hasActiveFilters = true;
    //         }
    //     }
    // }

    protected function updateFiltersStatus(): void
{
    $this->hasActiveFilters = false;
    
    // Check for periode date filter
    if (isset($this->tableFilters['periode'])) {
        $periodeFilter = $this->tableFilters['periode'];
        if (!empty($periodeFilter['start_date']) || !empty($periodeFilter['end_date'])) {
            $this->hasActiveFilters = true;
        }
    }
    
    // Check for other filters (existing code)
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
    // protected function getFilteredQuery(): Builder
    // {
    //     // Gunakan $this->getTable()->getQuery() sebagai pengganti getTableQuery() yang deprecated
    //     $query = $this->getTable()->getQuery();

    //     // Terapkan filter tambahan jika ada
        
    //     // Filter berdasarkan status jika ada
    //     if (isset($this->tableFilters['status']) && !empty($this->tableFilters['status']['value'])) {
    //         $status = $this->tableFilters['status']['value'];
    //         $query->where('status', $status);
    //     }
        
    //     // Filter berdasarkan problem summary jika ada
    //     if (isset($this->tableFilters['problem_summary'])) {
    //         $problemFilter = $this->tableFilters['problem_summary'];
    //         if (is_array($problemFilter) && !empty($problemFilter['value'])) {
    //             $query->where('problem_summary', $problemFilter['value']);
    //         } elseif (!is_array($problemFilter) && !empty($problemFilter)) {
    //             $query->where('problem_summary', $problemFilter);
    //         }
    //     }
        
    //     // Filter berdasarkan periode
    //     if (isset($this->tableFilters['created_at_period'])) {
    //         $periodFilter = $this->tableFilters['created_at_period'];
            
    //         if (!empty($periodFilter['year'])) {
    //             $query->whereYear('created_at', $periodFilter['year']);
    //         }
            
    //         if (!empty($periodFilter['month'])) {
    //             $query->whereMonth('created_at', $periodFilter['month']);
    //         }
    //     }
        
    //     return $query;
    // }
    protected function getFilteredQuery(): Builder
{
    $query = $this->getTable()->getQuery();

    // Apply status filter
    if (isset($this->tableFilters['status']) && !empty($this->tableFilters['status']['value'])) {
        $status = $this->tableFilters['status']['value'];
        $query->where('status', $status);
    }
    
    // Apply problem summary filter
    if (isset($this->tableFilters['problem_summary'])) {
        $problemFilter = $this->tableFilters['problem_summary'];
        if (is_array($problemFilter) && !empty($problemFilter['value'])) {
            $query->where('problem_summary', $problemFilter['value']);
        } elseif (!is_array($problemFilter) && !empty($problemFilter)) {
            $query->where('problem_summary', $problemFilter);
        }
    }
    
    // Apply periode filter
    if (isset($this->tableFilters['periode'])) {
        $periodeFilter = $this->tableFilters['periode'];
        
        if (!empty($periodeFilter['start_date'])) {
            $query->whereDate('created_at', '>=', $periodeFilter['start_date']);
        }
        
        if (!empty($periodeFilter['end_date'])) {
            $query->whereDate('created_at', '<=', $periodeFilter['end_date']);
        }
    }
    
    return $query;
}

    protected function getTableFiltersFormColumns(): int
    {
        return 3; // Tampilkan 3 kolom filter untuk layout yang lebih rapi
    }

    protected function getTableFiltersFormWidth(): string
    {
        return '4xl'; // Perlebar form filter untuk tampilan yang lebih baik
    }

    // Kustomisasi tampilan kosong saat tidak ada data
    protected function getTableEmptyStateIcon(): ?string
    {
        return 'heroicon-o-ticket';
    }

    protected function getTableEmptyStateHeading(): ?string
    {
        $activeTab = $this->getActiveTab();
        
        if ($activeTab === 'Open') {
            return 'Tidak ada tiket terbuka';
        } elseif ($activeTab === 'Pending') {
            return 'Tidak ada tiket pending';
        } elseif ($activeTab === 'Closed') {
            return 'Tidak ada tiket yang selesai';
        } else {
            return 'Tidak ada tiket ditemukan';
        }
    }

    protected function getTableEmptyStateDescription(): ?string
    {
        $activeTab = $this->getActiveTab();
        
        if ($activeTab === 'Open') {
            return 'Semua tiket sudah ditangani dengan baik.';
        } elseif ($activeTab === 'Pending') {
            return 'Tidak ada tiket yang sedang pending saat ini.';
        } elseif ($activeTab === 'Closed') {
            return 'Belum ada tiket yang diselesaikan.';
        } else {
            return 'Tiket tidak ditemukan. Silakan coba filter lain atau buat tiket baru.';
        }
    }

    

    protected function getTableEmptyStateActions(): array
    {
        return [
            Action::make('create')
                ->label('Buat Tiket Baru')
                ->url(static::getResource()::getUrl('create'))
                ->icon('heroicon-o-plus')
                ->button(),
        ];
    }
}