<?php

use App\Http\Controllers\Position\StockPositionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [StockPositionController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

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
});

Route::get('/stock-position/create', [StockPositionController::class, 'create'])->name('stockPosition.create');
Route::post('/stock-position/store', [StockPositionController::class, 'store'])->name('stockPosition.store');
Route::get('/stock-position/{stockPosition}', [StockPositionController::class, 'show'])->name('stockPosition.show');
Route::get('/stock-position/{stockPosition}/edit', [StockPositionController::class, 'edit'])->name('stockPosition.edit');
Route::put('/stock-position/{stockPosition}', [StockPositionController::class, 'update'])->name('stockPosition.update');
Route::delete('/stock-position/{stockPosition}', [StockPositionController::class, 'destroy'])->name('stockPosition.destroy');
Route::get('/stock-position/{stockPosition}/download-qr', [StockPositionController::class, 'downloadQr'])->name('stockPosition.download-qr');
Route::get('/stock-positions/export', [StockPositionController::class, 'export'])->name('stockPosition.export');

require __DIR__.'/auth.php';
