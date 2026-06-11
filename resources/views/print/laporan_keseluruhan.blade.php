<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keseluruhan {{ $from }} s/d {{ $to }}</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #000; }
        .page { padding: 15mm 20mm; }
        .header { border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 16px; }
        .header h1 { font-size: 18px; }
        .header p { font-size: 10px; color: #555; }
        .summary-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-bottom: 16px; }
        .card { border: 1px solid #ddd; border-radius: 6px; padding: 10px 14px; }
        .card .label { font-size: 10px; color: #555; text-transform: uppercase; font-weight: bold; }
        .card .value { font-size: 16px; font-weight: bold; margin-top: 4px; }
        .card.highlight { background: #f0fdf4; border-color: #bbf7d0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th { background: #222; color: #fff; padding: 6px 10px; text-align: left; font-size: 11px; }
        td { border: 1px solid #ddd; padding: 5px 10px; font-size: 11px; }
        tr:nth-child(even) td { background: #f9f9f9; }
        h3 { margin-bottom: 8px; font-size: 13px; border-left: 3px solid #222; padding-left: 8px; }
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
        <h1>LAPORAN KESELURUHAN</h1>
        <p>Waroeng Dalaraos Jatihandap &nbsp;|&nbsp; Periode: {{ \Carbon\Carbon::parse($from)->format('d F Y') }} – {{ \Carbon\Carbon::parse($to)->format('d F Y') }}</p>
        <p>Dicetak: {{ now()->format('d F Y, H:i') }}</p>
    </div>

    <div class="summary-grid">
        <div class="card">
            <div class="label">Katering (Lunas)</div>
            <div class="value">Rp {{ number_format($totalKatering, 0, ',', '.') }}</div>
            <div style="font-size:10px; color:#555; margin-top:2px;">{{ $jumlahOrderKatering }} pesanan</div>
        </div>
        <div class="card">
            <div class="label">POS Dine-in / Take-away</div>
            <div class="value">Rp {{ number_format($totalPos, 0, ',', '.') }}</div>
            <div style="font-size:10px; color:#555; margin-top:2px;">{{ $jumlahTxPos }} transaksi</div>
        </div>
        <div class="card">
            <div class="label">Laba Barang Kering</div>
            <div class="value" style="color:#16a34a;">Rp {{ number_format($labaBersihKeringan, 0, ',', '.') }}</div>
            <div style="font-size:10px; color:#555; margin-top:2px;">dari Rp {{ number_format($totalKeringanOmset, 0, ',', '.') }} omset</div>
        </div>
        <div class="card highlight" style="grid-column: span 3;">
            <div class="label">Total Pemasukan (Omset)</div>
            <div class="value" style="font-size:22px;">Rp {{ number_format($grandTotal, 0, ',', '.') }}</div>
            <div style="font-size:11px; color:#16a34a; margin-top:2px; font-weight:bold;">Laba Bersih: Rp {{ number_format($totalLaba, 0, ',', '.') }}</div>
        </div>
    </div>

    {{-- Katering Table --}}
    <h3>Daftar Pesanan Katering Lunas</h3>
    <table>
        <thead>
            <tr><th>Tiket</th><th>Pemesan</th><th>Tgl Acara</th><th>Porsi</th><th>Total</th><th>Status</th></tr>
        </thead>
        <tbody>
            @forelse($katerings as $k)
            <tr>
                <td><strong>{{ $k->id }}</strong></td>
                <td>{{ $k->nama_pelanggan }}</td>
                <td>{{ \Carbon\Carbon::parse($k->tanggal_acara)->format('d/m/Y') }}</td>
                <td style="text-align:center;">{{ $k->jumlah_porsi }} pax</td>
                <td>Rp {{ number_format($k->total, 0, ',', '.') }}</td>
                <td>{{ $k->status_pesanan }}</td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center; color:#999;">Tidak ada data</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- POS Transactions --}}
    <h3>Daftar Transaksi POS & Barang Kering</h3>
    <table>
        <thead>
            <tr><th>No. Struk</th><th>Tipe</th><th>Metode Bayar</th><th>Waktu</th><th>Total</th></tr>
        </thead>
        <tbody>
            @php $allTx = $posTx->merge($keringanTx)->sortByDesc('created_at'); @endphp
            @forelse($allTx as $tx)
            <tr>
                <td><strong>{{ $tx->receipt_number }}</strong></td>
                <td>{{ $tx->type }}</td>
                <td>{{ $tx->payment_method }}</td>
                <td>{{ $tx->created_at->format('d/m/Y H:i') }}</td>
                <td>Rp {{ number_format($tx->total_amount, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center; color:#999;">Tidak ada data</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

</body>
</html>
