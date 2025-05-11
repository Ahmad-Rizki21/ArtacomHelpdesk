<?php

namespace App\Filament\AlfaLawson\Resources;

use App\Filament\AlfaLawson\Resources\TableRemoteResource\Pages;
use App\Models\AlfaLawson\TableRemote;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;

class TableRemoteResource extends Resource
{
    protected static ?string $model = TableRemote::class;
    protected static ?string $navigationGroup = 'Customer';
    protected static ?string $navigationIcon = 'heroicon-o-server';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Basic Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('Site_ID')
                                    ->required()
                                    ->maxLength(255)
                                    ->label('Site ID')
                                    ->placeholder('Enter Site ID')
                                    ->helperText('Unique identifier for the site')
                                    ->columnSpan(1),
                                Forms\Components\TextInput::make('Nama_Toko')
                                    ->maxLength(32)
                                    ->label('Store Name')
                                    ->placeholder('Enter Store Name')
                                    ->helperText('Name of the store')
                                    ->columnSpan(1),
                            ]),
                    ])
                    ->columns(2),

                Section::make('Network Details')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('DC')
                                    ->maxLength(32)
                                    ->label('Distribution Center')
                                    ->placeholder('Enter DC Alfa')
                                    ->helperText('Data Center code')
                                    ->columnSpan(1),
                                Forms\Components\TextInput::make('IP_Address')
                                    ->maxLength(32)
                                    ->label('IP Address')
                                    ->placeholder('Enter IP Address')
                                    ->helperText('e.g., 192.168.1.1')
                                    ->columnSpan(1),
                                Forms\Components\TextInput::make('Vlan')
                                    ->maxLength(4)
                                    ->label('VLAN')
                                    ->placeholder('Enter VLAN')
                                    ->helperText('VLAN number')
                                    ->columnSpan(1),
                                Forms\Components\TextInput::make('Controller')
                                    ->maxLength(16)
                                    ->label('Controller')
                                    ->placeholder('Enter Controller')
                                    ->helperText('Controller name or ID')
                                    ->columnSpan(1),
                            ]),
                    ])
                    ->columns(2),

                Section::make('Operational Details')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('Customer')
                                    ->maxLength(16)
                                    ->label('Customer')
                                    ->placeholder('Enter Customer')
                                    ->helperText('Customer name or ID')
                                    ->columnSpan(1),
                                Forms\Components\DatePicker::make('Online_Date')
                                    ->label('Online Date')
                                    ->native(false)
                                    ->helperText('Date when site went online')
                                    ->default(now())
                                    ->columnSpan(1),
                                Forms\Components\TextInput::make('Link')
                                    ->maxLength(8)
                                    ->label('Link')
                                    ->placeholder('Enter Link')
                                    ->helperText('Link identifier')
                                    ->columnSpan(1),
                                Forms\Components\TextInput::make('Status')
                                    ->maxLength(16)
                                    ->label('Status')
                                    ->placeholder('Enter Status')
                                    ->helperText('e.g., Active, Inactive')
                                    ->columnSpan(1),
                            ]),
                    ])
                    ->columns(2),

                Section::make('Additional Notes')
                    ->schema([
                        Forms\Components\Textarea::make('Keterangan')
                            ->label('Description')
                            ->placeholder('Enter Description')
                            ->helperText('Additional notes or comments')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('Site_ID')
                    ->label('Site ID')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('Nama_Toko')
                    ->label('Store Name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('DC')
                    ->label('Distribution Center')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('IP_Address')
                    ->label('IP Address')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('Vlan')
                    ->label('VLAN')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('Controller')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('Customer')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('Online_Date')
                    ->label('Online Date')
                    ->date()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('Link')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('Status')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('Keterangan')
                    ->label('Description')
                    ->limit(30)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        return strlen($column->getState()) > 30 ? $column->getState() : null;
                    })
                    ->toggleable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTableRemotes::route('/'),
            'create' => Pages\CreateTableRemote::route('/create'),
            'edit' => Pages\EditTableRemote::route('/{record}/edit'),
        ];
    }
}