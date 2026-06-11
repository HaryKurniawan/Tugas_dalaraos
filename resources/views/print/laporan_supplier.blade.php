<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Supplier - {{ $suplier->nama_suplier }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #000; }
        .page { padding: 15mm 20mm; }
        .header { border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 16px; }
        .header h1 { font-size: 18px; }
        .header p { font-size: 10px; color: #555; }
        .title-section { margin-bottom: 14px; }
        .title-section h2 { font-size: 15px; font-weight: bold; }
        .title-section p { font-size: 11px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        th { background: #222; color: #fff; padding: 6px 10px; text-align: left; font-size: 11px; }
        td { border: 1px solid #ddd; padding: 6px 10px; font-size: 11px; vertical-align: top; }
        tr:nth-child(even) td { background: #f9f9f9; }
        .summary { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 6px; padding: 12px 16px; margin-bottom: 14px; }
        .summary table { margin-bottom: 0; }
        .summary th { background: #16a34a; }
        .expired { color: #dc2626; font-weight: bold; }
        .footer { margin-top: 16px; display: flex; justify-content: space-between; font-size: 10px; color: #555; border-top: 1px solid #ccc; padding-top: 10px; }
        .ttd { text-align: center; }
        .ttd .box { height: 55px; border: 1px solid #999; width: 130px; margin: 6px auto 0; }
        .btn-area { padding: 10px 20mm; background: #fef3c7; font-family: Arial; }
        @media print { .btn-area { display: none; } body { -webkit-print-color-adjust: exact; print-color-adjust: exact; } }
    </style>
</head>
<body>

<div class="btn-area">
    <button onclick="window.print()" style="padding:6px 20px; background:#16a34a; color:white; border:none; border-radius:6px; cursor:pointer; font-weight:bold;">🖨 Cetak PDF</button>
    <button onclick="window.close()" style="margin-left:8px; padding:6px 14px; background:#6b7280; color:white; border:none; border-radius:6px; cursor:pointer;">Tutup</button>
</div>

<div class="page">
    <div class="header">
        <h1>LAPORAN KONSINYASI SUPPLIER</h1>
        <p>Waroeng Dalaraos Jatihandap | Dicetak: {{ now()->format('d F Y, H:i') }}</p>
    </div>

    <div class="title-section">
        <h2>Supplier: {{ $suplier->nama_suplier }}</h2>
        <p>Kontak: {{ $suplier->kontak ?? '-' }} | Tipe: {{ $suplier->tipe }}</p>
    </div>

    {{-- Ringkasan Keuangan --}}
    <div class="summary">
        <table>
            <thead>
                <tr>
                    <th colspan="2">Ringkasan Keuangan Konsinyasi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Total Nilai Titipan (Modal Supplier)</strong></td>
                    <td><strong>Rp {{ number_format($totalModal, 0, ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <td>Total Nilai Jual (Omset Dalaraos)</td>
                    <td>Rp {{ number_format($totalOmset, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td><strong>Keuntungan Dalaraos</strong></td>
                    <td style="color:#16a34a;"><strong>Rp {{ number_format($totalLaba, 0, ',', '.') }}</strong></td>
                </tr>
                <tr style="background:#fff7ed;">
                    <td><strong>💳 Yang Harus Dibayar ke Supplier</strong></td>
                    <td style="color:#dc2626; font-size:14px;"><strong>Rp {{ number_format($totalBayarSupplier, 0, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Daftar Barang --}}
    <table>
        <thead>
            <tr>
                <th>SKU</th>
                <th>Nama Barang</th>
                <th>Stok Masuk</th>
                <th>Terjual</th>
                <th>Retur/Sisa</th>
                <th>Tgl Masuk</th>
                <th>Kadaluarsa</th>
                <th>H. Beli</th>
                <th>H. Jual</th>
                <th>Keuntungan</th>
                <th>Bayar Supplier</th>
            </tr>
        </thead>
        <tbody>
            @forelse($barangs as $b)
            @php
                $terjual = $b->stok_awal_konsinyasi - $b->jumlah_stok;
                $sisa    = $b->jumlah_stok;
                $bayar   = $terjual * $b->harga_beli;
                $laba    = $terjual * ($b->harga_jual - $b->harga_beli);
                $isExp   = $b->tanggal_expired && \Carbon\Carbon::parse($b->tanggal_expired)->isPast();
            @endphp
            <tr>
                <td>{{ $b->sku }}</td>
                <td>{{ $b->nama_barang }}</td>
                <td style="text-align:center;">{{ $b->stok_awal_konsinyasi ?? ($b->jumlah_stok + $terjual) }}</td>
                <td style="text-align:center;">{{ $terjual }}</td>
                <td style="text-align:center;">{{ $sisa }}</td>
                <td>{{ $b->created_at->format('d/m/Y') }}</td>
                <td class="{{ $isExp ? 'expired' : '' }}">
                    {{ $b->tanggal_expired ? \Carbon\Carbon::parse($b->tanggal_expired)->format('d/m/Y') : '-' }}
                    @if($isExp) ⚠️ @endif
                </td>
                <td>Rp {{ number_format($b->harga_beli, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($b->harga_jual, 0, ',', '.') }}</td>
                <td style="color:#16a34a;">Rp {{ number_format($laba, 0, ',', '.') }}</td>
                <td style="color:#dc2626;">Rp {{ number_format($bayar, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr><td colspan="11" style="text-align:center; color:#999;">Tidak ada data barang dari supplier ini.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div class="ttd">
            <div>Dibuat Oleh,</div>
            <div class="box"></div>
            <div style="margin-top:4px;">(__________________)</div>
            <div style="font-size:9px; color:#888;">Admin Waroeng Dalaraos</div>
        </div>
        <div style="text-align:center; color:#777; font-size:9px; align-self: flex-end;">
            Laporan ini dicetak pada {{ now()->format('d/m/Y H:i') }}<br>
            Waroeng Dalaraos Jatihandap
        </div>
        <div class="ttd">
            <div>Perwakilan Supplier,</div>
            <div class="box"></div>
            <div style="margin-top:4px;">(__________________)</div>
            <div style="font-size:9px; color:#888;">{{ $suplier->nama_suplier }}</div>
        </div>
    </div>
</div>

</body>
</html>
