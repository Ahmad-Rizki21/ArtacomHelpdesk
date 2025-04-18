<?php

namespace App\Filament\Resources\TicketBackboneResource\Pages;

use App\Filament\Resources\TicketBackboneResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use App\Models\TicketBackboneAction;
use Filament\Support\Exceptions\Halt;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class EditTicketBackbone extends EditRecord
{
    protected static string $resource = TicketBackboneResource::class;

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
                        ->required(),
                    Forms\Components\Textarea::make('description')
                        ->label('Deskripsi Tindakan')
                        ->placeholder('Jelaskan tindakan yang dilakukan...')
                        ->required(),
                ])
                ->action(function (array $data): void {
                    // Simpan progress baru
                    $ticketAction = TicketBackboneAction::create([
                        'ticket_backbone_id' => $this->record->id,
                        'user_id' => Auth::id(),
                        'action_type' => $data['action_type'],
                        'description' => $data['description'],
                        'status' => $this->record->status
                    ]);

                    // Update status tiket jika perlu
                    if ($data['action_type'] === 'Pending Clock') {
                        $this->record->update([
                            'status' => 'PENDING',
                            'pending_date' => now()
                        ]);
                    } elseif ($data['action_type'] === 'Completed') {
                        // Jika tipe aksi Completed, update status CLOSED dan isi action_description
                        $this->record->update([
                            'status' => 'CLOSED',
                            'closed_date' => now(),
                            'action_description' => $data['description'] // Isi action_description otomatis
                        ]);
                    } elseif ($data['action_type'] === 'Start Clock' && $this->record->status === 'PENDING') {
                        $this->record->update([
                            'status' => 'OPEN'
                        ]);
                    }

                    Notification::make()
                        ->success()
                        ->title('Progress Berhasil Ditambahkan')
                        ->send();

                    $this->refreshForm();
                })
                ->visible(fn () => $this->record->status !== 'CLOSED'),
        ];
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('no_ticket')
                ->label('No Ticket')
                ->disabled(),
                
            Forms\Components\Select::make('cid')
                ->label('CID / Sid Layanan ISP')
                ->options(fn () => \App\Models\BackboneCID::pluck('cid', 'id')->toArray())
                ->searchable()
                ->required(),
                
            Forms\Components\Select::make('lokasi_id')
                ->label('Lokasi Backbone')
                ->options(fn () => \App\Models\BackboneCID::pluck('lokasi', 'id')->toArray())
                ->searchable()
                ->required(),
                
            Forms\Components\Select::make('jenis_isp')
                ->label('Jenis Layanan ISP')
                ->options([
                    'INDIBIZ' => 'INDIBIZ',
                    'ASTINET' => 'ASTINET',
                    'ICON PLUS' => 'ICON PLUS',
                    'FIBERNET' => 'FIBERNET',
                ])
                ->disabled(),
                
            Forms\Components\Textarea::make('extra_description')
                ->label('Extra Description')
                ->placeholder('Masukkan deskripsi tambahan di sini...')
                ->rows(4)
                ->required(),
                
            Forms\Components\Select::make('status')
                ->label('Status')
                ->options([
                    'OPEN' => 'OPEN',
                    'PENDING' => 'PENDING',
                    'CLOSED' => 'CLOSED',
                ])
                ->required()
                ->live()
                ->afterStateUpdated(function (Get $get, Set $set, string $state) {
                    if ($state === 'CLOSED') {
                        $set('closed_date', now()->format('Y-m-d H:i:s'));
                        
                        // Cek apakah action_description masih kosong
                        if (empty($get('action_description'))) {
                            // Ambil deskripsi dari ticket_action terakhir dengan tipe Completed jika ada
                            $latestCompletedAction = TicketBackboneAction::where('ticket_backbone_id', $this->record->id)
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
                    
                    // Set pending_date if status is PENDING
                    if ($state === 'PENDING' && !$get('pending_date')) {
                        $set('pending_date', now()->format('Y-m-d H:i:s'));
                    }
                }),
                
            // Action description field for resolution
            Forms\Components\Textarea::make('action_description')
                ->label('Action Description / Resolution')
                ->placeholder('Jelaskan tindakan yang dilakukan untuk menyelesaikan tiket ini...')
                ->helperText('Field ini wajib diisi saat ticket CLOSED. Isi otomatis dari Progress terbaru atau isi secara manual.')
                ->rows(4)
                ->required(fn (Get $get): bool => $get('status') === 'CLOSED')
                ->visible(fn (Get $get): bool => $get('status') === 'CLOSED'),
                
            Forms\Components\DateTimePicker::make('open_date')
                ->label('Open Date')
                ->disabled(),
                
            Forms\Components\DateTimePicker::make('pending_date')
                ->label('Pending Date')
                ->disabled(),
                
            Forms\Components\DateTimePicker::make('closed_date')
                ->label('Closed Date')
                ->disabled(),

            // Bagian untuk menampilkan riwayat tindakan dengan desain yang diperbarui
            Forms\Components\Section::make('Progress Tindakan')
                ->schema([
                    Forms\Components\Placeholder::make('progress_timeline')
                        ->label(false)
                        ->content(function ($record) {
                            if (!$record || !$record->exists) {
                                return 'Belum ada data progress.';
                            }
                            
                            // Get actions in reverse chronological order (newest first)
                            $actions = $record->actions()
                                ->with('user')
                                ->latest('created_at')
                                ->get();
                            
                            if ($actions->isEmpty()) {
                                return 'Belum ada data progress.';
                            }
                            
                            $html = '<div class="space-y-4">';
                            
                            foreach ($actions as $action) {
                                $date = $action->created_at->format('d M Y H:i:s');
                                $user = $action->user ? $action->user->name : 'System';
                                $type = $action->action_type;
                                
                                // Determine badge color based on action type
                                $badgeColor = match ($type) {
                                    'Open Clock' => 'bg-blue-600 text-white',
                                    'Pending Clock' => 'bg-yellow-600 text-white',
                                    'Start Clock' => 'bg-green-600 text-white',
                                    'Completed' => 'bg-gray-600 text-white',
                                    default => 'bg-purple-600 text-white',
                                };
                                
                                // Format the description to preserve whitespace in a pre tag
                                $description = '<pre style="font-family:monospace;font-size:14px;background:#222;color:#fff;border-radius:8px;padding:8px;overflow-x:auto;white-space:pre-wrap;">' . e($action->description) . '</pre>';
                                
                                $html .= <<<HTML
                                <div class="border border-gray-700 rounded-lg overflow-hidden">
                                    <div class="flex items-center justify-between p-3 bg-gray-800">
                                        <span class="$badgeColor text-xs font-medium px-2.5 py-1 rounded">$type</span>
                                        <span class="text-sm text-gray-400">$date</span>
                                    </div>
                                    <div class="p-3">
                                        $description
                                    </div>
                                    <div class="bg-gray-800 p-2 text-right text-gray-400 text-xs">
                                        Oleh: $user
                                    </div>
                                </div>
                                HTML;
                            }
                            
                            $html .= '</div>';
                            
                            return new HtmlString($html);
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
            // Cari TicketBackboneAction bertipe 'Completed' terbaru
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
                $ticketAction = TicketBackboneAction::create([
                    'ticket_backbone_id' => $this->record->id,
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
                TicketBackboneAction::create([
                    'ticket_backbone_id' => $this->record->id,
                    'user_id' => Auth::id(),
                    'action_type' => 'Pending Clock',
                    'description' => 'Ticket dalam status pending',
                    'status' => 'PENDING'
                ]);
            }
            // Jika status berubah menjadi OPEN dari PENDING, tambahkan aksi Start Clock
            elseif ($data['status'] === 'OPEN' && $this->record->status === 'PENDING') {
                TicketBackboneAction::create([
                    'ticket_backbone_id' => $this->record->id,
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
        TicketBackboneAction::create([
            'ticket_backbone_id' => $this->record->id,
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