<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Models\Transaction;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Routes for CSV upload
    Route::get('/csv-upload', function () {
        return Inertia::render('CSVUpload/CSVUpload');
    })->name('csvupload');
    Route::post('/csv-upload', [App\Http\Controllers\UploadController::class, 'store']);

    // Transaction listing
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
