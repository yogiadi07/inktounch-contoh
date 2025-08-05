@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Transaksi Baru</h2>

    <form action="{{ route('transactions.store') }}" method="POST">
        @csrf

        <div id="product-list">
            <div class="flex gap-4 mb-2">
                <select name="products[0][product_id]" class="border rounded p-2 w-full">
                    <option value="">-- Pilih Produk --</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} - Rp{{ number_format($product->selling_price) }}</option>
                    @endforeach
                </select>
                <input type="number" name="products[0][quantity]" placeholder="Qty" class="border rounded p-2 w-24">
            </div>
        </div>

        <button type="button" id="add-product" class="text-blue-500 mb-4">+ Tambah Produk</button>

        <div class="mb-4">
            <label for="payment_method" class="block">Metode Pembayaran</label>
            <select name="payment_method" id="payment_method" class="border rounded p-2 w-full">
                <option value="Tunai">Tunai</option>
                <option value="Transfer">Transfer</option>
                <option value="QRIS">QRIS</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="paid_amount" class="block">Jumlah Bayar</label>
            <input type="number" name="paid_amount" class="border rounded p-2 w-full" required>
        </div>

        <button type="submit" class="bg-blue-600 text-black px-4 py-2 rounded">Simpan Transaksi</button>
    </form>
</div>

<script>
    let index = 1;
    document.getElementById('add-product').addEventListener('click', () => {
        const list = document.getElementById('product-list');
        const html = `
            <div class="flex gap-4 mb-2">
                <select name="products[${index}][product_id]" class="border rounded p-2 w-full">
                    <option value="">-- Pilih Produk --</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} - Rp{{ number_format($product->selling_price) }}</option>
                    @endforeach
                </select>
                <input type="number" name="products[${index}][quantity]" placeholder="Qty" class="border rounded p-2 w-24">
            </div>`;
        list.insertAdjacentHTML('beforeend', html);
        index++;
    });
</script>
@endsection
