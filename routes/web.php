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

    // Settings index is visible to all authenticated users
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
});

// Settings mutations (admin/editor only)
Route::middleware(['auth', 'can-edit'])->group(function () {
    Route::post('/settings/polish-types', [SettingsController::class, 'addPolishType'])->name('settings.polish-types.add');
    Route::put('/settings/polish-types/{polishType}', [SettingsController::class, 'updatePolishType'])->name('settings.polish-types.update');
    Route::delete('/settings/polish-types/{polishType}', [SettingsController::class, 'deletePolishType'])->name('settings.polish-types.delete');

    Route::post('/settings/product-types', [SettingsController::class, 'addProductType'])->name('settings.product-types.add');
    Route::put('/settings/product-types/{productType}', [SettingsController::class, 'updateProductType'])->name('settings.product-types.update');
    Route::delete('/settings/product-types/{productType}', [SettingsController::class, 'deleteProductType'])->name('settings.product-types.delete');

    Route::post('/settings/stone-types', [SettingsController::class, 'addStoneType'])->name('settings.stone-types.add');
    Route::put('/settings/stone-types/{stoneType}', [SettingsController::class, 'updateStoneType'])->name('settings.stone-types.update');
    Route::delete('/settings/stone-types/{stoneType}', [SettingsController::class, 'deleteStoneType'])->name('settings.stone-types.delete');

    // User management in settings
    Route::post('/settings/users', [SettingsController::class, 'createUser'])->name('settings.users.create');
    Route::put('/settings/users/{user}/role', [SettingsController::class, 'updateUserRole'])->name('settings.users.update-role');
    Route::post('/settings/users/{user}/reset-password', [SettingsController::class, 'resetUserPassword'])->name('settings.users.reset-password');
    Route::delete('/settings/users/{user}', [SettingsController::class, 'deleteUser'])->name('settings.users.delete');
});

// Маршруты для позиций, требующие авторизации (должны быть ПЕРЕД параметрическими маршрутами)
Route::middleware(['auth','can-edit'])->group(function () {
    Route::get('/stock-position/create', [StockPositionController::class, 'create'])->name('stockPosition.create');
    Route::post('/stock-position/store', [StockPositionController::class, 'store'])->name('stockPosition.store');
    Route::get('/stock-position/{stockPosition}/edit', [StockPositionController::class, 'edit'])->name('stockPosition.edit');
    Route::put('/stock-position/{stockPosition}', [StockPositionController::class, 'update'])->name('stockPosition.update');
    Route::delete('/stock-position/{stockPosition}', [StockPositionController::class, 'destroy'])->name('stockPosition.destroy');
});
// Export available to authenticated users (including viewer)
Route::middleware('auth')->get('/stock-positions/export', [StockPositionController::class, 'export'])->name('stockPosition.export');

// Публичные маршруты для позиций (доступны по ссылкам)
Route::get('/stock-position/{stockPosition}', [StockPositionController::class, 'show'])->name('stockPosition.show');

// Список поддонов доступен всем аутентифицированным (включая viewer)
Route::middleware('auth')->get('/pallet', [PalletController::class, 'index'])->name('pallet.index');

// Мутации поддонов только для тех, кто может редактировать
Route::middleware(['auth','can-edit'])->group(function () {
    Route::get('/pallet/create', [PalletController::class, 'create'])->name('pallet.create');
    Route::post('/pallet', [PalletController::class, 'store'])->name('pallet.store');
    Route::get('/pallet/{pallet}/edit', [PalletController::class, 'edit'])->name('pallet.edit');
    Route::put('/pallet/{pallet}', [PalletController::class, 'update'])->name('pallet.update');
    Route::patch('/pallet/{pallet}/status', [PalletController::class, 'updateStatus'])->name('pallet.update-status');
    Route::delete('/pallet/{pallet}', [PalletController::class, 'destroy'])->name('pallet.destroy');
});
// Export and QR download allowed for authenticated users (including viewer)
Route::middleware('auth')->group(function () {
    Route::get('/pallet/export', [PalletController::class, 'export'])->name('pallet.export');
    Route::get('/pallet/{pallet}/download-qr', [PalletController::class, 'downloadQr'])->name('pallet.download-qr');
});

// Публичные маршруты для поддонов (доступны по QR-коду)
Route::get('/pallet/{pallet}', [PalletController::class, 'show'])->name('pallet.show');

require __DIR__ . '/auth.php';
