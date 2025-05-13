<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketResource\Pages;
use App\Models\Ticket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use IbrahimBougaoua\FilaProgress\Forms\Components\CircleProgressEntry;  
use IbrahimBougaoua\FilaProgress\Forms\Components\ProgressBarEntry;   
use IbrahimBougaoua\FilaProgress\Tables\Columns\CircleProgress;  
use IbrahimBougaoua\FilaProgress\Tables\Columns\ProgressBar;  
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Grouping\Group;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;
    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationLabel = 'Tickets';
    protected static ?string $navigationGroup = 'Helpdesk';
    protected static ?int $navigationSort = 1;
    
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereIn('status', ['OPEN', 'PENDING'])->count();
    }
    
    
    public static function getNavigationBadgeColor(): ?string
    {
        $openTicketsCount = static::getModel()::where('status', 'OPEN')->count();
        
        if ($openTicketsCount > 0) {
            return 'danger';
        }
        
        $pendingTicketsCount = static::getModel()::where('status', 'PENDING')->count();
        
        if ($pendingTicketsCount > 0) {
            return 'warning';
        }
        
        return 'success';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Ticket Information')
                ->description('Details of the support ticket')
                ->schema([
                    // Ticket information fields remain the same...
                    Grid::make(2)->schema([
                        Forms\Components\Select::make('service')
                            ->label('Service')
                            ->options([
                                'ISP-JAKINET' => 'ISP-JAKINET',
                                'ISP-JELANTIK' => 'ISP-JELANTIK',
                            ])
                            ->required()
                            ->columnSpan(1),
                        
                        Forms\Components\TextInput::make('ticket_number')
                            ->label('Ticket Number')
                            ->disabled()
                            ->default(fn () => 'TFTTH-' . strtoupper(uniqid()))
                            ->required()
                            ->columnSpan(1),
                    ]),

                    Grid::make(2)->schema([
                        Forms\Components\Select::make('customer_id')
                            ->label('Customer')
                            ->relationship('customer', 'composite_data')
                            ->searchable()
                            ->required()
                            ->columnSpan(1),
                        
                        Forms\Components\Select::make('sla_id')
                            ->label('SLA')
                            ->relationship('sla', 'name')
                            ->required()
                            ->columnSpan(1),
                    ]),

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
                        ->placeholder('Enter additional details here...')
                        ->rows(3)
                        ->required(),
                ]),

            Section::make('Ticket Status')
                ->description('Current status and additional information')
                ->schema([
                    Grid::make(2)->schema([
                        Forms\Components\DateTimePicker::make('report_date')
                            ->label('Report Date')
                            ->default(now())
                            ->disabled()
                            ->columnSpan(1),
                        
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
                                    $set('closed_date', now());
                                } else {
                                    $set('closed_date', null);
                                }
                            })
                            ->columnSpan(1),
                    ]),

                    // Modified to make assigned_to always optional
                    Forms\Components\Select::make('assigned_to')
                        ->label('Assign to Technician')
                        ->options(function () {
                            // Get all users with TEKNISI role formatted as "Name"
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
                        ->live()
                        ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                            \Illuminate\Support\Facades\Log::info('Email teknisi diubah ke: ' . $state);
                        }),

                    Forms\Components\Textarea::make('action_description')
                        ->label('Resolution / Action')
                        ->placeholder('Jika ingin melakukan Closed Ticket WAJIB mengisi Action nya...')
                        ->helperText('Field ini wajib diisi saat ticket CLOSED. Isi otomatis dari Progress terbaru atau isi secara manual.')
                        ->rows(3)
                        ->required(fn (Get $get): bool => $get('status') === 'CLOSED')
                        ->visible(fn (Get $get): bool => $get('status') === 'CLOSED')
                        ->live(),

                    Forms\Components\FileUpload::make('evidance_paths')
                ->label('Upload Evidence')
                ->multiple()
                ->acceptedFileTypes([
                    'image/*',
                    'video/*',
                    'application/pdf',
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'application/vnd.ms-excel',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'text/plain',
                ])
                ->maxSize(10240)
                ->directory('evidances')
                ->preserveFilenames()
                ->enableDownload()
                ->maxFiles(10)
                ->afterStateUpdated(function ($state) {
                    Log::info('Uploaded Evidance Paths Before Save: ' . json_encode($state));
                })
                ->dehydrated(true) // Pastikan data disimpan ke database
                ->helperText('Unggah beberapa bukti (gambar, video, atau dokumen). Maksimum 10MB per file.'),

                    Grid::make(2)->schema([
                        Forms\Components\TextInput::make('pending_clock')
                            ->label('Pending Clock')
                            ->disabled()
                            ->placeholder('No Pending Yet')
                            ->columnSpan(1),
                        
                        Forms\Components\TextInput::make('closed_date')
                            ->label('Closed Date')
                            ->disabled()
                            ->placeholder('Ticket Not Closed')
                            ->columnSpan(1),
                    ]),
                ]),
                
            // Other sections remain the same...
            Section::make('System Information')
                ->description('Internal tracking details')
                ->schema([
                    Grid::make(2)->schema([
                        Forms\Components\DateTimePicker::make('created_at')
                            ->label('Created At')
                            ->disabled()
                            ->columnSpan(1),
                        
                        Forms\Components\DateTimePicker::make('updated_at')
                            ->label('Updated At')
                            ->disabled()
                            ->columnSpan(1),
                    ]),

                    Forms\Components\Hidden::make('created_by')
                        ->default(fn () => Auth::user()->id)
                        ->disabled(),
                ]),

                Section::make('Progress Information')
                ->description('Track ticket progress')
                ->visible(fn (?Ticket $record) => $record !== null)
                ->schema([
                    Forms\Components\Select::make('progress_status')
                        ->label('Progress Status')
                        ->options([
                            '10' => 'Started',
                            '25' => 'In Progress',
                            '50' => 'Partially Complete',
                            '75' => 'Nearly Complete',
                            '100' => 'Completed'
                        ])
                        ->default('10')
                        ->columnSpan(1),

                    Forms\Components\TextInput::make('progress_percentage')
                        ->label('Progress Percentage')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(100)
                        ->default(10)
                        ->suffix('%')
                        ->columnSpan(1)
                ])
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            // ->defaultGroup('status')
            ->groups([
                Group::make('status')
                    ->getDescriptionFromRecordUsing(fn (Ticket $record): string => match ($record->status) {
                        'OPEN' => 'Ticket is open',
                        'PENDING' => 'Ticket is pending',
                        'CLOSED' => 'Ticket is closed',
                        default => 'Unknown status',
                    }),
            ])
            ->columns([
                
                TextColumn::make('ticket_number')
                    ->label('Ticket No')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('customer.composite_data')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('problem_summary')
                    ->label('Problem')
                    ->badge()
                    ->color('primary')
                    ->searchable(),
                
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'OPEN' => 'danger',
                        'PENDING' => 'warning',
                        'CLOSED' => 'success',
                        default => 'gray',
                    }),
                
                TextColumn::make('extra_description')
                    ->label('Description')
                    ->limit(30)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 30) {
                            return null;
                        }
                        return $state;
                    }),

                TextColumn::make('sla.name')
                    ->label('SLA')
                    ->badge()
                    ->color(fn ($state): string => match ($state) {
                        'HIGH' => 'danger',
                        'MEDIUM' => 'warning',
                        'LOW' => 'primary',
                        default => 'gray',
                    }),
                
                TextColumn::make('report_date')
                    ->label('Reported On')
                    ->date()
                    ->sortable(),

                TextColumn::make('pending_clock')
                    ->label('Pending Date')
                    ->placeholder('Belum ada Pending')
                    ->sortable(),
                
                TextColumn::make('closed_date')
                    ->label('Closed Date')
                    ->placeholder('Belum ada Ticket Closed')
                    ->sortable(),

                TextColumn::make('action_description')
                    ->label('Resolution / Action')
                    ->placeholder('Belum ada Penanganan')
                    ->limit(30)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 30) {
                            return null;
                        }
                        return $state;
                    })
                    ->badge() 
                    ->color('success')
                    ->formatStateUsing(function ($state, $record) {
                        if (empty($state) && $record->status === 'CLOSED') {
                            $completedAction = $record->actions()
                                ->where('action_type', 'Completed')
                                ->latest('created_at')
                                ->first();
                                
                            if ($completedAction) {
                                $record->update(['action_description' => $completedAction->description]);
                                return $completedAction->description;
                            }
                            return 'Belum ada Penanganan';
                        }
                        
                        if (empty($state)) {
                            return 'Belum ada Penanganan';
                        }
                        
                        return $state;
                    }),

                    
                //LAPORAN SLA
                
                // Tambahkan kolom-kolom SLA
            //     TextColumn::make('duration_in_days')
            //     ->label('Durasi (d hh:mm)')
            //     ->sortable()
            //     ->searchable()
            //     ->placeholder('Belum Selesai'),
                
            // TextColumn::make('uptime_percentage')
            //     ->label('Uptime (%)')
            //     ->sortable()
            //     ->formatStateUsing(fn ($state) => $state ? number_format($state, 2) . '%' : 'N/A'),
                
            // TextColumn::make('allowed_downtime')
            //     ->label('Downtime Maks')
            //     ->sortable(),
                
            // TextColumn::make('sla_status')
            //     ->label('Status SLA')
            //     ->badge()
            //     ->color(fn (string $state): string => match ($state) {
            //         'Memenuhi SLA' => 'success',
            //         'Melebihi SLA' => 'danger',
            //         'Dalam Proses' => 'gray',
            //         default => 'gray',
            //     })
            //     ->sortable(),
            
            

                // Ubah kolom technician.name menjadi assigned_to
                TextColumn::make('assigned_to')
                ->label('Assigned To')
                ->placeholder('Not Assigned')
                ->formatStateUsing(function ($state) {
                    if (empty($state)) {
                        return 'Not Assigned';
                    }
                    
                    $user = \App\Models\User::where('email', $state)->first();
                    
                    if ($user) {
                        return $user->name;
                    }
                    
                    return $state;
                })
                ->sortable()
                ->searchable(),
                    

                // CircleProgress::make('Progress')  
                //     ->getStateUsing(fn ($record) => [  
                //         'total' => 100,  
                //         'progress' => $record->progress_percentage,  
                //     ])
                //     ->label('Progress'),  
                
                ProgressBar::make('Progress Bar')  
                    ->getStateUsing(fn ($record) => [  
                        'total' => 100,  
                        'progress' => $record->progress_percentage,  
                    ])
                    ->label('Progress Bar'),   
            
                TextColumn::make('creator.name')
                    ->label('Created By')
                    ->sortable()
                    ->searchable(),
            ])
            
            ->defaultSort('created_at', 'desc')
            ->striped()
        ->poll('10s') // Auto refresh setiap 10 detik
            // ->filters([
            //     SelectFilter::make('status')
            //         ->label('Filter Status')
            //         ->options([
            //             'OPEN' => 'OPEN',
            //             'PENDING' => 'PENDING',
            //             'CLOSED' => 'CLOSED',
            //         ]),
            //     Filter::make('created_at_period')
            //         ->label('Filter by Period')
            //         ->form([
            //             Forms\Components\Select::make('year')
            //                 ->label('Year')
            //                 ->options(
            //                     collect(range(2022, now()->year))
            //                         ->reverse()
            //                         ->mapWithKeys(fn ($year) => [$year => $year])
            //                 ),
            //             Forms\Components\Select::make('month')
            //                 ->label('Month')
            //                 ->options([
            //                     '01' => 'January',
            //                     '02' => 'February',
            //                     '03' => 'March',
            //                     '04' => 'April',
            //                     '05' => 'May',
            //                     '06' => 'June',
            //                     '07' => 'July',
            //                     '08' => 'August',
            //                     '09' => 'September',
            //                     '10' => 'October',
            //                     '11' => 'November',
            //                     '12' => 'December',
            //                 ]),

            //         ])
            //         ->query(fn (Builder $query, array $data) => $query
            //             ->when($data['year'], fn ($q) => $q->whereYear('created_at', $data['year']))
            //             ->when($data['month'], fn ($q) => $q->whereMonth('created_at', $data['month']))
            //         ),
                
            //     SelectFilter::make('problem_summary')
            //         ->label('Problem Type')
            //         ->options([
            //             'INDIKATOR LOS' => 'INDIKATOR LOS',
            //             'LOW SPEED' => 'LOW SPEED',
            //             'MODEM HANG' => 'MODEM HANG',
            //             'NO INTERNET ACCESS' => 'NO INTERNET ACCESS',
            //         ]),
            // ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'OPEN' => 'OPEN',
                        'PENDING' => 'PENDING',
                        'CLOSED' => 'CLOSED',
                    ]),
                
                // Date picker dengan tampilan yang lebih kompak
                Filter::make('periode')
                    ->form([
                        Grid::make(2)
                            ->schema([
                                DatePicker::make('start_date')
                                    ->label('Dari Tanggal')
                                    ->displayFormat('d M Y')
                                    ->native(false)
                                    ->closeOnDateSelection()
                                    ->maxDate(now())
                                    ->placeholder('Pilih Tanggal'),
                                    
                                DatePicker::make('end_date')
                                    ->label('Sampai Tanggal')
                                    ->displayFormat('d M Y')
                                    ->native(false)
                                    ->closeOnDateSelection()
                                    ->maxDate(now())
                                    ->placeholder('Pilih Tanggal')
                            ])
                            ->columns(2)
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['start_date'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['end_date'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['start_date'] ?? null) {
                            $indicators[] = 'Dari: ' . Carbon::parse($data['start_date'])->format('d M Y');
                        }
                        if ($data['end_date'] ?? null) {
                            $indicators[] = 'Sampai: ' . Carbon::parse($data['end_date'])->format('d M Y');
                        }
                        return $indicators;
                    }),
                
                SelectFilter::make('problem_summary')
                    ->label('Problem Type')
                    ->options([
                        'INDIKATOR LOS' => 'INDIKATOR LOS',
                        'LOW SPEED' => 'LOW SPEED',
                        'MODEM HANG' => 'MODEM HANG',
                        'NO INTERNET ACCESS' => 'NO INTERNET ACCESS',
                    ]),
            ])
            ->filtersLayout(Tables\Enums\FiltersLayout::AboveContent)
            ->filtersFormColumns(3)
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->color('info'),
                Tables\Actions\EditAction::make()
                    ->color('warning'),
                Tables\Actions\DeleteAction::make()
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Delete Ticket')
                    ->modalDescription('Are you sure you want to delete this ticket? This action cannot be undone.'),
                    
                Tables\Actions\Action::make('updateResolution')
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
                    ->action(function (Ticket $record, array $data): void {
                        $record->update([
                            'action_description' => $data['action_description']
                        ]);
                        
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
                                $record->actions()->create([
                                    'user_id' => Auth::id(),
                                    'action_type' => 'Completed',
                                    'description' => $data['action_description'],
                                    'status' => 'CLOSED'
                                ]);
                            }
                        } else {
                            $record->actions()->create([
                                'user_id' => Auth::id(),
                                'action_type' => 'Note',
                                'description' => $data['action_description'],
                                'status' => $record->status
                            ]);
                        }
                        
                        Notification::make()
                            ->title('Resolution telah diperbarui')
                            ->success()
                            ->send();
                    })
                    ->successNotificationTitle('Resolution telah diperbarui')
                    ->visible(fn (Ticket $record) => Auth::check() && Auth::user()->can('update', $record))

                    

            ])

                    


            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    
                    Tables\Actions\BulkAction::make('updateStatus')
                        ->label('Update Status')
                        ->icon('heroicon-o-arrow-path')
                        ->form([
                            Forms\Components\Select::make('status')
                                ->label('New Status')
                                ->options([
                                    'OPEN' => 'OPEN',
                                    'PENDING' => 'PENDING',
                                    'CLOSED' => 'CLOSED',
                                ])
                                ->required(),
                                
                            Forms\Components\Textarea::make('action_description')
                                ->label('Resolution / Action')
                                ->required()
                                ->placeholder('Jelaskan tindakan yang dilakukan')
                                ->visible(fn (Get $get): bool => $get('status') === 'CLOSED'),
                        ])
                        ->action(function (Collection $records, array $data): void {
                            foreach ($records as $record) {
                                $oldStatus = $record->status;
                                
                                $updateData = [
                                    'status' => $data['status'],
                                ];
                                
                                if ($data['status'] === 'CLOSED') {
                                    $updateData['action_description'] = $data['action_description'];
                                    $updateData['closed_date'] = now();
                                }
                                
                                $record->update($updateData);
                                
                                if ($data['status'] === 'CLOSED') {
                                    $record->actions()->create([
                                        'user_id' => Auth::id(),
                                        'action_type' => 'Completed',
                                        'description' => $data['action_description'],
                                        'status' => 'CLOSED'
                                    ]);
                                } else {
                                    $record->actions()->create([
                                        'user_id' => Auth::id(),
                                        'action_type' => 'Status Change',
                                        'description' => "Status changed from {$oldStatus} to {$data['status']}",
                                        'status' => $data['status']
                                    ]);
                                }
                            }
                            
                            Notification::make()
                                ->title('Status tiket berhasil diperbarui')
                                ->success()
                                ->send();
                        })
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
            'view' => Pages\ViewTicket::route('/{record}'),
        ];
    }
}