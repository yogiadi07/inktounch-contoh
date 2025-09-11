<div>
    <div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-white">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <!-- Back to Welcome Button -->
            <div class="mb-6">
                <a href="{{ route('welcome') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 font-medium transition-colors duration-200">
                    <span>⬅️</span>
                    <span>Kembali ke Beranda</span>
                </a>
            </div>
            
            <div class="bg-white py-10 px-8 shadow-2xl rounded-2xl">
                <div class="text-center mb-8">
                    <div class="flex items-center justify-center gap-3 mb-2">
                        <img src="{{ asset('images/InkTouch.png') }}" alt="InkTouch Logo" class="h-16 w-auto" onerror="this.style.display='none'">
                    </div>
                    <p class="text-lg text-gray-700 font-medium">Buat akun baru</p>
                </div>

                <form wire:submit.prevent="register" class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-black mb-2">👤 Nama Lengkap</label>
                        <input wire:model="name" type="text" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium" placeholder="Masukkan nama lengkap">
                        @error('name') <span class="text-red-500 text-sm font-medium">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-black mb-2">📧 Alamat Email</label>
                        <input wire:model="email" type="email" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium" placeholder="Masukkan alamat email">
                        @error('email') <span class="text-red-500 text-sm font-medium">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-black mb-2">🔒 Kata Sandi</label>
                        <input wire:model="password" type="password" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium" placeholder="Masukkan kata sandi">
                        @error('password') <span class="text-red-500 text-sm font-medium">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-black mb-2">🔒 Konfirmasi Kata Sandi</label>
                        <input wire:model="password_confirmation" type="password" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium" placeholder="Konfirmasi kata sandi">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-black mb-2">🛡️ Role</label>
                        <select wire:model="role" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium">
                            <option value="staff">Staff</option>
                            <option value="admin">Admin</option>
                        </select>
                        @error('role') <span class="text-red-500 text-sm font-medium">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="w-full bg-yellow-400 hover:bg-yellow-500 text-black font-bold py-3 px-6 rounded-xl border-2 border-yellow-400 hover:border-yellow-500 transition-all duration-200 shadow-lg hover:shadow-xl">
                        📝 Buat Akun
                    </button>
                </form>
                
                <div class="mt-6 text-sm text-gray-600">
                    <div class="flex items-center justify-between">
                        <span>Sudah punya akun?</span>
                        <a href="{{ route('login') }}" class="font-bold text-yellow-600 hover:text-yellow-700">Masuk sekarang</a>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
