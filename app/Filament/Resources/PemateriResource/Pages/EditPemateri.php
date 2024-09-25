<?php

namespace App\Filament\Resources\PemateriResource\Pages;

use App\Filament\Resources\PemateriResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPemateri extends EditRecord
{
    protected static string $resource = PemateriResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
