<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Database\QueryException;
use App\Models\Customer;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Cek apakah customer_id sudah ada di database
        if (Customer::where('customer_id', $data['customer_id'])->exists()) {
            Notification::make()
                ->danger()
                ->title('Duplikasi Data')
                ->body('Data yang Anda masukkan sudah terdaftar di dalam Database.')
                ->send();

            // Batalkan proses pembuatan record
            $this->halt();
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->color('success') 
            ->title('Pelanggan Berhasil Ditambahkan')
            ->body('Data pelanggan baru telah berhasil disimpan.');
    }

    
}
