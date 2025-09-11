@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="bg-white overflow-hidden shadow-2xl rounded-2xl">
        <div class="p-8">
            <div class="mb-8 text-center">
                <h2 class="text-4xl font-bold text-black">👑 Dasbor Admin</h2>
                <div class="w-24 h-2 bg-yellow-400 mx-auto mt-3 mb-4 rounded-full"></div>
                <p class="text-lg text-gray-700 font-medium">Selamat datang, {{ auth()->user()->name }}! Anda memiliki akses penuh ke sistem.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                <div class="bg-yellow-50 border-2 border-yellow-400 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center">
                            <span class="text-black text-xl font-bold">📦</span>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-bold text-black">Total Produk</dt>
                            <dd class="text-2xl font-bold text-black">{{ \App\Models\Product::count() }}</dd>
                        </div>
                    </div>
                </div>

                <div class="bg-yellow-50 border-2 border-yellow-400 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center">
                            <span class="text-black text-xl font-bold">🏷️</span>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-bold text-black">Total Kategori</dt>
                            <dd class="text-2xl font-bold text-black">{{ \App\Models\Category::count() }}</dd>
                        </div>
                    </div>
                </div>

                <div class="bg-yellow-50 border-2 border-yellow-400 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center">
                            <span class="text-black text-xl font-bold">💰</span>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-bold text-black">Total Transaksi</dt>
                            <dd class="text-2xl font-bold text-black">0</dd>
                        </div>
                    </div>
                </div>

                <div class="bg-yellow-50 border-2 border-yellow-400 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-black rounded-full flex items-center justify-center">
                            <span class="text-yellow-400 text-xl font-bold">👑</span>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-bold text-black">Role</dt>
                            <dd class="text-2xl font-bold text-black">{{ ucfirst(auth()->user()->role) }}</dd>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <a href="{{ route('products.index') }}" class="block p-6 bg-yellow-50 border-2 border-yellow-400 rounded-xl hover:bg-yellow-100 hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center mr-4">
                            <span class="text-black text-xl font-bold">📦</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-black">Kelola Produk</h3>
                            <p class="text-sm text-gray-700">Add, edit, and manage products</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('categories.index') }}" class="block p-6 bg-yellow-50 border-2 border-yellow-400 rounded-xl hover:bg-yellow-100 hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center mr-4">
                            <span class="text-black text-xl font-bold">🏷️</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-black">Kelola Kategori</h3>
                            <p class="text-sm text-gray-700">Add, edit, and manage product categories</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('transactions.index') }}" class="block p-6 bg-yellow-50 border-2 border-yellow-400 rounded-xl hover:bg-yellow-100 hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center mr-4">
                            <span class="text-black text-xl font-bold">💰</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-black">Buat Transaksi</h3>
                            <p class="text-sm text-gray-700">Create new sales transaction</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.reports') }}" class="block p-6 bg-yellow-50 border-2 border-yellow-400 rounded-xl hover:bg-yellow-100 hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center mr-4">
                            <span class="text-black text-xl font-bold">📊</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-black">Reports</h3>
                            <p class="text-sm text-gray-700">View sales reports and analytics</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.inventory-logs') }}" class="block p-6 bg-yellow-50 border-2 border-yellow-400 rounded-xl hover:bg-yellow-100 hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center mr-4">
                            <span class="text-black text-xl font-bold">📋</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-black">Inventory Logs</h3>
                            <p class="text-sm text-gray-700">Monitor product stock changes</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
