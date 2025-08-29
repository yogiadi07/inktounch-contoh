<div>
    <div class="bg-white overflow-hidden shadow-2xl rounded-2xl border-4 border-yellow-400">
        <div class="p-8">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-4xl font-bold text-black">📊 Sales Report</h2>
                    <div class="w-24 h-2 bg-yellow-400 mt-2 rounded-full"></div>
                </div>
                <button 
                    wire:click="exportReport" 
                    class="bg-green-500 hover:bg-green-600 text-white font-bold py-4 px-8 rounded-xl border-2 border-green-500 hover:border-green-600 transition-all duration-300 transform hover:scale-105 shadow-lg"
                >
                    📄 Export Excel
                </button>
            </div>

            <!-- Filter Section -->
            <div class="mb-8 p-6 bg-yellow-50 border-2 border-yellow-400 rounded-2xl">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-black mb-2">📅 Start Date</label>
                        <input wire:model.live="start_date" type="date" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-black mb-2">📅 End Date</label>
                        <input wire:model.live="end_date" type="date" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-black mb-2">📈 Report Type</label>
                        <select wire:model.live="report_type" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium">
                            <option value="daily">📅 Daily</option>
                            <option value="weekly">📅 Weekly</option>
                            <option value="monthly">📅 Monthly</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-black mb-2">🏷️ Category</label>
                        <select wire:model.live="category_id" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium">
                            <option value="">🏷️ All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white border-2 border-yellow-400 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-bold text-gray-600 mb-2">💰 Total Revenue</h3>
                            <p class="text-2xl font-bold text-green-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                        </div>
                        <div class="text-4xl">💰</div>
                    </div>
                </div>

                <div class="bg-white border-2 border-yellow-400 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-bold text-gray-600 mb-2">💳 Total Transactions</h3>
                            <p class="text-2xl font-bold text-blue-600">{{ number_format($totalTransactions, 0, ',', '.') }}</p>
                        </div>
                        <div class="text-4xl">💳</div>
                    </div>
                </div>

                <div class="bg-white border-2 border-yellow-400 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-bold text-gray-600 mb-2">📈 Average Transaction</h3>
                            <p class="text-2xl font-bold text-purple-600">Rp {{ number_format($averageTransaction, 0, ',', '.') }}</p>
                        </div>
                        <div class="text-4xl">📈</div>
                    </div>
                </div>
            </div>

            <!-- Charts and Tables -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Sales Trend -->
                <div class="bg-white border-2 border-yellow-400 rounded-2xl p-6 shadow-lg">
                    <h3 class="text-xl font-bold text-black mb-6">📈 Sales Trend</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-yellow-400">
                                <tr>
                                    <th class="px-4 py-3 text-left font-bold text-black">📅 Period</th>
                                    <th class="px-4 py-3 text-left font-bold text-black">💳 Transactions</th>
                                    <th class="px-4 py-3 text-left font-bold text-black">💰 Revenue</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                @forelse($salesData as $data)
                                <tr class="border-b border-gray-200 hover:bg-yellow-50 transition-colors duration-200">
                                    <td class="px-4 py-3 font-medium text-gray-900">{{ $data['formatted_period'] }}</td>
                                    <td class="px-4 py-3 font-bold text-blue-600">{{ $data['transaction_count'] }}</td>
                                    <td class="px-4 py-3 font-bold text-green-600">Rp {{ number_format($data['total_revenue'], 0, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-8 text-center">
                                        <div class="text-gray-500">
                                            <div class="text-4xl mb-2">📈</div>
                                            <div class="font-bold">No data available</div>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Top Products -->
                <div class="bg-white border-2 border-yellow-400 rounded-2xl p-6 shadow-lg">
                    <h3 class="text-xl font-bold text-black mb-6">🏆 Top Products</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-yellow-400">
                                <tr>
                                    <th class="px-4 py-3 text-left font-bold text-black">📦 Product</th>
                                    <th class="px-4 py-3 text-left font-bold text-black">📊 Sold</th>
                                    <th class="px-4 py-3 text-left font-bold text-black">💰 Revenue</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                @forelse($topProducts as $product)
                                <tr class="border-b border-gray-200 hover:bg-yellow-50 transition-colors duration-200">
                                    <td class="px-4 py-3 font-bold text-black">{{ $product->name }}</td>
                                    <td class="px-4 py-3 font-bold text-blue-600">{{ $product->total_sold }}</td>
                                    <td class="px-4 py-3 font-bold text-green-600">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-8 text-center">
                                        <div class="text-gray-500">
                                            <div class="text-4xl mb-2">🏆</div>
                                            <div class="font-bold">No data available</div>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Category Statistics -->
            <div class="bg-white border-2 border-yellow-400 rounded-2xl p-6 shadow-lg">
                <h3 class="text-xl font-bold text-black mb-6">🏷️ Category Statistics</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-yellow-400">
                            <tr>
                                <th class="px-4 py-3 text-left font-bold text-black">🏷️ Category</th>
                                <th class="px-4 py-3 text-left font-bold text-black">💳 Transactions</th>
                                <th class="px-4 py-3 text-left font-bold text-black">📊 Qty Sold</th>
                                <th class="px-4 py-3 text-left font-bold text-black">💰 Revenue</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @forelse($categoryStats as $stat)
                            <tr class="border-b border-gray-200 hover:bg-yellow-50 transition-colors duration-200">
                                <td class="px-4 py-3 font-bold text-black">{{ $stat->category_name }}</td>
                                <td class="px-4 py-3 font-bold text-blue-600">{{ $stat->transaction_count }}</td>
                                <td class="px-4 py-3 font-bold text-purple-600">{{ $stat->total_quantity }}</td>
                                <td class="px-4 py-3 font-bold text-green-600">Rp {{ number_format($stat->total_revenue, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center">
                                    <div class="text-gray-500">
                                        <div class="text-4xl mb-2">🏷️</div>
                                        <div class="font-bold">No data available</div>
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
