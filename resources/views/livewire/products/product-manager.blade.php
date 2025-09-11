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

    <div class="bg-white overflow-hidden shadow-2xl rounded-2xl">
        <div class="p-8">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-4xl font-bold text-black">📦 Manajemen Produk</h2>
                </div>
                <button 
                    wire:click="openCreateForm" 
                    class="bg-yellow-400 hover:bg-yellow-500 text-black font-bold py-4 px-8 rounded-xl border-2 border-yellow-400 hover:border-yellow-500 transition-all duration-300 transform hover:scale-105 shadow-lg"
                >
                    ➕ Tambah Produk
                </button>
            </div>

            <!-- Product Form -->
            @if($showForm)
                <div class="mb-8 p-8 bg-yellow-50 border-2 border-yellow-400 rounded-2xl">
                    <h3 class="text-2xl font-bold text-black mb-6">
                        {{ $editingProduct ? '✏️ Edit Produk' : '➕ Tambah Produk Baru' }}
                    </h3>

                    <form wire:submit.prevent="save" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Product Image -->
                            <div class="md:col-span-1">
                                <label class="block text-sm font-bold text-black mb-2">🖼️ Gambar Produk</label>
                                <div class="border-2 border-gray-300 rounded-xl p-4 text-center">
                                    <img src="{{ asset('images/product-placeholder.svg') }}" alt="Gambar Produk" class="w-full h-32 object-contain mx-auto mb-2">
                                    <p class="text-sm text-gray-600">Gambar Placeholder</p>
                                </div>
                            </div>
                            
                            <div class="md:col-span-2 space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-bold text-black mb-2">📝 Nama Produk</label>
                                        <input wire:model="name" type="text" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium">
                                        @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-bold text-black mb-2">🏷️ Kategori</label>
                                        <select wire:model="category_id" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium">
                                            <option value="">Pilih Kategori</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('category_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    @if($editingProduct)
                                    <div>
                                        <label class="block text-sm font-bold text-black mb-2">➕ Tambah Stok</label>
                                        <input wire:model="added_stock" type="number" min="0" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium" placeholder="Jumlah stok yang ditambahkan">
                                        @error('added_stock') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    @endif

                                    <div>
                                        <label class="block text-sm font-bold text-black mb-2">💰 Harga Beli</label>
                                        <input wire:model="purchase_price" type="number" step="0.01" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium">
                                        @error('purchase_price') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-bold text-black mb-2">💵 Harga Jual</label>
                                        <input wire:model="selling_price" type="number" step="0.01" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium">
                                        @error('selling_price') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-bold text-black mb-2">📦 Stok</label>
                                        <input wire:model="stock" type="number" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium">
                                        @error('stock') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-bold text-black mb-2">📏 Satuan</label>
                                        <select wire:model="unit" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium">
                                            <option value="">Pilih Satuan</option>
                                            <option value="pcs">Pcs (Pieces)</option>
                                            <option value="kg">Kg (Kilogram)</option>
                                            <option value="gram">Gram</option>
                                            <option value="liter">Liter</option>
                                            <option value="ml">ML (Mililiter)</option>
                                            <option value="meter">Meter</option>
                                            <option value="cm">CM (Centimeter)</option>
                                            <option value="box">Box</option>
                                            <option value="pack">Pack</option>
                                            <option value="botol">Botol</option>
                                        </select>
                                        @error('unit') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-black mb-2">🖼️ Gambar Produk</label>
                            
                            <!-- Current Image Preview -->
                            @if($editingProduct && $editingProduct->image)
                                <div class="mb-3">
                                    <p class="text-sm text-gray-600 mb-2">Gambar saat ini:</p>
                                    <div class="w-24 h-24 rounded-lg overflow-hidden border-2 border-gray-200">
                                        @php
                                            $currentImg = null;
                                            if (\Illuminate\Support\Str::startsWith($editingProduct->image, 'products/')) {
                                                $currentImg = asset('storage/' . $editingProduct->image);
                                            } else {
                                                $currentImg = asset('uploads/products/' . $editingProduct->image);
                                            }
                                        @endphp
                                        @php $previewBuster = $editingProduct->updated_at ? '?v=' . strtotime($editingProduct->updated_at) : ''; @endphp
                                        <img src="{{ $currentImg . $previewBuster }}"
                                             alt="Current image"
                                             class="w-full h-full object-cover"
                                             onerror="this.onerror=null;this.src='{{ asset('images/product-placeholder.svg') }}';">
                                    </div>
                                    @if(config('app.debug'))
                                        <div class="text-xs text-gray-500 mt-1">
                                            Path: storage/{{ $editingProduct->image }}
                                        </div>
                                    @endif
                                </div>
                            @endif
                            
                            <input wire:model="image" type="file" accept="image/*" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium">
                            <div wire:loading wire:target="image" class="text-xs text-gray-500 mt-2">Mengunggah gambar...</div>
                            @error('image') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex justify-between items-center gap-6 pt-8">
                            <button type="button" wire:click="closeForm" class="px-6 py-3 border-2 border-gray-300 rounded-xl font-bold text-black hover:bg-gray-50 transition-all duration-200">
                                ❌ Batal
                            </button>
                            <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-black font-bold py-3 px-8 rounded-xl border-2 border-yellow-400 hover:border-yellow-500 transition-all duration-300 transform hover:scale-105 shadow-lg">
                                {{ $editingProduct ? '✏️ Perbarui Produk' : '💾 Simpan Produk' }}
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- Products Table -->
            <div class="overflow-x-auto bg-white rounded-2xl shadow-lg mt-8">
                <table class="min-w-full">
                    <thead class="bg-yellow-400">
                        <tr>
                            <th class="px-6 py-4 text-left font-bold text-black">🖼️ Gambar</th>
                            <th class="px-6 py-4 text-left font-bold text-black">📋 SKU</th>
                            <th class="px-6 py-4 text-left font-bold text-black">📝 Nama</th>
                            <th class="px-6 py-4 text-left font-bold text-black">🏷️ Kategori</th>
                            <th class="px-6 py-4 text-left font-bold text-black">💵 Harga Jual</th>
                            <th class="px-6 py-4 text-left font-bold text-black">📦 Stok</th>
                            <th class="px-6 py-4 text-left font-bold text-black">⚡ Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @forelse($products as $product)
                        <tr class="border-b border-gray-200 hover:bg-yellow-50 transition-colors duration-200" wire:key="product-row-{{ $product->id }}-{{ $product->image }}">
                            <td class="px-6 py-4">
                                <div class="w-16 h-16 rounded-lg overflow-hidden border-2 border-gray-200">
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
                                         onerror="this.onerror=null;this.src='{{ asset('images/product-placeholder.svg') }}';"
                                         wire:key="product-img-{{ $product->id }}-{{ $product->image }}">
                                    @if(config('app.debug'))
                                        <div class="text-[10px] text-gray-500 mt-1 truncate w-32">
                                            {{ $product->image ?? 'placeholder' }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $product->sku }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $product->name }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $product->category->name ?? '-' }}</td>
                            <td class="px-6 py-4 font-bold text-green-600">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-sm font-bold rounded-full {{ $product->stock > 10 ? 'bg-green-100 text-green-800' : ($product->stock > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <button 
                                        wire:click="openEditForm({{ $product->id }})" 
                                        class="bg-yellow-400 hover:bg-yellow-500 text-black text-sm font-bold py-2 px-4 rounded-lg border-2 border-yellow-400 hover:border-yellow-500 transition-all duration-200"
                                    >
                                        ✏️ Edit
                                    </button>
                                    <button 
                                        wire:click="delete({{ $product->id }})" 
                                        wire:confirm="Apakah Anda yakin ingin menghapus produk ini?"
                                        class="bg-red-500 hover:bg-red-600 text-white text-sm font-bold py-2 px-4 rounded-lg border-2 border-red-500 hover:border-red-600 transition-all duration-200"
                                    >
                                        🗑️ Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <div class="text-6xl mb-4">📦</div>
                                    <div class="text-xl font-bold mb-2">Tidak ada produk ditemukan</div>
                                    <div class="text-sm">Mulai dengan menambahkan produk pertama Anda menggunakan tombol di atas.</div>
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
