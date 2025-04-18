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
use Filament\Tables\Filters\SelectFilter;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';
    protected static ?string $navigationLabel = 'Users';
    protected static ?string $navigationGroup = 'Settings';

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
                    ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                    ->dehydrated(fn ($state) => filled($state)),
                    
                Forms\Components\Select::make('roles')
                    ->label('Role')
                    ->multiple()
                    ->relationship('roles', 'name')
                    ->preload()
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('name')->label('Nama')->sortable()->searchable(),
                TextColumn::make('email')->label('Email')->sortable()->searchable(),
                TextColumn::make('roles.name')
                    ->label('Role')
                    ->badge()
                    ->separator(', ')
                    ->color('success'),
                TextColumn::make('created_at')->label('Dibuat Pada')->dateTime(),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->label('Filter by Role')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
                
                // Khusus untuk mengelola role
                Tables\Actions\Action::make('manageRoles')
                ->label('Kelola Role')
                ->color('success')
                ->icon('heroicon-o-shield-check')
                ->modalHeading(fn($record) => "Kelola Role untuk {$record->name}")
                ->form([
                    Forms\Components\Select::make('roles')
                        ->label('Role')
                        ->multiple()
                        ->options(\Spatie\Permission\Models\Role::pluck('name', 'id'))
                        ->default(function ($record) {
                            return $record->roles->pluck('id')->toArray();
                        })
                        ->required(),
                ])
                ->action(function (array $data, User $record): void {
                    $roleIds = $data['roles'] ?? [];
                    $roles = \Spatie\Permission\Models\Role::whereIn('id', $roleIds)->get();
                    $record->syncRoles($roles);
                    
                    Notification::make()
                        ->title('Role berhasil diperbarui')
                        ->success()
                        ->send();
                }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                
                Tables\Actions\BulkAction::make('updateRole')
                    ->label('Update Role')
                    ->icon('heroicon-o-user-group')
                    ->form([
                        Forms\Components\Select::make('role')
                            ->label('Pilih Role')
                            ->options(Role::pluck('name', 'id'))
                            ->required(),
                        
                        Forms\Components\Checkbox::make('removeExisting')
                            ->label('Hapus role sebelumnya?')
                            ->default(false),
                    ])
                    ->action(function (array $data, \Illuminate\Database\Eloquent\Collection $records): void {
                        $role = Role::find($data['role']);
                        
                        foreach ($records as $user) {
                            if ($data['removeExisting']) {
                                $user->roles()->detach();
                            }
                            
                            $user->assignRole($role);
                        }
                        
                        Notification::make()
                            ->title('Role berhasil diperbarui untuk ' . $records->count() . ' user')
                            ->success()
                            ->send();
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with('roles');
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