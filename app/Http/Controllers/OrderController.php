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
        $menuId = $request->input('menu_id');
        $menuData = Menu::find($menuId);

        if (!$menuData) {
            return redirect()->back()->with('error', 'Menu tidak valid.');
        }

        // Base Calculations
        $qty = (int) $request->input('jumlah_porsi', 0);
        $basePrice = $menuData->harga;
        $subtotal = $qty * $basePrice;

        // Dessert Calculations dinamis
        $dessertsInput = $request->input('desserts', []);
        $dessertTotal = 0;
        
        $sarikayaQty = 0;
        $pudingQty = 0;
        $jerukQty = 0;
        $pisangQty = 0;

        foreach ($dessertsInput as $dsId => $dsQty) {
            $dsQty = (int) $dsQty;
            if ($dsQty > 0) {
                $dsMenu = Menu::find($dsId);
                if ($dsMenu) {
                    $dessertTotal += $dsMenu->harga * $dsQty;
                    if ($dsMenu->sku == 'ds1') $sarikayaQty = $dsQty;
                    if ($dsMenu->sku == 'ds2') $pudingQty = $dsQty;
                    if ($dsMenu->sku == 'ds3') $jerukQty = $dsQty;
                    if ($dsMenu->sku == 'ds4') $pisangQty = $dsQty;
                }
            }
        }

        // Shipping Calculations
        $metodeKirim = $request->input('metode_kirim'); // gosend, ambil_sendiri, internal
        $ongkir = 0;
        $kecamatanStr = null;

        if ($qty > 70) {
            $metodeKirim = 'internal';
        } elseif ($metodeKirim === 'internal') {
            $metodeKirim = 'gosend';
        }

        if ($metodeKirim === 'internal') {
            $ongkir = (float) $request->input('kecamatan_id', 0);
            $kecamatanStr = $request->input('kecamatan_name', null);
        }

        $grandTotal = $subtotal + $dessertTotal + $ongkir;

        $alamat_lengkap = $request->input('alamat');
        if ($kecamatanStr) {
            $alamat_lengkap .= ', Kec. ' . $kecamatanStr;
        }
        if ($request->input('kode_pos')) {
            $alamat_lengkap .= ', Kode Pos: ' . $request->input('kode_pos');
        }

        // Prepare order data but DO NOT save yet
        $orderData = [
            'id' => 'ORD-' . strtoupper(Str::random(6)), // Generate ID early
            'nama_pelanggan' => $request->input('nama_pemesan'),
            'no_telpon' => $request->input('no_wa'),
            'tanggal_acara' => $request->input('tanggal_acara'),
            'jumlah_porsi' => $qty,
            'alamat' => $alamat_lengkap,
            'kecamatan' => $kecamatanStr,
            'menu' => $menuData->nama_menu,
            'menu_id' => $menuData->id,
            'total' => $grandTotal,
            'ongkir' => $ongkir,
            'metode_kirim' => $metodeKirim,
            'catatan' => $request->input('catatan'),
            'sarikaya_qty' => $sarikayaQty,
            'puding_coklat_qty' => $pudingQty,
            'jeruk_qty' => $jerukQty,
            'pisang_qty' => $pisangQty,
            'status_pembayaran' => 'Menunggu Verifikasi Admin',
            'status_pesanan' => 'Menunggu Konfirmasi',
        ];

        // Save to session
        session()->put('checkout_data', $orderData);

        // Redirect to payment page
        return redirect('/pembayaran')->with('info', 'Satu langkah lagi! Silakan unggah bukti pembayaran untuk menyelesaikan pesanan Anda.');
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

    public function show($id)
    {
        $pesanan = PesananKatering::where('id', $id)->firstOrFail();
        return view('pelanggan.detail_pesanan', compact('pesanan'));
    }

    public function pembayaran()
    {
        $data = session('checkout_data');

        if (!$data) {
            return redirect('/')->with('error', 'Tidak ada data pesanan. Silakan checkout terlebih dahulu.');
        }

        return view('pelanggan.pembayaran', compact('data'));
    }

    public function prosesPembayaran(Request $request)
    {
        $data = session('checkout_data');

        if (!$data) {
            return response()->json(['success' => false, 'message' => 'Sesi pembayaran telah habis atau tidak valid.'], 400);
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $order = new PesananKatering();
        // Mass assign from session data
        $order->id = $data['id'];
        $order->nama_pelanggan = $data['nama_pelanggan'];
        $order->no_telpon = $data['no_telpon'];
        $order->tanggal_acara = $data['tanggal_acara'];
        $order->jumlah_porsi = $data['jumlah_porsi'];
        $order->alamat = $data['alamat'];
        $order->kecamatan = $data['kecamatan'];
        $order->menu = $data['menu'];
        $order->menu_id = $data['menu_id'];
        $order->total = $data['total'];
        $order->ongkir = $data['ongkir'];
        $order->metode_kirim = $data['metode_kirim'];
        $order->catatan = $data['catatan'];
        $order->sarikaya_qty = $data['sarikaya_qty'];
        $order->puding_coklat_qty = $data['puding_coklat_qty'];
        $order->jeruk_qty = $data['jeruk_qty'];
        $order->pisang_qty = $data['pisang_qty'];
        $order->status_pembayaran = $data['status_pembayaran'];
        $order->status_pesanan = $data['status_pesanan'];

        if ($request->hasFile('bukti_transfer')) {
            $path = $request->file('bukti_transfer')->store('bukti-transfer', 'public');
            $order->bukti_transfer = $path;
        }

        $order->save();

        // Clear session
        session()->forget('checkout_data');

        return response()->json([
            'success' => true, 
            'order_id' => $order->id,
            'message' => 'Bukti pembayaran berhasil diunggah! Pesanan Anda telah dibuat dan sedang menunggu verifikasi admin.'
        ]);
    }
}
