<?php

namespace App\Filament\Resources\TicketBackboneResource\Pages;

use App\Filament\Resources\TicketBackboneResource;
use App\Exports\TicketBackboneExport;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use App\Models\TicketBackbone;
use Filament\Support\Enums\IconPosition;
use Filament\Resources\Components\Tab;

class ListTicketBackbones extends ListRecords
{
    protected static string $resource = TicketBackboneResource::class;

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
                ->badge(fn () => TicketBackbone::whereIn('status', ['OPEN', 'PENDING'])->count())
                ->icon('heroicon-o-bell-alert')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status', ['OPEN', 'PENDING'])),
                
            'Open' => Tab::make('Tiket Terbuka')
                ->badge(fn () => TicketBackbone::where('status', 'OPEN')->count())
                ->badgeColor('danger')
                ->icon('heroicon-o-exclamation-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'OPEN')),
                
            'Pending' => Tab::make('Tiket Pending')
                ->badge(fn () => TicketBackbone::where('status', 'PENDING')->count())
                ->badgeColor('warning')
                ->icon('heroicon-o-clock')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'PENDING')),
                
            'Closed' => Tab::make('Tiket Selesai')
                ->badge(fn () => TicketBackbone::where('status', 'CLOSED')->count())
                ->badgeColor('success')
                ->icon('heroicon-o-check-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'CLOSED')),
                
            'All' => Tab::make('Semua Tiket')
                ->badge(fn () => TicketBackbone::count())
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
        $fileName = "laporan_tickets_backbone";
        
        // Ambil nilai filter dengan cara yang lebih aman
        $statusFilter = $this->tableFilters['status'] ?? [];
        $status = $statusFilter['value'] ?? null;
        
        // Tambahkan ke nama file jika ada nilai
        if ($status) $fileName .= "_{$status}";
        $fileName .= ".xlsx";

        // Ekspor data ke Excel
        return Excel::download(new TicketBackboneExport($tickets), $fileName);
    }

    public function exportAllData()
    {
        // Ambil semua data tanpa filter
        $tickets = TicketBackbone::all();

        // Ekspor semua data ke Excel
        return Excel::download(new TicketBackboneExport($tickets), 'laporan_tickets_backbone_all.xlsx');
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
        
        if (isset($this->tableFilters['status']) && !empty($this->tableFilters['status']['value'])) {
            $this->hasActiveFilters = true;
        }
    }

    /**
     * Ambil query yang sudah difilter dari tabel.
     *
     * @return Builder
     */
    protected function getFilteredQuery(): Builder
    {
        // Gunakan $this->getTable()->getQuery() sebagai pengganti getTableQuery() yang deprecated
        $query = $this->getTable()->getQuery();

        // Terapkan filter tambahan jika ada
        
        // Filter berdasarkan status jika ada
        if (isset($this->tableFilters['status']) && !empty($this->tableFilters['status']['value'])) {
            $status = $this->tableFilters['status']['value'];
            $query->where('status', $status);
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