<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;


Route::get('/', function () {
    return view('dashboard');
});

Route::resource('products', ProductController::class);
Route::resource('categories', CategoryController::class);


Route::resource('transactions', TransactionController::class);
Route::get('transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');

Route::get('/laporan', [ReportController::class, 'index'])->name('reports.index');
Route::get('/laporan/export', [ReportController::class, 'exportExcel'])->name('reports.export');

Route::get('/inventory-logs', [\App\Http\Controllers\InventoryLogController::class, 'index'])->name('inventory-logs.index');
