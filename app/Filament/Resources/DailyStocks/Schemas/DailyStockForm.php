<?php

namespace App\Filament\Resources\DailyStocks\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DailyStockForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('menu_id')
                    ->required()
                    ->numeric(),
                DatePicker::make('tanggal')
                    ->required(),
                TextInput::make('stok_awal')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('stok_terjual')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('stok_sisa')
                    ->required()
                    ->numeric()
                    ->default(0),
                Select::make('status')
                    ->options(['Aktif' => 'Aktif', 'Ditutup' => 'Ditutup', 'Diberikan ke Karyawan' => 'Diberikan ke karyawan'])
                    ->default('Aktif')
                    ->required(),
            ]);
    }
}
