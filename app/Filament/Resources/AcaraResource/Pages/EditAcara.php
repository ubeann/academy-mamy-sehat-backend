<?php

namespace App\Filament\Resources\AcaraResource\Pages;

use App\Filament\Resources\AcaraResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAcara extends EditRecord
{
    protected static string $resource = AcaraResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
