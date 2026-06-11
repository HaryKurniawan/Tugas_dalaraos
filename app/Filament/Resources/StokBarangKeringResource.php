<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StokBarangKeringResource\Pages;
use App\Models\StokBarangKering;
use App\Models\Suplier;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;

class StokBarangKeringResource extends Resource
{
    protected static ?string $model = StokBarangKering::class;
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-archive-box';
    protected static \UnitEnum|string|null $navigationGroup = 'Inventori & Stok';
    protected static ?string $navigationLabel = 'Stok Barang Kering';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\Section::make('Informasi Barang')
                ->schema([
                    Forms\Components\TextInput::make('sku')->label('SKU / Kode Barang')->required()->unique(ignoreRecord: true),
                    Forms\Components\TextInput::make('nama_barang')->label('Nama Barang')->required(),
                    Forms\Components\Select::make('suplier_id')
                        ->label('Supplier')
                        ->options(Suplier::all()->pluck('nama_suplier', 'id'))
                        ->required()
                        ->createOptionForm([
                            Forms\Components\TextInput::make('nama_suplier')->label('Nama Supplier')->required(),
                            Forms\Components\TextInput::make('kontak')->label('No. HP / Kontak'),
                            Forms\Components\Textarea::make('alamat')->label('Alamat'),
                        ])
                        ->createOptionUsing(fn ($data) => Suplier::create($data)->id),
                    Forms\Components\TextInput::make('satuan')->label('Satuan (pcs/renceng/dll)')->default('pcs'),
                    Forms\Components\DatePicker::make('tanggal_expired')->label('Tanggal Kadaluarsa'),
                    Forms\Components\TextInput::make('lokasi_penyimpanan')->label('Lokasi Simpan'),
                ])->columns(2),

            Forms\Components\Section::make('Harga & Stok (Konsinyasi)')
                ->description('Sistem konsinyasi: barang dari supplier dititipkan dan dibayar setelah terjual.')
                ->schema([
                    Forms\Components\TextInput::make('harga_beli')->label('Harga Beli dari Supplier (Rp)')->numeric()->prefix('Rp')->required(),
                    Forms\Components\TextInput::make('harga_jual')->label('Harga Jual ke Pelanggan (Rp)')->numeric()->prefix('Rp')->required(),
                    Forms\Components\TextInput::make('jumlah_stok')->label('Jumlah Stok Masuk')->numeric()->default(0)->required(),
                ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sku')->label('SKU')->searchable()->weight('bold'),
                Tables\Columns\TextColumn::make('nama_barang')->label('Nama Barang')->searchable(),
                Tables\Columns\TextColumn::make('suplier.nama_suplier')->label('Supplier'),
                Tables\Columns\TextColumn::make('jumlah_stok')->label('Stok')->alignCenter()
                    ->color(fn ($record) => $record->jumlah_stok <= 5 ? 'danger' : 'success'),
                Tables\Columns\TextColumn::make('satuan')->label('Satuan'),
                Tables\Columns\TextColumn::make('harga_beli')->label('Harga Beli')->money('IDR'),
                Tables\Columns\TextColumn::make('harga_jual')->label('Harga Jual')->money('IDR'),
                Tables\Columns\TextColumn::make('keuntungan')
                    ->label('Keuntungan/item')
                    ->getStateUsing(fn ($record) => $record->harga_jual - $record->harga_beli)
                    ->money('IDR')
                    ->color('success'),
                Tables\Columns\TextColumn::make('tanggal_expired')
                    ->label('Kadaluarsa')
                    ->date('d M Y')
                    ->color(fn ($record) => $record->tanggal_expired && Carbon::parse($record->tanggal_expired)->isPast() ? 'danger' : 'success'),
                Tables\Columns\BadgeColumn::make('expired_status')
                    ->label('Status')
                    ->getStateUsing(function ($record) {
                        if (!$record->tanggal_expired) return 'OK';
                        $exp = Carbon::parse($record->tanggal_expired);
                        if ($exp->isPast()) return '⚠ KADALUARSA';
                        if ($exp->diffInDays(now()) <= 7) return '⚠ Hampir Exp';
                        return 'OK';
                    })
                    ->colors([
                        'danger'  => fn ($state) => str_contains($state, 'KADALUARSA'),
                        'warning' => fn ($state) => str_contains($state, 'Hampir'),
                        'success' => 'OK',
                    ]),
            ])
            ->filters([
                Tables\Filters\Filter::make('expired')
                    ->label('Sudah Kadaluarsa')
                    ->query(fn ($query) => $query->where('tanggal_expired', '<', now())),
                Tables\Filters\Filter::make('hampir_expired')
                    ->label('Hampir Kadaluarsa (7 hari)')
                    ->query(fn ($query) => $query->whereBetween('tanggal_expired', [now(), now()->addDays(7)])),
                Tables\Filters\Filter::make('stok_habis')
                    ->label('Stok Hampir Habis (≤5)')
                    ->query(fn ($query) => $query->where('jumlah_stok', '<=', 5)),
            ])
            ->actions([
                Action::make('laporan_supplier')
                    ->label('Laporan Supplier')
                    ->icon('heroicon-o-document-chart-bar')
                    ->color('info')
                    ->url(fn ($record) => route('admin.laporan.supplier', $record->suplier_id))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListStokBarangKerings::route('/'),
            'create' => Pages\CreateStokBarangKering::route('/create'),
            'edit'   => Pages\EditStokBarangKering::route('/{record}/edit'),
        ];
    }
}
