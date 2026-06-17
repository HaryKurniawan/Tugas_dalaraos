@extends('layouts.admin')

@section('title', 'Riwayat Stok Masuk')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Riwayat Stok Masuk</h1>
        <p class="text-gray-500">Catat penerimaan barang dari suplier ke gudang</p>
    </div>
    <button onclick="openModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium shadow-sm">
        + Catat Stok Masuk
    </button>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Menu (POS)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Masuk</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($riwayat as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $item->tanggal->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                        {{ $item->nama_barang }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-bold text-green-600">+ {{ $item->jumlah }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $item->keterangan ?? '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">Belum ada riwayat stok masuk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $riwayat->links() }}
    </div>
</div>

<!-- Modal Pencatatan -->
<div id="create-modal" class="fixed z-50 inset-0 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form action="{{ route('admin.stocks.masuk.store') }}" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Catat Penerimaan Stok</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Masuk</label>
                            <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="mt-1 w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 sm:text-sm" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Menu / Item (Stok POS)</label>
                            <select name="barang_id" class="mt-1 w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 sm:text-sm" required>
                                <option value="">-- Pilih Menu/Item --</option>
                                @foreach($menus as $menu)
                                    <option value="{{ $menu->id }}">{{ $menu->nama_produk }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Stok harian untuk hari ini akan otomatis ditambahkan.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jumlah Masuk</label>
                            <input type="number" name="jumlah" class="mt-1 w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 sm:text-sm" required min="1">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Keterangan Tambahan</label>
                            <textarea name="keterangan" rows="2" class="mt-1 w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 sm:text-sm" placeholder="No Invoice, dsb..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">Simpan</button>
                    <button type="button" onclick="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openModal() { document.getElementById('create-modal').classList.remove('hidden'); }
    function closeModal() { document.getElementById('create-modal').classList.add('hidden'); }
</script>
@endsection
