<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\HtmlString;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Customers';
    protected static ?string $navigationGroup = 'Helpdesk Management';
    

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Customer Details')
                ->description('Informasi detail pelanggan')
                ->schema([
                    Grid::make(2)->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->label('Full Name')
                            ->placeholder('Enter customer full name')
                            ->columnSpan(1)
                            ->helperText('Nama lengkap pelanggan'),

                        Forms\Components\TextInput::make('customer_id')
                            ->required()
                            ->label('Customer ID')
                            ->unique(ignoreRecord: true)
                            ->placeholder('Auto-generated or manual')
                            ->columnSpan(1)
                            ->helperText('Nomor identifikasi unik pelanggan'),
                    ]),

                    Grid::make(2)->schema([
                        Forms\Components\TextInput::make('ip_address')
                            ->required()
                            ->label('IP Address')
                            ->placeholder('e.g., 192.168.1.100')
                            ->columnSpan(1)
                            ->rule('ipv4')
                            ->helperText('Alamat IP pelanggan'),

                        Forms\Components\Select::make('service')
                            ->required()
                            ->label('Service Type')
                            ->options([
                                'ISP-JELANTIK' => 'ISP Jelantik',
                                'ISP-JAKINET' => 'ISP Jakinet',
                            ])
                            ->placeholder('Select Service')
                            ->columnSpan(1)
                            ->native(false)
                            ->helperText('Pilih jenis layanan'),
                    ]),
                ])
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('No')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('name')
                    ->label('Customer Name')
                    ->searchable()
                    ->description(fn ($record) => $record->customer_id)
                    ->color('primary')
                    ->weight('medium'),

                BadgeColumn::make('service')
                    ->label('Service')
                    ->color(fn (string $state): string => match ($state) {
                        'ISP-JELANTIK' => 'success',
                        'ISP-JAKINET' => 'warning',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'ISP-JELANTIK' => 'heroicon-m-globe-alt',
                        'ISP-JAKINET' => 'heroicon-m-server',
                        default => 'heroicon-m-question-mark-circle',
                    }),

                TextColumn::make('ip_address')
                    ->label('IP Address')
                    ->searchable()
                    ->copyable()
                    ->color('secondary')
                    ->formatStateUsing(function ($state) {
                        // Membuat link untuk redirect ke halaman ONT configuration
                        return new HtmlString(
                            '<a href="http://' . $state . '" target="_blank" class="text-primary-600 hover:text-primary-500 hover:underline font-medium">' . 
                            $state . 
                            ' <i class="ml-1 fas fa-external-link-alt text-xs"></i></a>'
                        );
                    })
                    ->html(),

                TextColumn::make('created_at')
                    ->label('Registered')
                    ->date()
                    ->sortable()
                    ->color('gray')
            ])
            ->filters([
                SelectFilter::make('service')
                    ->label('Filter by Service')
                    ->options([
                        'ISP-JELANTIK' => 'ISP Jelantik',
                        'ISP-JAKINET' => 'ISP Jakinet',
                    ])
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->color('info'),
                Tables\Actions\EditAction::make()
                    ->color('warning'),
                Tables\Actions\DeleteAction::make()
                    ->color('danger')
                    ->requiresConfirmation(),
                // Tambahkan action untuk menuju halaman konfigurasi ONT
                Tables\Actions\Action::make('ont_config')
                    ->label('ONT Config')
                    ->color('success')
                    ->icon('heroicon-o-cog')
                    ->url(fn (Customer $record): string => 'http://' . $record->ip_address)
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}