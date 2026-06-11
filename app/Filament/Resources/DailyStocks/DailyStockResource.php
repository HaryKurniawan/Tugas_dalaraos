<?php

namespace App\Filament\Resources\DailyStocks;

use App\Filament\Resources\DailyStocks\Pages\CreateDailyStock;
use App\Filament\Resources\DailyStocks\Pages\EditDailyStock;
use App\Filament\Resources\DailyStocks\Pages\ListDailyStocks;
use App\Models\DailyStock;
use App\Models\Menu;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;

class DailyStockResource extends Resource
{
    protected static ?string $model = DailyStock::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Stok Harian (Dine-in)';
    protected static ?string $navigationGroup = 'Inventori & Stok';
    protected static ?int $navigationSort = 1;
    protected static ?string $recordTitleAttribute = 'tanggal';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Input Stok Harian dari Pusat')
                ->description('Catat jumlah makanan yang dikirim dari Dalaraos Pusat hari ini.')
                ->schema([
                    Forms\Components\Select::make('menu_id')
                        ->label('Menu / Makanan')
                        ->options(Menu::all()->pluck('nama_menu', 'id'))
                        ->required()
                        ->searchable(),
                    Forms\Components\DatePicker::make('tanggal')
                        ->label('Tanggal Pengiriman')
                        ->default(today())
                        ->required(),
                    Forms\Components\TextInput::make('stok_awal')
                        ->label('Jumlah Dikirim dari Pusat')
                        ->numeric()
                        ->default(0)
                        ->required()
                        ->live()
                        ->afterStateUpdated(fn ($state, callable $set) => $set('stok_sisa', $state)),
                    Forms\Components\TextInput::make('stok_terjual')
                        ->label('Sudah Terjual')
                        ->numeric()
                        ->default(0)
                        ->disabled(),
                    Forms\Components\TextInput::make('stok_sisa')
                        ->label('Sisa Stok')
                        ->numeric()
                        ->default(0)
                        ->disabled(),
                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'Aktif'                  => 'Aktif (Masih Dijual)',
                            'Ditutup'                => 'Ditutup',
                            'Diberikan ke Karyawan'  => 'Diberikan ke Karyawan',
                        ])
                        ->default('Aktif')
                        ->required(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')->label('Tanggal')->date('d M Y')->sortable(),
                Tables\Columns\TextColumn::make('menu.nama_menu')->label('Menu')->searchable(),
                Tables\Columns\TextColumn::make('stok_awal')->label('Dari Pusat')->alignCenter(),
                Tables\Columns\TextColumn::make('stok_terjual')->label('Terjual')->alignCenter()
                    ->color('success'),
                Tables\Columns\TextColumn::make('stok_sisa')->label('Sisa')->alignCenter()
                    ->color(fn ($record) => $record->stok_sisa <= 0 ? 'danger' : ($record->stok_sisa <= 5 ? 'warning' : 'gray')),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'Aktif',
                        'gray'    => 'Ditutup',
                        'warning' => 'Diberikan ke Karyawan',
                    ]),
            ])
            ->filters([
                Tables\Filters\Filter::make('today')
                    ->label('Hari Ini')
                    ->query(fn ($query) => $query->where('tanggal', today()))
                    ->default(),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Aktif' => 'Aktif',
                        'Ditutup' => 'Ditutup',
                        'Diberikan ke Karyawan' => 'Diberikan ke Karyawan',
                    ]),
            ])
            ->actions([
                Action::make('tutup_hari')
                    ->label('Tutup Hari')
                    ->icon('heroicon-o-x-circle')
                    ->color('warning')
                    ->visible(fn ($record) => $record->status === 'Aktif')
                    ->requiresConfirmation()
                    ->modalHeading('Tutup Stok Hari Ini')
                    ->modalDescription('Sisa stok akan dicatat sebagai "Diberikan ke Karyawan". Lanjutkan?')
                    ->action(function ($record) {
                        $record->update(['status' => 'Diberikan ke Karyawan']);
                        Notification::make()->title('Stok ditutup - sisa diberikan ke karyawan')->success()->send();
                    }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('tutup_semua')
                        ->label('Tutup Semua (Bagi ke Karyawan)')
                        ->icon('heroicon-o-archive-box-x-mark')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $records->each(fn ($r) => $r->update(['status' => 'Diberikan ke Karyawan']));
                            Notification::make()->title('Semua stok yang dipilih sudah ditutup')->success()->send();
                        }),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('tanggal', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListDailyStocks::route('/'),
            'create' => CreateDailyStock::route('/create'),
            'edit'   => EditDailyStock::route('/{record}/edit'),
        ];
    }
}
