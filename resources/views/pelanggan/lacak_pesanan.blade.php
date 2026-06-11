@extends('layouts.pelanggan')

@section('content')
<div class="container-fluid p-0">

    <!-- Tabs removed to use Sidebar -->

    {{-- ===== MAIN TRACK CARD ===== --}}
    <div class="animate-fade-in track-card">

        {{-- Header --}}
        <div class="form-section-title mb-4">
            <div class="form-section-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                </svg>
            </div>
            Lacak Status Pesanan Saya
        </div>

        <p class="mb-4" style="font-size: 13px; color: var(--siraos-muted); line-height: 1.6;">
            Masukkan <strong>Nomor HP</strong> atau <strong>ID Pesanan</strong> untuk melihat status terkini
            pesanan katering atau pengiriman internal Anda.
        </p>

        {{-- Search Form --}}
        <form action="/lacak" method="GET" id="trackForm">
            <div class="row g-2 mb-4">
                <div class="col-md-9">
                    <div class="position-relative">
                        <div style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:var(--siraos-muted-light); pointer-events:none;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                            </svg>
                        </div>
                        <input type="text" name="query" id="trackQuery"
                               class="form-control track-search-input"
                               style="padding-left: 42px;"
                               placeholder="08123456789 atau ORD-12345"
                               value="{{ request('query') }}"
                               required autocomplete="off">
                    </div>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn-siraos-dark w-100 h-100 fw-bold rounded-3"
                            style="font-size: 13px; padding: 10px 16px;">
                        Cari Pesanan
                    </button>
                </div>
            </div>
        </form>

        {{-- Recent Searches (from localStorage) --}}
        <div id="recentSearchesContainer" class="d-none mb-4 animate-fade-in">
            <p style="font-size: 11px; color: var(--siraos-muted); margin-bottom: 6px; font-weight: 700;">Riwayat Pencarian Terakhir:</p>
            <div id="recentSearchesList" class="d-flex flex-wrap gap-2">
                <!-- Pills injected by JS -->
            </div>
        </div>

        <div class="form-section-divider" style="margin: 0 0 1.5rem;">
            <span class="form-section-divider-label">Hasil Pencarian</span>
        </div>

        {{-- Result Area --}}
        @if(request('query'))

            {{-- If results found (from controller) --}}
            @if(isset($pesanan) && $pesanan)
            <div class="animate-fade-in">
                {{-- Status Timeline --}}
                <div class="p-4 rounded-3 border mb-4"
                     style="background: linear-gradient(135deg, #f0fdf4, #fff); border-color: var(--siraos-primary-light) !important;">
                    <div class="d-flex align-items-start gap-3 mb-3">
                        <div style="width:42px; height:42px; background:var(--siraos-primary-glass);
                                    border:1px solid rgba(22,163,74,.2); border-radius:50%;
                                    display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                 fill="none" stroke="var(--siraos-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/>
                                <line x1="3" x2="21" y1="6" y2="6"/>
                                <path d="M16 10a4 4 0 0 1-8 0"/>
                            </svg>
                        </div>
                        <div>
                            <p class="mb-0 fw-bold" style="font-size:14px; color:var(--gray-900);">
                                Pesanan #{{ $pesanan->id ?? 'ORD-00001' }}
                            </p>
                            <span class="font-mono" style="font-size:10px; color:var(--siraos-muted);">
                                {{ $pesanan->created_at ?? now() }}
                            </span>
                        </div>
                        <span class="ms-auto badge rounded-pill px-3 py-2"
                              style="background:var(--siraos-primary-glass); color:var(--siraos-primary-dark);
                                     font-size:10px; font-weight:700; border:1px solid rgba(22,163,74,.2);">
                            {{ strtoupper($pesanan->status ?? 'Diproses') }}
                        </span>
                    </div>
                </div>
            </div>
            @else
            {{-- No result found --}}
            <div class="animate-fade-in track-empty-state">
                <span class="track-empty-icon">🔍</span>
                <p class="track-empty-title">Pesanan tidak ditemukan</p>
                <p class="track-empty-sub">
                    Tidak ada pesanan dengan nomor "<strong>{{ request('query') }}</strong>".<br>
                    Periksa kembali nomor HP atau ID pesanan Anda.
                </p>
            </div>
            @endif

        @else
        {{-- Default empty state --}}
        <div class="track-empty-state animate-fade-in">
            <span class="track-empty-icon">📦</span>
            <p class="track-empty-title">Belum ada pencarian</p>
            <p class="track-empty-sub">
                Silakan masukkan nomor telepon atau ID pesanan Anda di kolom pencarian di atas.
            </p>
        </div>
        @endif

        {{-- Quick tips --}}
        <div class="mt-5 pt-4 border-top">
            <p class="fw-bold mb-3" style="font-size: 11px; text-transform: uppercase; letter-spacing: .08em; color: var(--gray-700);">
                Tips Pencarian
            </p>
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="p-3 rounded-3 border h-100" style="background:#f9fafb;">
                        <p class="fw-bold mb-1" style="font-size:11px; color:var(--gray-800);">
                            📱 Nomor HP
                        </p>
                        <p class="mb-0" style="font-size:10px; color:var(--siraos-muted);">
                            Gunakan nomor WhatsApp yang didaftarkan saat pemesanan.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 rounded-3 border h-100" style="background:#f9fafb;">
                        <p class="fw-bold mb-1" style="font-size:11px; color:var(--gray-800);">
                            🏷️ ID Pesanan
                        </p>
                        <p class="mb-0" style="font-size:10px; color:var(--siraos-muted);">
                            Format: <span class="font-mono">ORD-XXXXX</span> — ada di email atau struk Anda.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 rounded-3 border h-100" style="background:#f9fafb;">
                        <p class="fw-bold mb-1" style="font-size:11px; color:var(--gray-800);">
                            💬 Hubungi Kami
                        </p>
                        <p class="mb-0" style="font-size:10px; color:var(--siraos-muted);">
                            Kesulitan? Chat WhatsApp Dalaraos Jatihandap.
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    const trackForm = document.getElementById('trackForm');
    const trackInput = document.getElementById('trackQuery');
    const recentContainer = document.getElementById('recentSearchesContainer');
    const recentList = document.getElementById('recentSearchesList');

    // Load recent searches from localStorage
    function loadRecentSearches() {
        let searches = JSON.parse(localStorage.getItem('siraos_tickets')) || [];
        if (searches.length > 0) {
            recentContainer.classList.remove('d-none');
            recentList.innerHTML = '';
            
            searches.forEach(function(query) {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'btn btn-sm btn-outline-secondary font-mono d-flex align-items-center gap-1 px-2 py-1';
                btn.style.fontSize = '10px';
                btn.style.borderRadius = '6px';
                btn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M12 7v5l4 2"/></svg>
                    ${query}
                `;
                btn.onclick = function() {
                    trackInput.value = query;
                    trackForm.submit();
                };
                recentList.appendChild(btn);
            });
        }
    }

    // Save to localStorage when form is submitted
    trackForm.addEventListener('submit', function(e) {
        const query = trackInput.value.trim();
        if (query) {
            let searches = JSON.parse(localStorage.getItem('siraos_tickets')) || [];
            
            // Remove if already exists to put it at the front
            searches = searches.filter(q => q !== query);
            
            // Add to front
            searches.unshift(query);
            
            // Keep only last 5 searches
            if (searches.length > 5) {
                searches.pop();
            }
            
            localStorage.setItem('siraos_tickets', JSON.stringify(searches));
        }
    });

    loadRecentSearches();

    // If there is a successful query right now from the controller, ensure it's saved.
    // Assuming if request('query') is present, it means a search was just executed.
    const currentQuery = "{{ request('query') }}";
    if (currentQuery) {
        let searches = JSON.parse(localStorage.getItem('siraos_tickets')) || [];
        if (!searches.includes(currentQuery)) {
             searches.unshift(currentQuery);
             if(searches.length > 5) searches.pop();
             localStorage.setItem('siraos_tickets', JSON.stringify(searches));
             loadRecentSearches();
        }
    }
});
</script>
@endpush
