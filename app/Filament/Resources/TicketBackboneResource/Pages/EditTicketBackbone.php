<?php

namespace App\Filament\Resources\TicketBackboneResource\Pages;

use App\Filament\Resources\TicketBackboneResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;

class EditTicketBackbone extends EditRecord
{
    protected static string $resource = TicketBackboneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->successNotificationTitle(null) // Nonaktifkan notifikasi default
                ->after(function ($record) {
                    $this->showDeleteNotification($record);
                    $this->redirect(TicketBackboneResource::getUrl('index'));
                }),
        ];
    }

   


    protected function afterSave(): void
{
    // Tampilkan notifikasi interaktif
    Notification::make()
        ->title('Ticket Berhasil Diperbarui')
        ->body('Data ticket backbone telah diperbarui dengan sukses!')
        ->success() // Tipe sukses (bisa diganti dengan ->info(), ->warning(), atau ->danger())
        ->persistent() // Tetap tampil sampai ditutup user
        ->send();

    // Redirect ke halaman index
    $this->redirect(TicketBackboneResource::getUrl('index'));
}

protected function showDeleteNotification($record): void
{
    Notification::make()
        ->title('❌ Ticket Berhasil Dihapus!')
        ->body("Ticket dengan ID **{$record->id}** dan nama **{$record->name}** telah dihapus dari sistem.")
        ->icon('heroicon-o-trash') // Tambahkan ikon trash
        ->color('danger') // Warna merah untuk penghapusan
        ->persistent() // Notifikasi tetap ada sampai ditutup user
        ->send();
}

}
