<?php

namespace App\Filament\Resources\StokBarangKeringResource\Pages;

use App\Filament\Resources\StokBarangKeringResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions;

class EditStokBarangKering extends EditRecord
{
    protected static string $resource = StokBarangKeringResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
