<?php

namespace App\Filament\Resources;

use App\Models\LogSession;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\LogActivityResource\Pages\ListLogActivities;

class LogActivityResource extends Resource
{
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Activity Logs';
    protected static ?string $navigationGroup = 'Systems';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user_id')->label('User ID')->sortable(),
                TextColumn::make('ip_address')->label('IP Address')->sortable(),
                TextColumn::make('user_agent')->label('User Agent')->wrap(),
                TextColumn::make('last_activity')
                    ->label('Last Activity')
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable(),
            ])
            ->query(LogSession::query()); // Pastikan ini menggunakan Eloquent Model
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLogActivities::route('/'),
        ];
    }
}
