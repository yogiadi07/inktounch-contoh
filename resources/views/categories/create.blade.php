@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-md mx-auto">
    <h2 class="text-xl font-semibold mb-4">{{ isset($category) ? 'Edit' : 'Tambah' }} Kategori</h2>

    <form action="{{ isset($category) ? route('categories.update', $category->id) : route('categories.store') }}" method="POST">
        @csrf
        @if(isset($category)) @method('PUT') @endif

        <div class="mb-4">
            <label class="block text-gray-700">Nama Kategori</label>
            <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" class="w-full border px-4 py-2 rounded" required>
        </div>

        <div class="flex justify-end">
            <a href="{{ route('categories.index') }}" class="text-gray-600 px-4 py-2">Batal</a>
            <button type="submit" class="bg-blue-500 text-black px-4 py-2 rounded hover:bg-blue-600">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection
