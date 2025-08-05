@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-semibold mb-4">Selamat datang di InkTouch POS</h2>
    <p class="text-gray-600">Gunakan menu di atas untuk mengelola Produk dan Kategori.</p>
    <div class="mt-6 grid grid-cols-2 gap-4">
        <a href="{{ route('products.index') }}" class="block p-4 bg-blue-100 rounded hover:bg-blue-200 text-blue-800 font-medium text-center">
            ➤ Kelola Produk
        </a>
        <a href="{{ route('categories.index') }}" class="block p-4 bg-green-100 rounded hover:bg-green-200 text-green-800 font-medium text-center">
            ➤ Kelola Kategori
        </a>
    </div>
</div>
@endsection
