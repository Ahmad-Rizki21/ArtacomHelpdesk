<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketBackboneResource\Pages;
use App\Models\TicketBackbone;
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
                ->live() // Tambahkan live untuk trigger perubahan di `jenis_isp`
                ->afterStateUpdated(fn ($state, $set) => $set('jenis_isp', BackboneCID::where('id', $state)->value('jenis_isp'))),

            Select::make('lokasi_id')
                ->label('Lokasi Backbone')
                ->searchable()
                ->options(fn () => BackboneCID::pluck('lokasi', 'id')->toArray())
                ->required(),

                Hidden::make('created_by')
            ->default(fn () => Filament::auth()->user()->id), // Mengambil ID user yang sedang login

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
                ->disabled(), // Supaya tidak bisa diubah manual

                // Forms\Components\Textarea::make('extra_description')
                // ->label('Extra Description')
                // ->placeholder('Masukkan deskripsi tambahan di sini...')
                // ->rows(4)
                // ->default(fn ($get) => $get('status') === 'OPEN' ? 'Belum Ada Deskripsi Tambahan' : null)
                // ->disabled(fn ($get) => $get('status') !== 'PENDING')
                // ->required(),
                Forms\Components\Textarea::make('extra_description')
                ->label('Extra Description')
                ->placeholder('Masukkan deskripsi tambahan di sini...')
                ->rows(4)
                ->default(null) // Default kosong, bukan "Belum Ada Deskripsi Tambahan"
                ->disabled(fn ($get) => $get('status') === 'OPEN') // Disable saat OPEN
                ->live(),


                

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
                'status'      => $ticket->status,
                'open_date'   => $ticket->open_date?->format('d-m-Y H:i') ?? 'N/A',
                'pending_date'=> $ticket->pending_date?->format('d-m-Y H:i') ?? 'Belum ada Pending',
                'closed_date' => $ticket->closed_date?->format('d-m-Y H:i') ?? 'Belum ada Ticket Closed',
                'created_by'  => optional($ticket->creator)->name ?? 'Unknown',
                'created_at' => $ticket->created_at ?? null,  // Pastikan ada
                'updated_at' => $ticket->updated_at ?? null,  // Pastikan ada
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
                    'OPEN' => 'danger',   // Merah
                    'PENDING' => 'warning', // Kuning/Orange
                    'CLOSED' => 'success',  // Hijau
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
            ->dateTime('Y-m-d H:i:s') // Format sesuai dengan database
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
            EditAction::make(),
            ViewAction::make(),
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
            'edit' => Pages\EditTicketBackbone::route('/{record}/edit'),
        ];
    }
}
