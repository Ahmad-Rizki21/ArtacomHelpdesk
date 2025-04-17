<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';
    protected static ?string $navigationLabel = 'Users';
    protected static ?string $navigationGroup = 'Systems';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Nama'),
                Forms\Components\TextInput::make('email')
                    ->required()
                    ->email()
                    ->label('Email'),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required(fn ($livewire) => $livewire instanceof Pages\CreateUser)
                    ->label('Password')
                    ->dehydrateStateUsing(fn ($state) => bcrypt($state))
                    ->dehydrated(fn ($state) => filled($state)),
                    Forms\Components\Select::make('role')
                    ->required()
                    ->label('Role')
                    ->options([
                        'Admin' => 'Admin',
                        'Helpdesk' => 'Helpdesk',
                        
                    ])
                    ->default('helpdesk') // Set default role
                     ->searchable(), // Tambahkan pencarian jika opsi banyak
                    
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('name')->label('Nama')->sortable()->searchable(),
                TextColumn::make('email')->label('Email')->sortable()->searchable(),
                TextColumn::make('role')->label('Role')->sortable()->searchable(),
                TextColumn::make('created_at')->label('Dibuat Pada')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}