<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SIRAOS – Platform pemesanan katering tradisional & modern Waroeng Dalaraos Jatihandap, Bandung.">
    <title>SIRAOS – Waroeng Dalaraos Jatihandap</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&family=JetBrains+Mono:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/pelanggan.css') }}">

    @stack('styles')
</head>
<body>

    <!-- ===== NAVBAR ===== -->
    <nav class="siraos-navbar navbar sticky-top">
        <div class="container-fluid px-3 px-lg-4">

            <!-- Brand -->
            <a class="navbar-brand d-flex align-items-center text-decoration-none" href="/">
                <div class="navbar-brand-icon">
                    <!-- Pot / Catering icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"/>
                        <path d="m3 9 2.45-4.9A2 2 0 0 1 7.24 3h9.52a2 2 0 0 1 1.8 1.1L21 9"/>
                        <path d="M12 3v6"/>
                    </svg>
                </div>
                <span class="brand-label">SIRAOS</span>
                <span class="brand-sub d-none d-md-inline">Waroeng Dalaraos</span>
            </a>

            <!-- Hamburger Menu Button -->
            <button class="btn btn-link text-gray-900 p-0 ms-auto text-decoration-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mainSidebar" aria-controls="mainSidebar">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/>
                </svg>
            </button>
        </div>
    </nav>

    <!-- ===== OFFCANVAS SIDEBAR ===== -->
    <div class="offcanvas offcanvas-end border-0 shadow-lg" tabindex="-1" id="mainSidebar" aria-labelledby="mainSidebarLabel" style="width: 300px; z-index: 1060;">
        <div class="offcanvas-header border-bottom p-4">
            <h5 class="offcanvas-title fw-bold text-gray-900 d-flex align-items-center gap-2" id="mainSidebarLabel" style="font-size: 16px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--siraos-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"/>
                    <path d="m3 9 2.45-4.9A2 2 0 0 1 7.24 3h9.52a2 2 0 0 1 1.8 1.1L21 9"/><path d="M12 3v6"/>
                </svg>
                Menu Navigasi
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-4 d-flex flex-column">
            
            <a href="/" class="text-decoration-none d-flex align-items-center gap-3 p-3 rounded-3 mb-2 {{ request()->is('/') ? 'bg-light text-siraos-primary-dark fw-bold' : 'text-gray-800' }}" style="transition: all 0.2s;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                Pesan Katering
            </a>

            <a href="/pesanan-saya" class="text-decoration-none d-flex align-items-center gap-3 p-3 rounded-3 mb-2 {{ request()->is('pesanan-saya') ? 'bg-light text-siraos-primary-dark fw-bold' : 'text-gray-800' }}" style="transition: all 0.2s;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                Lacak Pesanan Saya
            </a>
            
            <hr class="my-4 text-muted">
            
            <div class="mt-auto p-3 rounded-3" style="background: rgba(22, 163, 74, 0.05); border: 1px dashed rgba(22,163,74,0.3);">
                <p class="mb-1 fw-bold" style="font-size: 13px; color: var(--siraos-primary-dark);">Butuh Bantuan?</p>
                <p class="mb-0 text-muted" style="font-size: 11px;">Hubungi admin kami jika ada kendala pemesanan: <strong>0812-3456-7890</strong></p>
            </div>
        </div>
    </div>

    <!-- ===== MAIN CONTENT ===== -->
    <main>
        @yield('content')
    </main>

    <!-- ===== FOOTER ===== -->
    <footer class="siraos-footer">
        <p class="footer-text mb-0">
            SIRAOS Jatihandap
            <span class="footer-dot">&bull;</span>
            Waroeng Dalaraos Bandung
            <span class="footer-dot">&bull;</span>
            v2.0
            <span class="footer-dot">&bull;</span>
            @auth
                {{ Auth::user()->nama }} &middot; {{ strtoupper(Auth::user()->role) }}
            @else
                Pengunjung
            @endauth
        </p>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Fix: ensure correct JS bundle (not css) -->
    @stack('scripts')
</body>
</html>
