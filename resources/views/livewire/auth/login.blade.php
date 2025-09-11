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
                    <p class="text-lg text-gray-700 font-medium">Masuk ke akun Anda</p>
                </div>

                @if (session()->has('error'))
                    <div class="mb-6 bg-red-50 border-2 border-red-300 text-red-800 px-4 py-3 rounded-xl">
                        {{ session('error') }}
                    </div>
                @endif

                <form wire:submit.prevent="login" class="space-y-6">
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
                        <label for="password" class="block text-sm font-bold text-black mb-2">🔒 Kata Sandi</label>
                        <input 
                            wire:model="password" 
                            type="password" 
                            id="password"
                            class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium"
                            placeholder="Masukkan kata sandi Anda"
                            required
                        >
                        @error('password') <span class="text-red-500 text-sm font-medium">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                            <input type="checkbox" wire:model="remember" class="rounded border-gray-300 text-yellow-500 focus:ring-yellow-400">
                            Ingat saya
                        </label>
                        <a href="{{ route('password.request') }}" class="text-sm font-bold text-yellow-600 hover:text-yellow-700">Lupa kata sandi?</a>
                    </div>

                    <button 
                        type="submit" 
                        class="w-full bg-yellow-400 hover:bg-yellow-500 text-black font-bold py-3 px-6 rounded-xl border-2 border-yellow-400 hover:border-yellow-500 transition-all duration-200 shadow-lg hover:shadow-xl"
                    >
                        🚀 Masuk
                    </button>
                </form>
    
                </div>
            </div>
        </div>
    </div>
</div>
{{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}
