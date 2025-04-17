<?php

namespace App\Filament\Resources\BackboneCIDResource\Pages;

use App\Filament\Resources\BackboneCIDResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use App\Models\BackboneCID;

class CreateBackboneCID extends CreateRecord
{
    protected static string $resource = BackboneCIDResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Cek apakah Backbone CID dengan data yang sama sudah ada
        if (BackboneCID::where('cid', $data['cid'])->exists()) {
            Notification::make()
                ->danger()
                ->title('Duplikasi Data')
                ->body('CID Backbone yang sama sudah terdaftar di dalam Database.')
                ->send();

            // Batalkan proses penyimpanan data
            $this->halt();
        }

        return $data;
    }

}

