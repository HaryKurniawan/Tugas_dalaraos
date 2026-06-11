<?php

namespace App\Filament\Resources\StokBarangKeringResource\Pages;

use App\Filament\Resources\StokBarangKeringResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;

class ListStokBarangKerings extends ListRecords
{
    protected static string $resource = StokBarangKeringResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Barang Kering'),
        ];
    }
}
