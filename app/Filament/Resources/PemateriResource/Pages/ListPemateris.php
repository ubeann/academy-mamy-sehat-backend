<?php

namespace App\Filament\Resources\PemateriResource\Pages;

use App\Filament\Resources\PemateriResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPemateris extends ListRecords
{
    protected static string $resource = PemateriResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
