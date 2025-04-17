<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;


class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getDeletedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->color('danger') 
            ->title('User deleted')
            ->body('The user has been deleted successfully.');
    }
    
}
