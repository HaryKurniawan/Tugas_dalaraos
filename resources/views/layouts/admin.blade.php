<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - @yield('title', 'SIRAOS')</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
    <!-- AlpineJS for interactive dropdowns if needed -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-50 text-gray-900 antialiased flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-gray-900 text-white flex flex-col h-full flex-shrink-0 transition-all duration-300">
        <div class="h-16 flex items-center px-6 bg-gray-950 border-b border-gray-800">
            <span class="font-bold text-xl text-green-500">SIRAOS</span>
            <span class="ml-2 text-gray-400 font-medium text-sm">Admin</span>
        </div>

        <div class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-green-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                    </path>
                </svg>
                Beranda
            </a>

            <!-- MODUL KATERING -->
            <div class="pt-4 pb-2">
                <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Modul Katering</p>
            </div>

            <a href="{{ route('admin.orders.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('admin.orders.*') ? 'bg-green-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                Daftar Pesanan Katering
            </a>

            <a href="{{ route('admin.menus.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('admin.menus.*') ? 'bg-green-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                    </path>
                </svg>
                Menu Katering
            </a>

            <a href="{{ route('admin.kecamatan.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('admin.kecamatan.*') ? 'bg-green-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Kecamatan
            </a>

            <!-- MODUL POS -->
            <div class="pt-4 pb-2">
                <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Modul POS</p>
            </div>

            <a href="{{ route('admin.pos.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('admin.pos.*') ? 'bg-green-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                    </path>
                </svg>
                Kasir (POS)
            </a>

            <a href="{{ route('admin.produk-pos.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('admin.produk-pos.*') ? 'bg-green-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                </svg>
                Daftar Produk POS
            </a>

            <a href="{{ route('admin.pos-transactions.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('admin.pos-transactions.*') ? 'bg-green-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                    </path>
                </svg>
                Riwayat Transaksi POS
            </a>

            <div class="pt-2 pb-2" x-data="{ open: {{ request()->routeIs('admin.stocks.*') ? 'true' : 'false' }} }">
                <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Manajemen Stok</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.stocks.daily') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('admin.stocks.daily') ? 'bg-green-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                        Stok Harian (POS)
                    </a>
                    <a href="{{ route('admin.stocks.masuk') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('admin.stocks.masuk') ? 'bg-green-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                        Riwayat Stok Masuk
                    </a>
                </div>
            </div>
        </div>

        <!-- User Profile/Logout -->
        <div class="p-4 border-t border-gray-800">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="flex items-center gap-3 px-3 py-2 w-full text-left text-red-400 hover:bg-red-900/30 hover:text-red-300 rounded-md transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content wrapper -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-50">
        <!-- Top header / Breadcrumb area (optional) -->
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 shadow-sm z-10">
            <h2 class="text-xl font-semibold text-gray-800">@yield('title', 'Dashboard')</h2>
            <div class="text-sm text-gray-500">
                {{ auth()->user()->name ?? 'Administrator' }}
            </div>
        </header>

        <!-- Scrollable Content -->
        <main class="flex-1 overflow-y-auto p-6">
            @if (session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-md shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-md shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">
                                {{ session('error') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Extra Scripts -->
    @yield('scripts')
</body>

</html>