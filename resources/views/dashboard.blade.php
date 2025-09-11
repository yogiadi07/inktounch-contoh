@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="bg-white overflow-hidden shadow-2xl rounded-2xl">
        <div class="p-6">
            <div class="mb-6">
                <h2 class="text-2xl font-bold">Dasbor Staff</h2>
                <p class="mt-2">Selamat datang, {{ auth()->user()->name }}!</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <div class="border border-gray-300 rounded p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-500 rounded flex items-center justify-center">
                                <span class="text-white text-sm">💰</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-medium">Transaksi Hari Ini</dt>
                            <dd class="text-lg font-bold">{{ \App\Models\Transaction::whereDate('transaction_date', today())->count() }}</dd>
                        </div>
                    </div>
                </div>

                <div class="border border-gray-300 rounded p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded flex items-center justify-center">
                                <span class="text-white text-sm">💵</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-medium">Pendapatan Hari Ini</dt>
                            <dd class="text-lg font-bold">Rp {{ number_format(\App\Models\Transaction::whereDate('transaction_date', today())->sum('total_price'), 0, ',', '.') }}</dd>
                        </div>
                    </div>
                </div>

                <div class="border border-gray-300 rounded p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-500 rounded flex items-center justify-center">
                                <span class="text-white text-sm">👤</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-medium">Role</dt>
                            <dd class="text-lg font-bold">{{ ucfirst(auth()->user()->role) }}</dd>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <a href="{{ route('transactions.index') }}" class="block p-6 bg-yellow-50 border-2 border-yellow-400 rounded-xl hover:bg-yellow-100 hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center mr-4">
                            <span class="text-black text-xl font-bold">💰</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-black">Buat Transaksi</h3>
                            <p class="text-sm text-gray-700">Buat transaksi penjualan baru</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('transactions.index') }}" class="block p-6 bg-yellow-50 border-2 border-yellow-400 rounded-xl hover:bg-yellow-100 hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center mr-4">
                            <span class="text-black text-xl font-bold">📋</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-black">Riwayat Transaksi</h3>
                            <p class="text-sm text-gray-700">Lihat riwayat transaksi penjualan</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
