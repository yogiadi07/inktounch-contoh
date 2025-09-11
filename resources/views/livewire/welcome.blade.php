<div>
    <div class="min-h-screen bg-white flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-4xl">
            <div class="bg-white py-12 px-8 shadow-2xl rounded-2xl">
                <div class="text-center">
                    <div class="flex items-center justify-center gap-4 mb-4">
                        <img src="{{ asset('images/InkTouch.png') }}" alt="InkTouch Logo" class="h-24 w-auto" onerror="this.style.display='none'">
                    </div>
                    <p class="text-xl text-gray-700 mb-12 font-medium">Professional Point of Sale System</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                        <div class="p-6 bg-gray-50 rounded-xl border border-gray-200 hover:border-gray-300 transition-all duration-300 hover:shadow-lg">
                            <div class="w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-black text-xl font-bold">📦</span>
                            </div>
                            <h3 class="font-bold text-black text-lg mb-3">Manajemen Produk</h3>
                            <p class="text-sm text-gray-700">Kelola produk dengan mudah, termasuk upload gambar dan tracking stok real-time.</p>
                        </div>
                        
                        <div class="p-6 bg-gray-50 rounded-xl border border-gray-200 hover:border-gray-300 transition-all duration-300 hover:shadow-lg">
                            <div class="w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-black text-xl font-bold">💰</span>
                            </div>
                            <h3 class="font-bold text-black text-lg mb-3">Sistem POS Pintar</h3>
                            <p class="text-sm text-gray-700">Proses transaksi cepat dengan keranjang belanja dan multiple payment methods.</p>
                        </div>
                        
                        <div class="p-6 bg-gray-50 rounded-xl border border-gray-200 hover:border-gray-300 transition-all duration-300 hover:shadow-lg">
                            <div class="w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-black text-xl font-bold">📊</span>
                            </div>
                            <h3 class="font-bold text-black text-lg mb-3">Analitik Penjualan</h3>
                            <p class="text-sm text-gray-700">Dashboard analitik lengkap dengan export Excel dan insights bisnis mendalam.</p>
                        </div>
                        
                        <div class="p-6 bg-gray-50 rounded-xl border border-gray-200 hover:border-gray-300 transition-all duration-300 hover:shadow-lg">
                            <div class="w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-black text-xl font-bold">📋</span>
                            </div>
                            <h3 class="font-bold text-black text-lg mb-3">Kontrol Inventori</h3>
                            <p class="text-sm text-gray-700">Tracking stok masuk-keluar dan log inventori lengkap dengan notifikasi otomatis.</p>
                        </div>
                        
                        <div class="p-6 bg-gray-50 rounded-xl border border-gray-200 hover:border-gray-300 transition-all duration-300 hover:shadow-lg">
                            <div class="w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-black text-xl font-bold">👥</span>
                            </div>
                            <h3 class="font-bold text-black text-lg mb-3">Akses Multi-Pengguna</h3>
                            <p class="text-sm text-gray-700">Role-based access control dengan level Admin dan Staff untuk keamanan optimal.</p>
                        </div>
                        
                        <div class="p-6 bg-gray-50 rounded-xl border border-gray-200 hover:border-gray-300 transition-all duration-300 hover:shadow-lg">
                            <div class="w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-black text-xl font-bold">⚡</span>
                            </div>
                            <h3 class="font-bold text-black text-lg mb-3">Update Real-time</h3>
                            <p class="text-sm text-gray-700">Interface responsif dengan LiveWire, update data real-time tanpa reload halaman.</p>
                        </div>
                    </div>

                    
                    
                    @guest
                        <div class="flex items-center justify-center">
                            <a href="{{ route('login') }}" class="inline-flex items-center justify-center py-4 px-12 rounded-2xl text-xl font-bold text-black bg-yellow-400 hover:bg-yellow-500 focus:outline-none focus:ring-4 focus:ring-yellow-300 transition-all duration-300 transform hover:scale-105 shadow-lg">
                                🚀 Login
                            </a>
                        </div>
                    @else
                        <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center py-4 px-12 rounded-2xl text-xl font-bold text-black bg-yellow-400 hover:bg-yellow-500 focus:outline-none focus:ring-4 focus:ring-yellow-300 transition-all duration-300 transform hover:scale-105 shadow-lg">
                            📦 Masuk ke Produk
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </div>
</div>
