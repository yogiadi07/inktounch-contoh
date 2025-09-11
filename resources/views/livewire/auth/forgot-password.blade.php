<div>
    <div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-white">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <!-- Back to Login Button -->
            <div class="mb-6">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 font-medium transition-colors duration-200">
                    <span>⬅️</span>
                    <span>Kembali ke Login</span>
                </a>
            </div>
            
            <div class="bg-white py-10 px-8 shadow-2xl rounded-2xl">
                <div class="text-center mb-8">
                    <div class="flex items-center justify-center gap-3 mb-2">
                        <img src="{{ asset('images/InkTouch.png') }}" alt="InkTouch Logo" class="h-16 w-auto" onerror="this.style.display='none'">
                    </div>
                    <h1 class="text-2xl font-bold text-black mb-2">🔒 Reset Password</h1>
                    <p class="text-lg text-gray-700 font-medium">Atur ulang kata sandi Anda</p>
                </div>

                @if (session()->has('success'))
                    <div class="mb-6 bg-green-50 border-2 border-green-300 text-green-800 px-4 py-3 rounded-xl">
                        {{ session('success') }}
                    </div>
                @endif

                <form wire:submit.prevent="resetPassword" class="space-y-6">
                    <div>
                        <label for="email" class="block text-sm font-bold text-black mb-2">📧 Alamat Email</label>
                        <input 
                            wire:model="email" 
                            type="email" 
                            id="email"
                            class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium"
                            placeholder="Masukkan email Anda"
                            required
                        >
                        @error('email') <span class="text-red-500 text-sm font-medium">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-bold text-black mb-2">🔒 Kata Sandi Baru</label>
                        <input 
                            wire:model="password" 
                            type="password" 
                            id="password"
                            class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium"
                            placeholder="Masukkan kata sandi baru"
                            required
                        >
                        @error('password') <span class="text-red-500 text-sm font-medium">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-bold text-black mb-2">🔒 Konfirmasi Kata Sandi</label>
                        <input 
                            wire:model="password_confirmation" 
                            type="password" 
                            id="password_confirmation"
                            class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium"
                            placeholder="Konfirmasi kata sandi baru"
                            required
                        >
                    </div>

                    <button 
                        type="submit" 
                        class="w-full bg-yellow-400 hover:bg-yellow-500 text-black font-bold py-3 px-6 rounded-xl border-2 border-yellow-400 hover:border-yellow-500 transition-all duration-200 shadow-lg hover:shadow-xl"
                    >
                        🔄 Reset Password
                    </button>
                </form>

                </div>
            </div>
        </div>
    </div>
</div>
