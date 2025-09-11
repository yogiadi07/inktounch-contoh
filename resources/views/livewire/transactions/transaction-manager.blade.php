<div>
    @if (session()->has('success'))
        <div class="mb-6 bg-yellow-50 border-2 border-yellow-400 text-black px-6 py-4 rounded-xl font-medium">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 bg-red-50 border-2 border-red-400 text-red-800 px-6 py-4 rounded-xl font-medium">
            ❌ {{ session('error') }}
        </div>
    @endif

    <div class="bg-white overflow-hidden shadow-2xl rounded-2xl ">
        <div class="p-8">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-4xl font-bold text-black">💳 Manajemen Transaksi</h2>
                </div>
                <button 
                    wire:click="openCreateForm" 
                    class="bg-yellow-400 hover:bg-yellow-500 text-black font-bold py-4 px-8 rounded-xl border-2 border-yellow-400 hover:border-yellow-500 transition-all duration-300 transform hover:scale-105 shadow-lg"
                >
                    ➕ Buat Transaksi
                </button>
            </div>

            <!-- Bagian Filter -->
            <div class="mb-8 p-6 bg-yellow-50 border-2 border-yellow-400 rounded-2xl">
                <div class="flex flex-wrap gap-6 items-end">
                    <div>
                        <label class="block text-sm font-bold text-black mb-2">📅 Tanggal Mulai</label>
                        <input wire:model="start_date" type="date" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-black mb-2">📅 Tanggal Akhir</label>
                        <input wire:model="end_date" type="date" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium">
                    </div>
                    <div class="flex gap-3">
                        <button wire:click="applyFilter" class="bg-yellow-400 hover:bg-yellow-500 text-black font-bold py-3 px-6 rounded-xl border-2 border-yellow-400 hover:border-yellow-500 transition-all duration-200">🔍 Saring</button>
                        <button wire:click="clearFilter" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-xl border-2 border-gray-500 hover:border-gray-600 transition-all duration-200">🔄 Atur Ulang</button>
                    </div>
                </div>
            </div>

            <!-- Form Buat Transaksi -->
            @if($showCreateForm)
                <div class="mb-8 p-8 bg-yellow-50 border-2 border-yellow-400 rounded-2xl">
                    <h3 class="text-2xl font-bold text-black mb-6">➕ Buat Transaksi Baru</h3>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Pilih Produk -->
                        <div>
                            <h4 class="text-xl font-bold text-black mb-4">📦 Pilih Produk</h4>
                            
                            <!-- Pencarian & Filter Produk -->
                            <div class="mb-4 space-y-3">
                                <div class="flex gap-3">
                                    <input 
                                        wire:model.live="search" 
                                        type="text" 
                                        placeholder="🔍 Cari produk berdasarkan nama atau SKU..."
                                        class="flex-1 px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium"
                                    >
                                    <select 
                                        wire:model.live="selectedCategory" 
                                        class="px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium"
                                    >
                                        <option value="">Semua Kategori</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button 
                                    wire:click="clearSearch" 
                                    class="text-sm bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition-all duration-200"
                                >
                                    🔄 Hapus Filter
                                </button>
                            </div>

                            <!-- Daftar Produk -->
                            <div class="max-h-96 overflow-y-auto bg-white border-2 border-gray-300 rounded-xl">
                                @forelse($products as $product)
                                    <div class="p-4 border-b border-gray-200 hover:bg-yellow-50 transition-colors duration-200">
                                        <div class="flex justify-between items-start mb-3">
                                            <div class="flex items-start gap-3 flex-1">
                                                <div class="w-12 h-12 rounded-lg overflow-hidden border-2 border-gray-200 flex-shrink-0">
                                                    @php
                                                        $imgUrl = $product->image ? (\Illuminate\Support\Str::startsWith($product->image, 'products/')
                                                            ? asset('storage/' . $product->image)
                                                            : asset('uploads/products/' . $product->image))
                                                            : asset('images/product-placeholder.svg');
                                                        $buster = $product->updated_at ? '?v=' . strtotime($product->updated_at) : '';
                                                    @endphp
                                                    <img src="{{ $imgUrl . $buster }}" 
                                                         alt="Gambar {{ $product->name }}" 
                                                         class="w-full h-full object-cover"
                                                         onerror="this.onerror=null;this.src='{{ asset('images/product-placeholder.svg') }}';">
                                                </div>
                                                <div class="flex-1">
                                                    <p class="font-bold text-black">{{ $product->name }}</p>
                                                    <p class="text-sm text-gray-600 font-medium">SKU: {{ $product->sku }}</p>
                                                    <p class="text-sm text-gray-600 font-medium">💰 Rp {{ number_format($product->selling_price, 0, ',', '.') }}</p>
                                                    <p class="text-sm {{ $product->stock > 10 ? 'text-green-600' : ($product->stock > 0 ? 'text-yellow-600' : 'text-red-600') }} font-bold">
                                                        📦 Stok: {{ $product->stock }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center gap-3">
                                            <input 
                                                type="number" 
                                                min="1" 
                                                max="{{ $product->stock }}" 
                                                value="1"
                                                wire:model="quantities.{{ $product->id }}"
                                                class="w-20 px-3 py-2 border-2 border-gray-300 rounded-lg text-center font-bold focus:border-yellow-400 focus:ring-2 focus:ring-yellow-200"
                                            >
                                            <button 
                                                wire:click="addToCart({{ $product->id }})"
                                                class="bg-yellow-400 hover:bg-yellow-500 text-black font-bold py-2 px-4 rounded-lg border-2 border-yellow-400 hover:border-yellow-500 transition-all duration-200"
                                                {{ $product->stock <= 0 ? 'disabled' : '' }}
                                            >
                                                ➕ Tambah ke Keranjang
                                            </button>
                                        </div>
                                    </div>
                                @empty
                                    <p class="p-6 text-gray-500 text-center">
                                        @if($search || $selectedCategory)
                                            🔍 Tidak ada produk yang sesuai dengan kriteria pencarian
                                        @else
                                            📦 Tidak ada produk tersedia
                                        @endif
                                    </p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Keranjang & Pembayaran -->
                        <div>
                            <h4 class="text-xl font-bold text-black mb-4">🛒 Keranjang Belanja</h4>
                            
                            <!-- Ringkasan Keranjang -->
                            @if(count($cart) > 0)
                                <div class="mb-4 p-4 bg-yellow-50 border-2 border-yellow-400 rounded-xl">
                                    <div class="flex justify-between items-center">
                                        <span class="font-bold text-black">📦 Item di Keranjang: {{ count($cart) }}</span>
                                        <button 
                                            wire:click="$set('cart', [])" 
                                            class="text-sm bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded-lg transition-all duration-200"
                                        >
                                            🗑️ Hapus Semua
                                        </button>
                                    </div>
                                </div>
                            @endif

                            <!-- Cart Items -->
                            <div class="max-h-64 overflow-y-auto bg-white border-2 border-gray-300 rounded-xl mb-6">
                                @forelse($cart as $index => $item)
                                    <div class="p-4 border-b border-gray-200 hover:bg-yellow-50 transition-colors duration-200">
                                        <div class="flex justify-between items-start mb-3">
                                            <div class="flex items-start gap-3 flex-1">
                                                <div class="w-12 h-12 rounded-lg overflow-hidden border-2 border-gray-200 flex-shrink-0">
                                                    @php
                                                        $cartImgUrl = isset($item['image']) && $item['image']
                                                            ? (\Illuminate\Support\Str::startsWith($item['image'], 'products/')
                                                                ? asset('storage/' . $item['image'])
                                                                : asset('uploads/products/' . $item['image']))
                                                            : asset('images/product-placeholder.svg');
                                                    @endphp
                                                    <img src="{{ $cartImgUrl }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover" onerror="this.onerror=null;this.src='{{ asset('images/product-placeholder.svg') }}';">
                                                </div>
                                                <div class="flex-1">
                                                    <p class="font-bold text-black">{{ $item['name'] }}</p>
                                                    <p class="text-sm text-gray-600 font-medium">💰 Rp {{ number_format($item['price'], 0, ',', '.') }} per item</p>
                                                    <p class="text-sm text-green-600 font-bold">
                                                        Subtotal: Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <button 
                                                    wire:click="updateQuantity({{ $index }}, {{ $item['quantity'] - 1 }})"
                                                    class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-1 px-2 rounded transition-all duration-200"
                                                    {{ $item['quantity'] <= 1 ? 'disabled' : '' }}
                                                >
                                                    -
                                                </button>
                                                <span class="px-3 py-1 bg-yellow-100 border-2 border-yellow-400 rounded font-bold text-black min-w-[3rem] text-center">
                                                    {{ $item['quantity'] }}
                                                </span>
                                                <button 
                                                    wire:click="updateQuantity({{ $index }}, {{ $item['quantity'] + 1 }})"
                                                    class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-1 px-2 rounded transition-all duration-200"
                                                    {{ $item['quantity'] >= $item['stock'] ? 'disabled' : '' }}
                                                >
                                                    +
                                                </button>
                                                <span class="text-xs text-gray-500 ml-2">Maks: {{ $item['stock'] }}</span>
                                            </div>
                                            
                                            <button 
                                                wire:click="removeFromCart({{ $index }})"
                                                class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-3 rounded-lg border-2 border-red-500 hover:border-red-600 transition-all duration-200"
                                            >
                                                🗑️ Hapus
                                            </button>
                                        </div>
                                    </div>
                                @empty
                                    <p class="p-6 text-gray-500 text-center">🛒 Keranjang kosong</p>
                                @endforelse
                            </div>

                            @if(count($cart) > 0)
                                <!-- Bagian Pembayaran -->
                                <div class="bg-white border-2 border-yellow-400 rounded-xl p-6">
                                    <h5 class="text-lg font-bold text-black mb-4">💳 Pembayaran</h5>
                                    
                                    <div class="space-y-4">
                                        <div class="flex justify-between items-center p-3 bg-yellow-50 rounded-lg">
                                            <span class="font-bold text-black">Total:</span>
                                            <span class="font-bold text-xl text-green-600">Rp {{ number_format($this->getCartTotal(), 0, ',', '.') }}</span>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-bold text-black mb-2">💳 Metode Pembayaran</label>
                                            <select wire:model="payment_method" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium">
                                                <option value="cash">💵 Tunai</option>
                                                <option value="card">💳 Kartu</option>
                                                <option value="transfer">🏦 Transfer</option>
                                            </select>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-bold text-black mb-2">💰 Jumlah Pembayaran</label>
                                            <input 
                                                wire:model.live="paid_amount" 
                                                type="number" 
                                                step="1"
                                                min="{{ $this->getCartTotal() }}"
                                                class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium"
                                                placeholder="Minimum: Rp {{ number_format($this->getCartTotal(), 0, ',', '.') }}"
                                            >
                                            @if($this->getCartTotal() > 0)
                                                <button 
                                                    wire:click="$set('paid_amount', {{ $this->getCartTotal() }})"
                                                    class="mt-2 bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg text-sm"
                                                >
                                                    💰 Set Jumlah Pas (Rp {{ number_format($this->getCartTotal(), 0, ',', '.') }})
                                                </button>
                                            @endif
                                        </div>
                                        
                                        @if($paid_amount && $paid_amount >= $this->getCartTotal())
                                            <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg border-2 border-green-200">
                                                <span class="font-bold text-green-800">💸 Kembalian:</span>
                                                <span class="font-bold text-xl text-green-600">Rp {{ number_format($paid_amount - $this->getCartTotal(), 0, ',', '.') }}</span>
                                            </div>
                                        @endif
                                        
                                        @if(!$paid_amount || $paid_amount < $this->getCartTotal())
                                            <div class="mt-2 p-3 bg-red-50 border-2 border-red-200 rounded-lg">
                                                <p class="text-red-700 font-bold text-sm">
                                                    ⚠️ Masukkan jumlah pembayaran (minimum: Rp {{ number_format($this->getCartTotal(), 0, ',', '.') }})
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="flex gap-4 mt-6">
                                        <button 
                                            wire:click="closeCreateForm" 
                                            class="flex-1 px-6 py-3 border-2 border-gray-300 rounded-xl font-bold text-black hover:bg-gray-50 transition-all duration-200"
                                        >
                                            ❌ Batal
                                        </button>
                                        <button 
                                            wire:click="save" 
                                            class="flex-1 bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-xl border-2 border-green-500 hover:border-green-600 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                            {{ (!$paid_amount || $paid_amount < $this->getCartTotal() || count($cart) == 0) ? 'disabled' : '' }}
                                        >
                                            ✅ Proses Transaksi
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <button wire:click="closeCreateForm" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-xl border-2 border-gray-500 hover:border-gray-600 transition-all duration-200">
                            ❌ Tutup
                        </button>
                    </div>
                </div>
            @endif

            <!-- Detail Transaksi -->
            @if($showDetailModal && $selectedTransaction)
                <div class="mb-8 p-8 bg-yellow-50 border-2 border-yellow-400 rounded-2xl">
                    <h3 class="text-2xl font-bold text-black mb-6">📊 Detail Transaksi</h3>

                    <div class="space-y-6">
                        <div class="grid grid-cols-2 gap-6 text-sm">
                            <div class="p-3 bg-white rounded-lg border border-gray-200"><strong class="text-black">📝 No. Invoice:</strong> {{ $selectedTransaction->no_invoice }}</div>
                            <div class="p-3 bg-white rounded-lg border border-gray-200"><strong class="text-black">👤 Kasir:</strong> {{ $selectedTransaction->cashier_name }}</div>
                            <div class="p-3 bg-white rounded-lg border border-gray-200"><strong class="text-black">📅 Tanggal:</strong> {{ $selectedTransaction->transaction_date ? $selectedTransaction->transaction_date->format('d/m/Y H:i') : 'N/A' }}</div>
                            <div class="p-3 bg-white rounded-lg border border-gray-200"><strong class="text-black">💳 Pembayaran:</strong> {{ ucfirst($selectedTransaction->payment_method) }}</div>
                        </div>

                        <div class="bg-white rounded-xl p-6 border-2 border-gray-200">
                            <h4 class="font-bold text-black mb-4">📦 Detail Produk:</h4>
                            <div class="space-y-3">
                                @foreach($selectedTransaction->details as $detail)
                                    <div class="flex justify-between items-center p-3 bg-yellow-50 rounded-lg">
                                        <span class="font-medium text-black">{{ $detail->product->name }} ({{ $detail->quantity }}x)</span>
                                        <span class="font-bold text-green-600">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="bg-white rounded-xl p-6 border-2 border-green-200">
                            <div class="space-y-3">
                                <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                                    <span class="font-bold text-black">💰 Total:</span>
                                    <span class="font-bold text-xl text-green-600">Rp {{ number_format($selectedTransaction->total_price, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                                    <span class="font-bold text-black">💵 Dibayar:</span>
                                    <span class="font-bold text-blue-600">Rp {{ number_format($selectedTransaction->paid_amount, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center p-3 bg-yellow-50 rounded-lg">
                                    <span class="font-bold text-black">💸 Kembalian:</span>
                                    <span class="font-bold text-yellow-600">Rp {{ number_format($selectedTransaction->change_amount, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <button wire:click="closeDetailModal" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-xl border-2 border-gray-500 hover:border-gray-600 transition-all duration-200">
                            ❌ Tutup
                        </button>
                    </div>
                </div>
            @endif

            <!-- Tabel Transaksi -->
            <div class="overflow-x-auto bg-white rounded-2xl border-2 border-yellow-400 shadow-lg">
                <table class="min-w-full">
                    <thead class="bg-yellow-400">
                        <tr>
                            <th class="px-6 py-4 text-left font-bold text-black">📝 No. Invoice</th>
                            <th class="px-6 py-4 text-left font-bold text-black">👤 Kasir</th>
                            <th class="px-6 py-4 text-left font-bold text-black">📅 Tanggal</th>
                            <th class="px-6 py-4 text-left font-bold text-black">💰 Total</th>
                            <th class="px-6 py-4 text-left font-bold text-black">💳 Pembayaran</th>
                            <th class="px-6 py-4 text-left font-bold text-black">⚡ Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @forelse($transactions as $transaction)
                        <tr class="border-b border-gray-200 hover:bg-yellow-50 transition-colors duration-200">
                            <td class="px-6 py-4 font-bold text-black">{{ $transaction->no_invoice }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $transaction->cashier_name }}</td>
                            <td class="px-6 py-4 text-gray-700 font-medium">{{ $transaction->transaction_date ? $transaction->transaction_date->format('d/m/Y H:i') : 'N/A' }}</td>
                            <td class="px-6 py-4 font-bold text-green-600">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-sm font-bold rounded-full bg-green-100 text-green-800">
                                    {{ ucfirst($transaction->payment_method) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <button 
                                    wire:click="showDetail({{ $transaction->id }})" 
                                    class="bg-yellow-400 hover:bg-yellow-500 text-black text-sm font-bold py-2 px-4 rounded-lg border-2 border-yellow-400 hover:border-yellow-500 transition-all duration-200"
                                >
                                    📊 Detail
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <div class="text-6xl mb-4">💳</div>
                                    <div class="text-xl font-bold mb-2">Tidak ada transaksi</div>
                                    <div class="text-sm">Mulai dengan membuat transaksi pertama menggunakan tombol di atas.</div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
