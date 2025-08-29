<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\Login;
use App\Livewire\Products\ProductManager;
use App\Livewire\Categories\CategoryManager;
use App\Livewire\Transactions\TransactionManager;
use App\Livewire\Reports\ReportManager;
use App\Livewire\InventoryLogs\InventoryLogManager;

Route::get('/', App\Livewire\Welcome::class)->name('welcome');

Route::get('/login', Login::class)->name('login')->middleware('guest');

Route::middleware(['auth'])->group(function () {
    // Admin Dashboard
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard')->middleware('role:admin');

    // Staff Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard')->middleware('role:staff,admin');

    // Admin only routes
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/reports', ReportManager::class)->name('admin.reports');
        Route::get('/admin/inventory-logs', InventoryLogManager::class)->name('admin.inventory-logs');
    });

    // Shared routes (admin and staff)
    Route::get('/products', ProductManager::class)->name('products.index')->middleware('role:admin,staff');
    Route::get('/categories', CategoryManager::class)->name('categories.index')->middleware('role:admin,staff');
    Route::get('/transactions', TransactionManager::class)->name('transactions.index')->middleware('role:admin,staff');
    
    // Logout route
    Route::post('/logout', function () {
        auth()->logout();
        return redirect()->route('welcome');
    })->name('logout');
});
