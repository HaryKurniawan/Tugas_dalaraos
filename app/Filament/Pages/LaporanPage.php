<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\PesananKatering;
use App\Models\PosTransaction;
use App\Models\StokBarangKering;
use App\Models\DailyStock;
use Carbon\Carbon;

class LaporanPage extends Page
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Laporan';
    protected static ?string $title = 'Laporan Keseluruhan';
    protected static ?int $navigationSort = 10;
    protected string $view = 'filament.pages.laporan-page';

    public string $period = 'today';
    public ?string $date_from = null;
    public ?string $date_to   = null;

    public function mount(): void
    {
        $this->date_from = today()->toDateString();
        $this->date_to   = today()->toDateString();
    }

    public function setPeriod(string $period): void
    {
        $this->period = $period;
        match ($period) {
            'today'   => [$this->date_from, $this->date_to] = [today()->toDateString(), today()->toDateString()],
            'week'    => [$this->date_from, $this->date_to] = [now()->startOfWeek()->toDateString(), now()->endOfWeek()->toDateString()],
            'month'   => [$this->date_from, $this->date_to] = [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()],
            default   => null,
        };
    }

    protected function getViewData(): array
    {
        $from = Carbon::parse($this->date_from)->startOfDay();
        $to   = Carbon::parse($this->date_to)->endOfDay();

        // === KATERING ===
        $katerings = PesananKatering::whereBetween('created_at', [$from, $to])
            ->where('status_pembayaran', 'Lunas')->get();
        $totalKatering = $katerings->sum('total');

        // === POS (Dine-in / Take-away) ===
        $posTx = PosTransaction::whereBetween('created_at', [$from, $to])
            ->whereIn('type', ['Dine-in', 'Take-away'])->get();
        $totalPos = $posTx->sum('total_amount');

        // === BARANG KERING / KONSINYASI ===
        $keringanTx = PosTransaction::whereBetween('created_at', [$from, $to])
            ->where('type', 'Keringan')->with('items')->get();

        $labaBersihKeringan = 0;
        $modalKeringan      = 0;
        foreach ($keringanTx as $tx) {
            foreach ($tx->items as $item) {
                $stok = StokBarangKering::find($item->item_id);
                if ($stok) {
                    $labaBersihKeringan += ($stok->harga_jual - $stok->harga_beli) * $item->qty;
                    $modalKeringan      += $stok->harga_beli * $item->qty;
                }
            }
        }
        $totalKeringanOmset = $keringanTx->sum('total_amount');

        return [
            'totalKatering'       => $totalKatering,
            'jumlahOrderKatering' => $katerings->count(),
            'totalPos'            => $totalPos,
            'jumlahTxPos'         => $posTx->count(),
            'totalKeringanOmset'  => $totalKeringanOmset,
            'labaBersihKeringan'  => $labaBersihKeringan,
            'modalKeringan'       => $modalKeringan,
            'grandTotal'          => $totalKatering + $totalPos + $totalKeringanOmset,
            'totalLaba'           => $totalKatering + $totalPos + $labaBersihKeringan,
            'katerings'           => $katerings,
            'posTx'               => $posTx,
            'keringanTx'          => $keringanTx,
        ];
    }
}
