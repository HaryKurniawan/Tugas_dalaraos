@extends('layouts.pelanggan')

@section('content')
<div class="container-fluid p-0 pb-5 mb-5">

    <!-- Tabs removed to use Sidebar -->

    <div class="animate-fade-in pt-4">

        {{-- ===== CATALOG HEADER ===== --}}
        <div class="text-center mb-4">
            <h1 class="fw-bold text-gray-900 mb-2" style="font-size: 1.8rem; letter-spacing: -0.02em; line-height: 1.3;">Pilihan Paket Katering Tradisional &amp; Modern</h1>
            <p class="text-muted mx-auto mb-0" style="max-width: 550px; font-size: 14px;">
                Masakan Nusantara bercita rasa tinggi. Pilih paket di bawah untuk mengkalkulasi pesanan secara otomatis.
            </p>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3 mt-5 pt-3 border-bottom pb-2">
            <span class="fw-bold text-gray-800" style="font-size: 14px;">Semua Menu</span>
            <span class="badge bg-light text-dark border font-mono px-2 py-1" style="font-size: 11px;">Tersedia 4 Pilihan Menu</span>
        </div>

        {{-- ===== PACKAGE CARDS ===== --}}
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 g-3 mb-4">

            {{-- Paket 1 --}}
            <div class="col">
                <div class="siraos-card h-100"
                     onclick="selectPackage('m8', 35000, 'Paket Khas Sunda – Timbel Standard', this)">
                    <div class="siraos-card-img-wrapper">
                        <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=600&q=80"
                             alt="Paket Khas Sunda Timbel Standard">
                        <span class="badge-siraos">⭐ Paling Populer</span>
                    </div>
                    <div class="card-body-inner">
                        <h4 class="card-package-name">Paket Khas Sunda – Timbel Standard</h4>
                        <div class="card-price-row">
                            <span class="card-price">Rp 35.000</span>
                            <span class="card-price-unit">/ Box</span>
                        </div>
                        <span class="card-menu-list-label">Isi Box:</span>
                        <ul class="card-menu-list">
                            <li>Nasi Timbel / Liwet Kupas Daun</li>
                            <li>Lauk Utama: Daging / Ayam / Ikan</li>
                            <li>Pendamping: Perkedel / Pepes Tahu</li>
                            <li>Sayuran: Tumis Khas Sunda</li>
                            <li>Lalab segar &amp; Sambal Khas</li>
                        </ul>
                    </div>
                    <div class="card-footer-inner">
                        <button type="button" class="btn-select-pkg">Pilih Paket Ini</button>
                    </div>
                </div>
            </div>

            {{-- Paket 2 --}}
            <div class="col">
                <div class="siraos-card h-100"
                     onclick="selectPackage('m9', 49000, 'Paket Khas Sunda Premium (Double Lauk)', this)">
                    <div class="siraos-card-img-wrapper">
                        <img src="https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&w=600&q=80"
                             alt="Paket Khas Sunda Premium">
                        <span class="badge-siraos">👑 Mewah &amp; Komplet</span>
                    </div>
                    <div class="card-body-inner">
                        <h4 class="card-package-name">Paket Khas Sunda Premium (Double Lauk)</h4>
                        <div class="card-price-row">
                            <span class="card-price">Rp 49.000</span>
                            <span class="card-price-unit">/ Box</span>
                        </div>
                        <span class="card-menu-list-label">Isi Box:</span>
                        <ul class="card-menu-list">
                            <li>Nasi Timbel / Liwet / Tutug Oncom</li>
                            <li>Lauk Sapi: Gepuk / Daging Serundeng</li>
                            <li>Lauk Kedua: Ayam Bakar / Goreng</li>
                            <li>Pendamping: Perkedel / Pepes Tahu</li>
                            <li>Sayuran: Tumis Pilihan Spesial</li>
                        </ul>
                    </div>
                    <div class="card-footer-inner">
                        <button type="button" class="btn-select-pkg">Pilih Paket Ini</button>
                    </div>
                </div>
            </div>

            {{-- Paket 3 --}}
            <div class="col">
                <div class="siraos-card h-100"
                     onclick="selectPackage('m10', 35000, 'Paket Nasi Putih Standard', this)">
                    <div class="siraos-card-img-wrapper">
                        <img src="https://images.unsplash.com/photo-1585032226651-759b368d7246?auto=format&fit=crop&w=600&q=80"
                             alt="Paket Nasi Putih Standard">
                        <span class="badge-siraos">✅ Pilihan Praktis</span>
                    </div>
                    <div class="card-body-inner">
                        <h4 class="card-package-name">Paket Nasi Putih Standard</h4>
                        <div class="card-price-row">
                            <span class="card-price">Rp 35.000</span>
                            <span class="card-price-unit">/ Box</span>
                        </div>
                        <span class="card-menu-list-label">Isi Box:</span>
                        <ul class="card-menu-list">
                            <li>Nasi Putih Premium Pulen</li>
                            <li>Lauk Utama: Daging / Ayam / Ikan</li>
                            <li>Pendamping: Lauk Tambahan Gurih</li>
                            <li>Sayuran: Tumis / Sayur Berkuah</li>
                            <li>Pelengkap: Sambal Waroeng</li>
                        </ul>
                    </div>
                    <div class="card-footer-inner">
                        <button type="button" class="btn-select-pkg">Pilih Paket Ini</button>
                    </div>
                </div>
            </div>

            {{-- Paket 4 --}}
            <div class="col">
                <div class="siraos-card h-100"
                     onclick="selectPackage('m12', 42000, 'Paket Nasi Tumpeng Kuning', this)">
                    <div class="siraos-card-img-wrapper">
                        <img src="https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?auto=format&fit=crop&w=600&q=80"
                             alt="Paket Nasi Tumpeng Kuning">
                        <span class="badge-siraos">🎊 Spesial Syukuran</span>
                    </div>
                    <div class="card-body-inner">
                        <h4 class="card-package-name">Paket Nasi Tumpeng Kuning</h4>
                        <div class="card-price-row">
                            <span class="card-price">Rp 42.000</span>
                            <span class="card-price-unit">/ Box</span>
                        </div>
                        <span class="card-menu-list-label">Isi Box:</span>
                        <ul class="card-menu-list">
                            <li>Nasi Kuning Harum</li>
                            <li>Lauk Utama: Ayam Bakar / Goreng</li>
                            <li>Lauk Samping: Semur Telur Pindang</li>
                            <li>Sayuran: Urap Sayur Bumbu Kelapa</li>
                            <li>Pendamping: Perkedel Kentang</li>
                        </ul>
                    </div>
                    <div class="card-footer-inner">
                        <button type="button" class="btn-select-pkg">Pilih Paket Ini</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 text-center p-3 rounded-4" style="background: rgba(22,163,74,.05); border: 1px dashed rgba(22,163,74,.3);">
             <p class="mb-0 text-siraos-primary fw-bold" style="font-size: 13px;">👆 Pilih salah satu paket di atas untuk mulai memesan</p>
        </div>

        {{-- ===== DESSERT SECTION ===== --}}
        @if(isset($desserts) && count($desserts) > 0)
        <div class="mt-5 pt-4 border-top">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-gray-900 mb-1" style="font-size: 1.4rem;">🍨 Tambahan Dessert</h2>
                    <p class="text-muted mb-0" style="font-size: 13px;">Sempurnakan hidangan dengan yang manis. <span class="text-danger fw-bold">*Hanya dapat di-checkout bersama paket katering.</span></p>
                </div>
            </div>

            <div class="row row-cols-2 row-cols-md-4 g-3 mb-4">
                @foreach($desserts as $ds)
                <div class="col">
                    <div class="siraos-card h-100 p-2 text-center" style="cursor: default;">
                        @if($ds->gambar)
                            <img src="{{ str_starts_with($ds->gambar, 'http') ? $ds->gambar : Storage::url($ds->gambar) }}" alt="{{ $ds->nama_menu }}" class="w-100 object-cover mb-2 rounded-3" style="height: 120px;">
                        @else
                            <div class="w-100 mb-2 rounded-3 bg-gray-100 border d-flex align-items-center justify-content-center text-muted" style="height: 120px; font-size: 24px;">{{ explode(' ', $ds->badge)[0] ?? '🍨' }}</div>
                        @endif
                        
                        <h5 class="fw-bold text-gray-800 mb-1" style="font-size: 14px;">{{ $ds->nama_menu }}</h5>
                        <p class="font-mono text-siraos-amber-dark fw-bold mb-2" style="font-size: 12px;">Rp {{ number_format($ds->harga, 0, ',', '.') }}</p>
                        
                        <div class="mt-2">
                            <button type="button" class="btn btn-sm btn-outline-siraos-primary w-100 rounded-pill fw-bold" style="font-size: 12px;" onclick="openDessertModal({{ $ds->id }}, '{{ addslashes($ds->nama_menu) }}')">
                                + Tambahkan
                            </button>
                        </div>
                        <!-- We still need a visual indicator if they added it -->
                        <span class="badge bg-success position-absolute top-0 end-0 m-2 d-none" id="badge_ds_{{ $ds->id }}" style="font-size: 10px;">0 di Keranjang</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>

{{-- ===== MENU DETAIL MODAL ===== --}}
<div class="modal fade" id="menuDetailModal" tabindex="-1" aria-labelledby="menuDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content border-0 shadow rounded-4">
      <div class="modal-header border-bottom-0 pb-0 mt-2">
        <h5 class="modal-title fw-bold text-gray-900" id="menuDetailModalLabel" style="font-size: 18px;">Pilih Detail Lauk</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body pt-2">
         <p class="text-muted mb-4" style="font-size: 13px;">Sesuaikan pilihan nasi, lauk utama, dan pendamping untuk paket ini.</p>
         
         <div id="modal-options-container">
            <!-- Options injected by JS -->
         </div>
      </div>
      <div class="modal-footer border-top p-3">
        <button type="button" class="btn btn-siraos-dark w-100 rounded-pill py-2" onclick="saveMenuOptions()">Simpan Pilihan &amp; Lanjutkan</button>
      </div>
    </div>
  </div>
</div>

{{-- ===== DESSERT QUANTITY MODAL ===== --}}
<div class="modal fade" id="dessertQtyModal" tabindex="-1" aria-labelledby="dessertQtyModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content border-0 shadow rounded-4">
      <div class="modal-header border-bottom-0 pb-0 mt-2">
        <h5 class="modal-title fw-bold text-gray-900" id="dessertQtyModalLabel" style="font-size: 16px;">Tentukan Jumlah</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center pt-2">
         <p class="text-muted mb-4" style="font-size: 13px;" id="dessertModalName">Nama Dessert</p>
         
         <div class="d-flex justify-content-center align-items-center gap-3 mb-3">
            <button type="button" class="btn btn-outline-secondary rounded-circle p-0" style="width: 36px; height: 36px; font-size: 18px;" onclick="changeModalQty(-1)">-</button>
            <input type="number" id="modalDessertQty" class="form-control text-center fw-bold font-mono" style="width: 70px; font-size: 16px;" value="1" min="0" readonly>
            <button type="button" class="btn btn-siraos-primary rounded-circle p-0" style="width: 36px; height: 36px; font-size: 18px;" onclick="changeModalQty(1)">+</button>
         </div>
      </div>
      <div class="modal-footer border-top p-3">
        <input type="hidden" id="modalDessertId">
        <button type="button" class="btn btn-siraos-dark w-100 rounded-pill py-2" onclick="confirmDessertQty()">Konfirmasi</button>
      </div>
    </div>
  </div>
</div>

{{-- ===== FLOATING BOTTOM BAR (REDIRECTS TO CHECKOUT PAGE) ===== --}}
<div id="floating-cart" class="fixed-bottom p-3 p-md-4 d-none animate-fade-in" style="z-index: 1040; pointer-events: none;">
    <div class="container-fluid p-0 mx-auto" style="max-width: 600px; pointer-events: auto;">
        <div class="bg-siraos-dark text-white rounded-pill shadow-lg p-3 d-flex align-items-center justify-content-between" 
             style="background: linear-gradient(135deg, var(--siraos-dark), var(--siraos-dark-2)); border: 1px solid rgba(255,255,255,0.1); cursor: pointer; transform: translateY(0); transition: all 0.2s;"
             onmouseover="this.style.transform='translateY(-2px)';" onmouseout="this.style.transform='translateY(0)';"
             onclick="goToCheckout()">
            <div class="d-flex align-items-center gap-3 ms-2">
                <div class="position-relative">
                    <span class="fs-3">🛒</span>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-2 border-dark" id="floating-qty-badge" style="font-size: 10px;">1</span>
                </div>
                <div class="d-flex flex-column justify-content-center">
                    <div class="font-mono fw-bold" style="font-size: 16px; color: #fff; line-height: 1.2;" id="floating-total">Rp 0</div>
                    <div style="font-size: 10px; color: rgba(255,255,255,0.6);">Total Pembayaran</div>
                </div>
            </div>
            <div class="fw-bold d-flex align-items-center gap-2 me-2 px-3 py-2 rounded-pill" style="font-size: 12px; background: var(--siraos-primary); color: #fff; border: 1px solid rgba(255,255,255,0.2);">
                Checkout
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const priceMap = {
        m8:  { price: 35000, name: 'Paket Khas Sunda – Timbel Standard' },
        m9:  { price: 49000, name: 'Paket Khas Sunda Premium' },
        m10: { price: 35000, name: 'Paket Nasi Putih Standard' },
        m12: { price: 42000, name: 'Paket Nasi Tumpeng Kuning' },
    };

    let selectedMenuId = null;
    let tempSelectedMenu = null;
    let menuDetailModal = null;
    let dessertQtyModal = null;
    let dessertCart = {};

    document.addEventListener("DOMContentLoaded", function() {
        menuDetailModal = new bootstrap.Modal(document.getElementById('menuDetailModal'));
        dessertQtyModal = new bootstrap.Modal(document.getElementById('dessertQtyModal'));
        
        // Load existing dessert cart if any
        try {
            const saved = localStorage.getItem('siraos_dessert_cart');
            if(saved) {
                dessertCart = JSON.parse(saved);
                for(let id in dessertCart) {
                    updateDessertBadge(id, dessertCart[id]);
                }
            }
        } catch(e) {}
    });

    const packageOptionsData = {
        m8: [
            { label: 'Pilihan Nasi', name: 'nasi', options: ['Nasi Timbel', 'Nasi Liwet', 'Nasi Tutug Oncom', 'Nasi Bakar'] },
            { label: 'Lauk Utama (Daging)', name: 'daging', options: ['Gepuk', 'Daging Bumbu Serundeng', 'Daging Blado', 'Daging Cabe Ijo'] },
            { label: 'Lauk Tambahan (Ayam/Ikan)', name: 'ayam_ikan', options: ['Ayam Bakar Pedas', 'Ayam Goreng Kecap', 'Ayam Suir', 'Ayam Opor', 'Gurame Asam Manis', 'Ikan Mas Goreng'] },
            { label: 'Pendamping', name: 'pendamping', options: ['Perkedel Jagung', 'Perkedel Kentang', 'Pepes Tahu', 'Tempe Bacem'] },
            { label: 'Tumisan', name: 'tumisan', options: ['Ikan Asin Cabe & Tomat Ijo', 'Jamur', 'Keciwis', 'Buncis', 'Ca Kangkung', 'Ulukuteuk Leunca'] }
        ],
        m9: [
            { label: 'Pilihan Nasi', name: 'nasi', options: ['Nasi Timbel', 'Nasi Liwet', 'Nasi Tutug Oncom', 'Nasi Bakar'] },
            { label: 'Lauk Pertama (Daging)', name: 'daging1', options: ['Rendang', 'Gepuk', 'Sambal Goreng', 'Teriyaki', 'Lada Hitam'] },
            { label: 'Lauk Kedua (Ayam)', name: 'ayam', options: ['Ayam Teriyaki', 'Ayam Suir', 'Ayam Kuluyuk', 'Ayam Bakar Pedas'] },
            { label: 'Pendamping', name: 'pendamping', options: ['Perkedel Kentang', 'Perkedel Tahu', 'Tempe Bacem'] }
        ],
        default: [
            { label: 'Pilihan Nasi', name: 'nasi', options: ['Nasi Putih', 'Nasi Kuning', 'Nasi Uduk'] },
            { label: 'Lauk Utama', name: 'lauk', options: ['Ayam Bakar', 'Ayam Goreng', 'Telur Pindang', 'Daging Rendang'] },
            { label: 'Pendamping', name: 'pendamping', options: ['Perkedel Kentang', 'Tempe Kering', 'Urap'] }
        ]
    };

    function fmt(n) {
        return 'Rp ' + n.toLocaleString('id-ID');
    }

    function selectPackage(id, price, name, el) {
        // Store temporarily before modal confirmation
        tempSelectedMenu = { id, price, name, el };
        
        // Populate modal
        const container = document.getElementById('modal-options-container');
        container.innerHTML = '';
        
        const opts = packageOptionsData[id] || packageOptionsData['default'];
        
        opts.forEach((group, index) => {
            let html = `<div class="mb-3">
                <label class="form-label fw-bold text-siraos-primary-dark mb-1" style="font-size: 12px;">${group.label}</label>
                <select class="form-select form-select-sm menu-option-select" data-label="${group.label}">`;
            
            group.options.forEach(opt => {
                html += `<option value="${opt}">${opt}</option>`;
            });
            
            html += `</select></div>`;
            container.innerHTML += html;
        });

        // Show Modal
        menuDetailModal.show();
    }

    function saveMenuOptions() {
        if(!tempSelectedMenu) return;

        // Collect options
        let optionsStr = "Detail Menu:\n";
        document.querySelectorAll('.menu-option-select').forEach(sel => {
            optionsStr += `- ${sel.getAttribute('data-label')}: ${sel.value}\n`;
        });
        
        // Save to localStorage for checkout page
        localStorage.setItem('siraos_cart_options', optionsStr);

        // Confirm selection visually
        selectedMenuId = tempSelectedMenu.id;

        document.querySelectorAll('.siraos-card').forEach(card => {
            card.classList.remove('active');
            let btn = card.querySelector('.btn-select-pkg');
            if(btn) btn.textContent = 'Pilih Paket Ini';
        });
        tempSelectedMenu.el.classList.add('active');
        let tempBtn = tempSelectedMenu.el.querySelector('.btn-select-pkg');
        if(tempBtn) tempBtn.textContent = '✓ Terpilih';

        // Show floating cart
        const floatingCart = document.getElementById('floating-cart');
        floatingCart.classList.remove('d-none');
        document.getElementById('floating-total').textContent = fmt(tempSelectedMenu.price);

        // Close modal
        menuDetailModal.hide();
    }

    function updateDessertBadge(id, qty) {
        let badge = document.getElementById('badge_ds_' + id);
        if(badge) {
            if(qty > 0) {
                badge.textContent = qty + ' di Keranjang';
                badge.classList.remove('d-none');
            } else {
                badge.classList.add('d-none');
            }
        }
    }

    function openDessertModal(id, name) {
        document.getElementById('modalDessertId').value = id;
        document.getElementById('dessertModalName').textContent = name;
        
        let currentQty = dessertCart[id] || 1; 
        if (currentQty === 0) currentQty = 1; // Default to 1 if they open it and it was 0
        document.getElementById('modalDessertQty').value = currentQty;
        
        dessertQtyModal.show();
    }

    function changeModalQty(delta) {
        let input = document.getElementById('modalDessertQty');
        let val = parseInt(input.value) || 0;
        val += delta;
        if(val < 0) val = 0;
        input.value = val;
    }

    function confirmDessertQty() {
        let id = document.getElementById('modalDessertId').value;
        let qty = parseInt(document.getElementById('modalDessertQty').value) || 0;
        
        dessertCart[id] = qty;
        localStorage.setItem('siraos_dessert_cart', JSON.stringify(dessertCart));
        
        updateDessertBadge(id, qty);
        
        dessertQtyModal.hide();
        
        // Show notification
        if(!selectedMenuId && qty > 0) {
            const Toast = Swal.mixin({
              toast: true,
              position: "top-end",
              showConfirmButton: false,
              timer: 3000,
              timerProgressBar: true,
            });
            Toast.fire({
              icon: "success",
              title: "Dessert ditambahkan! Silakan pilih paket katering utama untuk checkout."
            });
        } else if (qty === 0) {
            const Toast = Swal.mixin({
              toast: true,
              position: "top-end",
              showConfirmButton: false,
              timer: 2000,
            });
            Toast.fire({
              icon: "info",
              title: "Dessert dihapus dari keranjang."
            });
        }
    }

    function goToCheckout() {
        if(selectedMenuId) {
            window.location.href = '/checkout?menu_id=' + selectedMenuId;
        }
    }
</script>
@endpush
