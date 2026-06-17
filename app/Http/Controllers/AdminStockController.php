<?php

namespace App\Http\Controllers;

use App\Models\DailyStock;
use App\Models\ProdukPos;

use App\Models\StokMasuk;

use Illuminate\Http\Request;

class AdminStockController extends Controller
{
    // --- STOK HARIAN (POS) ---
    public function dailyIndex()
    {
        $today = today()->toDateString();
        // Pastikan semua produk pos ada di daily stock hari ini
        $menus = ProdukPos::all();
        
        foreach ($menus as $menu) {
            DailyStock::firstOrCreate(
                ['produk_pos_id' => $menu->id, 'tanggal' => $today],
                ['stok_awal' => 0, 'stok_terjual' => 0, 'stok_sisa' => 0, 'status' => 'Aktif']
            );
        }

        $stocks = DailyStock::with('produkPos')
                    ->where('tanggal', $today)
                    ->get();

        return view('admin.stocks.daily', compact('stocks', 'today'));
    }

    public function dailyUpdate(Request $request, $id)
    {
        $stock = DailyStock::findOrFail($id);
        $request->validate([
            'stok_awal' => 'required|integer|min:0',
        ]);

        $stock->stok_awal = $request->stok_awal;
        $stock->stok_sisa = $stock->stok_awal - $stock->stok_terjual;
        $stock->save();

        return redirect()->route('admin.stocks.daily')->with('success', 'Stok harian berhasil diperbarui.');
    }



    // --- RIWAYAT STOK MASUK ---
    public function masukIndex()
    {
        $riwayat = StokMasuk::orderBy('tanggal', 'desc')->orderBy('created_at', 'desc')->paginate(20);
        $menus = ProdukPos::all();
        
        return view('admin.stocks.masuk', compact('riwayat', 'menus'));
    }

    public function masukStore(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:produk_pos,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $menu = ProdukPos::findOrFail($request->barang_id);

        StokMasuk::create([
            'tipe_barang' => 'Stok POS',
            'barang_id' => $menu->id,
            'nama_barang' => $menu->nama_produk,
            'jumlah' => $request->jumlah,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
        ]);

        // Tambah stok harian ke POS jika tanggalnya hari ini
        if ($request->tanggal === today()->toDateString()) {
            $daily = DailyStock::firstOrCreate(
                ['produk_pos_id' => $menu->id, 'tanggal' => today()->toDateString()],
                ['stok_awal' => 0, 'stok_terjual' => 0, 'stok_sisa' => 0, 'status' => 'Aktif']
            );
            $daily->stok_awal += $request->jumlah;
            $daily->stok_sisa = $daily->stok_awal - $daily->stok_terjual;
            $daily->save();
        }

        return redirect()->route('admin.stocks.masuk')->with('success', 'Stok masuk berhasil dicatat.');
    }
}
