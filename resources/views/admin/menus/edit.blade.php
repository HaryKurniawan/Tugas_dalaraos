@extends('layouts.admin')

@section('title', 'Edit Menu')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 max-w-4xl">

    {{-- Header --}}
    <div class="mb-6">
        <a href="{{ route('admin.menus.index') }}" class="text-sm text-green-600 hover:underline">← Kembali ke Daftar Menu</a>
        <h1 class="text-2xl font-semibold text-gray-900 mt-2">Edit Menu: <span class="text-green-700">{{ $menu->nama_menu }}</span></h1>
    </div>

    {{-- Error Validation --}}
    @if($errors->any())
        <div class="mb-5 bg-red-50 border-l-4 border-red-400 p-4 rounded-md">
            <p class="text-sm font-semibold text-red-700 mb-1">Terdapat kesalahan input:</p>
            <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.menus.update', $menu->id) }}" method="POST" enctype="multipart/form-data"
          class="bg-white shadow rounded-lg">

        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-3 gap-0 divide-y md:divide-y-0 md:divide-x divide-gray-100">

            {{-- Kolom Kiri: Informasi Menu --}}
            <div class="col-span-2 p-6 space-y-5">
                <h2 class="text-base font-semibold text-gray-800 border-b pb-2">Informasi Menu</h2>

                <div class="grid grid-cols-2 gap-4">
                    {{-- SKU --}}
                    <div>
                        <label for="sku" class="block text-sm font-medium text-gray-700 mb-1">SKU <span class="text-red-500">*</span></label>
                        <input type="text" id="sku" name="sku" value="{{ old('sku', $menu->sku) }}"
                               placeholder="Contoh: M01"
                               class="block w-full rounded-md border @error('sku') border-red-400 @else border-gray-300 @enderror px-3 py-2 text-sm shadow-sm focus:border-green-500 focus:ring-green-500">
                        @error('sku')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    {{-- Nama Menu --}}
                    <div>
                        <label for="nama_menu" class="block text-sm font-medium text-gray-700 mb-1">Nama Menu <span class="text-red-500">*</span></label>
                        <input type="text" id="nama_menu" name="nama_menu" value="{{ old('nama_menu', $menu->nama_menu) }}"
                               class="block w-full rounded-md border @error('nama_menu') border-red-400 @else border-gray-300 @enderror px-3 py-2 text-sm shadow-sm focus:border-green-500 focus:ring-green-500">
                        @error('nama_menu')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    {{-- Harga --}}
                    <div>
                        <label for="harga" class="block text-sm font-medium text-gray-700 mb-1">Harga Jual <span class="text-red-500">*</span></label>
                        <div class="flex rounded-md shadow-sm">
                            <span class="inline-flex items-center rounded-l-md border border-r-0 border-gray-300 px-3 text-gray-500 text-sm bg-gray-50">Rp</span>
                            <input type="number" id="harga" name="harga" value="{{ old('harga', $menu->harga) }}"
                                   min="0"
                                   class="block w-full rounded-r-md border @error('harga') border-red-400 @else border-gray-300 @enderror px-3 py-2 text-sm focus:border-green-500 focus:ring-green-500">
                        </div>
                        @error('harga')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    {{-- Kategori --}}
                    <div>
                        <label for="kategori" class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                        <select id="kategori" name="kategori"
                                class="block w-full rounded-md border @error('kategori') border-red-400 @else border-gray-300 @enderror px-3 py-2 text-sm shadow-sm focus:border-green-500 focus:ring-green-500">
                            @foreach(['Katering','Dessert'] as $kat)
                                <option value="{{ $kat }}" @selected(old('kategori', $menu->kategori) === $kat)>{{ $kat }}</option>
                            @endforeach
                        </select>
                        @error('kategori')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Badge --}}
                <div>
                    <label for="badge" class="block text-sm font-medium text-gray-700 mb-1">Badge <span class="text-gray-400 font-normal">(opsional)</span></label>
                    <input type="text" id="badge" name="badge" value="{{ old('badge', $menu->badge) }}"
                           placeholder="Contoh: Terlaris, Promo"
                           class="block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-green-500 focus:ring-green-500">
                </div>

                {{-- Isi Paket --}}
                <div>
                    <label for="isi_paket" class="block text-sm font-medium text-gray-700 mb-1">
                        Isi Paket <span class="text-gray-400 font-normal">(opsional, satu item per baris)</span>
                    </label>
                    <textarea id="isi_paket" name="isi_paket" rows="4"
                              placeholder="Nasi Putih&#10;Ayam Bakar&#10;..."
                              class="block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-green-500 focus:ring-green-500">{{ old('isi_paket', $menu->isi_paket ? implode("\n", $menu->isi_paket) : '') }}</textarea>
                    <p class="mt-1 text-xs text-gray-400">Ketik satu item per baris.</p>
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="3"
                              class="block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-green-500 focus:ring-green-500">{{ old('deskripsi', $menu->deskripsi) }}</textarea>
                </div>
            </div>

            {{-- Kolom Kanan: Gambar --}}
            <div class="p-6 space-y-5">
                <h2 class="text-base font-semibold text-gray-800 border-b pb-2">Foto Menu</h2>

                <div>
                    <label for="gambar" class="block text-sm font-medium text-gray-700 mb-2">Ubah Foto: Upload atau URL</label>
                    <div class="mb-4">
                        <input type="url" id="gambar_url" name="gambar_url" value="{{ old('gambar_url', str_starts_with($menu->gambar ?? '', 'http') ? $menu->gambar : '') }}"
                               placeholder="Atau masukkan URL gambar (https://...)"
                               class="block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-green-500 focus:ring-green-500 mb-2">
                        @error('gambar_url')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <div id="preview-container" class="mb-3 {{ $menu->gambar ? '' : 'hidden' }}">
                        <p class="text-xs text-gray-500 mb-1">Gambar saat ini / Preview:</p>
                        <img id="preview-img" src="{{ $menu->gambar ? (str_starts_with($menu->gambar, 'http') ? $menu->gambar : Storage::url($menu->gambar)) : '' }}" 
                             alt="Preview" class="w-full rounded-lg object-cover border border-gray-200 max-h-48">
                    </div>

                    <label for="gambar"
                           class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                        <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                        </svg>
                        <span class="text-sm text-gray-500">Klik untuk upload file gambar baru</span>
                        <span class="text-xs text-gray-400 mt-1">Biarkan kosong jika tidak ingin mengubah</span>
                    </label>
                    <input type="file" id="gambar" name="gambar" accept="image/*" class="hidden"
                           onchange="previewImage(this)">
                    @error('gambar')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- Tombol Submit --}}
        <div class="px-6 py-4 bg-gray-50 rounded-b-lg flex items-center justify-between border-t border-gray-100">
            <div>
                <!-- Form Hapus diubah menjadi button javascript agar tidak bentrok dengan parent form -->
                <button type="button" onclick="confirmDelete()" class="text-sm text-red-600 hover:text-red-800 font-medium">Hapus Menu ini</button>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.menus.index') }}"
                   class="rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100">
                    Batal
                </a>
                <button type="submit"
                        class="rounded-md bg-green-600 px-5 py-2 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                    Simpan Perubahan
                </button>
            </div>
        </div>

    </form>
</div>

<!-- Hidden Form untuk Hapus -->
<form id="delete-form" action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<script>
function previewImage(input) {
    const img = document.getElementById('preview-img');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            if (img) {
                img.src = e.target.result;
            }
            const container = document.getElementById('preview-container');
            if (container) container.classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function confirmDelete() {
    if(confirm('Yakin ingin menghapus menu ini?')) {
        document.getElementById('delete-form').submit();
    }
}
</script>
@endsection
