<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SPK Katering - {{ $pesanan->id }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #000; background: #fff; }
        .page { width: 210mm; min-height: 148mm; padding: 10mm 15mm; page-break-after: always; border-bottom: 3px dashed #999; }
        .page:last-child { border-bottom: none; page-break-after: auto; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #000; padding-bottom: 8px; margin-bottom: 12px; }
        .header-left h1 { font-size: 18px; font-weight: bold; }
        .header-left p { font-size: 10px; color: #555; }
        .header-right { text-align: right; }
        .header-right .badge { background: #222; color: #fff; padding: 2px 10px; border-radius: 4px; font-size: 10px; display: inline-block; margin-bottom: 4px; }
        .header-right h2 { font-size: 16px; font-weight: bold; }
        .rangkap-label { background: #f0f0f0; border-left: 4px solid #000; padding: 4px 8px; margin-bottom: 10px; font-size: 10px; font-weight: bold; }
        table.info { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table.info td { padding: 3px 6px; vertical-align: top; font-size: 11px; }
        table.info td:first-child { width: 35%; font-weight: bold; color: #444; }
        table.menu { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table.menu th { background: #222; color: #fff; padding: 5px 8px; text-align: left; font-size: 11px; }
        table.menu td { border: 1px solid #ccc; padding: 5px 8px; font-size: 11px; }
        .total-row { background: #f5f5f5; font-weight: bold; }
        .footer { margin-top: 12px; display: flex; justify-content: space-between; font-size: 10px; color: #555; border-top: 1px solid #ccc; padding-top: 8px; }
        .ttd { text-align: center; }
        .ttd .box { height: 50px; border: 1px solid #999; width: 120px; margin: 4px auto 0; }
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="padding:10px; background:#fef3c7; font-size:13px; font-weight:bold;">
        ⚠️ Akan dicetak 2 rangkap: Rangkap 1 (Admin) dan Rangkap 2 (Dapur).
        <button onclick="window.print()" style="margin-left:15px; padding:6px 16px; background:#16a34a; color:white; border:none; border-radius:6px; cursor:pointer; font-weight:bold;">🖨 Cetak Sekarang</button>
        <button onclick="window.close()" style="margin-left:8px; padding:6px 16px; background:#6b7280; color:white; border:none; border-radius:6px; cursor:pointer;">Tutup</button>
    </div>

    @foreach(['ARSIP ADMIN', 'BAGIAN DAPUR'] as $rangkap)
    <div class="page">
        <div class="header">
            <div class="header-left">
                <h1>Waroeng Dalaraos</h1>
                <p>Jl. Jatihandap, Bandung</p>
                <p>WA: +62 xxx-xxxx-xxxx</p>
            </div>
            <div class="header-right">
                <div class="badge">SPK Katering</div>
                <h2>{{ $pesanan->id }}</h2>
                <p style="font-size:10px;">{{ now()->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <div class="rangkap-label">{{ $loop->iteration }}. RANGKAP {{ $loop->iteration }} – {{ $rangkap }}</div>

        <table class="info">
            <tr><td>Nama Pemesan</td><td>: {{ $pesanan->nama_pelanggan }}</td></tr>
            <tr><td>No. WhatsApp</td><td>: {{ $pesanan->no_telpon }}</td></tr>
            <tr><td>Tanggal Acara</td><td>: {{ \Carbon\Carbon::parse($pesanan->tanggal_acara)->format('d F Y') }}</td></tr>
            <tr><td>Jumlah Porsi</td><td>: <strong>{{ $pesanan->jumlah_porsi }} Pax</strong></td></tr>
            <tr><td>Metode Kirim</td><td>: {{ $pesanan->metode_kirim ?? 'Ambil Sendiri' }}</td></tr>
            @if($pesanan->alamat)
            <tr><td>Alamat Acara</td><td>: {{ $pesanan->alamat }}, {{ $pesanan->kecamatan }}</td></tr>
            @endif
        </table>

        <table class="menu">
            <thead>
                <tr>
                    <th>Paket / Menu</th>
                    <th style="text-align:right; width:100px;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>{{ $pesanan->menu }}</strong>
                        @if($pesanan->catatan)
                        <br><small style="color:#555;">Catatan: {{ $pesanan->catatan }}</small>
                        @endif
                        @php
                            $desserts = [];
                            if ($pesanan->sarikaya_qty > 0)     $desserts[] = $pesanan->sarikaya_qty . 'x Kue Sarikaya';
                            if ($pesanan->puding_coklat_qty > 0) $desserts[] = $pesanan->puding_coklat_qty . 'x Pudding Fla';
                            if ($pesanan->jeruk_qty > 0)        $desserts[] = $pesanan->jeruk_qty . 'x Jeruk Segar';
                            if ($pesanan->pisang_qty > 0)       $desserts[] = $pesanan->pisang_qty . 'x Pisang';
                        @endphp
                        @if(count($desserts) > 0)
                        <br><small style="color:#777;">+ Dessert: {{ implode(', ', $desserts) }}</small>
                        @endif
                    </td>
                    <td style="text-align:right; vertical-align:top;">Rp {{ number_format($pesanan->total - $pesanan->ongkir, 0, ',', '.') }}</td>
                </tr>
                @if($pesanan->ongkir > 0)
                <tr><td>Ongkos Kirim ({{ $pesanan->kecamatan }})</td><td style="text-align:right;">Rp {{ number_format($pesanan->ongkir, 0, ',', '.') }}</td></tr>
                @endif
                <tr class="total-row">
                    <td><strong>TOTAL PEMBAYARAN</strong></td>
                    <td style="text-align:right;"><strong>Rp {{ number_format($pesanan->total, 0, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>

        <div style="font-size:10px; color:#555; margin-bottom:10px;">
            Status Pembayaran: <strong>{{ $pesanan->status_pembayaran }}</strong>
            &nbsp;|&nbsp; Status Pesanan: <strong>{{ $pesanan->status_pesanan }}</strong>
        </div>

        <div class="footer">
            <div class="ttd">
                <div>Penerima Pesanan,</div>
                <div class="box"></div>
                <div style="margin-top:4px;">(__________________)</div>
            </div>
            <div style="text-align:center; color:#777; font-size:9px; align-self:flex-end;">
                Dokumen ini dicetak pada {{ now()->format('d/m/Y H:i') }}<br>
                Waroeng Dalaraos Jatihandap
            </div>
            <div class="ttd">
                <div>Bagian {{ $rangkap }},</div>
                <div class="box"></div>
                <div style="margin-top:4px;">(__________________)</div>
            </div>
        </div>
    </div>
    @endforeach

</body>
</html>
