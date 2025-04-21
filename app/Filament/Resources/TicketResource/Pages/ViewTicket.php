<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Grid;
use Illuminate\Support\HtmlString;
use Filament\Notifications\Notification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use App\Models\TicketAction;

class ViewTicket extends ViewRecord
{
    protected static string $resource = TicketResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Ticket Information')
                    ->description('Details of the support ticket')
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('service')->label('Service'),
                            TextEntry::make('ticket_number')->label('Ticket Number'),
                        ]),
                        Grid::make(2)->schema([
                            TextEntry::make('customer.composite_data')->label('Customer'),
                            TextEntry::make('sla.name')
                                ->label('SLA')
                                ->badge()
                                ->color(fn ($state): string => match ($state) {
                                    'HIGH' => 'danger',
                                    'MEDIUM' => 'warning',
                                    'LOW' => 'primary',
                                    default => 'gray',
                                }),
                        ]),
                        TextEntry::make('problem_summary')
                            ->label('Problem Summary')
                            ->badge()
                            ->color('primary'),
                        TextEntry::make('extra_description')
                            ->label('Extra Description')
                            ->markdown(),
                    ]),

                Section::make('Ticket Status')
                    ->description('Current status and additional information')
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('report_date')
                                ->label('Report Date')
                                ->dateTime(),
                            TextEntry::make('status')
                                ->label('Status')
                                ->badge()
                                ->color(fn (string $state): string => match ($state) {
                                    'OPEN' => 'danger',
                                    'PENDING' => 'warning',
                                    'CLOSED' => 'success',
                                    default => 'gray',
                                }),
                        ]),
                        
                        // Added assigned technician display
                        TextEntry::make('assigned_to')
                            ->label('Assigned Technician')
                            ->placeholder('No technician assigned')
                            ->formatStateUsing(function ($state) {
                                if (empty($state)) {
                                    return new HtmlString('<span class="text-gray-500">No technician assigned</span>');
                                }
                                
                                $user = \App\Models\User::where('email', $state)->first();
                                
                                if ($user) {
                                    return new HtmlString('<span class="text-green-500 font-medium">' . e($user->name) . '</span>');
                                }
                                
                                return $state;
                            }),
                        
                        // Resolution/Action display
                        TextEntry::make('action_description')
                            ->label('Resolution / Action')
                            ->placeholder('Belum ada penanganan')
                            ->columnSpanFull()
                            ->visible(fn ($record) => !empty($record->action_description) || $record->status === 'CLOSED')
                            ->formatStateUsing(function ($state, $record) {
                                if (empty($state) && $record->status === 'CLOSED') {
                                    // Jika kosong tapi status CLOSED, cari dari action terbaru
                                    $completedAction = $record->actions()
                                        ->where('action_type', 'Completed')
                                        ->latest('created_at')
                                        ->first();
                                    
                                    if ($completedAction) {
                                        // Gunakan styling yang konsisten dengan tema gelap
                                        return new HtmlString('<div class="p-3 rounded border border-gray-600 bg-gray-800 text-white">' . e($completedAction->description) . '</div>');
                                    }
                                    
                                    return 'Belum ada penanganan';
                                }
                                
                                if (empty($state)) {
                                    return 'Belum ada penanganan';
                                }
                                
                                // Gunakan styling yang konsisten dengan tema gelap
                                return new HtmlString('<div class="p-3 rounded border border-gray-600 bg-gray-800 text-white">' . e($state) . '</div>');
                            }),
                            
                        TextEntry::make('evidance_path')
                            ->label('Evidence')
                            ->visible(fn ($record) => !empty($record->evidance_path))
                            ->columnSpanFull()
                            ->extraAttributes(['class' => 'text-center'])
                            ->formatStateUsing(function ($state) {
                                if (empty($state)) {
                                    return 'Tidak ada bukti';
                                }
                                return new HtmlString('<img src="'.asset('storage/'.$state).'" class="max-w-xs mx-auto" alt="Evidence" />');
                            }),
                        Grid::make(2)->schema([
                            TextEntry::make('pending_clock')
                                ->label('Pending Clock')
                                ->placeholder('No Pending Yet')
                                ->dateTime(),
                            TextEntry::make('closed_date')
                                ->label('Closed Date')
                                ->placeholder('Ticket Not Closed')
                                ->dateTime(),
                        ]),
                    ]),

                // PROGRESS ACTIONS (RIWAYAT TINDAKAN)
                Section::make('Progress Tindakan')
                    ->description('Riwayat tindakan yang dilakukan pada tiket')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('actions')
                            ->hiddenLabel()
                            ->schema([
                                TextEntry::make('action_type')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'Open Clock' => 'info',
                                        'Pending Clock' => 'warning',
                                        'Start Clock' => 'success',
                                        'Completed' => 'gray',
                                        default => 'secondary',
                                    }),
                                TextEntry::make('description')
                                    ->columnSpan(2)
                                    ->formatStateUsing(fn($state) =>
                                        new HtmlString('<pre style="font-family:monospace;font-size:14px;background:#222;color:#fff;border-radius:8px;padding:8px;">'.e($state).'</pre>')
                                    ),
                                TextEntry::make('created_at')
                                    ->dateTime()
                                    ->label('Waktu'),
                                TextEntry::make('user.name')
                                    ->label('Oleh')
                            ])
                            ->columns(4)
                            ->visible(fn ($record) => $record->actions()->count() > 0),
                        TextEntry::make('no_actions')
                            ->label(false)
                            ->state('Belum ada data progress.')
                            ->visible(fn ($record) => $record->actions()->count() === 0),
                    ]),

                Section::make('System Information')
                    ->description('Internal tracking details')
                    ->collapsed()
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('created_at')->label('Created At')->dateTime(),
                            TextEntry::make('updated_at')->label('Updated At')->dateTime(),
                        ]),
                        TextEntry::make('creator.name')->label('Created By'),
                    ]),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('export_pdf')
                ->label('Ekspor PDF')
                ->color('success')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function () {
                    $ticket = $this->record->load(['actions.user', 'customer', 'sla']);
                    $today = now()->format('d-m-Y H:i:s');
                    $company = 'FTTH JELANTIK HELPDESK';

                    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('tickets.pdf-template', [
                        'ticket' => $ticket,
                        'today' => $today,
                        'company' => $company,
                    ]);
                    $filename = 'TICKET-' . $ticket->ticket_number . '.pdf';

                    return response()->streamDownload(
                        fn () => print($pdf->stream()),
                        $filename
                    );
                }),

            \Filament\Actions\EditAction::make()->label('Edit'),
            \Filament\Actions\DeleteAction::make()->label('Delete'),

            // Modified form for add progress with optional technician assignment
            \Filament\Actions\Action::make('addProgress')
                ->label('Tambah Progress')
                ->form([
                    \Filament\Forms\Components\Select::make('action_type')
                        ->label('Tipe Aksi')
                        ->options([
                            'Open Clock' => 'Open Clock',
                            'Pending Clock' => 'Pending Clock',
                            'Start Clock' => 'Start Clock',
                            'Completed' => 'Completed',
                            'Note' => 'Catatan/Tindakan'
                        ])
                        ->required()
                        ->live(),
                    \Filament\Forms\Components\Textarea::make('description')
                        ->label('Deskripsi Tindakan')
                        ->placeholder("C:\\Users\\USER>ping 8.8.8.8\nPinging 8.8.8.8 ...")
                        ->rows(8)
                        ->required()
                        ->helperText(function (\Filament\Forms\Get $get) {
                            if ($get('action_type') === 'Completed') {
                                return 'Deskripsi ini akan otomatis mengisi field Resolution/Action pada ticket';
                            }
                            return 'Bisa copy-paste hasil ping, akan tampil sesuai baris.';
                        }),
                    // Add optional field for technician assignment
                    \Filament\Forms\Components\Select::make('assigned_to')
                        ->label('Assign to Technician')
                        ->options(function () {
                            return \App\Models\User::role('TEKNISI')
                                ->get()
                                ->mapWithKeys(function ($user) {
                                    return [$user->email => $user->name];
                                })
                                ->toArray();
                        })
                        ->searchable()
                        ->required(false) // Always optional
                        ->placeholder('Tidak ada teknisi yang ditugaskan')
                        ->helperText('Opsional: pilih teknisi jika diperlukan kunjungan ke lokasi')
                        ->visible(fn (\Filament\Forms\Get $get): bool => $get('action_type') === 'Pending Clock'),
                ])
                ->action(function (array $data): void {
                    try {
                        // Buat ticket action baru
                        $ticketAction = \App\Models\TicketAction::create([
                            'ticket_id' => $this->record->getKey(),
                            'user_id' => \Illuminate\Support\Facades\Auth::user()->getKey(),
                            'action_type' => $data['action_type'],
                            'description' => $data['description'],
                            'status' => $this->record->status
                        ]);

                        $updates = [];
                        
                        // Update ticket berdasarkan tipe tindakan
                        if ($data['action_type'] === 'Pending Clock') {
                            $updates['status'] = 'PENDING';
                            $updates['pending_clock'] = now();
                            
                            // Add assigned_to only if provided
                            if (!empty($data['assigned_to'])) {
                                $updates['assigned_to'] = $data['assigned_to'];
                                
                                // Enhance the description with technician assignment info
                                $technicianName = \App\Models\User::where('email', $data['assigned_to'])->first()?->name ?? 'Unknown';
                                $ticketAction->update([
                                    'description' => $data['description'] . "\n\nTeknisi $technicianName ditugaskan untuk kunjungan ke lokasi."
                                ]);
                            }
                        } elseif ($data['action_type'] === 'Start Clock') {
                            $updates['status'] = 'OPEN';
                        } elseif ($data['action_type'] === 'Completed') {
                            $updates['status'] = 'CLOSED';
                            $updates['closed_date'] = now();
                            // Set action_description dengan deskripsi dari ticket action
                            $updates['action_description'] = $data['description'];
                        }
                        
                        if (!empty($updates)) {
                            $this->record->update($updates);
                        }

                        Notification::make()
                            ->success()
                            ->title('Progress Berhasil Ditambahkan')
                            ->send();

                        $this->redirect($this->getResource()::getUrl('view', ['record' => $this->record->getKey()]));
                    } catch (\Exception $e) {
                        Notification::make()
                            ->danger()
                            ->title('Gagal menambahkan progress')
                            ->body($e->getMessage())
                            ->send();
                    }
                })
                ->visible(fn ($record) => $record->status !== 'CLOSED'),
                
            // Tambahkan action untuk langsung mengisi Resolution/Action
            \Filament\Actions\Action::make('updateResolution')
                ->label('Update Resolution')
                ->color('warning')
                ->icon('heroicon-o-document-text')
                ->form([
                    \Filament\Forms\Components\Textarea::make('action_description')
                        ->label('Resolution / Action')
                        ->placeholder('Jelaskan penanganan atau resolusi dari ticket ini')
                        ->rows(5)
                        ->required()
                        ->default(fn ($record) => $record->action_description)
                ])
                ->action(function (array $data): void {
                    try {
                        // Update action_description pada ticket
                        $this->record->update([
                            'action_description' => $data['action_description']
                        ]);
                        
                        // Tambahkan juga sebagai ticket action baru (opsional)
                        if ($this->record->status === 'CLOSED') {
                            // Update action Completed terakhir
                            $completedAction = $this->record->actions()
                                ->where('action_type', 'Completed')
                                ->latest('created_at')
                                ->first();
                                
                            if ($completedAction) {
                                $completedAction->update([
                                    'description' => $data['action_description']
                                ]);
                            } else {
                                // Buat action Completed baru jika belum ada
                                TicketAction::create([
                                    'ticket_id' => $this->record->getKey(),
                                    'user_id' => Auth::user()->getKey(),
                                    'action_type' => 'Completed',
                                    'description' => $data['action_description'],
                                    'status' => 'CLOSED'
                                ]);
                            }
                        } else {
                            // Buat Note baru jika ticket belum CLOSED
                            TicketAction::create([
                                'ticket_id' => $this->record->getKey(),
                                'user_id' => Auth::user()->getKey(),
                                'action_type' => 'Note',
                                'description' => $data['action_description'],
                                'status' => $this->record->status
                            ]);
                        }

                        Notification::make()
                            ->success()
                            ->title('Resolution/Action Berhasil Diperbarui')
                            ->send();

                        $this->redirect($this->getResource()::getUrl('view', ['record' => $this->record->getKey()]));
                    } catch (\Exception $e) {
                        Notification::make()
                            ->danger()
                            ->title('Gagal memperbarui Resolution/Action')
                            ->body($e->getMessage())
                            ->send();
                    }
                }),
            
            // Add action to update assigned technician separately - modified without allowDeselection
            \Filament\Actions\Action::make('updateTechnician')
                ->label('Assign Teknisi')
                ->color('info')
                ->icon('heroicon-o-user-plus')
                ->form([
                    // Add an option to select "No technician" with a radio first
                    \Filament\Forms\Components\Radio::make('technician_option')
                        ->label('Opsi Teknisi')
                        ->options([
                            'none' => 'Tidak ada teknisi (hapus penugasan)',
                            'assign' => 'Pilih teknisi untuk ditugaskan',
                        ])
                        ->default(function ($record) {
                            return empty($record->assigned_to) ? 'none' : 'assign';
                        })
                        ->live(),
                    
                    \Filament\Forms\Components\Select::make('assigned_to')
                        ->label('Pilih Teknisi')
                        ->options(function () {
                            return \App\Models\User::role('TEKNISI')
                                ->get()
                                ->mapWithKeys(function ($user) {
                                    return [$user->email => $user->name];
                                })
                                ->toArray();
                        })
                        ->searchable()
                        ->required(fn (\Filament\Forms\Get $get): bool => $get('technician_option') === 'assign')
                        ->placeholder('Pilih teknisi')
                        ->default(fn ($record) => $record->assigned_to)
                        ->visible(fn (\Filament\Forms\Get $get): bool => $get('technician_option') === 'assign')
                ])
                ->action(function (array $data): void {
                    try {
                        $oldTechnician = $this->record->assigned_to;
                        
                        // Determine the new technician based on the radio selection
                        $newTechnician = null;
                        if ($data['technician_option'] === 'assign' && !empty($data['assigned_to'])) {
                            $newTechnician = $data['assigned_to'];
                        }
                        
                        // Update technician assignment
                        $this->record->update([
                            'assigned_to' => $newTechnician
                        ]);
                        
                        // Create a ticket action to record this change
                        $description = '';
                        
                        if (empty($oldTechnician) && !empty($newTechnician)) {
                            // New assignment
                            $technicianName = \App\Models\User::where('email', $newTechnician)->first()?->name ?? 'Unknown';
                            $description = "Teknisi $technicianName ditugaskan untuk menangani tiket ini.";
                        } elseif (!empty($oldTechnician) && empty($newTechnician)) {
                            // Assignment removed
                            $oldTechnicianName = \App\Models\User::where('email', $oldTechnician)->first()?->name ?? 'Unknown';
                            $description = "Penugasan teknisi $oldTechnicianName dicabut.";
                        } elseif (!empty($oldTechnician) && !empty($newTechnician) && $oldTechnician !== $newTechnician) {
                            // Assignment changed
                            $oldTechnicianName = \App\Models\User::where('email', $oldTechnician)->first()?->name ?? 'Unknown';
                            $newTechnicianName = \App\Models\User::where('email', $newTechnician)->first()?->name ?? 'Unknown';
                            $description = "Penugasan teknisi diubah dari $oldTechnicianName ke $newTechnicianName.";
                        } else {
                            // No change or same technician reassigned
                            $description = "Tidak ada perubahan pada penugasan teknisi.";
                        }
                        
                        if ($description !== "Tidak ada perubahan pada penugasan teknisi.") {
                            TicketAction::create([
                                'ticket_id' => $this->record->getKey(),
                                'user_id' => Auth::user()->getKey(),
                                'action_type' => 'Note',
                                'description' => $description,
                                'status' => $this->record->status
                            ]);
                        }

                        Notification::make()
                            ->success()
                            ->title('Penugasan Teknisi Berhasil Diperbarui')
                            ->send();

                        $this->redirect($this->getResource()::getUrl('view', ['record' => $this->record->getKey()]));
                    } catch (\Exception $e) {
                        Notification::make()
                            ->danger()
                            ->title('Gagal memperbarui penugasan teknisi')
                            ->body($e->getMessage())
                            ->send();
                    }
                }),
        ];
    }
}