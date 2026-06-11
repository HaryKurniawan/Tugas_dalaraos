<?php

namespace App\Filament\Resources\DailyStocks\Pages;

use App\Filament\Resources\DailyStocks\DailyStockResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDailyStocks extends ListRecords
{
    protected static string $resource = DailyStockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
