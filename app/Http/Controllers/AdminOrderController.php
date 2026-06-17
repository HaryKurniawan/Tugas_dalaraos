<?php

namespace App\Http\Controllers;

use App\Models\PesananKatering;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function dashboard()
    {
        $totalPesanan = PesananKatering::count();
        $pesananBaru = PesananKatering::where('status_pesanan', 'Menunggu Konfirmasi')->count();
        $menungguPembayaran = PesananKatering::whereIn('status_pembayaran', ['Menunggu Pembayaran', 'Menunggu Verifikasi Admin'])->count();
        
        // Pendapatan dari pesanan yang sudah lunas
        $pendapatan = PesananKatering::where('status_pembayaran', 'Lunas')->sum('total');

        $pesananTerbaru = PesananKatering::orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.dashboard', compact('totalPesanan', 'pesananBaru', 'menungguPembayaran', 'pendapatan', 'pesananTerbaru'));
    }

    public function index(Request $request)
    {
        $query = PesananKatering::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('id', 'like', "%{$search}%")
                  ->orWhere('nama_pelanggan', 'like', "%{$search}%")
                  ->orWhere('no_telpon', 'like', "%{$search}%");
        }

        if ($request->filled('status_pesanan')) {
            $query->where('status_pesanan', $request->status_pesanan);
        }

        if ($request->filled('status_pembayaran')) {
            $query->where('status_pembayaran', $request->status_pembayaran);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = PesananKatering::findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $order = PesananKatering::findOrFail($id);

        $request->validate([
            'status_pesanan' => 'required|string',
            'status_pembayaran' => 'required|string',
            'catatan_admin' => 'nullable|string',
        ]);

        $order->status_pesanan = $request->status_pesanan;
        $order->status_pembayaran = $request->status_pembayaran;
        $order->catatan_admin = $request->catatan_admin;
        $order->save();

        return redirect()->route('admin.orders.show', $order->id)->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
