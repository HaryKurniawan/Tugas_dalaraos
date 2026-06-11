<?php

namespace App\Filament\Resources\DailyStocks\Pages;

use App\Filament\Resources\DailyStocks\DailyStockResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDailyStock extends EditRecord
{
    protected static string $resource = DailyStockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
