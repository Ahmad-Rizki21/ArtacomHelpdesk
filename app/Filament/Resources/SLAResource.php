<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SLAResource\Pages;
use App\Models\SLA;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;

class SLAResource extends Resource
{
    protected static ?string $model = SLA::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'SLA';
    protected static ?string $navigationGroup = 'Helpdesk';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('response_time')
                    ->required()
                    ->numeric()
                    ->label('Waktu Respons (jam)'),
                Forms\Components\TextInput::make('resolution_time')
                    ->required()
                    ->numeric()
                    ->label('Waktu Penyelesaian (jam)'),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('response_time')->label('Waktu Respons (jam)'),
                Tables\Columns\TextColumn::make('resolution_time')->label('Waktu Penyelesaian (jam)'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->label('Dibuat Pada'),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->label('Diperbarui Pada'),
            ])
            ->filters([
                //
            ])
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
            'index' => Pages\ListSLAs::route('/'),
            'create' => Pages\CreateSLA::route('/create'),
            'edit' => Pages\EditSLA::route('/{record}/edit'),
        ];
    }
}