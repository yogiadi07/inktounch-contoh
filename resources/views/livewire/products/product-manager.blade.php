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

    <div class="bg-white overflow-hidden shadow-2xl rounded-2xl border-4 border-yellow-400">
        <div class="p-8">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-4xl font-bold text-black">📦 Product Management</h2>
                    <div class="w-24 h-2 bg-yellow-400 mt-2 rounded-full"></div>
                </div>
                <button 
                    wire:click="openCreateForm" 
                    class="bg-yellow-400 hover:bg-yellow-500 text-black font-bold py-4 px-8 rounded-xl border-2 border-yellow-400 hover:border-yellow-500 transition-all duration-300 transform hover:scale-105 shadow-lg"
                >
                    ➕ Add Product
                </button>
            </div>

            <!-- Product Form -->
            @if($showForm)
                <div class="mb-8 p-8 bg-yellow-50 border-2 border-yellow-400 rounded-2xl">
                    <h3 class="text-2xl font-bold text-black mb-6">
                        {{ $editingProduct ? '✏️ Edit Product' : '➕ Add New Product' }}
                    </h3>

                    <form wire:submit="save" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-black mb-2">📝 Product Name</label>
                                <input wire:model="name" type="text" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium">
                                @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-black mb-2">🏷️ Category</label>
                                <select wire:model="category_id" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-black mb-2">💰 Purchase Price</label>
                                <input wire:model="purchase_price" type="number" step="0.01" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium">
                                @error('purchase_price') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-black mb-2">💵 Selling Price</label>
                                <input wire:model="selling_price" type="number" step="0.01" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium">
                                @error('selling_price') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            @if(!$editingProduct)
                                <div>
                                    <label class="block text-sm font-bold text-black mb-2">📦 Initial Stock</label>
                                    <input wire:model="stock" type="number" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium">
                                    @error('stock') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            @else
                                <div>
                                    <label class="block text-sm font-bold text-black mb-2">➕ Add Stock</label>
                                    <input wire:model="added_stock" type="number" placeholder="Leave empty if not adding stock" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium">
                                    @error('added_stock') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    <p class="text-xs text-gray-600 mt-1 font-medium">📊 Current stock: {{ $editingProduct->stock ?? 0 }}</p>
                                </div>
                            @endif

                            <div>
                                <label class="block text-sm font-bold text-black mb-2">📏 Unit</label>
                                <input wire:model="unit" type="text" placeholder="pcs, kg, liter, etc" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium">
                                @error('unit') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-black mb-2">🖼️ Product Image</label>
                            <input wire:model="image" type="file" accept="image/*" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium">
                            @error('image') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex gap-4 pt-4">
                            <button type="button" wire:click="closeForm" class="px-6 py-3 border-2 border-gray-300 rounded-xl font-bold text-black hover:bg-gray-50 transition-all duration-200">
                                ❌ Cancel
                            </button>
                            <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-black font-bold py-3 px-8 rounded-xl border-2 border-yellow-400 hover:border-yellow-500 transition-all duration-300 transform hover:scale-105 shadow-lg">
                                {{ $editingProduct ? '✏️ Update' : '💾 Save' }}
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- Products Table -->
            <div class="overflow-x-auto bg-white rounded-2xl border-2 border-yellow-400 shadow-lg">
                <table class="min-w-full">
                    <thead class="bg-yellow-400">
                        <tr>
                            <th class="px-6 py-4 text-left font-bold text-black">📋 SKU</th>
                            <th class="px-6 py-4 text-left font-bold text-black">📝 Name</th>
                            <th class="px-6 py-4 text-left font-bold text-black">🏷️ Category</th>
                            <th class="px-6 py-4 text-left font-bold text-black">💵 Selling Price</th>
                            <th class="px-6 py-4 text-left font-bold text-black">📦 Stock</th>
                            <th class="px-6 py-4 text-left font-bold text-black">📏 Unit</th>
                            <th class="px-6 py-4 text-left font-bold text-black">⚡ Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @forelse($products as $product)
                        <tr class="border-b border-gray-200 hover:bg-yellow-50 transition-colors duration-200">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $product->sku }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $product->name }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $product->category->name ?? '-' }}</td>
                            <td class="px-6 py-4 font-bold text-green-600">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-sm font-bold rounded-full {{ $product->stock > 10 ? 'bg-green-100 text-green-800' : ($product->stock > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-700 font-medium">{{ $product->unit }}</td>
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
                                        wire:confirm="Are you sure you want to delete this product?"
                                        class="bg-red-500 hover:bg-red-600 text-white text-sm font-bold py-2 px-4 rounded-lg border-2 border-red-500 hover:border-red-600 transition-all duration-200"
                                    >
                                        🗑️ Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <div class="text-6xl mb-4">📦</div>
                                    <div class="text-xl font-bold mb-2">No products found</div>
                                    <div class="text-sm">Start by adding your first product using the button above.</div>
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
