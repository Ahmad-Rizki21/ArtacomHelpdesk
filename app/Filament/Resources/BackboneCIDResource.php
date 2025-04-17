<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BackboneCIDResource\Pages;
use App\Filament\Resources\BackboneCIDResource\RelationManagers;
use App\Models\BackboneCID;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BackboneCIDResource extends Resource
{
    protected static ?string $model = BackboneCID::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard';
    protected static ?string $navigationLabel = 'Backbone CIDs';
    protected static ?string $navigationGroup = 'Backbone';


    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('cid')
                    ->label('Customer Identification Number')
                    ->required()
                    ->unique(),

                Forms\Components\TextInput::make('lokasi')
                    ->label('Lokasi Backbone')
                    ->required(),

                Forms\Components\Select::make('jenis_isp')
                    ->label('Jenis ISP')
                    ->options([
                        'INDIBIZ' => 'INDIBIZ',
                        'ASTINET' => 'ASTINET',
                        'ICON PLUS' => 'ICON PLUS',
                        'FIBERNET' => 'FIBERNET',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cid')->label('CID'),
                Tables\Columns\TextColumn::make('lokasi')->label('Lokasi'),
                Tables\Columns\TextColumn::make('jenis_isp')->label('Jenis ISP'),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat pada')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListBackboneCIDS::route('/'),
            'create' => Pages\CreateBackboneCID::route('/create'),
            'edit' => Pages\EditBackboneCID::route('/{record}/edit'),
        ];
    }
}
