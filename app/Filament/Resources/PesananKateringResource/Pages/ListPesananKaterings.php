<?php

namespace App\Filament\Resources\PesananKateringResource\Pages;

use App\Filament\Resources\PesananKateringResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;

class ListPesananKaterings extends ListRecords
{
    protected static string $resource = PesananKateringResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
