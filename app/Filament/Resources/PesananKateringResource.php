<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PesananKateringResource\Pages;
use App\Models\PesananKatering;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;

class PesananKateringResource extends Resource
{
    protected static ?string $model = PesananKatering::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Pesanan Katering';
    protected static ?string $navigationGroup = 'Katering';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Data Pemesan')
                ->schema([
                    Forms\Components\TextInput::make('nama_pelanggan')->label('Nama Pemesan')->required(),
                    Forms\Components\TextInput::make('no_telpon')->label('No. WhatsApp')->required(),
                    Forms\Components\DatePicker::make('tanggal_acara')->label('Tanggal Acara')->required(),
                    Forms\Components\TextInput::make('jumlah_porsi')->label('Jumlah Porsi')->numeric()->required(),
                    Forms\Components\TextInput::make('alamat')->label('Alamat Acara'),
                    Forms\Components\TextInput::make('kecamatan')->label('Kecamatan'),
                    Forms\Components\Textarea::make('catatan')->label('Catatan / Detail Menu')->rows(4),
                ])->columns(2),

            Forms\Components\Section::make('Status & Verifikasi')
                ->schema([
                    Forms\Components\Select::make('status_pembayaran')
                        ->label('Status Pembayaran')
                        ->options([
                            'Menunggu Pembayaran' => 'Menunggu Pembayaran',
                            'Lunas' => 'Lunas',
                            'Dibatalkan' => 'Dibatalkan',
                        ])
                        ->required(),
                    Forms\Components\Select::make('status_pesanan')
                        ->label('Status Pesanan')
                        ->options([
                            'Menunggu Konfirmasi' => 'Menunggu Konfirmasi',
                            'Dikonfirmasi' => 'Dikonfirmasi',
                            'Diproses Dapur' => 'Diproses Dapur',
                            'Siap Kirim' => 'Siap Kirim',
                            'Selesai' => 'Selesai',
                            'Dibatalkan' => 'Dibatalkan',
                        ])
                        ->required(),
                    Forms\Components\Textarea::make('catatan_admin')->label('Catatan Admin')->rows(3),
                ])->columns(2),

            Forms\Components\Section::make('Pembayaran')
                ->schema([
                    Forms\Components\TextInput::make('total')->label('Total Harga')->prefix('Rp')->numeric(),
                    Forms\Components\TextInput::make('ongkir')->label('Ongkos Kirim')->prefix('Rp')->numeric(),
                    Forms\Components\FileUpload::make('bukti_transfer')->label('Bukti Transfer')->image()->directory('bukti-transfer'),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('No. Tiket')->searchable()->sortable()->weight('bold'),
                Tables\Columns\TextColumn::make('nama_pelanggan')->label('Nama Pemesan')->searchable(),
                Tables\Columns\TextColumn::make('no_telpon')->label('No. WA'),
                Tables\Columns\TextColumn::make('tanggal_acara')->label('Tgl. Acara')->date('d M Y')->sortable(),
                Tables\Columns\TextColumn::make('jumlah_porsi')->label('Porsi')->suffix(' pax')->alignCenter(),
                Tables\Columns\TextColumn::make('total')->label('Total')->money('IDR')->sortable(),
                Tables\Columns\BadgeColumn::make('status_pembayaran')
                    ->label('Pembayaran')
                    ->colors([
                        'danger'  => 'Menunggu Pembayaran',
                        'success' => 'Lunas',
                        'gray'    => 'Dibatalkan',
                    ]),
                Tables\Columns\BadgeColumn::make('status_pesanan')
                    ->label('Status')
                    ->colors([
                        'warning' => 'Menunggu Konfirmasi',
                        'info'    => fn ($state) => in_array($state, ['Dikonfirmasi', 'Diproses Dapur']),
                        'primary' => 'Siap Kirim',
                        'success' => 'Selesai',
                        'danger'  => 'Dibatalkan',
                    ]),
                Tables\Columns\TextColumn::make('created_at')->label('Dipesan')->dateTime('d M Y H:i')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_pesanan')
                    ->label('Status Pesanan')
                    ->options([
                        'Menunggu Konfirmasi' => 'Menunggu Konfirmasi',
                        'Dikonfirmasi' => 'Dikonfirmasi',
                        'Diproses Dapur' => 'Diproses Dapur',
                        'Siap Kirim' => 'Siap Kirim',
                        'Selesai' => 'Selesai',
                    ]),
                Tables\Filters\SelectFilter::make('status_pembayaran')
                    ->label('Status Pembayaran')
                    ->options([
                        'Menunggu Pembayaran' => 'Menunggu Pembayaran',
                        'Lunas' => 'Lunas',
                    ]),
            ])
            ->actions([
                // Quick status change actions
                Action::make('konfirmasi')
                    ->label('Konfirmasi')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status_pesanan === 'Menunggu Konfirmasi')
                    ->action(function ($record) {
                        $record->update(['status_pesanan' => 'Dikonfirmasi']);
                        Notification::make()->title('Pesanan dikonfirmasi')->success()->send();
                    }),

                Action::make('proses_dapur')
                    ->label('Proses Dapur')
                    ->icon('heroicon-o-fire')
                    ->color('warning')
                    ->visible(fn ($record) => $record->status_pesanan === 'Dikonfirmasi')
                    ->action(function ($record) {
                        $record->update(['status_pesanan' => 'Diproses Dapur']);
                        Notification::make()->title('Status diubah ke: Diproses Dapur')->success()->send();
                    }),

                Action::make('siap_kirim')
                    ->label('Siap Kirim')
                    ->icon('heroicon-o-truck')
                    ->color('info')
                    ->visible(fn ($record) => $record->status_pesanan === 'Diproses Dapur')
                    ->action(function ($record) {
                        $record->update(['status_pesanan' => 'Siap Kirim']);
                        Notification::make()->title('Status diubah ke: Siap Kirim')->success()->send();
                    }),

                Action::make('selesai')
                    ->label('Selesai')
                    ->icon('heroicon-o-flag')
                    ->color('success')
                    ->visible(fn ($record) => $record->status_pesanan === 'Siap Kirim')
                    ->action(function ($record) {
                        $record->update(['status_pesanan' => 'Selesai', 'status_pembayaran' => 'Lunas']);
                        Notification::make()->title('Pesanan selesai!')->success()->send();
                    }),

                Action::make('verifikasi_bayar')
                    ->label('Verifikasi Lunas')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->visible(fn ($record) => $record->status_pembayaran === 'Menunggu Pembayaran')
                    ->requiresConfirmation()
                    ->modalHeading('Verifikasi Pembayaran')
                    ->modalDescription('Tandai pesanan ini sebagai LUNAS?')
                    ->action(function ($record) {
                        $record->update(['status_pembayaran' => 'Lunas']);
                        Notification::make()->title('Pembayaran diverifikasi - LUNAS')->success()->send();
                    }),

                // Print SPK
                Action::make('cetak_spk')
                    ->label('Cetak SPK')
                    ->icon('heroicon-o-printer')
                    ->color('gray')
                    ->url(fn ($record) => route('admin.spk.print', $record->id))
                    ->openUrlInNewTab(),

                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPesananKaterings::route('/'),
            'create' => Pages\CreatePesananKatering::route('/create'),
            'edit'   => Pages\EditPesananKatering::route('/{record}/edit'),
        ];
    }
}
