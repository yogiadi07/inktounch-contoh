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
                    <h2 class="text-4xl font-bold text-black">📋 Inventory Log Management</h2>
                    <div class="w-24 h-2 bg-yellow-400 mt-2 rounded-full"></div>
                </div>
                <button 
                    wire:click="openCreateForm" 
                    class="bg-yellow-400 hover:bg-yellow-500 text-black font-bold py-4 px-8 rounded-xl border-2 border-yellow-400 hover:border-yellow-500 transition-all duration-300 transform hover:scale-105 shadow-lg"
                >
                    ➕ Add Log
                </button>
            </div>

            <!-- Filter Section -->
            <div class="mb-8 p-6 bg-yellow-50 border-2 border-yellow-400 rounded-2xl">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-6 items-end">
                    <div>
                        <label class="block text-sm font-bold text-black mb-2">📅 Start Date</label>
                        <input wire:model="start_date" type="date" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-black mb-2">📅 End Date</label>
                        <input wire:model="end_date" type="date" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-black mb-2">📦 Product</label>
                        <select wire:model="product_id" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium">
                            <option value="">📦 All Products</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-black mb-2">🔄 Type</label>
                        <select wire:model="type" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium">
                            <option value="">🔄 All Types</option>
                            <option value="in">📈 In</option>
                            <option value="out">📉 Out</option>
                        </select>
                    </div>
                    <div class="flex gap-3">
                        <button wire:click="applyFilter" class="bg-yellow-400 hover:bg-yellow-500 text-black font-bold py-3 px-6 rounded-xl border-2 border-yellow-400 hover:border-yellow-500 transition-all duration-200">🔍 Filter</button>
                        <button wire:click="clearFilter" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-xl border-2 border-gray-500 hover:border-gray-600 transition-all duration-200">🔄 Reset</button>
                    </div>
                </div>
            </div>

            <!-- Create/Edit Form -->
            @if($showCreateForm)
                <div class="mb-8 p-8 bg-yellow-50 border-2 border-yellow-400 rounded-2xl">
                    <h3 class="text-2xl font-bold text-black mb-6">
                        {{ $editingLogId ? '✏️ Edit Inventory Log' : '➕ Add Inventory Log' }}
                    </h3>

                    <form wire:submit="{{ $editingLogId ? 'update' : 'save' }}" class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-black mb-2">📦 Product</label>
                            <select wire:model="form_product_id" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium">
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }} (Stock: {{ $product->stock }})</option>
                                @endforeach
                            </select>
                            @error('form_product_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-black mb-2">🔄 Type</label>
                            <select wire:model="form_type" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium">
                                <option value="in">📈 In</option>
                                <option value="out">📉 Out</option>
                            </select>
                            @error('form_type') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-black mb-2">📊 Quantity</label>
                            <input wire:model="form_quantity" type="number" min="1" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium">
                            @error('form_quantity') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-black mb-2">📝 Description</label>
                            <textarea wire:model="form_description" rows="3" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium"></textarea>
                            @error('form_description') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex gap-4 pt-4">
                            <button type="button" wire:click="closeCreateForm" class="px-6 py-3 border-2 border-gray-300 rounded-xl font-bold text-black hover:bg-gray-50 transition-all duration-200">
                                ❌ Cancel
                            </button>
                            <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-black font-bold py-3 px-8 rounded-xl border-2 border-yellow-400 hover:border-yellow-500 transition-all duration-300 transform hover:scale-105 shadow-lg">
                                {{ $editingLogId ? '✏️ Update' : '💾 Save' }}
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- Logs Table -->
            <div class="overflow-x-auto bg-white rounded-2xl border-2 border-yellow-400 shadow-lg">
                <table class="min-w-full">
                    <thead class="bg-yellow-400">
                        <tr>
                            <th class="px-6 py-4 text-left font-bold text-black">📅 Date</th>
                            <th class="px-6 py-4 text-left font-bold text-black">📦 Product</th>
                            <th class="px-6 py-4 text-left font-bold text-black">🔄 Type</th>
                            <th class="px-6 py-4 text-left font-bold text-black">📊 Quantity</th>
                            <th class="px-6 py-4 text-left font-bold text-black">📝 Description</th>
                            <th class="px-6 py-4 text-left font-bold text-black">⚡ Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @forelse($logs as $log)
                        <tr class="border-b border-gray-200 hover:bg-yellow-50 transition-colors duration-200">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $log->logged_at ? $log->logged_at->format('d/m/Y H:i') : 'N/A' }}</td>
                            <td class="px-6 py-4 font-bold text-black">{{ $log->product->name }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-sm font-bold rounded-full {{ $log->type === 'in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $log->type === 'in' ? '📈 In' : '📉 Out' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-bold text-blue-600">{{ $log->quantity }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $log->description }}</td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <button 
                                        wire:click="edit({{ $log->id }})" 
                                        class="bg-yellow-400 hover:bg-yellow-500 text-black text-sm font-bold py-2 px-4 rounded-lg border-2 border-yellow-400 hover:border-yellow-500 transition-all duration-200"
                                    >
                                        ✏️ Edit
                                    </button>
                                    <button 
                                        wire:click="delete({{ $log->id }})" 
                                        wire:confirm="Are you sure you want to delete this log?"
                                        class="bg-red-500 hover:bg-red-600 text-white text-sm font-bold py-2 px-4 rounded-lg border-2 border-red-500 hover:border-red-600 transition-all duration-200"
                                    >
                                        🗑️ Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <div class="text-6xl mb-4">📋</div>
                                    <div class="text-xl font-bold mb-2">No inventory logs found</div>
                                    <div class="text-sm">Start by adding your first log using the button above.</div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

                <!-- Pagination -->
                @if($logs->hasPages())
                    <div class="mt-6">
                        {{ $logs->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
