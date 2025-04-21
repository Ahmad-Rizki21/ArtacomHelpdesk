<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\Evidance;
use App\Models\TicketAction;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class EditTicket extends EditRecord
{
    protected static string $resource = TicketResource::class;

    // Tambahkan method refreshForm
    protected function refreshForm(): void
    {
        // Refresh record dari database
        $this->record->refresh();

        // Perbarui form dengan data terbaru
        $this->form->fill($this->record->toArray());
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->requiresConfirmation()
                ->modalHeading('Konfirmasi Hapus Ticket')
                ->modalDescription('Apakah Anda yakin ingin menghapus ticket ini? Tindakan ini tidak dapat dibatalkan.')
                ->modalSubmitActionLabel('Ya, Hapus')
                ->modalCancelActionLabel('Batal')
                ->successNotificationTitle('🗑️ Ticket Berhasil Dihapus!')
                ->after(function () {
                    Notification::make()
                        ->success()
                        ->title('🗑️ Ticket Telah Dihapus!')
                        ->body('Ticket ini telah dihapus secara permanen.')
                        ->send();
                }),

            // Tambahkan action untuk menambahkan progress
            Actions\Action::make('addProgress')
                ->label('Tambah Progress')
                ->form([
                    Forms\Components\Select::make('action_type')
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
                    Forms\Components\Textarea::make('description')
                        ->label('Deskripsi Tindakan')
                        ->placeholder('Jelaskan tindakan yang dilakukan...')
                        ->required(),
                    // Add optional field for technician assignment when status is changing to PENDING
                    Forms\Components\Select::make('assigned_to')
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
                        ->helperText('Opsional: pilih teknisi jika diperlukan kunjungan ke lokasi')
                        ->visible(fn (Get $get): bool => $get('action_type') === 'Pending Clock'),
                ])
                ->action(function (array $data): void {
                    // Simpan progress baru
                    $ticketAction = TicketAction::create([
                        'ticket_id' => $this->record->id,
                        'user_id' => Auth::id(),
                        'action_type' => $data['action_type'],
                        'description' => $data['description'],
                        'status' => $this->record->status
                    ]);

                    // Update status tiket jika perlu
                    if ($data['action_type'] === 'Pending Clock') {
                        $updates = [
                            'status' => 'PENDING',
                            'pending_clock' => now()
                        ];
                        
                        // Add technician assignment only if selected
                        if (!empty($data['assigned_to'])) {
                            $updates['assigned_to'] = $data['assigned_to'];
                            
                            // Enhance description with technician info if assigned
                            $technicianName = \App\Models\User::where('email', $data['assigned_to'])->first()?->name ?? 'Unknown';
                            $ticketAction->update([
                                'description' => $data['description'] . "\n\nTeknisi $technicianName ditugaskan untuk kunjungan ke lokasi."
                            ]);
                        }
                        
                        $this->record->update($updates);
                    } elseif ($data['action_type'] === 'Completed') {
                        // Jika tipe aksi Completed, update status CLOSED dan isi action_description
                        $this->record->update([
                            'status' => 'CLOSED',
                            'closed_date' => now(),
                            'action_description' => $data['description'] // Isi action_description otomatis
                        ]);
                    }

                    Notification::make()
                        ->success()
                        ->title('Progress Berhasil Ditambahkan')
                        ->send();

                    $this->refreshForm();
                })
                ->visible(fn ($record) => $record->status !== 'CLOSED'),
        ];
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Select::make('service')
                ->label('Layanan')
                ->options([
                    'ISP-JAKINET' => 'ISP-JAKINET',
                    'ISP-JELANTIK' => 'ISP-JELANTIK',
                ])
                ->required(),
            Forms\Components\TextInput::make('ticket_number')
                ->label('No Ticket')
                ->disabled()
                ->default(fn () => 'TFTTH-' . strtoupper(uniqid()))
                ->required(),
            Forms\Components\Select::make('customer_id')
                ->label('Id Pelanggan')
                ->relationship('customer', 'composite_data')
                ->searchable()
                ->required(),
            Forms\Components\Select::make('problem_summary')
                ->required()
                ->label('Problem Summary')
                ->options([
                    'INDIKATOR LOS' => 'INDIKATOR LOS',
                    'LOW SPEED' => 'LOW SPEED',
                    'MODEM HANG' => 'MODEM HANG',
                    'NO INTERNET ACCESS' => 'NO INTERNET ACCESS',
                ]),
            Forms\Components\Textarea::make('extra_description')
                ->label('Extra Description')
                ->placeholder('Masukkan deskripsi tambahan di sini...')
                ->rows(4)
                ->required(),
            Forms\Components\DateTimePicker::make('report_date')
                ->label('Report Date')
                ->default(now())
                ->disabled(),
            Forms\Components\Select::make('status')
                ->label('Status')
                ->options([
                    'OPEN' => 'OPEN',
                    'PENDING' => 'PENDING',
                    'CLOSED' => 'CLOSED',
                ])
                ->default('OPEN')
                ->required()
                ->live()
                ->afterStateUpdated(function (Get $get, Set $set, string $state) {
                    if ($state === 'CLOSED') {
                        $set('closed_date', now()->format('Y-m-d H:i:s'));
                        
                        // Cek apakah action_description masih kosong
                        if (empty($get('action_description'))) {
                            // Ambil deskripsi dari ticket_action terakhir dengan tipe Completed jika ada
                            $latestCompletedAction = TicketAction::where('ticket_id', $this->record->id)
                                ->where('action_type', 'Completed')
                                ->latest('created_at')
                                ->first();

                            if ($latestCompletedAction) {
                                $set('action_description', $latestCompletedAction->description);
                            }
                        }
                    } else {
                        $set('closed_date', null);
                    }
                }),
            
            // Modified to make assigned_to always optional
            Forms\Components\Select::make('assigned_to')
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
                ->helperText('Opsional: pilih teknisi jika diperlukan kunjungan ke lokasi'),
                
            // Other fields remain the same
            Forms\Components\Textarea::make('action_description')
                ->label('Resolution / Action')
                ->placeholder('Jika ingin melakukan Closed Ticket WAJIB mengisi Action nya...')
                ->helperText('Field ini wajib diisi saat ticket CLOSED. Isi otomatis dari Progress terbaru atau isi secara manual.')
                ->rows(4)
                ->required(fn (Get $get): bool => $get('status') === 'CLOSED')
                ->visible(fn (Get $get): bool => $get('status') === 'CLOSED'),

            Forms\Components\FileUpload::make('evidance_path')
                ->label('Upload Evidence')
                ->acceptedFileTypes(['image/*', 'video/*', 'application/pdf'])
                ->maxSize(10240) // max 10MB
                ->directory('evidances') // direktori penyimpanan
                ->preserveFilenames(), // menjaga nama file asli

            Forms\Components\TextInput::make('pending_clock')
                ->label('Pending Clock')
                ->disabled()
                ->placeholder('Belum ada Pending'),
            Forms\Components\TextInput::make('closed_date')
                ->label('Closed Date')
                ->disabled()
                ->placeholder('Belum ada Ticket Closed'),
            Forms\Components\Select::make('sla_id')
                ->label('SLA')
                ->relationship('sla', 'name')
                ->required(),
                
            // Tambahan field action_description dengan peningkatan UX
            Forms\Components\Textarea::make('action_description')
                ->label('Action Description / Resolution')
                ->placeholder('Jelaskan tindakan yang dilakukan untuk menyelesaikan tiket ini...')
                ->helperText('Field ini wajib diisi saat ticket CLOSED. Isi otomatis dari Progress terbaru atau isi secara manual.')
                ->rows(4)
                ->required(fn (Get $get): bool => $get('status') === 'CLOSED')
                ->visible(fn (Get $get): bool => $get('status') === 'CLOSED'),

            Forms\Components\FileUpload::make('evidance_path')
                ->label('Upload Evidence')
                ->acceptedFileTypes(['image/*', 'video/*', 'application/pdf'])
                ->maxSize(10240) // max 10MB
                ->directory('evidances') // direktori penyimpanan
                ->preserveFilenames(), // menjaga nama file asli

            // Forms\Components\TextInput::make('pending_clock')
            //     ->label('Pending Clock')
            //     ->disabled()
            //     ->placeholder('Belum ada Pending'),
            // Forms\Components\TextInput::make('closed_date')
            //     ->label('Closed Date')
            //     ->disabled()
            //     ->placeholder('Belum ada Ticket Closed'),
            // Forms\Components\Select::make('sla_id')
            //     ->label('SLA')
            //     ->relationship('sla', 'name')
            //     ->required(),

            // Bagian untuk menampilkan evidences
            Forms\Components\Section::make('Evidences')
                ->schema([
                    Forms\Components\Repeater::make('evidances')
                        ->relationship('evidances')
                        ->schema([
                            Forms\Components\TextInput::make('file_path')->label('Evidance Path')->disabled(),
                            Forms\Components\TextInput::make('file_type')->label('Evidance Type')->disabled(),
                        ])
                        ->columns(2)
                        ->disabled() // Pastikan repeater ini hanya untuk menampilkan data, tidak bisa diubah
                ])
                ->collapsible()
                ->collapsed(false),
                
            // Tambahan bagian untuk menampilkan progress tindakan
            Forms\Components\Section::make('Progress Tindakan')
                ->schema([
                    Forms\Components\Placeholder::make('progress_timeline')
                        ->label(false)
                        ->content(function ($record) {
                            if (!$record || !$record->exists) {
                                return 'Belum ada data progress.';
                            }
                            
                            $actions = $record->actions()
                                ->with('user')
                                ->orderBy('created_at', 'desc')
                                ->get();
                            
                            if ($actions->isEmpty()) {
                                return 'Belum ada data progress.';
                            }
                            
                            $html = '<div class="space-y-4">';
                            
                            foreach ($actions as $action) {
                                $date = $action->created_at->format('d M Y H:i');
                                $user = $action->user ? $action->user->name : 'System';
                                $type = $action->action_type;
                                $description = $action->description;
                                
                                $badgeColor = match ($type) {
                                    'Open Clock' => 'bg-blue-100 text-blue-800',
                                    'Pending Clock' => 'bg-yellow-100 text-yellow-800',
                                    'Start Clock' => 'bg-green-100 text-green-800',
                                    'Completed' => 'bg-gray-100 text-gray-800',
                                    default => 'bg-gray-100 text-gray-800',
                                };
                                
                                $html .= <<<HTML
                                <div class="border p-4 rounded-lg">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="px-2 py-1 text-xs font-medium rounded $badgeColor">$type</span>
                                        <span class="text-sm text-gray-500">$date</span>
                                    </div>
                                    <p class="text-sm mb-2">$description</p>
                                    <div class="text-xs text-gray-500">oleh: $user</div>
                                </div>
                                HTML;
                            }
                            
                            $html .= '</div>';
                            
                            return new \Illuminate\Support\HtmlString($html);
                        }),
                ])
                ->collapsible()
                ->collapsed(false),
        ];
    }

      // Metode untuk validasi sebelum menyimpan
    protected function beforeSave(): void
    {
        $data = $this->form->getState();

        // Jika status CLOSED dan action_description masih kosong
        if ($data['status'] === 'CLOSED' && empty($data['action_description'])) {
            // Cari TicketAction bertipe 'Completed' terbaru
            $completedAction = $this->record->actions()
                ->where('action_type', 'Completed')
                ->latest('created_at')
                ->first();

            if ($completedAction) {
                // Isi action_description dengan deskripsi dari TicketAction terbaru
                $this->record->action_description = $completedAction->description;
            } else {
                // Tetap error jika tidak ada action Completed dan action_description kosong
                throw ValidationException::withMessages([
                    'action_description' => 'Action description wajib diisi saat menutup tiket.',
                ]);
            }
        }
        
        // Jika status berubah, tambahkan ke histori tindakan
        if ($this->record->exists && $this->record->status !== $data['status']) {
            // Jika status berubah menjadi CLOSED, tambahkan aksi Completed
            if ($data['status'] === 'CLOSED') {
                // Jika ada action_description, gunakan itu untuk Completed action
                $description = !empty($data['action_description']) 
                    ? $data['action_description'] 
                    : 'Ticket telah diselesaikan';
                
                // Buat ticket action baru
                $ticketAction = TicketAction::create([
                    'ticket_id' => $this->record->id,
                    'user_id' => Auth::id(),
                    'action_type' => 'Completed',
                    'description' => $description,
                    'status' => 'CLOSED'
                ]);
                
                // Update action_description di record jika kosong
                if (empty($this->record->action_description)) {
                    $this->record->action_description = $description;
                }
            }
            // Jika status berubah menjadi PENDING, tambahkan aksi Pending Clock
            elseif ($data['status'] === 'PENDING') {
                $description = 'Ticket dalam status pending';
                
                // Add technician info to description if assigned
                if (!empty($data['assigned_to'])) {
                    $technicianName = \App\Models\User::where('email', $data['assigned_to'])->first()?->name ?? 'Unknown';
                    $description .= "\n\nTeknisi $technicianName ditugaskan untuk kunjungan ke lokasi.";
                }
                
                TicketAction::create([
                    'ticket_id' => $this->record->id,
                    'user_id' => Auth::id(),
                    'action_type' => 'Pending Clock',
                    'description' => $description,
                    'status' => 'PENDING'
                ]);
            }
            // Jika status berubah menjadi OPEN dari PENDING, tambahkan aksi Start Clock
            elseif ($data['status'] === 'OPEN' && $this->record->status === 'PENDING') {
                TicketAction::create([
                    'ticket_id' => $this->record->id,
                    'user_id' => Auth::id(),
                    'action_type' => 'Start Clock',
                    'description' => 'Pengerjaan ticket dilanjutkan',
                    'status' => 'OPEN'
                ]);
            }
        }
    }

    protected function afterCreate(): void
    {
        // Tambahkan aksi Open Clock saat ticket baru dibuat
        TicketAction::create([
            'ticket_id' => $this->record->id,
            'user_id' => Auth::id(),
            'action_type' => 'Open Clock',
            'description' => 'Ticket baru dibuat',
            'status' => 'OPEN'
        ]);
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->color('success')
            ->title('✅ Ticket Berhasil Diperbarui!')
            ->body('Perubahan pada ticket telah disimpan. Klik tombol di bawah untuk melihat detailnya.')
            ->actions([
                Action::make('Lihat Ticket')
                    ->url($this->getResource()::getUrl('edit', ['record' => $this->record]))
                    ->button(),
            ])
            ->send();
    }
}