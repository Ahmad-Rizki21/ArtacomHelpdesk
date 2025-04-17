<?php

namespace App\Filament\Resources\BackboneCIDResource\Pages;

use App\Filament\Resources\BackboneCIDResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBackboneCID extends EditRecord
{
    protected static string $resource = BackboneCIDResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->requiresConfirmation() // Konfirmasi sebelum hapus
                ->modalHeading('Hapus Backbone CID?') // Custom modal
                ->modalDescription('Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.') 
                ->successNotificationTitle('Data berhasil dihapus'), // Notifikasi sukses
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Bisa digunakan untuk mengubah data sebelum ditampilkan di form edit
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Bisa digunakan untuk validasi tambahan sebelum data disimpan
        return $data;
    }

    protected function afterSave(): void
    {
        // Bisa digunakan untuk logging atau event setelah data disimpan
        session()->flash('success', 'Data Backbone CID berhasil diperbarui.');
    }

}
