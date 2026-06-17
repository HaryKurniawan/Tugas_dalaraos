@extends('layouts.pelanggan')

@section('content')

<div class="container-fluid p-0 pb-5 mb-5 mx-auto" style="max-width: 800px;">
    
    {{-- Header with Back Button --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="/" class="text-decoration-none text-gray-800 bg-white border shadow-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        </a>
        <h1 class="fw-bold fs-5 mb-0 text-gray-900">Checkout Pesanan</h1>
    </div>

    @if(!$selectedMenu)
        {{-- Empty State if accessed directly without valid menu_id --}}
        <div class="bg-white p-5 rounded-4 shadow-sm text-center border">
            <span class="fs-1 mb-3 d-block">🛒</span>
            <h4 class="fw-bold mb-2">Keranjang Kosong</h4>
            <p class="text-muted" style="font-size: 13px;">Silakan pilih paket katering terlebih dahulu sebelum melakukan checkout.</p>
            <a href="/" class="btn-siraos-primary px-4 py-2 mt-2 d-inline-block text-decoration-none">Kembali ke Beranda</a>
        </div>
    @else

        <form action="/pesan" method="POST" id="checkoutForm">
            @csrf
            <!-- Use actual DB ID for form submission -->
            <input type="hidden" name="menu_id" value="{{ $selectedMenu->id }}">

            {{-- 1. Alamat Pengiriman (Shopee style border) --}}
            <div class="bg-white rounded-3 shadow-sm mb-3 position-relative overflow-hidden">
                <div style="height: 4px; background: repeating-linear-gradient(45deg, var(--siraos-primary), var(--siraos-primary) 10px, transparent 10px, transparent 20px, var(--siraos-amber) 20px, var(--siraos-amber) 30px, transparent 30px, transparent 40px);"></div>
                <div class="p-3 p-md-4">
                    <div class="d-flex align-items-center gap-2 mb-2 text-siraos-primary-dark fw-bold" style="font-size: 14px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        Informasi Pemesan
                    </div>
                    <div class="ps-4 ms-1 mt-3">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label mb-1" style="font-size: 11px; font-weight: 700; color: var(--siraos-muted);">Nama Lengkap Pemesan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" name="nama_pemesan" placeholder="Contoh: Budi Santoso" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-1" style="font-size: 11px; font-weight: 700; color: var(--siraos-muted);">No. WhatsApp Aktif <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control form-control-sm font-mono" name="no_wa" placeholder="0812xxxxxx" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-1" style="font-size: 11px; font-weight: 700; color: var(--siraos-muted);">Tanggal Acara <span class="text-danger">*</span></label>
                                <input type="date" class="form-control form-control-sm" name="tanggal_acara" required>
                            </div>
                            <div class="col-md-12" id="alamat_container">
                                <label class="form-label mb-1 fw-bold" style="font-size: 11px; color: var(--siraos-muted);">Detail Alamat Pengiriman <span class="text-danger">*</span></label>
                                <div class="row g-2">
                                    <div class="col-12">
                                        <textarea class="form-control form-control-sm" name="alamat" id="alamat_input" rows="2" placeholder="Nama Jalan, RT/RW, Patokan Lengkap" required></textarea>
                                    </div>
                                    <div class="col-md-8">
                                        <select name="kecamatan_id" id="kecamatan_id" class="form-select form-select-sm" style="font-size: 12px;" required onchange="updateCheckout()">
                                            <option value="" data-name="">— Pilih Kecamatan —</option>
                                            @if(isset($kecamatans))
                                                @foreach($kecamatans as $kec)
                                                    <option value="10000" data-name="{{ $kec->nama }}">{{ $kec->nama }} (Flat Rp 10.000)</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <input type="hidden" name="kecamatan_name" id="kecamatan_name">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control form-control-sm font-mono" name="kode_pos" id="kode_pos" placeholder="Kode Pos" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12" id="info_pickup_container" style="display: none;">
                                <div class="alert alert-info py-2 mb-0" style="font-size: 11px;">
                                    <strong>Lokasi Pengambilan:</strong><br>
                                    Dalaraos Resto, Jl. Contoh Alamat Resto No. 123, Kecamatan, Kota
                                    <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2 mt-1 d-block" onclick="navigator.clipboard.writeText('Dalaraos Resto, Jl. Contoh Alamat Resto No. 123, Kecamatan, Kota'); alert('Alamat resto berhasil disalin!');">Salin Alamat</button>
                                </div>
                            </div>
                        </div>
                        <p class="mt-2 mb-0" style="font-size: 10px; color: var(--siraos-muted);">
                            * Tiket pesanan akan ditampilkan setelah checkout dan dapat dilacak kapan saja tanpa perlu login.
                        </p>
                    </div>
                </div>
            </div>

            {{-- 2. Produk Dipesan --}}
            <div class="bg-white rounded-3 shadow-sm mb-3 p-3 p-md-4 border">
                <p class="fw-bold mb-3 d-flex align-items-center gap-2" style="font-size: 14px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 9 2.45-4.9A2 2 0 0 1 7.24 3h9.52a2 2 0 0 1 1.8 1.1L21 9"/><path d="M12 3v6"/></svg>
                    Produk Dipesan
                </p>
                
                <div class="d-flex gap-3 align-items-start mb-3 pb-3 border-bottom">
                    <img src="{{ str_starts_with($selectedMenu->gambar, 'http') ? $selectedMenu->gambar : Storage::url($selectedMenu->gambar) }}" alt="Paket" class="rounded-3 border object-cover" style="width: 70px; height: 70px;">
                    <div class="flex-grow-1">
                        <strong class="text-gray-900 d-block" style="font-size: 13px; line-height: 1.3;">{{ $selectedMenu->nama_menu }}</strong>
                        <span class="badge bg-light text-dark border mt-1" style="font-size: 9px;">Pre-Order H-1</span>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <span class="font-mono fw-bold text-siraos-primary-dark" style="font-size: 13px;" id="pricePerItem">Rp {{ number_format($selectedMenu->harga, 0, ',', '.') }}</span>
                            <div class="d-flex align-items-center gap-1">
                                <span class="text-muted me-2" style="font-size: 11px;">Jumlah:</span>
                                <input type="number" class="form-control form-control-sm font-mono text-center fw-bold" style="width: 70px;" name="jumlah_porsi" id="jumlah_porsi" value="50" min="1" onchange="updateCheckout()">
                                <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2" style="font-size: 11px;" onclick="updateCheckout()">Oke</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Opsi Pengiriman --}}
                <div>
                    <label class="fw-bold mb-2 text-gray-900" style="font-size: 12px;">Opsi Pengiriman <span class="text-danger">*</span></label>
                    <div class="row g-2" id="shipping_options_container">
                        <div class="col-md-4" id="opt_internal">
                            <label class="radio-card-wrapper w-100 m-0 py-2 px-3" id="label_internal">
                                <input type="radio" name="metode_kirim" value="internal" class="form-check-input mt-0" onchange="updateCheckout()">
                                <div>
                                    <strong class="radio-card-label-title" style="font-size: 11px;">🚚 Diantar Dalaraos</strong>
                                    <span class="radio-card-label-sub" style="font-size: 9px;">Sesuai Kecamatan</span>
                                </div>
                            </label>
                        </div>
                        <div class="col-md-4" id="opt_gosend">
                            <label class="radio-card-wrapper active w-100 m-0 py-2 px-3" id="label_gosend">
                                <input type="radio" name="metode_kirim" value="gosend" class="form-check-input mt-0" checked onchange="updateCheckout()">
                                <div>
                                    <strong class="radio-card-label-title" style="font-size: 11px;">🛵 GoSend (Instan)</strong>
                                    <span class="radio-card-label-sub" style="font-size: 9px;">Ongkir manual admin</span>
                                </div>
                            </label>
                        </div>
                        <div class="col-md-4" id="opt_ambil">
                            <label class="radio-card-wrapper w-100 m-0 py-2 px-3" id="label_ambil">
                                <input type="radio" name="metode_kirim" value="ambil_sendiri" class="form-check-input mt-0" onchange="updateCheckout()">
                                <div>
                                    <strong class="radio-card-label-title" style="font-size: 11px;">🏪 Ambil Sendiri</strong>
                                    <span class="radio-card-label-sub" style="font-size: 9px;">Gratis ongkir</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div id="shipping_internal_banner" class="internal-shipping-banner d-none mt-2 p-2">
                        <div id="mobil_box_badge">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-success" style="font-size: 9px;">Wajib</span>
                                <span class="text-success fw-bold" style="font-size: 11px;">Mobil Box Dalaraos</span>
                            </div>
                            <p class="mb-0 mt-1" style="font-size: 10px; color: var(--siraos-muted);">Pesanan >70 porsi wajib diantar menggunakan armada mobil box internal kami.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. Dessert Tambahan --}}
            <div class="bg-white rounded-3 shadow-sm mb-3 p-3 p-md-4 border" id="dessert_section_container">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <p class="fw-bold mb-0 d-flex align-items-center gap-2" style="font-size: 14px;">
                        <span class="fs-5">🍨</span> Tambahan Dessert
                    </p>
                    <span class="badge bg-light text-dark" style="font-size: 9px;">Opsional</span>
                </div>

                <div class="row g-2">
                    @foreach($desserts as $ds)
                    <div class="col-6 col-md-3 d-none" id="dessert_col_{{ $ds->id }}">
                        <div class="border rounded-3 p-2 text-center" style="background: #f9fafb; position: relative;">
                            @if($ds->gambar)
                                <img src="{{ str_starts_with($ds->gambar, 'http') ? $ds->gambar : Storage::url($ds->gambar) }}" alt="{{ $ds->nama_menu }}" class="w-100 object-cover mb-2 rounded-2" style="height: 60px;">
                            @else
                                <div class="w-100 mb-2 rounded-2 bg-white border d-flex align-items-center justify-content-center text-muted" style="height: 60px; font-size: 10px;">{{ explode(' ', $ds->badge)[0] ?? '🍨' }}</div>
                            @endif
                            <span class="d-block text-truncate" style="font-size: 11px; font-weight: 700;" title="{{ $ds->nama_menu }}">{{ $ds->nama_menu }}</span>
                            <span class="font-mono text-siraos-amber-dark" style="font-size: 9px;">Rp {{ number_format($ds->harga, 0, ',', '.') }}</span>
                            
                            <div class="mt-2 text-start">
                                <label style="font-size: 9px; color: var(--gray-500);">Jumlah Porsi:</label>
                                <!-- Kita gunakan ID dinamis untuk dessert -->
                                <input type="number" name="desserts[{{ $ds->id }}]" id="dessert_{{ $ds->id }}" class="form-control form-control-sm text-center dessert-input" min="0" value="0" data-price="{{ $ds->harga }}" onchange="updateCheckout()">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- 3.5. Catatan Pesanan --}}
            <div class="bg-white rounded-3 shadow-sm mb-3 p-3 p-md-4 border">
                <p class="fw-bold mb-2 d-flex align-items-center gap-2" style="font-size: 14px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><line x1="10" y1="9" x2="8" y2="9"/></svg>
                    Catatan Pesanan &amp; Detail Lauk
                </p>
                <textarea class="form-control form-control-sm" name="catatan" id="catatan" rows="4" placeholder="Cth: Tolong sambalnya dipisah, atau detail lauk pilihan..."></textarea>
            </div>

            {{-- 4. Rincian Pembayaran --}}
            <div class="bg-white rounded-3 shadow-sm mb-5 p-3 p-md-4 border">
                <p class="fw-bold mb-3" style="font-size: 14px;">📄 Rincian Pembayaran</p>
                
                <div class="d-flex justify-content-between mb-2 text-muted" style="font-size: 12px;">
                    <span>Subtotal Produk (<span id="lbl_qty">50</span> box)</span>
                    <span class="font-mono text-gray-900" id="lbl_subtotal">Rp 0</span>
                </div>
                <div class="d-flex justify-content-between mb-2 text-muted" style="font-size: 12px; display: none !important;" id="row_dessert_calc">
                    <span>Subtotal Dessert</span>
                    <span class="font-mono text-gray-900" id="lbl_dessert">Rp 0</span>
                </div>
                <div class="d-flex justify-content-between mb-2 text-muted" style="font-size: 12px;">
                    <span>Subtotal Pengiriman</span>
                    <span class="font-mono text-gray-900" id="lbl_ongkir">Sesuai Aplikasi</span>
                </div>
                <hr class="my-2 border-dashed">
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <span class="fw-bold text-gray-900" style="font-size: 14px;">Total Estimasi</span>
                    <span class="font-mono fw-bold text-siraos-primary-dark fs-5" id="lbl_total_final">Rp 0</span>
                </div>
            </div>

            {{-- ===== STICKY BOTTOM CHECKOUT BAR ===== --}}
            <div class="fixed-bottom bg-white border-top shadow-lg" style="z-index: 1050;">
                <div class="container-fluid mx-auto d-flex justify-content-end align-items-center p-0" style="max-width: 800px;">
                    <div class="text-end py-2 px-3">
                        <div style="font-size: 11px; color: var(--gray-700);">Total Pembayaran</div>
                        <div class="font-mono fw-bold text-siraos-primary-dark" style="font-size: 18px; line-height: 1;" id="sticky_total">Rp 0</div>
                    </div>
                    <button type="submit" class="btn-siraos-dark h-100 px-4 py-3 m-0 rounded-0 d-flex align-items-center justify-content-center gap-2" style="font-size: 14px; min-width: 140px;">
                        Buat Pesanan
                    </button>
                </div>
            </div>

        </form>
    @endif
</div>
@endsection

@push('scripts')
<script>
    const basePrice = {{ $selectedMenu ? $selectedMenu->harga : 0 }};

    function fmt(n) {
        return 'Rp ' + n.toLocaleString('id-ID');
    }

    function updateCheckout() {
        if (basePrice === 0) return;

        const qty = parseInt(document.getElementById('jumlah_porsi').value) || 0;
        const subtotal = basePrice * qty;

        // Dessert calculation dinamis
        let dessertTotal = 0;
        document.querySelectorAll('.dessert-input').forEach(input => {
            const price = parseInt(input.getAttribute('data-price')) || 0;
            const q = parseInt(input.value) || 0;
            dessertTotal += price * q;
        });

        // Shipping logic
        const isInternalForced = qty > 70;
        let shippingCost = 0;
        let shippingText = 'Rp 0';

        if (isInternalForced) {
            // Force internal selection
            document.querySelector('input[name="metode_kirim"][value="internal"]').checked = true;
            document.getElementById('opt_internal').classList.remove('d-none');
            document.getElementById('opt_gosend').classList.add('d-none');
            document.getElementById('opt_ambil').classList.add('d-none');
            document.getElementById('mobil_box_badge').classList.remove('d-none');
        } else {
            // Allow gosend and ambil sendiri, hide internal
            if (document.querySelector('input[name="metode_kirim"]:checked').value === 'internal') {
                document.querySelector('input[name="metode_kirim"][value="gosend"]').checked = true;
            }
            document.getElementById('opt_internal').classList.add('d-none');
            document.getElementById('opt_gosend').classList.remove('d-none');
            document.getElementById('opt_ambil').classList.remove('d-none');
            document.getElementById('mobil_box_badge').classList.add('d-none');
        }

        const method = document.querySelector('input[name="metode_kirim"]:checked').value;

        // UI Toggle
        document.getElementById('label_internal').classList.toggle('active', method === 'internal');
        document.getElementById('label_gosend').classList.toggle('active', method === 'gosend');
        document.getElementById('label_ambil').classList.toggle('active', method === 'ambil_sendiri');

        if (method === 'internal') {
            document.getElementById('shipping_internal_banner').classList.remove('d-none');
            
            // Check if address container is visible, require the dropdown
            if(document.getElementById('alamat_container').style.display !== 'none') {
                document.getElementById('kecamatan_id').required = true;
            }

            const kecSelect = document.getElementById('kecamatan_id');
            const kecVal = kecSelect.value;
            if (kecVal && kecSelect.selectedIndex > 0) {
                shippingCost = parseInt(kecVal);
                shippingText = fmt(shippingCost);
                document.getElementById('kecamatan_name').value = kecSelect.options[kecSelect.selectedIndex].getAttribute('data-name');
            } else {
                shippingText = 'Pilih Wilayah di Atas';
                document.getElementById('kecamatan_name').value = '';
            }
        } else {
            document.getElementById('shipping_internal_banner').classList.add('d-none');
            // If it's not internal delivery, the shipping cost via our system is 0 (handled manually by admin for gosend)
            document.getElementById('kecamatan_id').required = false;

            if (method === 'gosend') {
                shippingText = 'Ongkir manual admin (GoSend)';
            } else {
                shippingText = 'Gratis (Ambil Sendiri)';
            }
        }

        // Address Field Visibility
        if (method === 'ambil_sendiri' || method === 'gosend') {
            document.getElementById('alamat_container').style.display = 'none';
            document.getElementById('alamat_input').required = false;
            document.getElementById('kecamatan_id').required = false;
            document.getElementById('kode_pos').required = false;
            document.getElementById('info_pickup_container').style.display = 'block';
        } else {
            document.getElementById('alamat_container').style.display = 'block';
            document.getElementById('alamat_input').required = true;
            document.getElementById('kecamatan_id').required = true;
            document.getElementById('kode_pos').required = true;
            document.getElementById('info_pickup_container').style.display = 'none';
        }

        const grandTotal = subtotal + dessertTotal + shippingCost;

        // Update Labels
        document.getElementById('lbl_qty').textContent = qty;
        document.getElementById('lbl_subtotal').textContent = fmt(subtotal);
        document.getElementById('lbl_ongkir').textContent = shippingText;
        
        const rowDessert = document.getElementById('row_dessert_calc');
        if (dessertTotal > 0) {
            document.getElementById('lbl_dessert').textContent = fmt(dessertTotal);
            rowDessert.style.setProperty('display', 'flex', 'important');
        } else {
            rowDessert.style.setProperty('display', 'none', 'important');
        }

        document.getElementById('lbl_total_final').textContent = fmt(grandTotal);
        document.getElementById('sticky_total').textContent = fmt(grandTotal);
    }

    // Init calculation on load
    document.addEventListener("DOMContentLoaded", function() {
        // Load saved user info
        const savedName = localStorage.getItem('siraos_nama');
        const savedPhone = localStorage.getItem('siraos_no_wa');
        const savedAddress = localStorage.getItem('siraos_alamat');
        const savedKodePos = localStorage.getItem('siraos_kode_pos');

        if (savedName) document.querySelector('input[name="nama_pemesan"]').value = savedName;
        if (savedPhone) document.querySelector('input[name="no_wa"]').value = savedPhone;
        if (savedAddress) document.querySelector('textarea[name="alamat"]').value = savedAddress;
        if (savedKodePos) document.querySelector('input[name="kode_pos"]').value = savedKodePos;

        // Save user info on input
        document.querySelector('input[name="nama_pemesan"]').addEventListener('input', function(e) {
            localStorage.setItem('siraos_nama', e.target.value);
        });
        document.querySelector('input[name="no_wa"]').addEventListener('input', function(e) {
            localStorage.setItem('siraos_no_wa', e.target.value);
        });
        document.querySelector('textarea[name="alamat"]').addEventListener('input', function(e) {
            localStorage.setItem('siraos_alamat', e.target.value);
        });
        document.querySelector('input[name="kode_pos"]').addEventListener('input', function(e) {
            localStorage.setItem('siraos_kode_pos', e.target.value);
        });

        // Load existing dessert cart if any
        let hasDessert = false;
        try {
            const savedDs = localStorage.getItem('siraos_dessert_cart');
            if(savedDs) {
                const dc = JSON.parse(savedDs);
                for(let id in dc) {
                    let el = document.getElementById('dessert_' + id);
                    if(el) {
                        let qty = parseInt(dc[id]) || 0;
                        if(qty > 0) {
                            el.value = qty;
                            let col = document.getElementById('dessert_col_' + id);
                            if(col) col.classList.remove('d-none');
                            hasDessert = true;
                        }
                    }
                }
            }
        } catch(e) {}
        
        if(!hasDessert) {
            document.getElementById('dessert_section_container').style.display = 'none';
        }

        updateCheckout();

        // Load saved menu options from localStorage
        const savedOptions = localStorage.getItem('siraos_cart_options');
        if (savedOptions) {
            document.getElementById('catatan').value = savedOptions;
            // Optionally clear it so it doesn't persist forever
            // localStorage.removeItem('siraos_cart_options');
        }
    });
</script>
@endpush
