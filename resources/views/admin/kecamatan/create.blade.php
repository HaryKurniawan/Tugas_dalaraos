@extends('layouts.admin')

@section('title', 'Tambah Kecamatan')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 max-w-3xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Tambah Kecamatan Baru</h1>
        <a href="{{ route('admin.kecamatan.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">← Kembali</a>
    </div>

    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            @if ($errors->any())
                <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4">
                    <div class="flex">
                        <div class="ml-3">
                            <p class="text-sm text-red-700 font-medium">Terdapat kesalahan pengisian:</p>
                            <ul class="mt-1 list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('admin.kecamatan.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    
                    <div class="sm:col-span-6">
                        <label for="nama" class="block text-sm font-medium text-gray-700">Nama Kecamatan <span class="text-red-500">*</span></label>
                        <div class="mt-1">
                            <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm px-3 py-2 border" placeholder="Contoh: Mandalajati">
                        </div>
                    </div>

                    <div class="sm:col-span-6">
                        <label for="ongkir" class="block text-sm font-medium text-gray-700">Biaya Ongkir (Rp) <span class="text-red-500">*</span></label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <span class="inline-flex items-center rounded-l-md border border-r-0 border-gray-300 bg-gray-50 px-3 text-gray-500 sm:text-sm">Rp</span>
                            <input type="number" name="ongkir" id="ongkir" value="{{ old('ongkir', 0) }}" min="0" required class="block w-full min-w-0 flex-1 rounded-none rounded-r-md border-gray-300 focus:border-green-500 focus:ring-green-500 sm:text-sm px-3 py-2 border" placeholder="5000">
                        </div>
                        <p class="mt-2 text-sm text-gray-500">Biaya ini akan otomatis ditambahkan ke total belanja jika pelanggan memilih kecamatan ini.</p>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-green-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
