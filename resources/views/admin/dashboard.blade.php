@extends('layouts.admin')

@section('title', 'Beranda')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Beranda Admin</h1>
    <p class="text-gray-500">Ringkasan aktivitas pesanan Dalaraos</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-gray-500 text-sm font-medium">Total Pesanan</h3>
        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalPesanan }}</p>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-gray-500 text-sm font-medium">Pesanan Baru</h3>
        <p class="text-3xl font-bold text-blue-600 mt-2">{{ $pesananBaru }}</p>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-gray-500 text-sm font-medium">Menunggu Pembayaran</h3>
        <p class="text-3xl font-bold text-orange-600 mt-2">{{ $menungguPembayaran }}</p>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-gray-500 text-sm font-medium">Pendapatan (Lunas)</h3>
        <p class="text-2xl font-bold text-green-600 mt-2">Rp {{ number_format($pendapatan, 0, ',', '.') }}</p>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-lg font-medium text-gray-900">Pesanan Terbaru</h2>
        <a href="{{ route('admin.orders.index') }}" class="text-sm text-green-600 hover:text-green-700 font-medium">Lihat Semua</a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Pesanan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($pesananTerbaru as $order)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="text-green-600 hover:underline">{{ $order->id }}</a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $order->nama_pelanggan }}<br>
                        <span class="text-xs text-gray-400">{{ $order->no_telpon }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        Rp {{ number_format($order->total, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($order->status_pesanan == 'Menunggu Konfirmasi') bg-yellow-100 text-yellow-800
                            @elseif($order->status_pesanan == 'Diproses') bg-blue-100 text-blue-800
                            @elseif($order->status_pesanan == 'Selesai') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ $order->status_pesanan }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada pesanan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
