<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\OperatorController;

Route::get('/', function () {
    return redirect()->route('kiosk.index');
});

// Kiosk & Display Routes (Public facing)
Route::get('/display', [App\Http\Controllers\DisplayController::class, 'index'])->name('display.index');

Route::prefix('kiosk')->name('kiosk.')->group(function () {
    Route::get('/', [App\Http\Controllers\KioskController::class, 'index'])->name('index');
    Route::post('/take-ticket', [App\Http\Controllers\KioskController::class, 'takeTicket'])->name('take-ticket');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Shared Dashboard (can redirect based on role)
    Route::get('/dashboard', function () {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('operator.index');
    })->name('dashboard');

    // Admin Routes
    Route::middleware(['role:admin,superadmin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('service-types', \App\Http\Controllers\Admin\ServiceTypeController::class);
        Route::get('service-types/check-code', [\App\Http\Controllers\Admin\ServiceTypeController::class, 'checkCode'])->name('service-types.check-code');
        Route::resource('counters', \App\Http\Controllers\Admin\CounterController::class);
        Route::get('counters/check-number', [\App\Http\Controllers\Admin\CounterController::class, 'checkNumber'])->name('counters.check-number');
        Route::get('users/check-email', [\App\Http\Controllers\Admin\UserController::class, 'checkEmail'])->name('users.check-email');
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);

        Route::get('general-settings', [\App\Http\Controllers\Admin\GeneralSettingController::class, 'index'])->name('general-settings.index');
        Route::post('general-settings/update', [\App\Http\Controllers\Admin\GeneralSettingController::class, 'update'])->name('general-settings.update');

        Route::get('display-settings', [\App\Http\Controllers\Admin\DisplaySettingController::class, 'index'])->name('display-settings.index');
        Route::post('display-settings/update', [\App\Http\Controllers\Admin\DisplaySettingController::class, 'updateSettings'])->name('display-settings.update');
        Route::post('display-settings/media', [\App\Http\Controllers\Admin\DisplaySettingController::class, 'storeMedia'])->name('display-settings.store-media');
        Route::delete('display-settings/media/{media}', [\App\Http\Controllers\Admin\DisplaySettingController::class, 'destroyMedia'])->name('display-settings.destroy-media');

        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');
    });

    // Operator Routes
    Route::middleware(['role:operator'])->prefix('operator')->name('operator.')->group(function () {
        Route::get('/', [OperatorController::class, 'index'])->name('index');
        Route::post('/call-next', [OperatorController::class, 'callNext'])->name('call-next');
        Route::post('/recall/{queue}', [OperatorController::class, 'recall'])->name('recall');
        Route::post('/complete/{queue}', [OperatorController::class, 'complete'])->name('complete');
        Route::post('/skip/{queue}', [OperatorController::class, 'skip'])->name('skip');
        Route::post('/transfer/{queue}', [OperatorController::class, 'transfer'])->name('transfer');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
