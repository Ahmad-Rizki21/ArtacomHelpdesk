<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketBackboneResource\Pages;
use App\Models\TicketBackbone;
use App\Models\TicketBackboneAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Models\BackboneCID;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\Action;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use App\Exports\TicketBackboneExport;
use Maatwebsite\Excel\Facades\Excel;

class TicketBackboneResource extends Resource
{
    protected static ?string $model = TicketBackbone::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationLabel = 'Ticket Backbone';
    protected static ?string $navigationGroup = 'Backbone';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('no_ticket')
                ->label('No Ticket')
                ->disabled()
                ->default(fn () => 'BackBone-' . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT)),

            Select::make('cid')
                ->label('CID / Sid Layanan ISP')
                ->searchable()
                ->options(fn () => BackboneCID::pluck('cid', 'id')->toArray())
                ->required()
                ->live()
                ->afterStateUpdated(fn ($state, $set) => $set('jenis_isp', BackboneCID::where('id', $state)->value('jenis_isp'))),

            Select::make('lokasi_id')
                ->label('Lokasi Backbone')
                ->searchable()
                ->options(fn () => BackboneCID::pluck('lokasi', 'id')->toArray())
                ->required(),

            Hidden::make('created_by')
                ->default(fn () => Filament::auth()->user()->id),

            Select::make('jenis_isp')
                ->label('Jenis Layanan ISP')
                ->searchable()
                ->options([
                    'INDIBIZ' => 'INDIBIZ',
                    'ASTINET' => 'ASTINET',
                    'ICON PLUS' => 'ICON PLUS',
                    'FIBERNET' => 'FIBERNET',
                ])
                ->default(fn ($get) => BackboneCID::where('id', $get('cid'))->value('jenis_isp') ?? 'Tidak Diketahui')
                ->required()
                ->disabled(),

                Forms\Components\Textarea::make('extra_description')
                ->label('Extra Description')
                ->placeholder('Masukkan deskripsi tambahan di sini...')
                ->rows(4)
                ->default(null)
                ->helperText(fn ($get) => $get('status') === 'OPEN' 
                    ? 'Anda sedang mengedit deskripsi tiket yang masih OPEN.' 
                    : 'Tambahkan informasi detail untuk tiket ini.')
                ->live(),

            // Action Description for resolutions - like in Ticket system
            Forms\Components\Textarea::make('action_description')
                ->label('Resolution / Action')
                ->placeholder('Jelaskan tindakan yang dilakukan untuk menyelesaikan tiket ini...')
                ->helperText('Field ini wajib diisi saat ticket CLOSED. Isi otomatis dari Progress terbaru atau isi secara manual.')
                ->rows(4)
                ->required(fn ($get) => $get('status') === 'CLOSED')
                ->visible(fn ($get) => $get('status') === 'CLOSED'),

            Select::make('status')
                ->label('Status')
                ->options([
                    'OPEN' => 'OPEN',
                    'PENDING' => 'PENDING',
                    'CLOSED' => 'CLOSED',
                ])
                ->default('OPEN')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    if ($state === 'PENDING') {
                        $set('pending_date', now());
                    } elseif ($state === 'CLOSED') {
                        $set('closed_date', now());
                    }
                }),

            DateTimePicker::make('open_date')
                ->label('Open Date')
                ->disabled()
                ->default(now())
                ->dehydrated(),

            DateTimePicker::make('pending_date')
                ->label('Pending Date')
                ->disabled()
                ->dehydrated(),

            DateTimePicker::make('closed_date')
                ->label('Closed Date')
                ->disabled()
                ->dehydrated(),
        ]);
    }

    public static function table(Table $table): Table
    {
        $tickets = TicketBackbone::query()
            ->with(['cidRelation', 'creator'])
            ->get()
            ->map(function ($ticket) {
                return [
                    'no_ticket'   => $ticket->no_ticket,
                    'cid'         => optional($ticket->cidRelation)->cid ?? 'N/A',
                    'jenis_isp'   => $ticket->jenis_isp,
                    'lokasi'      => TicketBackbone::lokasiList()[$ticket->lokasi_id] ?? 'N/A',
                    'extra_description' => $ticket->extra_description,
                    'action_description' => $ticket->action_description,
                    'status'      => $ticket->status,
                    'open_date'   => $ticket->open_date?->format('d-m-Y H:i') ?? 'N/A',
                    'pending_date'=> $ticket->pending_date?->format('d-m-Y H:i') ?? 'Belum ada Pending',
                    'closed_date' => $ticket->closed_date?->format('d-m-Y H:i') ?? 'Belum ada Ticket Closed',
                    'created_by'  => optional($ticket->creator)->name ?? 'Unknown',
                    'created_at' => $ticket->created_at ?? null,
                    'updated_at' => $ticket->updated_at ?? null,
                ];
            });

        // Simpan data yang ditampilkan di Filament ke session untuk ekspor
        session(['filtered_tickets' => $tickets]);

        return $table->columns([
            TextColumn::make('no_ticket')
                ->label('No Ticket')
                ->sortable()
                ->searchable(),

            TextColumn::make('cidRelation.cid')
                ->label('CID')
                ->sortable()
                ->searchable(),

            TextColumn::make('jenis_isp')
                ->label('Jenis ISP')
                ->sortable()
                ->formatStateUsing(fn ($state) => $state ?: 'Tidak Diketahui'),

            TextColumn::make('lokasiRelation.lokasi')
                ->label('Lokasi')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('extra_description')
                ->label('Extra Description')
                ->formatStateUsing(fn ($state) => $state && trim($state) !== '' ? $state : 'Belum Ada Deskripsi Tambahan')
                ->limit(50)
                ->sortable()
                ->searchable(),

            TextColumn::make('status')
                ->badge()
                ->sortable()
                ->color(fn ($state) => match ($state) {
                    'OPEN' => 'danger',
                    'PENDING' => 'warning', 
                    'CLOSED' => 'success',
                }),

            TextColumn::make('open_date')
                ->label('Open Date')
                ->dateTime('d/m/Y H:i')
                ->sortable(),

            TextColumn::make('pending_date_formatted')
                ->label('Pending Date')
                ->sortable(),

            TextColumn::make('closed_date_formatted')
                ->label('Closed Date')
                ->sortable(),

            Tables\Columns\TextColumn::make('created_at')
                ->label('Created At')
                ->dateTime('Y-m-d H:i:s')
                ->sortable(),

            Tables\Columns\TextColumn::make('updated_at')
                ->label('Updated At')
                ->dateTime('Y-m-d H:i:s')
                ->sortable(),

            TextColumn::make('creator.name')
                ->label('Created By')
                ->sortable()
                ->searchable(),
        ])
        ->filters([
            SelectFilter::make('status')
                ->label('Filter Status')
                ->options([
                    'OPEN' => 'OPEN',
                    'PENDING' => 'PENDING',
                    'CLOSED' => 'CLOSED',
                ]),
        ])
        ->actions([
            ViewAction::make(),
            EditAction::make(),
            DeleteAction::make()
                ->label('Hapus')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Hapus Ticket Backbone')
                ->modalDescription('Apakah Anda yakin ingin menghapus ticket ini?')
                ->modalButton('Ya, Hapus')
                ->successNotificationTitle('🚀 Ticket berhasil dihapus!')
                ->after(function ($record) {
                    Notification::make()
                        ->title('🗑️ Ticket Dihapus!')
                        ->body("Ticket dengan **ID #{$record->id}** telah dihapus oleh **" . Filament::auth()->user()->name . "**.")
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->send();
                }),
            
            // Add action for updating resolution
            Action::make('updateResolution')
                ->label('Update Resolution')
                ->icon('heroicon-o-document-text')
                ->color('success')
                ->form([
                    Forms\Components\Textarea::make('action_description')
                        ->label('Resolution / Action')
                        ->required()
                        ->placeholder('Jelaskan tindakan yang dilakukan')
                        ->default(function ($record) {
                            return $record->action_description;
                        })
                ])
                ->action(function (TicketBackbone $record, array $data): void {
                    // Update action_description pada ticket
                    $record->update([
                        'action_description' => $data['action_description']
                    ]);
                    
                    // Jika status CLOSED, update action Completed terbaru
                    if ($record->status === 'CLOSED') {
                        $completedAction = $record->actions()
                            ->where('action_type', 'Completed')
                            ->latest('created_at')
                            ->first();
                            
                        if ($completedAction) {
                            $completedAction->update([
                                'description' => $data['action_description']
                            ]);
                        } else {
                            // Buat action Completed baru jika belum ada
                            $record->actions()->create([
                                'user_id' => Filament::auth()->user()->id,
                                'action_type' => 'Completed',
                                'description' => $data['action_description'],
                                'status' => 'CLOSED'
                            ]);
                        }
                    } else {
                        // Tambahkan sebagai catatan jika ticket belum CLOSED
                        $record->actions()->create([
                            'user_id' => Filament::auth()->user()->id,
                            'action_type' => 'Note',
                            'description' => $data['action_description'],
                            'status' => $record->status
                        ]);
                    }
                    
                    // Tampilkan notifikasi sukses
                    Notification::make()
                        ->title('Resolution telah diperbarui')
                        ->success()
                        ->send();
                })
                ->successNotificationTitle('Resolution telah diperbarui'),
                
            // Add progress action
            Action::make('addProgress')
                ->label('Tambah Progress')
                ->icon('heroicon-o-plus-circle')
                ->color('primary')
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
                        ->placeholder("C:\\Users\\USER>ping 8.8.8.8\nPinging 8.8.8.8 ...")
                        ->rows(8)
                        ->required()
                        ->helperText(function (Forms\Get $get) {
                            if ($get('action_type') === 'Completed') {
                                return 'Deskripsi ini akan otomatis mengisi field Resolution/Action pada ticket';
                            }
                            return 'Bisa copy-paste hasil ping, akan tampil sesuai baris.';
                        }),
                ])
                ->action(function (TicketBackbone $record, array $data): void {
                    try {
                        // Buat ticket action baru
                        $ticketAction = TicketBackboneAction::create([
                            'ticket_backbone_id' => $record->id,
                            'user_id' => Filament::auth()->user()->id,
                            'action_type' => $data['action_type'],
                            'description' => $data['description'],
                            'status' => $record->status
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
                        } elseif ($data['action_type'] === 'Start Clock' && $record->status === 'PENDING') {
                            $updates['status'] = 'OPEN';
                        }
                        
                        if (!empty($updates)) {
                            $record->update($updates);
                        }

                        Notification::make()
                            ->success()
                            ->title('Progress Berhasil Ditambahkan')
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->danger()
                            ->title('Gagal menambahkan progress')
                            ->body($e->getMessage())
                            ->send();
                    }
                })
                ->visible(fn ($record) => $record->status !== 'CLOSED'),
        ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTicketBackbones::route('/'),
            'create' => Pages\CreateTicketBackbone::route('/create'),
            'view' => Pages\ViewTicketBackbone::route('/{record}'),
            'edit' => Pages\EditTicketBackbone::route('/{record}/edit'),
        ];
    }
}