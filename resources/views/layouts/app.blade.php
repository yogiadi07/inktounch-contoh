<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>InkTouch POS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite('resources/css/app.css')
    @livewireStyles
</head>
<body class="bg-white min-h-screen">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-20">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/InkTouch.png') }}" alt="InkTouch Logo" class="h-16 w-auto" onerror="this.style.display='none'">
                </div>
                
                <div class="flex items-center space-x-6">
                    <div class="hidden md:flex space-x-1">
                        @auth
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="text-black hover:bg-yellow-400 hover:text-black px-4 py-2 rounded-xl font-medium transition-all duration-200">🏠 Dasbor</a>
                                <a href="{{ route('products.index') }}" class="text-black hover:bg-yellow-400 hover:text-black px-4 py-2 rounded-xl font-medium transition-all duration-200">📦 Produk</a>
                                <a href="{{ route('categories.index') }}" class="text-black hover:bg-yellow-400 hover:text-black px-4 py-2 rounded-xl font-medium transition-all duration-200">🏷️ Kategori</a>
                                <a href="{{ route('transactions.index') }}" class="text-black hover:bg-yellow-400 hover:text-black px-4 py-2 rounded-xl font-medium transition-all duration-200">💰 Transaksi</a>
                                <a href="{{ route('admin.reports') }}" class="text-black hover:bg-yellow-400 hover:text-black px-4 py-2 rounded-xl font-medium transition-all duration-200">📊 Laporan</a>
                                <a href="{{ route('admin.inventory-logs') }}" class="text-black hover:bg-yellow-400 hover:text-black px-4 py-2 rounded-xl font-medium transition-all duration-200">📋 Inventori</a>
                            @else
                                <a href="{{ route('dashboard') }}" class="text-black hover:bg-yellow-400 hover:text-black px-4 py-2 rounded-xl font-medium transition-all duration-200">🏠 Dasbor</a>
                                <a href="{{ route('transactions.index') }}" class="text-black hover:bg-yellow-400 hover:text-black px-4 py-2 rounded-xl font-medium transition-all duration-200">💰 Transaksi</a>
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
        <div class="mb-4">
            <button type="button" id="smartBackBtn" onclick="smartBack()" class="inline-flex items-center gap-2 bg-white border-2 border-gray-300 hover:border-yellow-400 hover:bg-yellow-50 text-black font-bold px-4 py-2 rounded-xl transition-all duration-200" style="display: none;">
                <span>⬅️</span>
                <span>Kembali</span>
            </button>
        </div>
        <script>
            function smartBack() {
                if (window.history.length > 1 && document.referrer && !document.referrer.includes('/login') && !document.referrer.includes('/register') && !document.referrer.includes('/forgot-password')) {
                    window.history.back();
                } else {
                    // Fallback to appropriate dashboard based on user role
                    @auth
                        @if(auth()->user()->isAdmin())
                            window.location.href = '{{ route("admin.dashboard") }}';
                        @else
                            window.location.href = '{{ route("dashboard") }}';
                        @endif
                    @endauth
                }
            }
            
            // Show back button only if we have valid navigation history
            document.addEventListener('DOMContentLoaded', function() {
                const currentPath = window.location.pathname;
                const isDashboard = currentPath.includes('/dashboard');
                const hasValidReferrer = document.referrer && 
                    !document.referrer.includes('/login') && 
                    !document.referrer.includes('/register') && 
                    !document.referrer.includes('/forgot-password') &&
                    !document.referrer.includes(window.location.origin + '/');
                
                if (!isDashboard && (window.history.length > 1 || hasValidReferrer)) {
                    document.getElementById('smartBackBtn').style.display = 'inline-flex';
                }
            });
        </script>
        @yield('content')
        {{ $slot ?? '' }}
    </div>

    @livewireScripts
    @vite('resources/js/app.js')
</body>
</html>
