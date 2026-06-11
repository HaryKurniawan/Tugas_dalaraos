<?php

namespace App\Filament\Resources\PesananKateringResource\Pages;

use App\Filament\Resources\PesananKateringResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions;

class EditPesananKatering extends EditRecord
{
    protected static string $resource = PesananKateringResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
