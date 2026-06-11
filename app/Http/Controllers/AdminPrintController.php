<?php

namespace App\Http\Controllers;

use App\Models\PesananKatering;
use App\Models\PosTransaction;
use App\Models\StokBarangKering;
use App\Models\Suplier;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminPrintController extends Controller
{
    /**
     * Print SPK Katering (2 rangkap)
     */
    public function spk(string $id)
    {
        $pesanan = PesananKatering::findOrFail($id);
        return view('print.spk', compact('pesanan'));
    }

    /**
     * Print POS Receipt / Struk
     */
    public function struk(int $id)
    {
        $tx = PosTransaction::with('items')->findOrFail($id);
        return view('print.struk', compact('tx'));
    }

    /**
     * Print Laporan Supplier Konsinyasi
     */
    public function laporanSupplier(int $suplier_id)
    {
        $suplier = Suplier::findOrFail($suplier_id);
        $barangs = StokBarangKering::where('suplier_id', $suplier_id)->get();

        // Calculate totals
        $totalModal        = 0;
        $totalOmset        = 0;
        $totalLaba         = 0;
        $totalBayarSupplier = 0;

        foreach ($barangs as $b) {
            // Assume stok_awal = stok saat ini + yang sudah terjual
            // We track via POS transaction items
            $terjual = \App\Models\PosTransactionItem::where('item_type', 'Keringan')
                ->where('item_id', $b->id)->sum('qty');

            $modal   = $terjual * $b->harga_beli;
            $omset   = $terjual * $b->harga_jual;
            $laba    = $omset - $modal;

            $totalModal        += $modal;
            $totalOmset        += $omset;
            $totalLaba         += $laba;
            $totalBayarSupplier += $modal; // Bayar supplier = harga beli * qty terjual
        }

        return view('print.laporan_supplier', compact(
            'suplier', 'barangs',
            'totalModal', 'totalOmset', 'totalLaba', 'totalBayarSupplier'
        ));
    }

    /**
     * Print Laporan Keseluruhan
     */
    public function laporanKeseluruhan(Request $request)
    {
        $from = $request->input('from', today()->toDateString());
        $to   = $request->input('to',   today()->toDateString());

        $fromDt = Carbon::parse($from)->startOfDay();
        $toDt   = Carbon::parse($to)->endOfDay();

        $katerings = \App\Models\PesananKatering::whereBetween('created_at', [$fromDt, $toDt])
            ->where('status_pembayaran', 'Lunas')->get();
        $totalKatering = $katerings->sum('total');

        $posTx = PosTransaction::whereBetween('created_at', [$fromDt, $toDt])
            ->whereIn('type', ['Dine-in', 'Take-away'])->get();
        $totalPos = $posTx->sum('total_amount');

        $keringanTx = PosTransaction::whereBetween('created_at', [$fromDt, $toDt])
            ->where('type', 'Keringan')->with('items')->get();

        $labaBersihKeringan = 0;
        $totalKeringanOmset = $keringanTx->sum('total_amount');
        foreach ($keringanTx as $tx) {
            foreach ($tx->items as $item) {
                $stok = StokBarangKering::find($item->item_id);
                if ($stok) {
                    $labaBersihKeringan += ($stok->harga_jual - $stok->harga_beli) * $item->qty;
                }
            }
        }

        $grandTotal = $totalKatering + $totalPos + $totalKeringanOmset;
        $totalLaba  = $totalKatering + $totalPos + $labaBersihKeringan;

        return view('print.laporan_keseluruhan', compact(
            'from', 'to',
            'katerings', 'totalKatering', 'jumlahOrderKatering',
            'posTx', 'totalPos', 'jumlahTxPos',
            'keringanTx', 'totalKeringanOmset', 'labaBersihKeringan',
            'grandTotal', 'totalLaba'
        ))->with([
            'jumlahOrderKatering' => $katerings->count(),
            'jumlahTxPos'         => $posTx->count(),
        ]);
    }
}
