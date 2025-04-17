<?php

namespace App\Filament\Resources\TicketBackboneResource\Pages;

use App\Filament\Resources\TicketBackboneResource;
use Filament\Actions;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TicketBackboneExport;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables;

class ListTicketBackbones extends ListRecords
{
    protected static string $resource = TicketBackboneResource::class;

    /**
     * Menambahkan tombol di header (Create dan Export)
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('export')
                ->label('Export to Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(fn () => Excel::download(new TicketBackboneExport, 'ticket-backbone.xlsx')),
        ];
    }

    /**
     * Menambahkan aksi pada setiap baris tabel (Edit & Delete)
     */
    protected function getTableActions(): array
    {
        return [
            Tables\Actions\EditAction::make(),
    
            Tables\Actions\DeleteAction::make()
                ->icon('heroicon-o-trash')
                ->label('Hapus')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Hapus Ticket Backbone')
                ->modalDescription('Apakah Anda yakin ingin menghapus ticket ini?')
                ->modalButton('Ya, Hapus')
                ->successNotificationTitle('🚀 Ticket berhasil dihapus!')
                ->after(function ($record) {
                    Notification::make()
                        ->title('🗑️ Ticket Dihapus!')
                        ->body("Ticket dengan **ID #{$record->id}** telah dihapus oleh **" . \Illuminate\Support\Facades\Auth::user()->name . "**.")
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->send();
                }),
        ];
    }
    
    
}
