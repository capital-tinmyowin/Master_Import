<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemMasterImportController;
use App\Http\Controllers\SkuMasterImportController;
use App\Http\Controllers\ImportLogController;

// ==================== PUBLIC ROUTES ====================
// 1. Root route - ALWAYS redirects to login
Route::get('/', function () {
    return redirect('/login');
});

// 2. Include Breeze authentication routes
require __DIR__ . '/auth.php';

// ==================== LOGOUT ROUTE ====================
Route::post('/logout', function () {
    Auth::guard('web')->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// ==================== PROTECTED ROUTES ====================
Route::middleware(['auth'])->group(function () {

    // Dashboard/Home - Use Controller
    Route::get('/dashboard', [ImportLogController::class, 'index'])->name('dashboard');
    Route::get('/home', [ImportLogController::class, 'index'])->name('home');

    // Import Logs Routes
    Route::get('/import-logs/detail/{id}', [ImportLogController::class, 'detail'])->name('import-logs.detail');
    Route::get('/import-logs/data/{logId}', [ImportLogController::class, 'getData']);
    Route::get('/import-logs/errors/{logId}', [ImportLogController::class, 'getErrors']);
    Route::get('/import-logs/records/{id}/{type}', [ImportLogController::class, 'records'])->name('import-logs.records');

    Route::get('/item-import/preview', [ItemMasterImportController::class, 'showPreview'])->name('item-import.preview.show');
    // Route::post('/item-import/preview/csv-process', [ItemMasterImportController::class, 'processCsvPreview'])->name('item-import.preview.csv-process');
    // Route::post('/item-import/preview/excel-process', [ItemMasterImportController::class, 'processPreview'])->name('item-import.preview.excel-process');

    // ========== Authentication Check Route ==========
    Route::get('/check-auth', function () {
        return response()->json([
            'authenticated' => true,
            'user' => [
                'id' => Auth::id(),
                'name' => Auth::user()->name,
                'email' => Auth::user()->email
            ]
        ]);
    })->name('check.auth');

    // ========== ALL MItems Routes ==========
    Route::prefix('mitems')->group(function () {
        Route::get('/', [MItemController::class, 'index'])->name('mitems.index');
        Route::get('/create', [MItemController::class, 'create'])->name('mitems.create');
        Route::post('/', [MItemController::class, 'store'])->name('mitems.store');
        Route::get('/{id}/edit', [MItemController::class, 'edit'])->name('mitems.edit');
        Route::put('/{id}', [MItemController::class, 'update'])->name('mitems.update');

        Route::post('/mitems/delete-multiple', [MItemController::class, 'deleteMultiple'])->name('mitems.delete-multiple');
    Route::delete('/mitems/{id}', [MItemController::class, 'destroy'])->name('mitems.destroy');
        Route::get('/by-code/{item_code}', [ImportLogController::class, 'showByCode'])
            ->name('mitems.by.code.redirect');
        // Check item code
        Route::get('/check-item-code', [MItemController::class, 'checkItemCode'])->name('mitems.check.item.code');
        Route::post('/check-item-code', [MItemController::class, 'checkItemCodePost'])->name('mitems.check.item.code.post');

        // Export Routes
        Route::get('/export/excel', [MItemController::class, 'exportExcel'])->name('mitems.export.excel');
        Route::get('/export/csv', [MItemController::class, 'exportCsv'])->name('mitems.export.csv');

        // Import Routes
        Route::get('/import', [MItemController::class, 'import'])->name('mitems.import');
        Route::post('/import', [MItemController::class, 'import'])->name('mitems.import.post');
        Route::post('/preview-import', [MItemController::class, 'preview'])->name('mitems.preview');
    });

    // Import Pages
    Route::get('/item-master-import', function () {
        return view('import.item_master_import');
    })->name('import.item-master');

    Route::get('/sku-master-import', function () {
        return view('import.sku_master_import');
    })->name('import.sku-master');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ========== Item Import Routes (Grouped) ==========
    Route::prefix('item-import')->name('item-import.')->group(function () {
        Route::get('/', [ItemMasterImportController::class, 'index'])->name('index');

        // Preview routes - FIXED: Changed 'preview' to 'processPreview'
        Route::post('/preview/process', [ItemMasterImportController::class, 'processPreview'])->name('preview.process');
        Route::post('/preview/csv-process', [ItemMasterImportController::class, 'processCsvPreview'])->name('preview.csv-process');
        Route::post('/preview/confirm', [ItemMasterImportController::class, 'confirmImport'])->name('preview.confirm');
        Route::post('/preview/cancel', [ItemMasterImportController::class, 'cancelPreview'])->name('preview.cancel');

        // Direct import routes (optional - keep if you want direct import without preview)
        Route::post('/excel', [ItemMasterImportController::class, 'importExcel'])->name('excel');
        Route::post('/csv', [ItemMasterImportController::class, 'importCsv'])->name('csv');

        // Logs
        Route::get('/logs', [ItemMasterImportController::class, 'logs'])->name('logs');
    });

    // SKU Import Routes
    Route::prefix('sku-import')->name('sku-import.')->group(function () {
        Route::get('/', [SkuMasterImportController::class, 'index'])->name('index');
        Route::get('/preview/show', [SkuMasterImportController::class, 'showPreview'])->name('preview.show');

        // Preview routes
        Route::post('/preview/process', [SkuMasterImportController::class, 'processPreview'])->name('preview.process');
        Route::post('/preview/csv-process', [SkuMasterImportController::class, 'processCsvPreview'])->name('preview.csv-process');
        Route::post('/preview/confirm', [SkuMasterImportController::class, 'confirmImport'])->name('preview.confirm');
        Route::post('/preview/cancel', [SkuMasterImportController::class, 'cancelPreview'])->name('preview.cancel');

        // Direct import routes (optional)
        Route::post('/excel', [SkuMasterImportController::class, 'importExcel'])->name('excel');
        Route::post('/csv', [SkuMasterImportController::class, 'importCsv'])->name('csv');

        // Logs
        Route::get('/logs', [SkuMasterImportController::class, 'logs'])->name('logs');
    });
});
