<?php

namespace App\Filament\Resources\DailyStocks\Pages;

use App\Filament\Resources\DailyStocks\DailyStockResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDailyStock extends CreateRecord
{
    protected static string $resource = DailyStockResource::class;
}
