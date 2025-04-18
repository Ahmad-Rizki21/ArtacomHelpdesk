<?php

namespace App\Filament\Resources\TicketBackboneResource\Pages;

use App\Filament\Resources\TicketBackboneResource;
use App\Models\TicketBackboneAction;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class CreateTicketBackbone extends CreateRecord
{
    protected static string $resource = TicketBackboneResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        // Create Open Clock action
        TicketBackboneAction::create([
            'ticket_backbone_id' => $this->record->id,
            'user_id' => Auth::id(),
            'action_type' => 'Open Clock',
            'description' => 'Ticket baru dibuat',
            'status' => 'OPEN'
        ]);
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