<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>InkTouch POS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 font-sans">
    <nav class="bg-white shadow p-4 flex justify-between items-center">
        <h1 class="text-xl font-bold text-blue-600">InkTouch POS</h1>
        <ul class="flex space-x-4">
            <li><a href="{{ route('products.index') }}" class="text-gray-700 hover:text-blue-500">Produk</a></li>
            <li><a href="{{ route('categories.index') }}" class="text-gray-700 hover:text-blue-500">Kategori</a></li>
            <li><a href="{{ route('transactions.create') }}" class="text-gray-400 ">Transaksi</a></li>
            <li></li><a href="{{ route('transactions.index') }}" class="block px-4 py-2 hover:bg-gray-100">Riwayat Transaksi</a></li>
            <li><a href="{{ route('reports.index') }}" class="block px-4 py-2 hover:bg-gray-100">Laporan</a>
            <li><a href="{{ route('inventory-logs.index') }}" class="block px-4 py-2 hover:bg-gray-100">📦 Log Inventaris</a>
            </li>
            </li>
        </ul>
    </nav>

    <div class="p-6">
        @yield('content')
    </div>
</body>
</html>
