<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductionOrderController;
use App\Http\Controllers\Admin\SettingsController;
use Laravel\Fortify\Fortify;

Route::middleware(['web'])->group(function () {
    // Custom login view
    Fortify::loginView(fn() => view('auth.login'));

    // Custom registration view
    Fortify::registerView(fn() => view('auth.register'));

    // Custom password reset request view
    Fortify::requestPasswordResetLinkView(fn() => view('auth.passwords.email'));

    // Custom password reset form view
    Fortify::resetPasswordView(fn() => view('auth.passwords.reset'));

    // Optional: Custom email verification view
    Fortify::verifyEmailView(fn() => view('auth.verify'));
});

// Production Order Routes
Route::prefix('production')->group(function () {
    // Route for the main production management page
    Route::get('/index', [ProductionOrderController::class, 'index'])->name('production.index');

    // Create a new production order
    Route::get('/create', [ProductionOrderController::class, 'create'])->name('production.create');

    // Store a new production order
    Route::post('/store', [ProductionOrderController::class, 'store'])->name('production.store');

    // Show the released production orders page
    Route::get('/released', [ProductionOrderController::class, 'showReleasedProductions'])->name('production.released');

    // Show the kiosk view for clock functionality
    Route::get('/kiosk', [ProductionOrderController::class, 'kiosk'])->name('production.kiosk');

    // Show the Finished Orders
    Route::get('/finished', [ProductionOrderController::class, 'showFinishedOrders'])->name('production.finished');

    // Detailed view of a specific production order (using WO number as identifier)
    Route::get('/{wo_number}', [ProductionOrderController::class, 'show'])->name('production.show');

    // Update a specific production order by WO number
    Route::put('/{wo_number}', [ProductionOrderController::class, 'update'])->name('production.update');

    // Generate WO Number
    Route::post('/generate-wo-number', [ProductionOrderController::class, 'generateWONumberViaAjax'])->name('production.generateWONumber');

    // Generate Report for a specific production order by WO number
    Route::get('/generate-report/{wo_number}', [ProductionOrderController::class, 'generateReport'])->name('production.generateReport');

    // Export Report to PDF for a specific production order by WO number
    Route::get('/{wo_number}/export/pdf', [ProductionOrderController::class, 'exportReportToPDF'])->name('production.export.pdf');

    // Export Report to Excel for a specific production order by WO number
    Route::get('/{wo_number}/export/excel', [ProductionOrderController::class, 'exportReportToExcel'])->name('production.export.excel');

    // Transfer a production order to finished orders
    Route::post('/{wo_number}/transferToFinished', [ProductionOrderController::class, 'transferToFinished'])->name('production.transferToFinished');

    // Route to view the report in a new tab for Finished Order
    Route::get('/report/{finishedOrder}', [ProductionOrderController::class, 'viewReport'])->name('production.report.view');

    // Generate report for Finished Order (after transferring to finished)
    Route::get('/finished-orders/{wo_number}/generate-report', [ProductionOrderController::class, 'generateReport'])->name('finishedOrders.generateReport');

    // **New** Route to view partial fulfillment report for a production order
    Route::get('/partial-report/{productionOrder}', [ProductionOrderController::class, 'viewPartialReport'])->name('production.report.partial_view');
    //** show finished detail order */
    Route::get('/finished/{wo_number}', [ProductionOrderController::class, 'showFinished'])->name('production.showfinished');

    //generate pcr for finished
    Route::get('/finished-order/report/{wo_number}', [ProductionOrderController::class, 'generateFinishedOrderReport'])->name('finishedOrder.generateReport');

    // Export Report to PDF for a specific finished order by WO number
    Route::get('/{wo_number}/export/pdf', [ProductionOrderController::class, 'exportReportToPDF2'])->name('production.export.pdf');

    //generate coc for finished
    Route::get('/report/{finishedOrder}', [ProductionOrderController::class, 'viewReport'])->name('production.report.view');

    
});

// API Routes for fetching data dynamically
Route::prefix('api')->group(function () {
    // Endpoint to get active operations
    Route::get('/active-operations', [ProductionOrderController::class, 'getActiveOperations'])->name('api.activeOperations');
});

// Admin Routes for Settings
Route::prefix('admin')->group(function () {
    // Route to view the settings form
    Route::get('/settings', [SettingsController::class, 'edit'])->name('admin.settings.edit');

    // Route to update the settings
    Route::put('/settings', [SettingsController::class, 'update'])->name('admin.settings.update');
});

    //listed operation routes
    Route::get('/listed-operations', [ProductionOrderController::class, 'getListedOperations'])->name('listed-operations');
    Route::post('/listed-operations', [ProductionOrderController::class, 'createNewOperation'])->name('new-operation');
    Route::put('/operations/{id}', [ProductionOrderController::class, 'updateOperation'])->name('update-operation');
