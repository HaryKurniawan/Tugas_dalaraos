<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminPrintController;

Route::get('/', function () {
    return view('pelanggan.beranda');
});

Route::get('/pesanan-saya', [\App\Http\Controllers\OrderController::class, 'track']);
Route::get('/lacak', [\App\Http\Controllers\OrderController::class, 'track']);
Route::post('/pesan', [\App\Http\Controllers\OrderController::class, 'store']);

Route::get('/checkout', function (\Illuminate\Http\Request $request) {
    return view('pelanggan.checkout', ['menu_id' => $request->query('menu_id')]);
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// ===== ADMIN PRINT ROUTES =====
// These are accessed from within the Filament admin panel (no auth middleware here
// since admin panel already protects access)
Route::prefix('admin/print')->name('admin.')->group(function () {
    Route::get('/spk/{id}',                [AdminPrintController::class, 'spk'])->name('spk.print');
    Route::get('/struk/{id}',              [AdminPrintController::class, 'struk'])->name('struk.print');
    Route::get('/laporan-supplier/{id}',   [AdminPrintController::class, 'laporanSupplier'])->name('laporan.supplier');
    Route::get('/laporan-keseluruhan',     [AdminPrintController::class, 'laporanKeseluruhan'])->name('laporan.print');
});
