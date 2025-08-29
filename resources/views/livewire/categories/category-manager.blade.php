<div>
    @if (session()->has('success'))
        <div class="mb-6 bg-yellow-50 border-2 border-yellow-400 text-black px-6 py-4 rounded-xl font-medium">
            ✅ {{ session('success') }}
        </div>
    @endif

    <div class="bg-white overflow-hidden shadow-2xl rounded-2xl border-4 border-yellow-400">
        <div class="p-8">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-4xl font-bold text-black">🏷️ Category Management</h2>
                    <div class="w-24 h-2 bg-yellow-400 mt-2 rounded-full"></div>
                </div>
                <button 
                    wire:click="openCreateForm" 
                    class="bg-yellow-400 hover:bg-yellow-500 text-black font-bold py-4 px-8 rounded-xl border-2 border-yellow-400 hover:border-yellow-500 transition-all duration-300 transform hover:scale-105 shadow-lg"
                >
                    ➕ Add Category
                </button>
            </div>

            <!-- Category Form -->
            @if($showForm)
                <div class="mb-8 p-8 bg-yellow-50 border-2 border-yellow-400 rounded-2xl">
                    <h3 class="text-2xl font-bold text-black mb-6">
                        {{ $editingCategory ? '✏️ Edit Category' : '➕ Add New Category' }}
                    </h3>

                    <form wire:submit="save" class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-black mb-2">📝 Category Name</label>
                            <input wire:model="name" type="text" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium">
                            @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex gap-4 pt-4">
                            <button type="button" wire:click="closeForm" class="px-6 py-3 border-2 border-gray-300 rounded-xl font-bold text-black hover:bg-gray-50 transition-all duration-200">
                                ❌ Cancel
                            </button>
                            <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-black font-bold py-3 px-8 rounded-xl border-2 border-yellow-400 hover:border-yellow-500 transition-all duration-300 transform hover:scale-105 shadow-lg">
                                {{ $editingCategory ? '✏️ Update' : '💾 Save' }}
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- Categories Table -->
            <div class="overflow-x-auto bg-white rounded-2xl border-2 border-yellow-400 shadow-lg">
                <table class="min-w-full">
                    <thead class="bg-yellow-400">
                        <tr>
                            <th class="px-6 py-4 text-left font-bold text-black">🔢 ID</th>
                            <th class="px-6 py-4 text-left font-bold text-black">📝 Category Name</th>
                            <th class="px-6 py-4 text-left font-bold text-black">📦 Product Count</th>
                            <th class="px-6 py-4 text-left font-bold text-black">⚡ Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @forelse($categories as $category)
                        <tr class="border-b border-gray-200 hover:bg-yellow-50 transition-colors duration-200">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $category->id }}</td>
                            <td class="px-6 py-4 font-bold text-black text-lg">{{ $category->name }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-sm font-bold rounded-full bg-yellow-100 text-yellow-800">
                                    📦 {{ $category->products_count ?? 0 }} products
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <button 
                                        wire:click="openEditForm({{ $category->id }})" 
                                        class="bg-yellow-400 hover:bg-yellow-500 text-black text-sm font-bold py-2 px-4 rounded-lg border-2 border-yellow-400 hover:border-yellow-500 transition-all duration-200"
                                    >
                                        ✏️ Edit
                                    </button>
                                    <button 
                                        wire:click="delete({{ $category->id }})" 
                                        wire:confirm="Are you sure you want to delete this category?"
                                        class="bg-red-500 hover:bg-red-600 text-white text-sm font-bold py-2 px-4 rounded-lg border-2 border-red-500 hover:border-red-600 transition-all duration-200"
                                    >
                                        🗑️ Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <div class="text-6xl mb-4">🏷️</div>
                                    <div class="text-xl font-bold mb-2">No categories found</div>
                                    <div class="text-sm">Start by adding your first category using the button above.</div>
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
