<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminPrintController;

Route::get('/', function () {
    $desserts = \App\Models\Menu::where('kategori', 'Dessert')->get();
    return view('pelanggan.beranda', compact('desserts'));
});

Route::get('/pesanan-saya', [\App\Http\Controllers\OrderController::class, 'track']);
Route::get('/lacak', [\App\Http\Controllers\OrderController::class, 'track']);
Route::get('/pesanan/{id}', [\App\Http\Controllers\OrderController::class, 'show']);
Route::post('/pesan', [\App\Http\Controllers\OrderController::class, 'store']);
Route::get('/pembayaran', [\App\Http\Controllers\OrderController::class, 'pembayaran']);
Route::post('/pembayaran', [\App\Http\Controllers\OrderController::class, 'prosesPembayaran']);

Route::get('/checkout', function (\Illuminate\Http\Request $request) {
    $kecamatans = \App\Models\Kecamatan::with('ongkir')->get();
    
    // menu_id from frontend is currently the SKU (e.g., m8, m9)
    $menuSku = $request->query('menu_id');
    $selectedMenu = \App\Models\Menu::where('sku', $menuSku)->first();
    
    $desserts = \App\Models\Menu::where('kategori', 'Dessert')->get();

    return view('pelanggan.checkout', [
        'menu_id' => $menuSku,
        'selectedMenu' => $selectedMenu,
        'desserts' => $desserts,
        'kecamatans' => $kecamatans
    ]);
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'email_or_phone' => ['required'],
        'password' => ['required'],
    ]);

    $credentials = [
        'email' => $request->email_or_phone,
        'password' => $request->password,
    ];

    if (\Illuminate\Support\Facades\Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('admin/dashboard');
    }

    return back()->with('error', 'Kredensial tidak cocok dengan data kami.')->withInput();
});

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/logout', function (\Illuminate\Http\Request $request) {
    \Illuminate\Support\Facades\Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// ===== ADMIN PRINT ROUTES =====
// These are accessed from within the Filament admin panel (no auth middleware here
// since admin panel already protects access)
Route::prefix('admin/print')->name('admin.')->group(function () {
    Route::get('/spk/{id}',                [AdminPrintController::class, 'spk'])->name('spk.print');
    Route::get('/struk/{id}',              [AdminPrintController::class, 'struk'])->name('struk.print');
    Route::get('/laporan-supplier/{id}',   [AdminPrintController::class, 'laporanSupplier'])->name('laporan.supplier');
    Route::get('/laporan-keseluruhan',     [AdminPrintController::class, 'laporanKeseluruhan'])->name('laporan.print');
});
// ===== ADMIN CRUD ROUTES =====
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });
    Route::get('/dashboard', [\App\Http\Controllers\AdminOrderController::class, 'dashboard'])->name('dashboard');
    Route::get('/pos', [\App\Http\Controllers\AdminPosController::class, 'index'])->name('pos.index');
    Route::post('/pos', [\App\Http\Controllers\AdminPosController::class, 'store'])->name('pos.store');
    
    // Stok
    Route::prefix('stocks')->name('stocks.')->group(function () {
        Route::get('/daily', [\App\Http\Controllers\AdminStockController::class, 'dailyIndex'])->name('daily');
        Route::put('/daily/{id}', [\App\Http\Controllers\AdminStockController::class, 'dailyUpdate'])->name('daily.update');
        
        Route::get('/kering', [\App\Http\Controllers\AdminStockController::class, 'keringIndex'])->name('kering');
        Route::post('/kering', [\App\Http\Controllers\AdminStockController::class, 'keringStore'])->name('kering.store');
        Route::put('/kering/{id}', [\App\Http\Controllers\AdminStockController::class, 'keringUpdate'])->name('kering.update');
        
        Route::get('/masuk', [\App\Http\Controllers\AdminStockController::class, 'masukIndex'])->name('masuk');
        Route::post('/masuk', [\App\Http\Controllers\AdminStockController::class, 'masukStore'])->name('masuk.store');
    });

    Route::resource('kecamatan', \App\Http\Controllers\KecamatanController::class);
    Route::resource('menus', \App\Http\Controllers\MenuController::class);
    Route::resource('produk-pos', \App\Http\Controllers\AdminProdukPosController::class);
    Route::resource('orders', \App\Http\Controllers\AdminOrderController::class);

    // Riwayat Transaksi POS
    Route::get('/pos-transactions', [\App\Http\Controllers\AdminPosTransactionController::class, 'index'])->name('pos-transactions.index');
});
