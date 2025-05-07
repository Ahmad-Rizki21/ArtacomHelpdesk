<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use App\Exports\CustomersExport;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Customer;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('export')
                ->label('Export to Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function () {
                    $customers = Customer::all(); // Fetch all customers
                    return Excel::download(new CustomersExport($customers), 'customers_' . now()->format('Y-m-d_H-i-s') . '.xlsx');
                })
                ->requiresConfirmation()
                ->modalHeading('Export Customer Data')
                ->modalDescription('Are you sure you want to export all customer data to Excel?')
                ->modalSubmitActionLabel('Yes, export'),
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