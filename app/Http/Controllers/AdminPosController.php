<?php

namespace App\Http\Controllers;

use App\Models\ProdukPos;
use App\Models\PosTransaction;
use App\Models\PosTransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminPosController extends Controller
{
    public function index(Request $request)
    {
        $kategori = $request->get('kategori');
        $query = ProdukPos::query();
        
        if ($kategori) {
            $query->where('kategori', $kategori);
        }
        
        $menus = $query->orderBy('nama_produk')->get();
        $semuaKategori = ProdukPos::select('kategori')->distinct()->pluck('kategori');
        
        $today = today()->toDateString();
        $stocks = \App\Models\DailyStock::where('tanggal', $today)->pluck('stok_sisa', 'produk_pos_id');

        return view('admin.pos.index', compact('menus', 'semuaKategori', 'kategori', 'stocks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:produk_pos,id',
            'items.*.qty' => 'required|integer|min:1',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
            'total_amount' => 'required|numeric|min:0'
        ]);

        $receiptNumber = 'POS-' . strtoupper(Str::random(6));

        $transaction = PosTransaction::create([
            'receipt_number' => $receiptNumber,
            'type' => 'Walk-in',
            'total_amount' => $request->total_amount,
            'payment_method' => $request->payment_method,
            'notes' => $request->notes,
        ]);

        $today = today()->toDateString();

        foreach ($request->items as $item) {
            $menu = ProdukPos::find($item['id']);
            if ($menu) {
                PosTransactionItem::create([
                    'pos_transaction_id' => $transaction->id,
                    'item_type' => 'App\Models\ProdukPos',
                    'item_id' => $menu->id,
                    'item_name' => $menu->nama_produk,
                    'qty' => $item['qty'],
                    'price_per_item' => $menu->harga,
                    'subtotal' => $menu->harga * $item['qty'],
                ]);

                // Kurangi stok harian
                $dailyStock = \App\Models\DailyStock::where('produk_pos_id', $menu->id)->where('tanggal', $today)->first();
                if ($dailyStock) {
                    $dailyStock->stok_terjual += $item['qty'];
                    $dailyStock->stok_sisa = $dailyStock->stok_awal - $dailyStock->stok_terjual;
                    $dailyStock->save();
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil disimpan!',
            'receipt_number' => $receiptNumber
        ]);
    }
}
