<?php

namespace App\Filament\Resources\BackboneCIDResource\Pages;

use App\Filament\Resources\BackboneCIDResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;


class ListBackboneCIDS extends ListRecords
{
    protected static string $resource = BackboneCIDResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    

}
