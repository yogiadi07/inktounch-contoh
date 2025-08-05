@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">Riwayat Transaksi</h2>
        <a href="{{ route('transactions.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">+ Transaksi Baru</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    <form method="GET" action="{{ route('transactions.index') }}" class="mb-4 flex flex-wrap gap-4 items-end">
        <div>
            <label for="start_date" class="block text-sm text-gray-700">Dari Tanggal:</label>
            <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                   class="border rounded p-2">
        </div>
    
        <div>
            <label for="end_date" class="block text-sm text-gray-700">Sampai Tanggal:</label>
            <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                   class="border rounded p-2">
        </div>
    
        <button type="submit" class="bg-blue-600 text-black px-4 py-2 rounded">Filter</button>
        <a href="{{ route('transactions.index') }}" class="text-sm text-gray-500 underline ml-2">Reset</a>
    </form>
    <table class="w-full table-auto border">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-4 py-2">#</th>
                <th class="px-4 py-2">Invoice</th>
                <th class="px-4 py-2">Tanggal</th>
                <th class="px-4 py-2">Total</th>
                <th class="px-4 py-2">Dibayar</th>
                <th class="px-4 py-2">Kembalian</th>
                <th class="px-4 py-2">Metode</th>
                <th class="px-4 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $key => $tx)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $key + 1 }}</td>
                    <td class="px-4 py-2 font-mono">{{ $tx->no_invoice }}</td>
                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($tx->transaction_date)->format('d M Y H:i') }}</td>
                    <td class="px-4 py-2">Rp {{ number_format($tx->total_price, 0, ',', '.') }}</td>
                    <td class="px-4 py-2">Rp {{ number_format($tx->paid_amount, 0, ',', '.') }}</td>
                    <td class="px-4 py-2">Rp {{ number_format($tx->change_amount, 0, ',', '.') }}</td>
                    <td class="px-4 py-2">{{ $tx->payment_method }}</td>
                    <td class="px-4 py-2">
                        {{-- Tambahkan tombol lihat detail nanti --}}
                        <a href="{{ route('transactions.show', $tx->id) }}" class="text-blue-500 hover:underline">Lihat</a>

                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-gray-500 py-4">Belum ada transaksi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
