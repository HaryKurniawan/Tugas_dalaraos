@extends('layouts.pelanggan')

@section('content')
<div class="container-fluid p-0 pb-5 mb-5 mx-auto" style="max-width: 600px;">
    
    {{-- Header with Back Button --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="/lacak?query={{ $pesanan->id }}" class="text-decoration-none text-gray-900 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: #f3f4f6; border-radius: 50%;">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        </a>
        <h1 class="fw-bold fs-5 mb-0 text-gray-900">Detail Pesanan</h1>
    </div>

    {{-- Main Status Card --}}
    <div class="bg-white rounded-4 shadow-sm border mb-4 overflow-hidden">
        <div class="p-4" style="background: linear-gradient(135deg, var(--siraos-primary-light), #fff);">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <p class="mb-1" style="font-size: 11px; color: var(--siraos-muted);">ID Pesanan</p>
                    <h4 class="fw-bold text-gray-900 mb-0 font-mono">{{ $pesanan->id }}</h4>
                </div>
                <div class="text-end">
                    <p class="mb-1" style="font-size: 11px; color: var(--siraos-muted);">Tanggal Acara</p>
                    <p class="fw-bold text-gray-900 mb-0" style="font-size: 13px;">{{ \Carbon\Carbon::parse($pesanan->tanggal_acara)->format('d M Y') }}</p>
                </div>
            </div>
        </div>

        <div class="p-4">
            <h6 class="fw-bold mb-3" style="font-size: 13px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--gray-700);">Status Saat Ini</h6>
            
            <div class="d-flex flex-column gap-3">
                {{-- Status Pesanan --}}
                <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background: #f9fafb; border: 1px solid #e5e7eb;">
                    <div style="width: 40px; height: 40px; background: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--siraos-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    </div>
                    <div class="flex-grow-1">
                        <p class="mb-0" style="font-size: 11px; color: var(--siraos-muted);">Status Pesanan</p>
                        <p class="mb-0 fw-bold text-gray-900" style="font-size: 13px;">{{ $pesanan->status_pesanan }}</p>
                    </div>
                </div>

                {{-- Status Pembayaran --}}
                <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background: #f9fafb; border: 1px solid #e5e7eb;">
                    <div style="width: 40px; height: 40px; background: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="{{ $pesanan->status_pembayaran == 'Lunas' ? '#16a34a' : '#eab308' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                    </div>
                    <div class="flex-grow-1">
                        <p class="mb-0" style="font-size: 11px; color: var(--siraos-muted);">Status Pembayaran</p>
                        <p class="mb-0 fw-bold {{ $pesanan->status_pembayaran == 'Lunas' ? 'text-success' : 'text-warning' }}" style="font-size: 13px;">{{ $pesanan->status_pembayaran }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Customer & Address Info --}}
    <div class="bg-white rounded-4 shadow-sm border mb-4 p-4">
        <h6 class="fw-bold mb-3 d-flex align-items-center gap-2" style="font-size: 13px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--gray-700);">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            Informasi Pengiriman
        </h6>
        
        <div class="mb-3">
            <p class="mb-0 text-gray-900 fw-bold" style="font-size: 14px;">{{ $pesanan->nama_pelanggan }}</p>
            <p class="mb-0" style="font-size: 12px; color: var(--siraos-muted);">{{ $pesanan->no_telpon }}</p>
        </div>

        <div class="p-3 rounded-3" style="background: #f9fafb; border: 1px solid #e5e7eb;">
            <p class="mb-1" style="font-size: 11px; color: var(--siraos-muted); font-weight: 700;">Metode: {{ strtoupper(str_replace('_', ' ', $pesanan->metode_kirim)) }}</p>
            @if($pesanan->metode_kirim === 'ambil_sendiri')
                <p class="mb-0" style="font-size: 12px; color: var(--gray-900);">Pesanan akan diambil sendiri oleh pelanggan di restoran.</p>
            @elseif($pesanan->metode_kirim === 'gosend')
                <p class="mb-0" style="font-size: 12px; color: var(--gray-900);">Pesanan akan dikirim menggunakan GoSend oleh Admin (Ongkos kirim disesuaikan manual).</p>
            @else
                <p class="mb-0" style="font-size: 12px; color: var(--gray-900);">{{ $pesanan->alamat }}</p>
            @endif
        </div>
    </div>

    {{-- Order Summary --}}
    <div class="bg-white rounded-4 shadow-sm border mb-4 p-4">
        <h6 class="fw-bold mb-3 d-flex align-items-center gap-2" style="font-size: 13px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--gray-700);">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
            Ringkasan Pesanan
        </h6>

        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
            <div>
                <p class="mb-0 fw-bold" style="font-size: 13px;">{{ $pesanan->menu }}</p>
                <p class="mb-0" style="font-size: 11px; color: var(--siraos-muted);">{{ $pesanan->jumlah_porsi }} Porsi</p>
            </div>
            <p class="mb-0 fw-bold" style="font-size: 13px;">Rp {{ number_format($pesanan->total - $pesanan->ongkir, 0, ',', '.') }}</p>
        </div>

        @if($pesanan->ongkir > 0)
        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
            <p class="mb-0" style="font-size: 12px; color: var(--siraos-muted);">Ongkos Kirim</p>
            <p class="mb-0 fw-bold" style="font-size: 13px;">Rp {{ number_format($pesanan->ongkir, 0, ',', '.') }}</p>
        </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mt-3">
            <p class="mb-0 fw-bold text-gray-900" style="font-size: 14px;">Total Akhir</p>
            <h4 class="mb-0 fw-bold text-siraos-primary-dark font-mono">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</h4>
        </div>
    </div>

    {{-- Action Button if not paid --}}
    @if($pesanan->status_pembayaran === 'Menunggu Pembayaran')
    <a href="/pembayaran/{{ $pesanan->id }}" class="btn-siraos-primary w-100 py-3 rounded-3 fw-bold d-flex align-items-center justify-content-center gap-2 mb-4">
        Lanjutkan ke Pembayaran
    </a>
    @endif

</div>
@endsection
