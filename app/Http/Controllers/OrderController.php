<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PesananKatering;
use App\Models\Menu;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // For prototype purposes, we hardcode the menu data since the UI hardcodes it 
        // In a real app we'd fetch from Menu table
        $menus = [
            'm8' =>  ['price' => 35000, 'name' => 'Paket Khas Sunda – Timbel Standard'],
            'm9' =>  ['price' => 49000, 'name' => 'Paket Khas Sunda Premium'],
            'm10' => ['price' => 35000, 'name' => 'Paket Nasi Putih Standard'],
            'm12' => ['price' => 42000, 'name' => 'Paket Nasi Tumpeng Kuning'],
        ];

        $menuId = $request->input('menu_id');
        $menuData = $menus[$menuId] ?? null;

        if (!$menuData) {
            return redirect()->back()->with('error', 'Menu tidak valid.');
        }

        // Base Calculations
        $qty = (int) $request->input('jumlah_porsi', 0);
        $basePrice = $menuData['price'];
        $subtotal = $qty * $basePrice;

        // Dessert Calculations
        $sarikayaQty = (int) $request->input('sarikaya_qty', 0);
        $pudingQty = (int) $request->input('puding_coklat_qty', 0);
        $jerukQty = (int) $request->input('jeruk_qty', 0);
        $pisangQty = (int) $request->input('pisang_qty', 0);

        $dessertTotal = ($sarikayaQty * 5000) + ($pudingQty * 6000) + ($jerukQty * 5000) + ($pisangQty * 2500);

        // Shipping Calculations
        $metodeKirim = $request->input('metode_kirim'); // gosend, ambil_sendiri
        $ongkir = 0;
        $kecamatanStr = null;

        if ($qty >= 70) {
            $metodeKirim = 'internal';
            $ongkir = (float) $request->input('kecamatan_id', 0);
            $kecamatanStr = $request->input('kecamatan_name', null);
        }

        $grandTotal = $subtotal + $dessertTotal + $ongkir;

        // Create the order
        $order = new PesananKatering();
        // user_id is nullable (guest checkout)
        $order->nama_pelanggan = $request->input('nama_pemesan');
        $order->no_telpon = $request->input('no_wa');
        $order->tanggal_acara = $request->input('tanggal_acara');
        $order->jumlah_porsi = $qty;
        $order->alamat = $request->input('alamat');
        $order->kecamatan = $kecamatanStr;
        
        $order->menu = $menuData['name'];
        // $order->menu_id = ... (if we were mapping to DB menus)
        
        $order->total = $grandTotal;
        $order->ongkir = $ongkir;
        $order->metode_kirim = $metodeKirim;
        $order->catatan = $request->input('catatan');
        
        $order->sarikaya_qty = $sarikayaQty;
        $order->puding_coklat_qty = $pudingQty;
        $order->jeruk_qty = $jerukQty;
        $order->pisang_qty = $pisangQty;
        
        $order->status_pembayaran = 'Menunggu Verifikasi Admin';
        $order->status_pesanan = 'Draft';

        $order->save();

        // Redirect to tracking page with the new ticket ID
        return redirect('/lacak?query=' . $order->id)->with('success', 'Pesanan berhasil dibuat!');
    }

    public function track(Request $request)
    {
        $query = $request->query('query');
        $pesanan = null;

        if ($query) {
            // Find by exact Ticket ID or Phone Number
            $pesanan = PesananKatering::where('id', $query)
                ->orWhere('no_telpon', $query)
                ->orderBy('created_at', 'desc')
                ->first();
        }

        return view('pelanggan.lacak_pesanan', compact('pesanan'));
    }
}
