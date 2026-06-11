<x-filament-panels::page>

    {{-- Period Buttons --}}
    <div class="flex flex-wrap gap-3 mb-6">
        <button type="button" wire:click="setPeriod('today')"
            class="px-4 py-2 rounded-lg font-bold text-sm {{ $period === 'today' ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-700' }}">
            Hari Ini
        </button>
        <button type="button" wire:click="setPeriod('week')"
            class="px-4 py-2 rounded-lg font-bold text-sm {{ $period === 'week' ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-700' }}">
            Minggu Ini
        </button>
        <button type="button" wire:click="setPeriod('month')"
            class="px-4 py-2 rounded-lg font-bold text-sm {{ $period === 'month' ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-700' }}">
            Bulan Ini
        </button>

        <div class="flex items-center gap-2 ml-auto">
            <input type="date" wire:model="date_from" class="border-gray-300 rounded-lg text-sm shadow-sm focus:ring-primary-500 focus:border-primary-500">
            <span class="text-gray-500 text-sm">s/d</span>
            <input type="date" wire:model="date_to" class="border-gray-300 rounded-lg text-sm shadow-sm focus:ring-primary-500 focus:border-primary-500">
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm">
            <p class="text-xs font-bold text-gray-500 mb-1 uppercase tracking-wide">Katering Lunas</p>
            <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalKatering, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $jumlahOrderKatering }} pesanan</p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm">
            <p class="text-xs font-bold text-gray-500 mb-1 uppercase tracking-wide">POS Dine-in/Take-away</p>
            <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalPos, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $jumlahTxPos }} transaksi</p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm">
            <p class="text-xs font-bold text-gray-500 mb-1 uppercase tracking-wide">Omset Barang Kering</p>
            <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalKeringanOmset, 0, ',', '.') }}</p>
            <p class="text-xs text-green-600 mt-1">Laba: Rp {{ number_format($labaBersihKeringan, 0, ',', '.') }}</p>
        </div>
        <div class="bg-gradient-to-br from-primary-600 to-primary-700 rounded-xl p-5 shadow-md">
            <p class="text-xs font-bold text-primary-100 mb-1 uppercase tracking-wide">Total Pemasukan</p>
            <p class="text-2xl font-bold text-white">Rp {{ number_format($grandTotal, 0, ',', '.') }}</p>
            <p class="text-xs text-primary-200 mt-1">Laba Bersih: Rp {{ number_format($totalLaba, 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- Detail Tables --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Katering Detail --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-gray-900">📋 Pesanan Katering Lunas</h3>
                <a href="{{ route('admin.laporan.print', ['from' => $date_from, 'to' => $date_to]) }}" target="_blank"
                   class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1 rounded-full font-bold transition">
                    🖨 Cetak PDF
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                        <tr>
                            <th class="px-4 py-3 text-left">Tiket</th>
                            <th class="px-4 py-3 text-left">Pemesan</th>
                            <th class="px-4 py-3 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($katerings as $k)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-bold text-primary-700">{{ $k->id }}</td>
                            <td class="px-4 py-3">{{ $k->nama_pelanggan }}</td>
                            <td class="px-4 py-3 text-right font-bold">Rp {{ number_format($k->total, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="px-4 py-6 text-center text-gray-400">Tidak ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- POS & Keringan --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-gray-100">
                <h3 class="font-bold text-gray-900">🧾 Riwayat Transaksi POS</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                        <tr>
                            <th class="px-4 py-3 text-left">No. Struk</th>
                            <th class="px-4 py-3 text-left">Tipe</th>
                            <th class="px-4 py-3 text-left">Bayar</th>
                            <th class="px-4 py-3 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @php $allTx = $posTx->merge($keringanTx)->sortByDesc('created_at'); @endphp
                        @forelse($allTx as $tx)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-mono text-xs font-bold text-gray-700">{{ $tx->receipt_number }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 rounded-full text-xs font-bold
                                    {{ $tx->type === 'Keringan' ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700' }}">
                                    {{ $tx->type }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $tx->payment_method }}</td>
                            <td class="px-4 py-3 text-right font-bold">Rp {{ number_format($tx->total_amount, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-4 py-6 text-center text-gray-400">Tidak ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</x-filament-panels::page>
