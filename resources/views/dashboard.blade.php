@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <div class="mb-6">
                <h2 class="text-2xl font-bold">Staff Dashboard</h2>
                <p class="mt-2">Welcome, {{ auth()->user()->name }}!</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="border border-gray-300 rounded p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded flex items-center justify-center">
                                <span class="text-white text-sm">📦</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-medium">Total Products</dt>
                            <dd class="text-lg font-bold">{{ \App\Models\Product::count() }}</dd>
                        </div>
                    </div>
                </div>

                <div class="border border-gray-300 rounded p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded flex items-center justify-center">
                                <span class="text-white text-sm">🏷️</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-medium">Total Categories</dt>
                            <dd class="text-lg font-bold">{{ \App\Models\Category::count() }}</dd>
                        </div>
                    </div>
                </div>

                <div class="border border-gray-300 rounded p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-500 rounded flex items-center justify-center">
                                <span class="text-white text-sm">💰</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-medium">Today's Transactions</dt>
                            <dd class="text-lg font-bold">0</dd>
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
                <a href="{{ route('products.index') }}" class="block p-4 border border-gray-300 rounded hover:bg-gray-50">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-500 rounded flex items-center justify-center mr-3">
                            <span class="text-white text-sm">📦</span>
                        </div>
                        <div>
                            <h3 class="font-medium">Manage Products</h3>
                            <p class="text-sm text-gray-600">Add, edit, and manage products</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('categories.index') }}" class="block p-4 border border-gray-300 rounded hover:bg-gray-50">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-500 rounded flex items-center justify-center mr-3">
                            <span class="text-white text-sm">🏷️</span>
                        </div>
                        <div>
                            <h3 class="font-medium">Manage Categories</h3>
                            <p class="text-sm text-gray-600">Add, edit, and manage product categories</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('transactions.index') }}" class="block p-4 border border-gray-300 rounded hover:bg-gray-50">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-yellow-500 rounded flex items-center justify-center mr-3">
                            <span class="text-white text-sm">💰</span>
                        </div>
                        <div>
                            <h3 class="font-medium">Create Transaction</h3>
                            <p class="text-sm text-gray-600">Create new sales transaction</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('transactions.index') }}" class="block p-4 border border-gray-300 rounded hover:bg-gray-50">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-purple-500 rounded flex items-center justify-center mr-3">
                            <span class="text-white text-sm">📋</span>
                        </div>
                        <div>
                            <h3 class="font-medium">Transaction History</h3>
                            <p class="text-sm text-gray-600">View transaction history</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
