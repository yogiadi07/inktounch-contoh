<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>InkTouch POS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite('resources/css/app.css')
    @livewireStyles
</head>
<body class="bg-black min-h-screen">
    <nav class="bg-white border-b-4 border-yellow-400 shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-black">⚡ InkTouch POS</h1>
                </div>
                
                <div class="flex items-center space-x-6">
                    <div class="hidden md:flex space-x-1">
                        <a href="{{ route('products.index') }}" class="text-black hover:bg-yellow-400 hover:text-black px-4 py-2 rounded-xl font-medium transition-all duration-200">📦 Products</a>
                        <a href="{{ route('categories.index') }}" class="text-black hover:bg-yellow-400 hover:text-black px-4 py-2 rounded-xl font-medium transition-all duration-200">🏷️ Categories</a>
                        <a href="{{ route('transactions.index') }}" class="text-black hover:bg-yellow-400 hover:text-black px-4 py-2 rounded-xl font-medium transition-all duration-200">💰 Transactions</a>
                        
                        @auth
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.reports') }}" class="text-black hover:bg-yellow-400 hover:text-black px-4 py-2 rounded-xl font-medium transition-all duration-200">📊 Reports</a>
                                <a href="{{ route('admin.inventory-logs') }}" class="text-black hover:bg-yellow-400 hover:text-black px-4 py-2 rounded-xl font-medium transition-all duration-200">📋 Inventory</a>
                            @endif
                        @endauth
                    </div>
                    
                    @auth
                        <div class="flex items-center space-x-4">
                            <div class="bg-yellow-50 border-2 border-yellow-400 rounded-xl px-4 py-2">
                                <span class="text-black font-bold text-sm">{{ auth()->user()->name }}</span>
                                <span class="ml-2 px-2 py-1 text-xs rounded-full font-bold {{ auth()->user()->isAdmin() ? 'bg-black text-yellow-400' : 'bg-yellow-400 text-black' }}">
                                    {{ auth()->user()->isAdmin() ? '👑 Admin' : '👤 Staff' }}
                                </span>
                            </div>
                            <livewire:auth.logout />
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="p-6">
        @yield('content')
        {{ $slot ?? '' }}
    </div>

    @livewireScripts
    @vite('resources/js/app.js')
</body>
</html>
