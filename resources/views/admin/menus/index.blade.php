@extends('layouts.admin')

@section('title', 'Kelola Menu')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="sm:flex sm:items-center mb-6">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold text-gray-900">Daftar Menu</h1>
            <p class="mt-1 text-sm text-gray-600">Kelola semua menu makanan dan minuman yang tersedia.</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <a href="{{ route('admin.menus.create') }}"
               class="inline-flex items-center justify-center rounded-md bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                + Tambah Menu
            </a>
        </div>
    </div>

    {{-- Filter & Search --}}
    <form method="GET" action="{{ route('admin.menus.index') }}" class="mb-5 flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Cari</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama menu atau SKU..."
                   class="rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-green-500 focus:ring-green-500 w-64">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Kategori</label>
            <select name="kategori"
                    class="rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-green-500 focus:ring-green-500">
                <option value="">Semua Kategori</option>
                @foreach(['Katering','Dine-in','Minuman','Snack','Dessert'] as $kat)
                    <option value="{{ $kat }}" @selected(request('kategori') === $kat)>{{ $kat }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit"
                    class="rounded-md bg-gray-700 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
                Filter
            </button>
            @if(request()->hasAny(['search','kategori']))
                <a href="{{ route('admin.menus.index') }}"
                   class="rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50">
                    Reset
                </a>
            @endif
        </div>
    </form>

    {{-- Table --}}
    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg bg-white">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="py-3 pl-4 pr-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 sm:pl-6">Gambar</th>
                    <th class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">SKU</th>
                    <th class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Nama Menu</th>
                    <th class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Harga</th>
                    <th class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Kategori</th>
                    <th class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Badge</th>
                    <th class="px-3 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500 sm:pr-6">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($menus as $menu)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-3 pl-4 sm:pl-6">
                        @if($menu->gambar)
                            <img src="{{ str_starts_with($menu->gambar, 'http') ? $menu->gambar : Storage::url($menu->gambar) }}" alt="{{ $menu->nama_menu }}"
                                 class="h-12 w-12 rounded-full object-cover border border-gray-200">
                        @else
                            <div class="h-12 w-12 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-bold text-sm border border-gray-200">
                                {{ strtoupper(substr($menu->nama_menu, 0, 2)) }}
                            </div>
                        @endif
                    </td>
                    <td class="whitespace-nowrap px-3 py-3 text-sm text-gray-500 font-mono">{{ $menu->sku }}</td>
                    <td class="px-3 py-3 text-sm font-semibold text-gray-900">{{ $menu->nama_menu }}</td>
                    <td class="whitespace-nowrap px-3 py-3 text-sm text-gray-700">
                        Rp {{ number_format($menu->harga, 0, ',', '.') }}
                    </td>
                    <td class="whitespace-nowrap px-3 py-3">
                        @php
                            $warna = match($menu->kategori) {
                                'Katering' => 'bg-blue-100 text-blue-700',
                                'Dine-in'  => 'bg-green-100 text-green-700',
                                'Minuman'  => 'bg-cyan-100 text-cyan-700',
                                'Snack'    => 'bg-yellow-100 text-yellow-700',
                                'Dessert'  => 'bg-purple-100 text-purple-700',
                                default    => 'bg-gray-100 text-gray-600',
                            };
                        @endphp
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $warna }}">
                            {{ $menu->kategori }}
                        </span>
                    </td>
                    <td class="whitespace-nowrap px-3 py-3">
                        @if($menu->badge)
                            <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-700">
                                {{ $menu->badge }}
                            </span>
                        @else
                            <span class="text-gray-400 text-xs">—</span>
                        @endif
                    </td>
                    <td class="whitespace-nowrap py-3 pl-3 pr-4 text-right text-sm sm:pr-6">
                        <a href="{{ route('admin.menus.edit', $menu->id) }}"
                           class="text-green-600 hover:text-green-800 font-medium mr-4">Edit</a>
                        <form action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST"
                              class="inline-block"
                              onsubmit="return confirm('Yakin ingin menghapus menu {{ addslashes($menu->nama_menu) }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-12 text-center text-gray-400 text-sm">
                        Belum ada data menu. <a href="{{ route('admin.menus.create') }}" class="text-green-600 hover:underline">Tambah sekarang</a>.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($menus->hasPages())
        <div class="mt-4">
            {{ $menus->links() }}
        </div>
    @endif

</div>
@endsection
