@extends('layouts.admin')

@section('title', 'Detail Pesanan')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <a href="{{ route('admin.orders.index') }}" class="text-sm text-green-600 hover:text-green-700 flex items-center gap-1 mb-2">
            &larr; Kembali ke Daftar Pesanan
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Detail Pesanan #{{ $order->id }}</h1>
        <p class="text-gray-500">Waktu pesan: {{ $order->created_at->format('d F Y, H:i') }}</p>
    </div>
    
    <div class="flex gap-2">
        <a href="{{ route('admin.struk.print', $order->id) }}" target="_blank" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-md shadow-sm text-sm font-medium">Cetak Struk</a>
        <a href="{{ route('admin.spk.print', $order->id) }}" target="_blank" class="bg-gray-800 border border-transparent text-white hover:bg-gray-900 px-4 py-2 rounded-md shadow-sm text-sm font-medium">Cetak SPK</a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Kolom Kiri: Info Pelanggan & Pesanan -->
    <div class="md:col-span-2 space-y-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Informasi Pelanggan</h2>
            </div>
            <div class="p-6">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nama Lengkap</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ $order->nama_pelanggan }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nomor Telepon / WA</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ $order->no_telpon }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Metode Pengiriman</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            @if($order->metode_kirim == 'internal')
                                Kurir Internal (Dalaraos)
                            @elseif($order->metode_kirim == 'gosend')
                                Gosend / Ekspedisi (Pesan Sendiri)
                            @else
                                Ambil di Resto (Jemput Sendiri)
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Alamat Lengkap</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            @if($order->alamat)
                                {{ $order->alamat }}<br>
                                @if($order->kecamatan) {{ $order->kecamatan }}, @endif
                                {{ $order->kode_pos ?? '' }}
                            @else
                                <span class="text-gray-400 italic">Tidak ada alamat (diambil sendiri/gosend)</span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Rincian Pesanan</h2>
            </div>
            <div class="p-6">
                <!-- Items list... in reality this needs JSON decode from order logic, 
                     assuming subtotal / dessert / ongkir for now -->
                
                <div class="mt-6 border-t border-gray-200 pt-4">
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Total Paket Makanan</dt>
                            <dd class="text-sm font-medium text-gray-900">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Total Minuman & Snack</dt>
                            <dd class="text-sm font-medium text-gray-900">Rp {{ number_format($order->dessert_total ?? 0, 0, ',', '.') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Ongkos Kirim</dt>
                            <dd class="text-sm font-medium text-gray-900">Rp {{ number_format($order->ongkir ?? 0, 0, ',', '.') }}</dd>
                        </div>
                        <div class="flex justify-between border-t border-gray-200 pt-3">
                            <dt class="text-base font-bold text-gray-900">Grand Total</dt>
                            <dd class="text-base font-bold text-green-600">Rp {{ number_format($order->total, 0, ',', '.') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Status & Pembayaran -->
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Kelola Status Pesanan</h2>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status Pembayaran</label>
                        <select name="status_pembayaran" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md">
                            <option value="Menunggu Pembayaran" {{ $order->status_pembayaran == 'Menunggu Pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                            <option value="Menunggu Verifikasi Admin" {{ $order->status_pembayaran == 'Menunggu Verifikasi Admin' ? 'selected' : '' }}>Menunggu Verifikasi Admin</option>
                            <option value="Lunas" {{ $order->status_pembayaran == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                            <option value="Gagal" {{ $order->status_pembayaran == 'Gagal' ? 'selected' : '' }}>Gagal</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status Pesanan</label>
                        <select name="status_pesanan" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md">
                            <option value="Menunggu Konfirmasi" {{ $order->status_pesanan == 'Menunggu Konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                            <option value="Diproses" {{ $order->status_pesanan == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="Siap Diambil" {{ $order->status_pesanan == 'Siap Diambil' ? 'selected' : '' }}>Siap Diambil</option>
                            <option value="Dikirim" {{ $order->status_pesanan == 'Dikirim' ? 'selected' : '' }}>Dikirim</option>
                            <option value="Selesai" {{ $order->status_pesanan == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="Dibatalkan" {{ $order->status_pesanan == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Internal Admin</label>
                        <textarea name="catatan_admin" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm" placeholder="Catatan hanya bisa dilihat admin...">{{ $order->catatan_admin }}</textarea>
                    </div>

                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Bukti Pembayaran</h2>
            </div>
            <div class="p-6">
                @if($order->bukti_pembayaran)
                    <a href="{{ Storage::url($order->bukti_pembayaran) }}" target="_blank" class="block w-full">
                        <img src="{{ Storage::url($order->bukti_pembayaran) }}" alt="Bukti Transfer" class="w-full h-auto rounded border shadow-sm hover:opacity-90 transition-opacity">
                    </a>
                    <p class="text-xs text-center text-gray-500 mt-2">Klik gambar untuk memperbesar</p>
                @else
                    <div class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-md p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="mt-2 block text-sm font-medium text-gray-900">Belum ada bukti</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
