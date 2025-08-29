<div>
    <div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-black">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-10 px-8 shadow-2xl rounded-2xl border-4 border-yellow-400">
                <div class="text-center mb-8">
                    <h2 class="text-4xl font-bold text-black">⚡ InkTouch POS</h2>
                    <div class="w-16 h-1 bg-yellow-400 mx-auto mt-3 mb-4 rounded-full"></div>
                    <p class="text-lg text-gray-700 font-medium">Sign in to your account</p>
                </div>

                @if (session()->has('error'))
                    <div class="mb-6 bg-red-50 border-2 border-red-300 text-red-800 px-4 py-3 rounded-xl">
                        {{ session('error') }}
                    </div>
                @endif

                <form wire:submit="login" class="space-y-6">
                    <div>
                        <label for="email" class="block text-sm font-bold text-black mb-2">
                            📧 Email Address
                        </label>
                        <input wire:model="email" id="email" name="email" type="email" autocomplete="email" required 
                               class="appearance-none block w-full px-4 py-3 border-2 border-gray-300 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-yellow-300 focus:border-yellow-400 text-black font-medium">
                        @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-bold text-black mb-2">
                            🔒 Password
                        </label>
                        <input wire:model="password" id="password" name="password" type="password" autocomplete="current-password" required 
                               class="appearance-none block w-full px-4 py-3 border-2 border-gray-300 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-yellow-300 focus:border-yellow-400 text-black font-medium">
                        @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="group relative w-full flex justify-center py-4 px-6 border-2 border-yellow-400 text-lg font-bold rounded-xl text-black bg-yellow-400 hover:bg-yellow-500 hover:border-yellow-500 focus:outline-none focus:ring-4 focus:ring-yellow-300 transition-all duration-300 transform hover:scale-105 shadow-lg">
                            🚀 Sign In
                        </button>
                    </form>
                    
                    <div class="mt-6 text-sm text-gray-600">
                        <p><strong>Demo Credentials:</strong></p>
                        <p>Admin: admin@inktouch.com / password</p>
                        <p>Staff: staff@inktouch.com / password</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}
