@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow">

    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">Data Produk</h2>
        <a href="{{ route('products.create') }}" class="bg-blue-500 text-black px-4 py-2 rounded hover:bg-blue-600">+ Tambah Produk</a>
    </div>

    <table class="w-full table-auto border">
        <thead>
            <tr class="bg-gray-100 text-left">
                <th class="px-4 py-2">#</th>
                <th class="px-4 py-2">Gambar</th>
                <th class="px-4 py-2">Nama</th>
                <th class="px-4 py-2">Kategori</th>
                <th class="px-4 py-2 border-b">Harga Beli</th>
                <th class="px-4 py-2">Harga Jual</th>
                <th class="px-4 py-2">Stok</th>
                <th class="px-4 py-2">Satuan</th>
                <th class="px-4 py-2 border-b">SKU</th>
                <th class="px-4 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $key => $product)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $key + 1 }}</td>
    
                    <td class="px-4 py-2">
                        @if($product->image)
                            <img src="{{ asset('uploads/products/' . $product->image) }}" alt="{{ $product->name }}" class="w-16 h-16 object-cover rounded">
                        @else
                            <span class="text-gray-500">Tidak ada</span>
                        @endif
                    </td>
    
                    <td class="px-4 py-2">{{ $product->name }}</td>
                    <td class="px-4 py-2">{{ $product->category->name ?? '-' }}</td>
                    <td class="px-4 py-2">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                    <td class="px-4 py-2">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                    <td class="px-4 py-2">{{ $product->stock }}</td>
                    <td class="px-4 py-2">{{ $product->unit }}</td>
                    <td class="px-4 py-2">{{ $product->sku }}</td>

    
                    <td class="px-4 py-2">
                        <a href="{{ route('products.edit', $product->id) }}" class="text-blue-600 hover:underline">Edit</a> |
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Yakin hapus produk ini?')" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
</div>
@endsection
