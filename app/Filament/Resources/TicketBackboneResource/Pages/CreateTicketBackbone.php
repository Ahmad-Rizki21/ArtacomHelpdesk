<?php

namespace App\Filament\Resources\TicketBackboneResource\Pages;

use App\Filament\Resources\TicketBackboneResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateTicketBackbone extends CreateRecord
{
    protected static string $resource = TicketBackboneResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }


    

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->color('success') 
            ->title('Berhasil menambahkan tiket')
            ->body('Ticket berhasil di buat, Terimakasih.');
    }

}


