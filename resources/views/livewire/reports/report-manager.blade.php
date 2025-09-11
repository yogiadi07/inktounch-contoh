<div>
    <div class="bg-white overflow-hidden shadow-2xl rounded-2xl ">
        <div class="p-8">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-4xl font-bold text-black">📊 Laporan Penjualan</h2>
                </div>
                <button 
                    wire:click="exportReport" 
                    class="bg-green-500 hover:bg-green-600 text-white font-bold py-4 px-8 rounded-xl border-2 border-green-500 hover:border-green-600 transition-all duration-300 transform hover:scale-105 shadow-lg"
                >
                    📄 Ekspor Excel
                </button>
            </div>

            <!-- Filter Section -->
            <div class="mb-8 p-6 bg-yellow-50 border-2 border-yellow-400 rounded-2xl">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-black mb-2">📅 Tanggal Mulai</label>
                        <input wire:model.live="start_date" type="date" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-black mb-2">📅 Tanggal Akhir</label>
                        <input wire:model.live="end_date" type="date" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-black mb-2">📈 Jenis Laporan</label>
                        <select wire:model.live="report_type" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium">
                            <option value="daily">📅 Harian</option>
                            <option value="weekly">📅 Mingguan</option>
                            <option value="monthly">📅 Bulanan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-black mb-2">🏷️ Kategori</label>
                        <select wire:model.live="category_id" class="block w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200 font-medium">
                            <option value="">🏷️ Semua Kategori</option>
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
                            <h3 class="text-sm font-bold text-gray-600 mb-2">💰 Total Pendapatan</h3>
                            <p class="text-2xl font-bold text-green-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                        </div>
                        <div class="text-4xl">💰</div>
                    </div>
                </div>

                <div class="bg-white border-2 border-yellow-400 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-bold text-gray-600 mb-2">💳 Total Transaksi</h3>
                            <p class="text-2xl font-bold text-blue-600">{{ number_format($totalTransactions, 0, ',', '.') }}</p>
                        </div>
                        <div class="text-4xl">💳</div>
                    </div>
                </div>

                <div class="bg-white border-2 border-yellow-400 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-bold text-gray-600 mb-2">📈 Rata-rata Transaksi</h3>
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
                    <h3 class="text-xl font-bold text-black mb-6">📈 Tren Penjualan</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-yellow-400">
                                <tr>
                                    <th class="px-4 py-3 text-left font-bold text-black">📅 Periode</th>
                                    <th class="px-4 py-3 text-left font-bold text-black">💳 Transaksi</th>
                                    <th class="px-4 py-3 text-left font-bold text-black">💰 Pendapatan</th>
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
                                            <div class="font-bold">Tidak ada data</div>
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
                    <h3 class="text-xl font-bold text-black mb-6">🏆 Produk Terlaris</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-yellow-400">
                                <tr>
                                    <th class="px-4 py-3 text-left font-bold text-black">📦 Produk</th>
                                    <th class="px-4 py-3 text-left font-bold text-black">📊 Terjual</th>
                                    <th class="px-4 py-3 text-left font-bold text-black">💰 Pendapatan</th>
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
                                            <div class="font-bold">Tidak ada data</div>
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
                <h3 class="text-xl font-bold text-black mb-6">🏷️ Statistik Kategori</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-yellow-400">
                            <tr>
                                <th class="px-4 py-3 text-left font-bold text-black">🏷️ Kategori</th>
                                <th class="px-4 py-3 text-left font-bold text-black">💳 Transaksi</th>
                                <th class="px-4 py-3 text-left font-bold text-black">📊 Jumlah Terjual</th>
                                <th class="px-4 py-3 text-left font-bold text-black">💰 Pendapatan</th>
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
                                        <div class="font-bold">Tidak ada data</div>
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
