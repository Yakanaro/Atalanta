<?php

use App\Http\Controllers\PalletController;
use App\Http\Controllers\Position\StockPositionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [PalletController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');

    Route::post('/settings/polish-types', [SettingsController::class, 'addPolishType'])->name('settings.polish-types.add');
    Route::put('/settings/polish-types/{polishType}', [SettingsController::class, 'updatePolishType'])->name('settings.polish-types.update');
    Route::delete('/settings/polish-types/{polishType}', [SettingsController::class, 'deletePolishType'])->name('settings.polish-types.delete');

    Route::post('/settings/product-types', [SettingsController::class, 'addProductType'])->name('settings.product-types.add');
    Route::put('/settings/product-types/{productType}', [SettingsController::class, 'updateProductType'])->name('settings.product-types.update');
    Route::delete('/settings/product-types/{productType}', [SettingsController::class, 'deleteProductType'])->name('settings.product-types.delete');

    Route::post('/settings/stone-types', [SettingsController::class, 'addStoneType'])->name('settings.stone-types.add');
    Route::put('/settings/stone-types/{stoneType}', [SettingsController::class, 'updateStoneType'])->name('settings.stone-types.update');
    Route::delete('/settings/stone-types/{stoneType}', [SettingsController::class, 'deleteStoneType'])->name('settings.stone-types.delete');
});

// Маршруты для позиций, требующие авторизации (должны быть ПЕРЕД параметрическими маршрутами)
Route::middleware('auth')->group(function () {
    Route::get('/stock-position/create', [StockPositionController::class, 'create'])->name('stockPosition.create');
    Route::post('/stock-position/store', [StockPositionController::class, 'store'])->name('stockPosition.store');
    Route::get('/stock-position/{stockPosition}/edit', [StockPositionController::class, 'edit'])->name('stockPosition.edit');
    Route::put('/stock-position/{stockPosition}', [StockPositionController::class, 'update'])->name('stockPosition.update');
    Route::delete('/stock-position/{stockPosition}', [StockPositionController::class, 'destroy'])->name('stockPosition.destroy');
    Route::get('/stock-positions/export', [StockPositionController::class, 'export'])->name('stockPosition.export');
});

// Публичные маршруты для позиций (доступны по ссылкам)
Route::get('/stock-position/{stockPosition}', [StockPositionController::class, 'show'])->name('stockPosition.show');

// Маршруты для поддонов, требующие авторизации (должны быть ПЕРЕД параметрическими маршрутами)
Route::middleware('auth')->group(function () {
    Route::get('/pallet', [PalletController::class, 'index'])->name('pallet.index');
    Route::get('/pallet/create', [PalletController::class, 'create'])->name('pallet.create');
    Route::get('/pallet/export', [PalletController::class, 'export'])->name('pallet.export');
    Route::post('/pallet', [PalletController::class, 'store'])->name('pallet.store');
    Route::get('/pallet/{pallet}/edit', [PalletController::class, 'edit'])->name('pallet.edit');
    Route::put('/pallet/{pallet}', [PalletController::class, 'update'])->name('pallet.update');
    Route::patch('/pallet/{pallet}/status', [PalletController::class, 'updateStatus'])->name('pallet.update-status');
    Route::get('/pallet/{pallet}/download-qr', [PalletController::class, 'downloadQr'])->name('pallet.download-qr');
    Route::delete('/pallet/{pallet}', [PalletController::class, 'destroy'])->name('pallet.destroy');
});

// Публичные маршруты для поддонов (доступны по QR-коду)
Route::get('/pallet/{pallet}', [PalletController::class, 'show'])->name('pallet.show');

require __DIR__ . '/auth.php';
