@extends('layouts.admin')

@section('title', 'Daftar Pesanan')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Daftar Pesanan</h1>
        <p class="text-gray-500">Kelola semua pesanan pelanggan</p>
    </div>
</div>



<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="p-4 border-b border-gray-200 bg-gray-50">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="flex gap-4">
            
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari ID, Nama, atau No Telp..." class="rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm px-4 py-2 border w-1/3">
            
            
            <select name="status_pesanan" class="rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm px-4 py-2 border">
                <option value="">-- Semua Status Pesanan --</option>
                <option value="Menunggu Konfirmasi" {{ request('status_pesanan') == 'Menunggu Konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                <option value="Diproses" {{ request('status_pesanan') == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                <option value="Siap Diambil" {{ request('status_pesanan') == 'Siap Diambil' ? 'selected' : '' }}>Siap Diambil</option>
                <option value="Dikirim" {{ request('status_pesanan') == 'Dikirim' ? 'selected' : '' }}>Dikirim</option>
                <option value="Selesai" {{ request('status_pesanan') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="Dibatalkan" {{ request('status_pesanan') == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
            </select>

            <select name="status_pembayaran" class="rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm px-4 py-2 border">
                <option value="">-- Semua Status Bayar --</option>
                <option value="Menunggu Pembayaran" {{ request('status_pembayaran') == 'Menunggu Pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                <option value="Menunggu Verifikasi Admin" {{ request('status_pembayaran') == 'Menunggu Verifikasi Admin' ? 'selected' : '' }}>Menunggu Verifikasi Admin</option>
                <option value="Lunas" {{ request('status_pembayaran') == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                <option value="Gagal" {{ request('status_pembayaran') == 'Gagal' ? 'selected' : '' }}>Gagal</option>
            </select>


            <button type="submit" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-md shadow-sm text-sm font-medium">Filter</button>
            @if(request()->anyFilled(['search', 'status_pesanan', 'status_pembayaran']))
                <a href="{{ route('admin.orders.index') }}" class="text-gray-500 hover:text-gray-700 px-4 py-2 text-sm">Reset</a>
            @endif
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Pesanan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pembayaran</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Bayar</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Pesanan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $order->created_at->format('d M Y') }}<br>
                        <span class="text-xs">{{ $order->created_at->format('H:i') }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                        {{ $order->id }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $order->nama_pelanggan }}<br>
                        <span class="text-gray-500 text-xs">{{ $order->no_telpon }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        Rp {{ number_format($order->total, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($order->status_pembayaran == 'Lunas') bg-green-100 text-green-800
                            @elseif($order->status_pembayaran == 'Menunggu Verifikasi Admin') bg-yellow-100 text-yellow-800
                            @elseif($order->status_pembayaran == 'Menunggu Pembayaran') bg-gray-100 text-gray-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ $order->status_pembayaran }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full border
                            @if($order->status_pesanan == 'Menunggu Konfirmasi') border-yellow-200 text-yellow-800
                            @elseif($order->status_pesanan == 'Selesai') border-green-200 text-green-800
                            @elseif($order->status_pesanan == 'Dibatalkan') border-red-200 text-red-800
                            @else border-blue-200 text-blue-800 @endif">
                            {{ $order->status_pesanan }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="text-white bg-green-600 hover:bg-green-700 px-3 py-1.5 rounded text-xs font-semibold shadow-sm">Detail & Kelola</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                        Tidak ada data pesanan yang sesuai dengan filter.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $orders->links() }}
    </div>
</div>
@endsection
