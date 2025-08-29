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
                    <h2 class="text-4xl font-bold text-black">💳 Transaction Management</h2>
                    <div class="w-24 h-2 bg-yellow-400 mt-2 rounded-full"></div>
                </div>
                <button 
                    wire:click="openCreateForm" 
                    class="bg-yellow-400 hover:bg-yellow-500 text-black font-bold py-4 px-8 rounded-xl border-2 border-yellow-400 hover:border-yellow-500 transition-all duration-300 transform hover:scale-105 shadow-lg"
                >
                    ➕ Create Transaction
                </button>
            </div>

            <!-- Filter Section -->
            <div class="mb-8 p-6 bg-yellow-50 border-2 border-yellow-400 rounded-2xl">
                <div class="flex flex-wrap gap-6 items-end">
                    <div>
                        <label class="block text-sm font-bold text-black mb-2">📅 Start Date</label>
                        <input wire:model="start_date" type="date" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-black mb-2">📅 End Date</label>
                        <input wire:model="end_date" type="date" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium">
                    </div>
                    <div class="flex gap-3">
                        <button wire:click="applyFilter" class="bg-yellow-400 hover:bg-yellow-500 text-black font-bold py-3 px-6 rounded-xl border-2 border-yellow-400 hover:border-yellow-500 transition-all duration-200">🔍 Filter</button>
                        <button wire:click="clearFilter" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-xl border-2 border-gray-500 hover:border-gray-600 transition-all duration-200">🔄 Reset</button>
                    </div>
                </div>
            </div>

            <!-- Create Transaction Form -->
            @if($showCreateForm)
                <div class="mb-8 p-8 bg-yellow-50 border-2 border-yellow-400 rounded-2xl">
                    <h3 class="text-2xl font-bold text-black mb-6">➕ Create New Transaction</h3>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Products Selection -->
                        <div>
                            <h4 class="text-xl font-bold text-black mb-4">📦 Select Products</h4>
                            <div class="max-h-96 overflow-y-auto bg-white border-2 border-gray-300 rounded-xl">
                                @forelse($products as $product)
                                    <div class="p-4 border-b border-gray-200 flex justify-between items-center hover:bg-yellow-50 transition-colors duration-200">
                                        <div>
                                            <p class="font-bold text-black">{{ $product->name }}</p>
                                            <p class="text-sm text-gray-600 font-medium">💰 Rp {{ number_format($product->selling_price, 0, ',', '.') }} | 📦 Stock: {{ $product->stock }}</p>
                                        </div>
                                        <button 
                                            wire:click="addToCart({{ $product->id }})"
                                            class="bg-yellow-400 hover:bg-yellow-500 text-black font-bold py-2 px-4 rounded-lg border-2 border-yellow-400 hover:border-yellow-500 transition-all duration-200"
                                        >
                                            ➕ Add
                                        </button>
                                    </div>
                                @empty
                                    <p class="p-6 text-gray-500 text-center">📦 No products available</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Cart & Checkout -->
                        <div>
                            <h4 class="text-xl font-bold text-black mb-4">🛒 Shopping Cart</h4>
                            
                            <!-- Cart Items -->
                            <div class="max-h-64 overflow-y-auto bg-white border-2 border-gray-300 rounded-xl mb-6">
                                @forelse($cart as $index => $item)
                                    <div class="p-4 border-b border-gray-200 flex justify-between items-center hover:bg-yellow-50 transition-colors duration-200">
                                        <div class="flex-1">
                                            <p class="font-bold text-black">{{ $item['name'] }}</p>
                                            <p class="text-sm text-gray-600 font-medium">💰 Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <input 
                                                type="number" 
                                                wire:model="cart.{{ $index }}.quantity" 
                                                min="1" 
                                                max="{{ $item['stock'] }}"
                                                class="w-16 px-3 py-2 border-2 border-gray-300 rounded-lg text-center font-bold focus:border-yellow-400 focus:ring-2 focus:ring-yellow-200"
                                            >
                                            <button 
                                                wire:click="removeFromCart({{ $index }})"
                                                class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-3 rounded-lg border-2 border-red-500 hover:border-red-600 transition-all duration-200"
                                            >
                                                🗑️
                                            </button>
                                        </div>
                                    </div>
                                @empty
                                    <p class="p-6 text-gray-500 text-center">🛒 Cart is empty</p>
                                @endforelse
                            </div>

                            @if(count($cart) > 0)
                                <!-- Payment Section -->
                                <div class="bg-white border-2 border-yellow-400 rounded-xl p-6">
                                    <h5 class="text-lg font-bold text-black mb-4">💳 Payment</h5>
                                    
                                    <div class="space-y-4">
                                        <div class="flex justify-between items-center p-3 bg-yellow-50 rounded-lg">
                                            <span class="font-bold text-black">Total:</span>
                                            <span class="font-bold text-xl text-green-600">Rp {{ number_format($this->getCartTotal(), 0, ',', '.') }}</span>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-bold text-black mb-2">💳 Payment Method</label>
                                            <select wire:model="payment_method" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium">
                                                <option value="cash">💵 Cash</option>
                                                <option value="card">💳 Card</option>
                                                <option value="transfer">🏦 Transfer</option>
                                            </select>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-bold text-black mb-2">💰 Payment Amount</label>
                                            <input 
                                                wire:model="payment_amount" 
                                                type="number" 
                                                step="0.01"
                                                class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium"
                                                placeholder="Enter payment amount"
                                            >
                                        </div>
                                        
                                        @if($payment_amount && $payment_amount >= $this->getCartTotal())
                                            <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg border-2 border-green-200">
                                                <span class="font-bold text-green-800">💸 Change:</span>
                                                <span class="font-bold text-xl text-green-600">Rp {{ number_format($payment_amount - $this->getCartTotal(), 0, ',', '.') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="flex gap-4 mt-6">
                                        <button 
                                            wire:click="closeCreateForm" 
                                            class="flex-1 px-6 py-3 border-2 border-gray-300 rounded-xl font-bold text-black hover:bg-gray-50 transition-all duration-200"
                                        >
                                            ❌ Cancel
                                        </button>
                                        <button 
                                            wire:click="processTransaction" 
                                            class="flex-1 bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-xl border-2 border-green-500 hover:border-green-600 transition-all duration-200"
                                            @if(!$payment_amount || $payment_amount < $this->getCartTotal()) disabled @endif
                                        >
                                            ✅ Process Transaction
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <button wire:click="closeCreateForm" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-xl border-2 border-gray-500 hover:border-gray-600 transition-all duration-200">
                            ❌ Close
                        </button>
                    </div>
                </div>
            @endif

            <!-- Transaction Detail -->
            @if($showDetailModal && $selectedTransaction)
                <div class="mb-8 p-8 bg-yellow-50 border-2 border-yellow-400 rounded-2xl">
                    <h3 class="text-2xl font-bold text-black mb-6">📊 Transaction Detail</h3>

                    <div class="space-y-6">
                        <div class="grid grid-cols-2 gap-6 text-sm">
                            <div class="p-3 bg-white rounded-lg border border-gray-200"><strong class="text-black">📝 Invoice No:</strong> {{ $selectedTransaction->no_invoice }}</div>
                            <div class="p-3 bg-white rounded-lg border border-gray-200"><strong class="text-black">👤 Cashier:</strong> {{ $selectedTransaction->cashier_name }}</div>
                            <div class="p-3 bg-white rounded-lg border border-gray-200"><strong class="text-black">📅 Date:</strong> {{ $selectedTransaction->transaction_date ? $selectedTransaction->transaction_date->format('d/m/Y H:i') : 'N/A' }}</div>
                            <div class="p-3 bg-white rounded-lg border border-gray-200"><strong class="text-black">💳 Payment:</strong> {{ ucfirst($selectedTransaction->payment_method) }}</div>
                        </div>

                        <div class="bg-white rounded-xl p-6 border-2 border-gray-200">
                            <h4 class="font-bold text-black mb-4">📦 Product Details:</h4>
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
                                    <span class="font-bold text-black">💵 Paid:</span>
                                    <span class="font-bold text-blue-600">Rp {{ number_format($selectedTransaction->paid_amount, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center p-3 bg-yellow-50 rounded-lg">
                                    <span class="font-bold text-black">💸 Change:</span>
                                    <span class="font-bold text-yellow-600">Rp {{ number_format($selectedTransaction->change_amount, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <button wire:click="closeDetailModal" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-xl border-2 border-gray-500 hover:border-gray-600 transition-all duration-200">
                            ❌ Close
                        </button>
                    </div>
                </div>
            @endif

            <!-- Transactions Table -->
            <div class="overflow-x-auto bg-white rounded-2xl border-2 border-yellow-400 shadow-lg">
                <table class="min-w-full">
                    <thead class="bg-yellow-400">
                        <tr>
                            <th class="px-6 py-4 text-left font-bold text-black">📝 Invoice No</th>
                            <th class="px-6 py-4 text-left font-bold text-black">👤 Cashier</th>
                            <th class="px-6 py-4 text-left font-bold text-black">📅 Date</th>
                            <th class="px-6 py-4 text-left font-bold text-black">💰 Total</th>
                            <th class="px-6 py-4 text-left font-bold text-black">💳 Payment</th>
                            <th class="px-6 py-4 text-left font-bold text-black">⚡ Actions</th>
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
                                    <div class="text-xl font-bold mb-2">No transactions found</div>
                                    <div class="text-sm">Start by creating your first transaction using the button above.</div>
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
