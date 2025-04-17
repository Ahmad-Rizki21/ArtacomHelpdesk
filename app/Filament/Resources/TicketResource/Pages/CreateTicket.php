<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use App\Models\TicketAction;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;

class CreateTicket extends CreateRecord
{
    protected static string $resource = TicketResource::class;

    // Modifikasi beforeCreate untuk memastikan ticket_number dan created_by diisi
    protected function beforeCreate(): void
    {
        // Set created_by ke user saat ini
        $this->data['created_by'] = Auth::id();
        
        // Buat ticket_number jika belum ada
        if (empty($this->data['ticket_number'])) {
            $this->data['ticket_number'] = 'TFTTH-' . strtoupper(uniqid());
        }
        
        // Set tanggal laporan ke saat ini jika belum diisi
        if (empty($this->data['report_date'])) {
            $this->data['report_date'] = now();
        }
    }

    // Setelah ticket dibuat, tambahkan riwayat tindakan pertama
    protected function afterCreate(): void
    {
        // Buat TicketAction "Open Clock" pertama
        TicketAction::create([
            'ticket_id' => $this->record->id,
            'user_id' => Auth::id(),
            'action_type' => 'Open Clock', 
            'description' => 'Ticket baru dibuat',
            'status' => 'OPEN'
        ]);
    }

    // Kustomisasi notifikasi ketika ticket berhasil dibuat
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('🎫 Ticket Berhasil Dibuat!')
            ->body('Ticket baru telah dibuat dengan ID: ' . $this->record->ticket_number)
            ->actions([
                Action::make('lihatTicket')
                    ->label('Lihat Ticket')
                    ->url($this->getResource()::getUrl('view', ['record' => $this->record]))
                    ->button(),
            ])
            ->send();
    }

    // Redirect ke halaman view ticket setelah berhasil dibuat
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }
}