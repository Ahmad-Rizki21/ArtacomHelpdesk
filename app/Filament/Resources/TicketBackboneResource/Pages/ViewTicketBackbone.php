<?php

namespace App\Filament\Resources\TicketBackboneResource\Pages;

use App\Filament\Resources\TicketBackboneResource;
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
use App\Models\TicketBackboneAction;

class ViewTicketBackbone extends ViewRecord
{
    protected static string $resource = TicketBackboneResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Ticket Backbone Information')
                    ->description('Details of the backbone ticket')
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('no_ticket')->label('Ticket Number'),
                            TextEntry::make('cidRelation.cid')->label('CID / Sid Layanan ISP'),
                        ]),
                        Grid::make(2)->schema([
                            TextEntry::make('lokasiRelation.lokasi')->label('Lokasi Backbone'),
                            TextEntry::make('jenis_isp')->label('Jenis Layanan ISP'),
                        ]),
                        TextEntry::make('extra_description')
                            ->label('Extra Description')
                            ->markdown(),
                    ]),

                Section::make('Ticket Status')
                    ->description('Current status and additional information')
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('open_date')
                                ->label('Open Date')
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
                        // Display action_description
                        TextEntry::make('action_description')
                            ->label('Resolution / Action')
                            ->placeholder('Belum ada penanganan')
                            ->columnSpanFull()
                            ->visible(fn ($record) => !empty($record->action_description) || $record->status === 'CLOSED')
                            ->formatStateUsing(function ($state, $record) {
                                if (empty($state) && $record->status === 'CLOSED') {
                                    // If empty but status is CLOSED, look for most recent action
                                    $completedAction = $record->actions()
                                        ->where('action_type', 'Completed')
                                        ->latest('created_at')
                                        ->first();
                                    
                                    if ($completedAction) {
                                        return new HtmlString('<div class="p-3 rounded border border-gray-600 bg-gray-800 text-white">' . e($completedAction->description) . '</div>');
                                    }
                                    
                                    return 'Belum ada penanganan';
                                }
                                
                                if (empty($state)) {
                                    return 'Belum ada penanganan';
                                }
                                
                                return new HtmlString('<div class="p-3 rounded border border-gray-600 bg-gray-800 text-white">' . e($state) . '</div>');
                            }),
                        Grid::make(2)->schema([
                            TextEntry::make('pending_date_formatted')
                                ->label('Pending Date')
                                ->placeholder('No Pending Yet'),
                            TextEntry::make('closed_date_formatted')
                                ->label('Closed Date')
                                ->placeholder('Ticket Not Closed'),
                        ]),
                    ]),

                // PROGRESS ACTIONS (RIWAYAT TINDAKAN) - Using state() to ensure newest first
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
                            // The key fix: Provide pre-sorted actions with newest first
                            ->state(fn ($record) => $record->actions()->with('user')->latest('created_at')->get())
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
                    $ticket = $this->record->load(['actions.user', 'cidRelation', 'lokasiRelation', 'creator']);
                    $today = now()->format('d-m-Y H:i:s');
                    $company = 'BACKBONE HELPDESK';

                    $pdf = Pdf::loadView('tickets.backbone-pdf-template', [
                        'ticket' => $ticket,
                        'today' => $today,
                        'company' => $company,
                    ]);
                    $filename = 'BACKBONE-' . $ticket->no_ticket . '.pdf';

                    return response()->streamDownload(
                        fn () => print($pdf->stream()),
                        $filename
                    );
                }),

            \Filament\Actions\EditAction::make()->label('Edit'),
            \Filament\Actions\DeleteAction::make()->label('Delete'),

            // Form tambah progress dengan logic yang ditingkatkan untuk action_description
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
                ])
                ->action(function (array $data): void {
                    try {
                        // Buat ticket action baru
                        $ticketAction = TicketBackboneAction::create([
                            'ticket_backbone_id' => $this->record->getKey(),
                            'user_id' => Auth::user()->getKey(),
                            'action_type' => $data['action_type'],
                            'description' => $data['description'],
                            'status' => $this->record->status
                        ]);

                        $updates = [];
                        
                        // Update ticket berdasarkan tipe tindakan
                        if ($data['action_type'] === 'Pending Clock') {
                            $updates['status'] = 'PENDING';
                            $updates['pending_date'] = now();
                        } elseif ($data['action_type'] === 'Completed') {
                            $updates['status'] = 'CLOSED';
                            $updates['closed_date'] = now();
                            // Set action_description dengan deskripsi dari ticket action
                            $updates['action_description'] = $data['description'];
                        } elseif ($data['action_type'] === 'Start Clock' && $this->record->status === 'PENDING') {
                            $updates['status'] = 'OPEN';
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
                                TicketBackboneAction::create([
                                    'ticket_backbone_id' => $this->record->getKey(),
                                    'user_id' => Auth::user()->getKey(),
                                    'action_type' => 'Completed',
                                    'description' => $data['action_description'],
                                    'status' => 'CLOSED'
                                ]);
                            }
                        } else {
                            // Buat Note baru jika ticket belum CLOSED
                            TicketBackboneAction::create([
                                'ticket_backbone_id' => $this->record->getKey(),
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
        ];
    }
}