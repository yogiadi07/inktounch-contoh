@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Log Pergerakan Inventaris</h2>

    <table class="w-full table-auto border">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2">Tanggal</th>
                <th class="px-4 py-2">Produk</th>
                <th class="px-4 py-2">Tipe</th>
                <th class="px-4 py-2">Jumlah</th>
                <th class="px-4 py-2">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($logs as $log)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($log->logged_at)->format('d M Y H:i') }}</td>
                    <td class="px-4 py-2">{{ $log->product->name }}</td>
                    <td class="px-4 py-2">
                        <span class="{{ $log->type === 'in' ? 'text-green-600' : 'text-red-600' }}">
                            {{ strtoupper($log->type) }}
                        </span>
                    </td>
                    <td class="px-4 py-2">{{ $log->quantity }}</td>
                    <td class="px-4 py-2">{{ $log->description ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-gray-500 py-4">Belum ada log inventaris.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
