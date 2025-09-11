<div>
    @if (session()->has('success'))
        <div class="mb-6 bg-green-50 border-2 border-green-300 text-green-800 px-6 py-4 rounded-xl font-medium">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 bg-red-50 border-2 border-red-300 text-red-800 px-6 py-4 rounded-xl font-medium">
            ❌ {{ session('error') }}
        </div>
    @endif

    <div class="bg-white overflow-hidden shadow-2xl rounded-2xl ">
        <div class="p-8">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-4xl font-bold text-black">👥 User Management</h2>
                </div>
                <button 
                    wire:click="openCreate" 
                    class="bg-yellow-400 hover:bg-yellow-500 text-black font-bold py-3 px-6 rounded-xl border-2 border-yellow-400 hover:border-yellow-500 transition-all duration-200"
                >
                    ➕ Create User
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Form -->
                <div class="lg:col-span-1">
                    <div class="p-6 bg-yellow-50 border-2 border-yellow-400 rounded-2xl">
                        <h3 class="text-2xl font-bold text-black mb-4">{{ $editingId ? '✏️ Edit User' : '➕ Create New User' }}</h3>
                        <form wire:submit.prevent="save" class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-black mb-2">👤 Name</label>
                                <input wire:model="name" type="text" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium">
                                @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-black mb-2">📧 Email</label>
                                <input wire:model="email" type="email" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium">
                                @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-bold text-black mb-2">🔒 Password {{ $editingId ? '(opsional)' : '' }}</label>
                                    <input wire:model="password" type="password" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium">
                                    @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-black mb-2">🔒 Konfirmasi</label>
                                    <input wire:model="password_confirmation" type="password" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-black mb-2">🛡️ Role</label>
                                <select wire:model="role" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium">
                                    <option value="staff">Staff</option>
                                    <option value="admin">Admin</option>
                                </select>
                                @error('role') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="flex gap-3 pt-2">
                                <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-black font-bold py-2 px-4 rounded-lg border-2 border-yellow-400 hover:border-yellow-500 transition-all duration-200">
                                    {{ $editingId ? '💾 Update' : '💾 Save' }}
                                </button>
                                <button type="button" wire:click="resetForm" class="px-4 py-2 border-2 border-gray-300 rounded-lg font-bold text-black hover:bg-gray-50">Reset</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Table -->
                <div class="lg:col-span-2">
                    <div class="overflow-x-auto bg-white rounded-2xl border-2 border-yellow-400 shadow-lg">
                        <table class="min-w-full">
                            <thead class="bg-yellow-400">
                                <tr>
                                    <th class="px-6 py-4 text-left font-bold text-black">👤 Name</th>
                                    <th class="px-6 py-4 text-left font-bold text-black">📧 Email</th>
                                    <th class="px-6 py-4 text-left font-bold text-black">🛡️ Role</th>
                                    <th class="px-6 py-4 text-left font-bold text-black">⚡ Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                @forelse($users as $u)
                                <tr class="border-b border-gray-200 hover:bg-yellow-50 transition-colors duration-200">
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $u->name }}</td>
                                    <td class="px-6 py-4 text-gray-700">{{ $u->email }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 text-sm font-bold rounded-full {{ $u->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">{{ ucfirst($u->role) }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex gap-2">
                                            <button 
                                                wire:click="openEdit({{ $u->id }})" 
                                                class="bg-yellow-400 hover:bg-yellow-500 text-black text-sm font-bold py-2 px-4 rounded-lg border-2 border-yellow-400 hover:border-yellow-500 transition-all duration-200"
                                            >✏️ Edit</button>
                                            <button 
                                                wire:click="resetUserPassword({{ $u->id }})" 
                                                wire:confirm="Reset password user ini ke 'password'?"
                                                class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-bold py-2 px-4 rounded-lg border-2 border-blue-500 hover:border-blue-600 transition-all duration-200"
                                            >🔑 Reset</button>
                                            <button 
                                                wire:click="delete({{ $u->id }})" 
                                                wire:confirm="Yakin hapus user ini?"
                                                class="bg-red-500 hover:bg-red-600 text-white text-sm font-bold py-2 px-4 rounded-lg border-2 border-red-500 hover:border-red-600 transition-all duration-200"
                                            >🗑️ Delete</button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="text-gray-500">
                                            <div class="text-6xl mb-4">👥</div>
                                            <div class="text-xl font-bold mb-2">Tidak ada user</div>
                                            <div class="text-sm">Gunakan form di sebelah kiri untuk menambahkan user baru.</div>
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
    </div>
</div>
