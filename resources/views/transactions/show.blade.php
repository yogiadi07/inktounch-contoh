@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-3xl mx-auto">
    <h2 class="text-xl font-semibold mb-4">Detail Transaksi</h2>

    <div class="mb-4">
        <p><strong>No. Invoice:</strong> {{ $transaction->no_invoice }}</p>
        <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d M Y H:i') }}</p>
        <p><strong>Metode Pembayaran:</strong> {{ $transaction->payment_method }}</p>
    </div>

    <table class="w-full table-auto border mb-4">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2">Produk</th>
                <th class="px-4 py-2">Qty</th>
                <th class="px-4 py-2">Harga Satuan</th>
                <th class="px-4 py-2">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaction->details as $detail)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $detail->product->name ?? '-' }}</td>
                    <td class="px-4 py-2">{{ $detail->quantity }}</td>
                    <td class="px-4 py-2">Rp {{ number_format($detail->unit_price, 0, ',', '.') }}</td>
                    <td class="px-4 py-2">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="text-right">
        <p><strong>Total:</strong> Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</p>
        <p><strong>Dibayar:</strong> Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</p>
        <p><strong>Kembalian:</strong> Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</p>
    </div>

    <div class="mt-6">
        <a href="{{ route('transactions.index') }}" class="text-blue-600 hover:underline">← Kembali ke Riwayat</a>
    </div>
</div>
@endsection
