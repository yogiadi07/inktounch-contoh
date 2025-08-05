@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-xl mx-auto">
    <h2 class="text-xl font-semibold mb-4">{{ isset($product) ? 'Edit' : 'Tambah' }} Produk</h2>

    <form action="{{ isset($product) ? route('products.update', $product->id) : route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($product)) @method('PUT') @endif

        <div class="mb-4">
            <label class="block text-gray-700">Nama Produk</label>
            <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" class="w-full border px-4 py-2 rounded" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Kategori</label>
            <select name="category_id" class="w-full border px-4 py-2 rounded">
                <option value="">- Pilih Kategori -</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ (old('category_id', $product->category_id ?? '') == $cat->id) ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Harga Beli</label>
            <input type="number" step="0.01" name="purchase_price" value="{{ old('purchase_price', $product->purchase_price ?? '') }}" class="w-full border px-4 py-2 rounded">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Harga Jual</label>
            <input type="number" step="0.01" name="selling_price" value="{{ old('selling_price', $product->selling_price ?? '') }}" class="w-full border px-4 py-2 rounded">
        </div>

        @if(isset($product))
        <div class="mb-4">
            <label class="block text-gray-700">Tambah Stok</label>
            <input type="number" name="added_stock" value="0" min="0" class="w-full border px-4 py-2 rounded">
            <p class="text-sm text-gray-500">Biarkan 0 jika tidak menambah stok.</p>
        </div>
    @else
        <div class="mb-4">
            <label class="block text-gray-700">Stok Awal</label>
            <input type="number" name="stock" value="{{ old('stock', 0) }}" class="w-full border px-4 py-2 rounded">
        </div>
    @endif

        <div class="mb-4">
            <label for="unit" class="block text-sm font-medium text-gray-700">Satuan</label>
            <select name="unit" id="unit" class="mt-1 block w-full border rounded p-2">
                <option value="pcs">pcs</option>
                <option value="box">box</option>
                <option value="lusin">lusin</option>
                <option value="rim">rim</option>
                <option value="pak">pak</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Upload Gambar (opsional)</label>
            <input type="file" name="image" class="w-full">
        </div>

        <div class="flex justify-end">
            <a href="{{ route('products.index') }}" class="text-gray-600 px-4 py-2">Batal</a>
            <button type="submit" class="bg-blue-500 text-black px-4 py-2 rounded hover:bg-blue-600">
                Simpan
            </button>
        </div>
        
    </form>
</div>
@endsection
