<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk #{{ $tx->receipt_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Courier New', monospace; font-size: 12px; color: #000; background: #fff; }
        .receipt { width: 80mm; margin: 0 auto; padding: 6mm 4mm; }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        .divider { border-top: 1px dashed #000; margin: 6px 0; }
        .row { display: flex; justify-content: space-between; margin-bottom: 2px; }
        .row .name { flex: 1; padding-right: 4px; }
        .row .price { text-align: right; white-space: nowrap; }
        .total-row { font-weight: bold; font-size: 13px; }
        .store-name { font-size: 16px; font-weight: bold; }
        .footer-msg { font-size: 10px; color: #555; margin-top: 4px; }
        .btn-area { text-align: center; padding: 10px; background: #fef3c7; font-family: Arial, sans-serif; }
        @media print {
            .btn-area { display: none !important; }
            body { -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body>

<div class="btn-area">
    <button onclick="window.print()" style="padding:6px 20px; background:#16a34a; color:white; border:none; border-radius:6px; cursor:pointer; font-weight:bold; font-size:13px;">🖨 Cetak Struk</button>
    <button onclick="window.close()" style="margin-left:8px; padding:6px 16px; background:#6b7280; color:white; border:none; border-radius:6px; cursor:pointer; font-size:13px;">Tutup</button>
</div>

<div class="receipt">

    <div class="center">
        <div class="store-name">WAROENG DALARAOS</div>
        <div>Jatihandap, Bandung</div>
        <div style="font-size:10px; color:#555;">WA: +62 xxx-xxxx-xxxx</div>
    </div>

    <div class="divider"></div>

    <div class="row"><span>No. Struk</span><span class="bold">{{ $tx->receipt_number }}</span></div>
    <div class="row"><span>Tanggal</span><span>{{ $tx->created_at->format('d/m/Y H:i') }}</span></div>
    <div class="row"><span>Tipe</span><span>{{ $tx->type }}</span></div>
    <div class="row"><span>Bayar</span><span>{{ $tx->payment_method }}</span></div>

    <div class="divider"></div>

    @foreach($tx->items as $item)
    <div class="row">
        <span class="name">{{ $item->item_name }}</span>
    </div>
    <div class="row" style="padding-left: 10px; color:#555;">
        <span>{{ $item->qty }} x Rp {{ number_format($item->price_per_item, 0, ',', '.') }}</span>
        <span class="price">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
    </div>
    @endforeach

    <div class="divider"></div>

    <div class="row total-row">
        <span>TOTAL</span>
        <span>Rp {{ number_format($tx->total_amount, 0, ',', '.') }}</span>
    </div>

    @if($tx->payment_method === 'Cash')
    <div class="row" style="margin-top:4px;">
        <span>Bayar</span>
        <span>Rp ____________</span>
    </div>
    <div class="row">
        <span>Kembali</span>
        <span>Rp ____________</span>
    </div>
    @endif

    <div class="divider"></div>

    <div class="center footer-msg">
        Terima kasih sudah mampir!<br>
        Selamat menikmati makanannya 😊<br>
        <br>
        <small>*Struk ini sebagai bukti pembelian sah</small>
    </div>

</div>

</body>
</html>
